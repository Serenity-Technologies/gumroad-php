#!/usr/bin/env php
<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies
 * @license MIT
 */

/**
 * Batch DTO Constructor Converter
 * 
 * Converts all DTO files in the src/DTOs directory to use constructors.
 * This is a simplified version focused on the core conversion logic.
 */

echo "🚀 Batch DTO Constructor Conversion\n";
echo "==================================\n\n";

$dtoDirectory = __DIR__ . '/src/DTOs';
$convertedCount = 0;
$errorCount = 0;

// Get all PHP files except BaseDTO.php
$files = array_filter(glob("{$dtoDirectory}/*.php"), function($file) {
    return basename($file) !== 'BaseDTO.php';
});

echo "Found " . count($files) . " DTO files to process...\n\n";

foreach ($files as $filePath) {
    $fileName = basename($filePath);
    echo "Processing {$fileName}... ";
    
    try {
        if (convertDtoFile($filePath)) {
            echo "✅ CONVERTED\n";
            $convertedCount++;
        } else {
            echo "⏭️  SKIPPED\n";
        }
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n" . str_repeat('-', 40) . "\n";
echo "📊 Summary:\n";
echo "✅ Successfully converted: {$convertedCount}\n";
echo "❌ Errors: {$errorCount}\n";
echo "🏁 Batch conversion completed!\n";

function convertDtoFile(string $filePath): bool
{
    $content = file_get_contents($filePath);
    
    // Skip if already has constructor
    if (strpos($content, 'public function __construct') !== false) {
        return false;
    }
    
    // Extract properties
    $properties = extractDtoProperties($content);
    
    if (empty($properties)) {
        return false;
    }
    
    // Generate constructor
    $constructor = generateDtoConstructor($properties);
    
    // Remove property declarations
    $cleanContent = removeDtoProperties($content);
    
    // Insert constructor
    $finalContent = insertDtoConstructor($cleanContent, $constructor);
    
    // Save file
    file_put_contents($filePath, $finalContent);
    
    return true;
}

function extractDtoProperties(string $content): array
{
    $properties = [];
    $lines = explode("\n", $content);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match('/^(?:\#\[[^\]]+\]\s*)?public\s+(.+?)\s+\$(\w+);/', $line, $matches)) {
            $properties[] = [
                'type' => $matches[1],
                'name' => $matches[2]
            ];
        }
    }
    
    return $properties;
}

function generateDtoConstructor(array $properties): string
{
    $params = array_map(function($prop) {
        return "public {$prop['type']} \${$prop['name']}";
    }, $properties);
    
    $paramString = implode(",\n        ", $params);
    
    return "    public function __construct(\n        {$paramString}\n    ) {}\n";
}

function removeDtoProperties(string $content): string
{
    $lines = explode("\n", $content);
    $filtered = [];
    
    foreach ($lines as $line) {
        if (!preg_match('/^(?:\#\[[^\]]+\]\s*)?public\s+.+?\s+\$\w+;/', trim($line))) {
            $filtered[] = $line;
        }
    }
    
    return implode("\n", $filtered);
}

function insertDtoConstructor(string $content, string $constructor): string
{
    $pos = strpos($content, '{');
    if ($pos === false) return $content;
    
    return substr($content, 0, $pos + 1) . "\n" . $constructor . substr($content, $pos + 1);
}