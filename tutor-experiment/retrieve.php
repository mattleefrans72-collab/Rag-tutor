<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$qustion = "ding dong";

$client = HttpClient::create();
$response = $client->request(
    'POST',
    'http://127.0.0.1:11434/api/embed',
    [
      'json' => [
        'model' => 'embeddinggemma',
        'input' => $qustion,
      ],
    ]
  );
  $data = $response->toArray();
  $qustionEmbedding = $data['embeddings'][0];

$pdo = new PDO(
  'pgsql:host=127.0.0.1;port=5432;dbname=ai_tutor',
  'ai_tutor',
  "password",
  [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  ]
);
  $statement = $pdo->prepare('
  SELECT
    id,
    heading,
    content,
    source_url,
    (embedding <=> CAST(:question_vector AS vector))
      AS cosine_distance
  FROM spike_content_nodes
  WHERE node_type = :node_type
  ORDER BY cosine_distance
  LIMIT 1;
  ');

  try{
    $statement->execute([
      'question_vector' => json_encode($qustionEmbedding),
      'node_type' => 'detail',
    ]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    print_r($result);
  } catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
  }
  