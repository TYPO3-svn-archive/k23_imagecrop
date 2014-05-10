<?php

$MCONF['script']        = '_DISPATCH';
$MCONF['name']          = 'k23imagecrop';

$MCONF['configuration'] = array(
	'extensionName'               => 'K23Imagecrop',
	'pluginName'                  => 'fileimgcrop',
	'vendorName'                  => 'KERN23',
	'controller'                  => $_GET['tx_k23imagecrop_fileimgcrop']['controller'],
	'action'                      => $_GET['tx_k23imagecrop_fileimgcrop']['action'],
	'arguments'                   => $_GET['tx_k23imagecrop_fileimgcrop'],
	'settings'                    => ' =< plugin.tx_k23imagecrop.settings',
	'persistence'                 => ' =< plugin.tx_k23imagecrop.persistence',
	'view'                        => ' =< plugin.tx_k23imagecrop.view',
	'switchableControllerActions' => array(
		'Crop' => array(
			'1' => 'wizard',
			'2' => 'save'
		)
	)
);

?>
