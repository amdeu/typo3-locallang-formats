<?php

declare(strict_types=1);

namespace Amdeu\LocallangFormats\Parser;

/**
 * Parses JSON language files for TYPO3's localization system.
 *
 * Supports both flat and nested key structures.
 *
 * Example (flat):
 *   { "login.title": "Please log in" }
 *
 * Example (nested):
 *   { "login": { "title": "Please log in" } }
 */
class JsonFileParser extends AbstractFlatFileParser
{
    protected function parseFile(string $filePath): array
    {
        $content = file_get_contents($filePath);
        if ($content === false) {
            return [];
        }

        try {
            $data = json_decode($content, associative: true, flags: JSON_THROW_ON_ERROR);
            return is_array($data) ? $data : [];
        } catch (\JsonException) {
            return [];
        }
    }
}
