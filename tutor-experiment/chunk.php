<?php

$inputPath = __DIR__ . '/../materials/content/cleaned.md';

$content = file_get_contents($inputPath);

if ($content === false) {
  throw new RuntimeException("Could not read cleaned.md");
}

echo "Loaded cleaned.md successfully." . PHP_EOL;
echo "Characters: " . strlen($content) . PHP_EOL;

$lines = preg_split('/\R/', $content);

## Split the content into sections based on headings (##)
$sections = [];
$currentSection = null;

foreach ($lines as $line) {
  if (str_starts_with($line, '## ')) {
    if ($currentSection !== null) {
      $sections[] = $currentSection;
    }
    $currentSection = [
      'heading' => substr($line, 3),
      'lines' => [],
      ];
    continue;
  }
  if ($currentSection !== null) {
    $currentSection['lines'][] = $line;
  }
}

if ($currentSection !== null) {
  $sections[] = $currentSection;
}

foreach ($sections as $index => $section) {
  $sectionNumber = $index + 1;
  $sectionContent = trim(implode(PHP_EOL, $section['lines']));

  $chunks[] = [
    'id' => sprintf('google-linear-regression-%03d', $sectionNumber),
    'source_id' => 'google-linear-regression',
    'parent_id' => null,
    'node_type' => 'detail',
    'level' => 0,
    'heading' => $section['heading'],
    'content' => $sectionContent,
    'position' => $sectionNumber,
    'source_url' => 'https://developers.google.com/machine-learning/crash-course/linear-regression',
  ];
  
}

$outputDirectory = __DIR__ . '/../materials/chunks';
$outputPath = $outputDirectory . '/chunks.json';

$json = json_encode(
  $chunks,
  JSON_PRETTY_PRINT
    | JSON_UNESCAPED_SLASHES
    | JSON_THROW_ON_ERROR
);

$bytesWritten = file_put_contents($outputPath, $json);

if ($bytesWritten === false) {
  throw new RuntimeException('Could not write chunks.json');
}

echo 'Created: ' . $outputPath . PHP_EOL;
echo 'Chunks: ' . count($chunks) . PHP_EOL;
