<?php
namespace KERN23\K23Imagecrop\Mvc\Controller;


/***************************************************************
 *  Copyright notice
 *
 *  (c) 1999-2013 Hendrik Reimers <kontakt@kern23.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published b
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
 * Script Class for the crop form
 *
 * @author Hendrik Reimers <kontakt@kern23.de>
 */
class ActionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	
	/**
	 * Path to the Extension directory
	 *
	 * @var \string
	 */
	private $extPath;
	
	/**
	 * Initializes the controller
	 *
	 * @return void
	 */
	public function initializeAction() {
		// We need the Path
		$this->extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey());
	}
	
	/**
	 * Composite two images
	 *
	 * @param \string $magickToUse
	 * @param \string $dstFile
	 * @param \string $overlay
	 * @return void
	 */
	public function compositeImages($magickToUse, $dstFile, $overlay) {
		if ( $magickToUse == 'im' ) {
			\KERN23\K23Imagecrop\Utility\Imagick::compositeImage(
				$dstFile,
				PATH_site.$overlay,
				$dstFile
			);
		} else {
			\KERN23\K23Imagecrop\Utility\Gmagick::compositeImage(
				$dstFile,
				PATH_site.$overlay,
				$dstFile
			);
		}
	}
	
	/**
	 * Crops an image
	 *
	 * @param \string $magickToUse
	 * @param \string $srcFile
	 * @param \string $dstFile
	 * @param \integer $x
	 * @param \integer $y
	 * @param \integer $width
	 * @param \integer $height
	 * @param \integer $resize
	 * @param \integer $resW
	 * @param \integer $resH
	 * @return void
	 */
	public function cropImage($magickToUse, $srcFile, $dstFile, $x, $y, $width, $height, $resize, $resW, $resH) {
		if ( $magickToUse == 'im' ) {
			\KERN23\K23Imagecrop\Utility\Imagick::cropImage(
				$srcFile,
				$dstFile,
				$x,
				$y,
				$width,
				$height,
				$resize,
				$resW,
				$resH
			);
		} else {
			\KERN23\K23Imagecrop\Utility\Gmagick::cropImage(
				$srcFile,
				$dstFile,
				$x,
				$y,
				$width,
				$height,
				$resize,
				$resW,
				$resH
			);
		}
	}
	
	/**
	 * Renders the filename by TypoScript
	 *
	 * @param array $presetConf
	 * @param string $table
	 * @return string
	 */
	public function renderTypoScriptFileName(&$presetConf, $table) {
		$typoScriptService       = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService');
		$presetAsTypoScriptArray = $typoScriptService->convertPlainArrayToTypoScriptArray($presetConf);
		
		if ( is_array($presetAsTypoScriptArray['fileName.']) ) {
			$dstFile = $this->configurationManager->getContentObject()->TEXT($presetAsTypoScriptArray['fileName.']);
		} else $dstFile = $presetAsTypoScriptArray['fileName'];
		
		$origFileInfo = \KERN23\K23Imagecrop\Service\File::getFileAndFolderInfo($table);
		
		if ( sizeof($origFileInfo['fileInfo']) > 0 ) {
			foreach ( $origFileInfo['fileInfo'] as $key => $val ) {
				if ( !is_array($val) ) {
					$dstFile = str_replace('###'.strtoupper($key).'###', $val, $dstFile);
				}
			}
			
			$dstFile = str_replace('###BASENAME###', basename($origFileInfo['fileInfo']['name'],'.'.$origFileInfo['fileInfo']['extension']), $dstFile);
			$dstFile = str_replace('###BASEPATH###', dirname($origFileInfo['fileInfo']['url']), $dstFile);
		}
		
		return $dstFile;
	}
	
}

?>