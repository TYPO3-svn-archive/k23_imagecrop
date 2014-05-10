<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "k23_imagecrop".
 *
 * Auto generated 10-05-2014 09:11
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'KERN23 Image Crop (with Presets)',
	'description' => 'Simply crop Images in File Module. Admin can set multiple presets with preview overlay and resizing for cropping. Overlay can be merged to the cropped image.',
	'category' => 'be',
	'shy' => false,
	'version' => '1.5.1',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'cm1',
	'state' => 'stable',
	'uploadfolder' => true,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => true,
	'lockType' => '',
	'author' => 'Hendrik Reimers (kern23.de)',
	'author_email' => 'kontakt@kern23.de',
	'author_company' => 'KERN23',
	'CGLcompliance' => NULL,
	'CGLcompliance_note' => NULL,
	'constraints' => 
	array (
		'depends' => array(
			'extbase' => '6.0.0-6.2.99',
			'fluid' => '6.0.0-6.2.99',
			'typo3' => '6.0.0-6.2.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
);

?>