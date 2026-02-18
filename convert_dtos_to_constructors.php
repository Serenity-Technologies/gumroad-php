#!/usr/bin/env php
<?php

/**
 * DTO Constructor Conversion Script
 * 
 * This script automatically converts all DTO classes in the src/DTOs folder
 * from property-based definitions to constructor-based definitions.
 * 
 * Usage: php convert_dtos_to_constructors.php
 */

class DTOConverter
{
    private string $dtoDirectory;
    private array $convertedFiles = [];
    private array $skippedFiles = [];
    
    public function __construct(string $dtoDirectory)
    {
        $this->dtoDirectory = rtrim($dtoDirectory, '/');
    }
    
    public function run(): void
    {
        echo "🚀 Starting DTO Constructor Conversion...\n";
        echo "📂 Directory: {$this->dtoDirectory}\n\n";
        
        $files = $this->getDtoFiles();
        
        foreach ($files as $file) {
            $this->convertDtoFile($file);
        }
        
        $this->updateBaseDTO();
        $this->generateSummary();
    }
    
    private function getDtoFiles(): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->dtoDirectory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php' && $file->getFilename() !== 'BaseDTO.php') {
                $files[] = $file->getPathname();
            }
        }
        
        sort($files);
        return $files;
    }
    
    private function convertDtoFile(string $filePath): void
    {
        $fileName = basename($filePath);
        echo "🔄 Processing: {$fileName}... ";
        
        try {
            $content = file_get_contents($filePath);
            
            // Skip if already converted (has constructor)
            if (strpos($content, 'public function __construct') !== false) {
                echo "SKIPPED (already has constructor)\n";
                $this->skippedFiles[] = $fileName;
                return;
            }
            
            // Skip BaseDTO
            if (strpos($content, 'class BaseDTO') !== false) {
                echo "SKIPPED (BaseDTO)\n";
                $this->skippedFiles[] = $fileName;
                return;
            }
            
            $convertedContent = $this->transformDtoContent($content, $fileName);
            
            if ($convertedContent !== $content) {
                file_put_contents($filePath, $convertedContent);
                echo "✅ CONVERTED\n";
                $this->convertedFiles[] = $fileName;
            } else {
                echo "⚠️  NO CHANGES MADE\n";
                $this->skippedFiles[] = $fileName;
            }
            
        } catch (Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "\n";
        }
    }
    
    private function transformDtoContent(string $content, string $fileName): string
    {
        // Extract class name
        if (!preg_match('/class\s+(\w+)/', $content, $matches)) {
            return $content;
        }
        
        $className = $matches[1];
        
        // Parse properties
        $properties = $this->extractProperties($content);
        
        if (empty($properties)) {
            return $content;
        }
        
        // Generate constructor
        $constructor = $this->generateConstructor($properties);
        
        // Remove property declarations
        $contentWithoutProperties = $this->removePropertyDeclarations($content);
        
        // Insert constructor
        $finalContent = $this->insertConstructor($contentWithoutProperties, $constructor);
        
        return $finalContent;
    }
    
    private function extractProperties(string $content): array
    {
        $properties = [];
        $lines = explode("\n", $content);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Match property patterns like: public string $name;
            // or with attributes: #[DataCollectionOf(...)] public array $items;
            if (preg_match('/^(?:\#\[[^\]]+\]\s*)?(public|protected)\s+(.+?)\s+\$(\w+);/', $line, $matches)) {
                $visibility = $matches[1];
                $type = $matches[2];
                $name = $matches[3];
                
                $properties[] = [
                    'visibility' => $visibility,
                    'type' => $type,
                    'name' => $name,
                    'line' => $line
                ];
            }
        }
        
        return $properties;
    }
    
    private function generateConstructor(array $properties): string
    {
        $paramLines = [];
        $assignmentLines = [];
        
        foreach ($properties as $prop) {
            $paramType = $prop['type'];
            $paramName = $prop['name'];
            
            // Handle nullable types
            $isNullable = strpos($paramType, '?') === 0;
            $cleanType = ltrim($paramType, '?');
            
            // Generate parameter
            $param = "public {$paramType} \${$paramName}";
            $paramLines[] = "        {$param},";
        }
        
        // Remove trailing comma from last parameter
        if (!empty($paramLines)) {
            $paramLines[count($paramLines) - 1] = rtrim($paramLines[count($paramLines) - 1], ',');
        }
        
        $constructor = "    public function __construct(\n";
        $constructor .= implode("\n", $paramLines);
        $constructor .= "\n    ) {}\n";
        
        return $constructor;
    }
    
    private function removePropertyDeclarations(string $content): string
    {
        $lines = explode("\n", $content);
        $filteredLines = [];
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            // Skip property declarations and related attribute lines
            if (preg_match('/^(?:\#\[[^\]]+\]\s*)?(public|protected)\s+.+?\s+\$\w+;/', $trimmedLine)) {
                continue;
            }
            
            // Skip empty lines that might be between properties
            if (empty($trimmedLine) && end($filteredLines) === '') {
                continue;
            }
            
            $filteredLines[] = $line;
        }
        
        return implode("\n", $filteredLines);
    }
    
    private function insertConstructor(string $content, string $constructor): string
    {
        // Find the opening brace of the class
        $classStartPos = strpos($content, '{');
        if ($classStartPos === false) {
            return $content;
        }
        
        // Insert constructor after the opening brace
        $insertPos = $classStartPos + 1;
        
        return substr($content, 0, $insertPos) . "\n" . $constructor . substr($content, $insertPos);
    }
    
    private function updateBaseDTO(): void
    {
        $baseDtoPath = $this->dtoDirectory . '/BaseDTO.php';
        
        if (!file_exists($baseDtoPath)) {
            echo "⚠️  BaseDTO.php not found, skipping BaseDTO update\n";
            return;
        }
        
        echo "🔄 Updating BaseDTO.php... ";
        
        $baseDtoContent = file_get_contents($baseDtoPath);
        
        // Add helper methods if they don't exist
        $helperMethods = '
    /**
     * Create DTO from array data
     * 
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        // Always use manual constructor approach to avoid Laravel container dependencies
        $reflection = new \ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        
        $args = [];
        foreach ($parameters as $param) {
            $name = $param->getName();
            $args[] = $data[$name] ?? ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
        }
        
        return $reflection->newInstanceArgs($args);
    }
    
    /**
     * Convert DTO to array
     * 
     * @return array
     */
    public function toArray(): array
    {
        try {
            return $this->all();
        } catch (\Exception $e) {
            // Fallback to manual array conversion when Laravel container is not available
            $data = [];
            $reflection = new \ReflectionClass($this);
            foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                if (!$property->isStatic()) {
                    $data[$property->getName()] = $property->getValue($this);
                }
            }
            return $data;
        }
    }';
        
        // Check if methods already exist
        if (strpos($baseDtoContent, 'fromArray') === false) {
            // Find the closing brace of the class
            $lastBracePos = strrpos($baseDtoContent, '}');
            if ($lastBracePos !== false) {
                // Insert helper methods before the closing brace
                $baseDtoContent = substr($baseDtoContent, 0, $lastBracePos) . $helperMethods . "\n}\n";
                file_put_contents($baseDtoPath, $baseDtoContent);
                echo "✅ UPDATED\n";
            } else {
                echo "❌ ERROR: Could not find class closing brace\n";
            }
        } else {
            echo "SKIPPED (methods already exist)\n";
        }
    }
    
    private function generateSummary(): void
    {
        echo "\n" . str_repeat('=', 50) . "\n";
        echo "📊 CONVERSION SUMMARY\n";
        echo str_repeat('=', 50) . "\n";
        
        echo "✅ Converted Files: " . count($this->convertedFiles) . "\n";
        foreach ($this->convertedFiles as $file) {
            echo "   • {$file}\n";
        }
        
        echo "\n⏭️  Skipped Files: " . count($this->skippedFiles) . "\n";
        foreach ($this->skippedFiles as $file) {
            echo "   • {$file}\n";
        }
        
        echo "\n🏁 Conversion completed!\n";
        
        if (!empty($this->convertedFiles)) {
            echo "\n💡 Next steps:\n";
            echo "   1. Run tests to verify functionality\n";
            echo "   2. Update any existing code that uses ::from() method\n";
            echo "   3. Consider updating documentation\n";
        }
    }
}

// Run the converter
if (php_sapi_name() === 'cli') {
    $converter = new DTOConverter(__DIR__ . '/src/DTOs');
    $converter->run();
} else {
    echo "This script must be run from the command line.\n";
}