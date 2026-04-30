<?php

declare(strict_types=1);

use Symfony\Component\Translation\Loader;

defined('TYPO3') or die();

// Register loaders
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['yaml'] ??= Loader\YamlFileLoader::class;
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['yml']  ??= Loader\YamlFileLoader::class;
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['json'] ??= Loader\JsonFileLoader::class;
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['php']  ??= Loader\PhpFileLoader::class;
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['ini']  ??= Loader\IniFileLoader::class;
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['csv']  ??= Loader\CsvFileLoader::class;
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['po']   ??= Loader\PoFileLoader::class;

// Priority: xlf first so existing extensions are unaffected
if (!isset($GLOBALS['TYPO3_CONF_VARS']['LANG']['format']['priority'])) {
	$GLOBALS['TYPO3_CONF_VARS']['LANG']['format']['priority'] = 'xlf,yaml,yml,json,php,ini,csv,po';
}