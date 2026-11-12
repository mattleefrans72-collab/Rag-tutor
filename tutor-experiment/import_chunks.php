<?php 

$chunkFile = __DIR__ . '/../materials/chunks/chunks.json';

$json = file_get_contents($chunkFile);

if ($json === false) {
throw new RuntimeException('Could not read chunks.json');
}

$chunks = json_decode(
$json,
true,
flags: JSON_THROW_ON_ERROR
);

$pdo = new PDO(
  'pgsql:host=127.0.0.1;port=5432;dbname=ai_tutor',
  'ai_tutor',
  "password",
  [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  ]
);
echo $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS) . PHP_EOL;

$statement = $pdo->prepare('
    INSERT INTO spike_content_nodes (
        id,
        source_id,
        parent_id,
        node_type,
        level,
        heading,
        content,
        position,
        source_url,
        embedding,
        embedding_model
    )
    VALUES (
        :id,
        :source_id,
        :parent_id,
        :node_type,
        :level,
        :heading,
        :content,
        :position,
        :source_url,
        NULL,
        NULL
    )');

try {
  $pdo->beginTransaction();
  foreach ($chunks as $chunk) {
  $statement->execute([
      ':id' => $chunk['id'],
      ':source_id' => $chunk['source_id'],
      ':parent_id' => $chunk['parent_id'],
      ':node_type' => $chunk['node_type'],
      ':level' => $chunk['level'],
      ':heading' => $chunk['heading'],
      ':content' => $chunk['content'],
      ':position' => $chunk['position'],
      ':source_url' => $chunk['source_url'],
  ]);
}
  $pdo->commit();
  echo 'Chunks imported successfully.' . PHP_EOL;
} catch (Exception $e) {
  $pdo->rollBack();
  echo 'Failed to import chunks: ' . $e->getMessage() . PHP_EOL;
}

