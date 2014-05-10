<?php
namespace KERN23\K23Imagecrop\Controller;


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
class CropController extends \KERN23\K23Imagecrop\Mvc\Controller\ActionController {
	
	/*
	 * Shows the cropping wizard
	 *
	 * @return void
	 */
	public function wizardAction() {
		// Get the filename
		if ( !$this->request->hasArgument('file') ) {
			throw new \Exception('Argument "file" missing. Have you included the static TypoScript Template?');
		} else $fileName = $this->request->getArgument('file');
		
		// Get file data object
		$storageId   = intval($this->request->getArgument('id'));
		$file        = \KERN23\K23Imagecrop\Service\File::getFileData($fileName, $storageId);
		$storagePath = \KERN23\K23Imagecrop\Service\File::getStoragePath($storageId);
		
		// Get image info
		$imgInfo = \KERN23\K23Imagecrop\Utility\Image::getImageInfo(
			$file,
			$this->settings['image']['view']['maxW'],
			$this->settings['image']['view']['maxH']
		);
		
		// Get preview image info
		$preInfo = \KERN23\K23Imagecrop\Utility\Image::getImageInfo(
			$file,
			$this->settings['image']['preview']['maxW'],
			$this->settings['image']['preview']['maxH']
		);
		
		// Get relation scale of the presets
		if ( count($this->settings['presets']) > 0 ) {
			foreach ( $this->settings['presets'] as $key => $preset ) {
				// Scale for selection
				$presetScale = \KERN23\K23Imagecrop\Utility\Image::getScale(
					$preset['width'],
					$preset['height'],
					$this->settings['image']['view']['maxW'],
					$this->settings['image']['view']['maxH']
				);
				
				// Add scale
				$presets[$key] = array_merge($preset,$presetScale);
				
				// Scale of the preview window
				$presetScale = \KERN23\K23Imagecrop\Utility\Image::getScale(
					$preset['width'],
					$preset['height'],
					$this->settings['image']['preview']['maxW'],
					$this->settings['image']['preview']['maxH']
				);
				
				// add scale
				$presets[$key]['scaledWidthPreview'] = $presetScale['scaledWidth'];
				$presets[$key]['scaledHeightPreview'] = $presetScale['scaledHeight'];
			}
		}
		
		$imgInfo['table'] = $storageId.':'.$fileName;
		
		// Put it into the template
		$this->view->assign('image', $imgInfo);
		$this->view->assign('preview', $preInfo);
		$this->view->assign('presets', $presets);
		$this->view->assign('referer', $_SERVER['HTTP_REFERER']);
		$this->view->assign('storagePath', array(
			'basePath' => preg_replace('/^(.*)\/$/i', '$1', $storagePath),
			'sitePath' => PATH_site
		));
	}
	
	/*
	 * Crop and saves the new Image
	 *
	 * @return void
	 */
	public function saveAction() {
		$srcFile = \KERN23\K23Imagecrop\Service\File::getFullFilePathByTable($this->request->getArgument('table'));
		
		$preset     = $this->request->getArgument('preset');
		$presetConf = &$this->settings['presets'][$preset];
		
		$width   = ceil(($this->request->getArgument('w') / $this->request->getArgument('scaledPercent')) * 100);
		$height  = ceil(($this->request->getArgument('h') / $this->request->getArgument('scaledPercent')) * 100);
		$x       = ceil(($this->request->getArgument('x1') / $this->request->getArgument('scaledPercent')) * 100);
		$y       = ceil(($this->request->getArgument('y1') / $this->request->getArgument('scaledPercent')) * 100);
		
		$resize  = ( ($preset > -1) && ($presetConf['resize'] == 1) ) ? true : false;
		$resW    = ( $preset > -1 ) ? intval($presetConf['width']) : 0;
		$resH    = ( $preset > -1 ) ? intval($presetConf['height']) : 0;
		
		// Generate the destination fileName
		if ( isset($presetConf['fileName']) ) {
			$dstFile = PATH_site.$this->renderTypoScriptFileName($presetConf, $this->request->getArgument('table'));
		} else $dstFile = dirname($srcFile).'/'.basename($this->request->getArgument('newFilename'));
		
		// Get a unique filename if needed
		if ( $presetConf['overwriteFile'] != 1 ) {
			if ( file_exists($dstFile) && ($this->request->getArgument('overwriteFile') != '1') ) {
				$dstFile = \KERN23\K23Imagecrop\Service\File::getUniqueFilename($dstFile);
			}
		}
		
		// Crop the Image
		$this->cropImage($this->settings['magickToUse'], $srcFile, $dstFile, $x, $y, $width, $height, $resize, $resW, $resH);
		
		// Composite the image
		if ( ($this->settings['presets'][$preset]['composite'] == 1) && (@file_exists(PATH_site.$presetConf['overlay'])) ) {
			$this->compositeImages($this->settings['magickToUse'], $dstFile, $presetConf['overlay']);
		}
		
		// Get back to file overview
		header("Location: ".$this->request->getArgument('referer'));
	}
	
}

?>