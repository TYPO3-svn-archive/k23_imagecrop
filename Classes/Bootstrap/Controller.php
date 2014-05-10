<?php
namespace KERN23\K23Imagecrop\Bootstrap;


/***************************************************************
 *  Copyright notice
 *
 *  (c) 1999-2013 Kasper Skårhøj (kasperYYYY@typo3.com)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ExtBase Controller Bootloader
 *
 * @author Hendrik Reimers <kontakt@kern23.de>
 */
class Controller {
	
    public function initialize($configuration) {
        // Needed, because the controller won't be loaded correctly
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$configuration['extensionName']]['modules'][$configuration['pluginName']]['controllers'] = array(
            $configuration['controller'] => array(
                'actions' => array(
                    $configuration['action']
                )
            )
        );
 
        // Initialize extbase bootstrap
        $bootstrap = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Core\\Bootstrap');
        $bootstrap->initialize($configuration);
        $bootstrap->cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
        
        // Run plugin with the given configuration
        echo $bootstrap->run('', $configuration);
    }
	
}

?>