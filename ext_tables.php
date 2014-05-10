<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE == 'BE')	{
	$GLOBALS['TBE_MODULES_EXT']['xMOD_alt_clickmenu']['extendCMclasses'][] = array(
		'name' => 'KERN23\\K23Imagecrop\\Service\\ContextMenuOptions'
	);
	
	# Add Sprite icon
	t3lib_SpriteManager::addSingleIcons(
		array(
			'clickmenu' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY).'Resources/Public/Icons/cm_icon.gif',
		),
		$_EXTKEY
	);
	
	# Example TypoScript Configuration
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
		$_EXTKEY,
		'Configuration/TypoScript/Examples', 'K23 Imagecrop (Examples)'
	);
	
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModulePath('k23imagecrop',\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'cm1/');
}
?>
