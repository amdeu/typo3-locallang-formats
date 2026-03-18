<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Alternative locallang formats',
	'description' => 'Use alternative file formats like yaml and json for locallang files',
	'category' => 'be',
	'author' => 'Amadeus Kiener',
	'state' => 'stable',
	'version' => '1.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '13.4.0-13.99.99',
		],
		'conflicts' => [],
		'suggests' => [
			'container' => '',
		],
	],
];