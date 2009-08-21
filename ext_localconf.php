<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

Tx_Extbase_Utility_Extension::configureDispatcher(
	'DynamicSitemap',
	'Pi1',
	array(
		'Sitemap' => 'index',
	),
	array(
		'Sitemap' => 'index',
	)
);

