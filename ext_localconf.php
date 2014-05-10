<?php

if (!defined ("TYPO3_MODE")) 	die ("Access denied.");
$confArr = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);

t3lib_extMgm::addUserTSConfig('
	tx_k23imagecrop.enabled = '.intval($confArr['userEnabled']).'
');

Tx_Extbase_Utility_Extension::configurePlugin(
    'KERN23.'.$_EXTKEY,
    'fileimgcrop',
    array(
        'Crop' => 'wizard,save',
    ),
	array(
        'Crop' => 'wizard,save',
    )
);

// Edit Control panel icon hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['fileList']['editIconsHook'][] = 'EXT:k23_imagecrop/Classes/Hooks/EditIcons.php:EditIcons';

?>
