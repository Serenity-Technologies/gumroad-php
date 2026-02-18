# DTO Constructor Conversion Scripts

This repository contains scripts to automatically convert DTO classes from property-based definitions to constructor-based definitions.

## Available Scripts

### 1. `batch_convert_dtos.php` (Recommended)
Simple batch converter for all DTOs at once.

**Usage:**
```bash
php batch_convert_dtos.php
```

**Features:**
- Converts all DTO files in `src/DTOs/` directory
- Automatically skips files that already have constructors
- Provides conversion summary
- Handles property attributes correctly

### 2. `convert_dtos_to_constructors.php` (Full-featured)
Advanced converter with detailed logging and error handling.

**Usage:**
```bash
php convert_dtos_to_constructors.php
```

**Features:**
- Detailed processing logs
- File-by-file conversion tracking
- Automatic BaseDTO enhancement
- Comprehensive error reporting
- Conversion summary with statistics

### 3. `simple_dto_converter.php` (Single file)
Converts individual DTO files for testing/demo purposes.

**Usage:**
```bash
# Convert specific DTO
php simple_dto_converter.php ProductDTO.php

# Convert default DTO (CreateOfferCodeDTO.php)
php simple_dto_converter.php
```

## How It Works

The scripts perform the following transformations:

### Before (Property-based):
```php
class ProductDTO extends BaseDTO
{
    public string $id;
    public string $name;
    public ?string $description;
    public int $price;
}
```

### After (Constructor-based):
```php
class ProductDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description,
        public int $price,
    ) {}
}
```

## Key Features

- **Preserves Attributes**: Maintains Laravel Data attributes like `#[DataCollectionOf()]`
- **Handles Nullable Types**: Correctly processes `?type` annotations
- **Skips Existing Constructors**: Won't modify files that already have constructors
- **Error Resilient**: Graceful handling of edge cases and malformed files
- **Non-destructive**: Creates backups through version control rather than file copying

## Prerequisites

- PHP 8.1+
- Write permissions to the `src/DTOs/` directory
- All DTO files should follow the standard Laravel Data pattern

## Safety Notes

⚠️ **Important**: 
- Always commit your changes before running these scripts
- Run tests after conversion to ensure functionality
- The scripts modify files in-place
- Review converted files before committing

## Example Output

```
🚀 Batch DTO Constructor Conversion
==================================

Found 25 DTO files to process...

Processing ProductDTO.php... ✅ CONVERTED
Processing VariantDTO.php... ✅ CONVERTED
Processing OfferCodeDTO.php... ⏭️  SKIPPED
...

----------------------------------------
📊 Summary:
✅ Successfully converted: 23
❌ Errors: 0
🏁 Batch conversion completed!
```

## Troubleshooting

### Common Issues:

1. **Permission Denied**: Ensure write permissions on the DTO directory
2. **Parse Errors**: Check that DTO files have valid PHP syntax
3. **No Changes Made**: File might already have a constructor or no public properties

### Debugging:
Run with verbose output to see detailed processing information.

## Post-Conversion Steps

1. Run your test suite to verify functionality
2. Update any code that uses the `::from()` method to use constructors
3. Consider updating documentation to reflect the new constructor-based approach
4. Commit the changes with a descriptive message

## Customization

You can modify the scripts to:
- Handle different directory structures
- Add custom property filtering
- Include additional transformation rules
- Generate different constructor signatures

The core logic is in the `convertDtoFile()` function which can be easily adapted for specific needs.