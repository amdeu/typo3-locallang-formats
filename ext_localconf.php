<?php

declare(strict_types=1);

use Amdeu\LocallangFormats\Parser;

defined('TYPO3') or die();

// Register alternative language file formats.
// Priority order: xlf first, then yaml, json, php.
// XLF takes precedence so existing extensions are never affected.
$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['format']['priority'] = 'xlf,yaml,json,php';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['parser']['yaml'] = Parser\YamlFileParser::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['parser']['json'] = Parser\JsonFileParser::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['parser']['php'] = Parser\PhpFileParser::class;
