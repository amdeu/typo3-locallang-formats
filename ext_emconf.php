<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Alternative locallang formats',
	'description' => "Registers Symfony's built-in translation loaders for TYPO3 locallang files, enabling YAML, JSON, PHP, INI, CSV, and PO formats as alternatives to XLF",
	'category' => 'misc',
	'author' => 'Amadeus Kiener',
	'state' => 'stable',
	'version' => '2.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '14.3.0-14.3.99',
		],
		'conflicts' => [],
	],
];