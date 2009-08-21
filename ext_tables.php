<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	'DynamicSitemap',
	'Pi1',
	'Dynamic Sitemap'
);

t3lib_extMgm::addStaticFile(
	$_EXTKEY,
	'Configuration/TypoScript',
	'Dynamic Sitemap'
);

$tempColumns = array(
	'tx_dynamicsitemap_lastmod' => array(
		'exclude' => 1,
		'label' => '',
		'config' => array(
			'type' => 'passthrough',
		),
	),
);

t3lib_div::loadTCA('pages');
t3lib_extMgm::addTCAcolumns('pages', $tempColumns, 0);
unset($tempColumns);

