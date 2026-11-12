<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();

$chunkFilePath = __DIR__ . '/../materials/chunks/chunks.json';
$chunks = json_decode(file_get_contents($chunkFilePath), true);
$chunksWithEmbeddings = [];

foreach ($chunks as $chunk) {
  $nodeType = $chunk['node_type'];

  if ($nodeType !== 'detail') {
    echo "Skipping chunk with node_type: $nodeType" . PHP_EOL;
    continue;
  }

  $header = $chunk['heading'];
  $content = $chunk['content'];
  $embeddingText = sprintf("Section: %s\nContent: %s", $header, $content);

  $response = $client->request(
    'POST',
    'http://127.0.0.1:11434/api/embed',
    [
      'json' => [
        'model' => 'embeddinggemma',
        'input' => $embeddingText,
      ],
    ]
  );
  $data = $response->toArray();
  $chunk['embedding'] = $data['embeddings'][0];
  $chunksWithEmbeddings[$chunk['id']] = $chunk;
}




$pdo = new PDO(
  'pgsql:host=127.0.0.1;port=5432;dbname=ai_tutor',
  'ai_tutor',
  "password",
  [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  ]
);

$statement = $pdo->prepare('
    UPDATE spike_content_nodes
    SET embedding = :embedding, embedding_model = :embedding_model
    WHERE id = :id
');

try {
  $pdo->beginTransaction();
  foreach ($chunksWithEmbeddings as $chunkId => $chunkData) {
    $statement->execute([
      ':id' => $chunkId,
      ':embedding' => json_encode($chunkData['embedding']),
      ':embedding_model' => 'embeddinggemma',
    ]);
  }
  $pdo->commit();
} catch (Exception $e) {
  $pdo->rollBack();
  echo "Failed to update embeddings: " . $e->getMessage() . PHP_EOL;
} 


