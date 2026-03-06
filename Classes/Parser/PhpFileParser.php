<?php

declare(strict_types=1);

namespace Amdeu\LocallangFormats\Parser;

/**
 * Parses PHP array language files for TYPO3's localization system.
 *
 * Supports both flat and nested key structures.
 * No dependencies — the file is simply require'd and must return an array.
 *
 * Example (flat):
 *   <?php
 *   return [
 *       'login.title' => 'Please log in',
 *       'login.submit' => 'Submit',
 *   ];
 *
 * Example (nested):
 *   <?php
 *   return [
 *       'login' => [
 *           'title' => 'Please log in',
 *           'submit' => 'Submit',
 *       ],
 *   ];
 *
 */
class PhpFileParser extends AbstractFlatFileParser
{
    protected function parseFile(string $filePath): array
    {
        $data = require $filePath;
        return is_array($data) ? $data : [];
    }
}
