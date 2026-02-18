#!/usr/bin/env php
<?php

/**
 * Simple DTO Constructor Converter Example
 * 
 * This demonstrates how to convert a single DTO file to use constructors.
 * Usage: php simple_dto_converter.php [DTO_File_Name]
 */

function convertSingleDtoToConstructor(string $dtoFilePath): bool
{
    if (!file_exists($dtoFilePath)) {
        echo "❌ File not found: {$dtoFilePath}\n";
        return false;
    }
    
    $content = file_get_contents($dtoFilePath);
    $fileName = basename($dtoFilePath);
    
    echo "🔄 Converting {$fileName}...\n";
    
    // Extract class name
    if (!preg_match('/class\s+(\w+)/', $content, $matches)) {
        echo "❌ Could not find class name\n";
        return false;
    }
    
    $className = $matches[1];
    echo "📋 Found class: {$className}\n";
    
    // Extract properties
    $properties = extractProperties($content);
    
    if (empty($properties)) {
        echo "⚠️  No properties found to convert\n";
        return false;
    }
    
    echo "🔧 Found " . count($properties) . " properties:\n";
    foreach ($properties as $prop) {
        echo "   • {$prop['type']} \${$prop['name']}\n";
    }
    
    // Generate constructor
    $constructor = generateConstructor($properties);
    
    // Remove old property declarations
    $contentWithoutProperties = removePropertyDeclarations($content);
    
    // Insert constructor
    $finalContent = insertConstructor($contentWithoutProperties, $constructor);
    
    // Save the converted file
    file_put_contents($dtoFilePath, $finalContent);
    
    echo "✅ Successfully converted {$fileName}!\n";
    return true;
}

function extractProperties(string $content): array
{
    $properties = [];
    $lines = explode("\n", $content);
    
    foreach ($lines as $line) {
        $line = trim($line);
        // Match: public string $name; or #[Attribute] public array $items;
        if (preg_match('/^(?:\#\[[^\]]+\]\s*)?(public|protected)\s+(.+?)\s+\$(\w+);/', $line, $matches)) {
            $properties[] = [
                'visibility' => $matches[1],
                'type' => $matches[2],
                'name' => $matches[3]
            ];
        }
    }
    
    return $properties;
}

function generateConstructor(array $properties): string
{
    $paramLines = [];
    
    foreach ($properties as $prop) {
        $paramLines[] = "        public {$prop['type']} \${$prop['name']},";
    }
    
    // Remove trailing comma
    if (!empty($paramLines)) {
        $paramLines[count($paramLines) - 1] = rtrim($paramLines[count($paramLines) - 1], ',');
    }
    
    return "    public function __construct(\n" . implode("\n", $paramLines) . "\n    ) {}\n";
}

function removePropertyDeclarations(string $content): string
{
    $lines = explode("\n", $content);
    $filteredLines = [];
    
    foreach ($lines as $line) {
        $trimmedLine = trim($line);
        // Skip property declarations
        if (preg_match('/^(?:\#\[[^\]]+\]\s*)?(public|protected)\s+.+?\s+\$\w+;/', $trimmedLine)) {
            continue;
        }
        $filteredLines[] = $line;
    }
    
    return implode("\n", $filteredLines);
}

function insertConstructor(string $content, string $constructor): string
{
    $classStartPos = strpos($content, '{');
    if ($classStartPos === false) {
        return $content;
    }
    
    $insertPos = $classStartPos + 1;
    return substr($content, 0, $insertPos) . "\n" . $constructor . substr($content, $insertPos);
}

// Main execution
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Get DTO file from command line argument or use default
$dtoFileName = $argv[1] ?? 'CreateOfferCodeDTO.php';
$dtoFilePath = __DIR__ . '/src/DTOs/' . $dtoFileName;

convertSingleDtoToConstructor($dtoFilePath);