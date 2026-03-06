<?php

declare(strict_types=1);

namespace Amdeu\LocallangFormats\Parser;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Parses YAML language files for TYPO3's localization system.
 *
 * Supports both flat and nested key structures.
 * Uses symfony/yaml which is already a TYPO3 core dependency.
 *
 * Example (flat):
 *   login.title: Please log in
 *
 * Example (nested):
 *   login:
 *     title: Please log in
 */
class YamlFileParser extends AbstractFlatFileParser
{
    protected function parseFile(string $filePath): array
    {
        try {
            $data = Yaml::parseFile($filePath);
            return is_array($data) ? $data : [];
        } catch (ParseException) {
            return [];
        }
    }
}
