<?php

namespace KERN23\K23Imagecrop\Utility;

class Image {
	
	/**
	 * Gets all Information of an Image
	 *
	 * @param string $fileName
	 * @param string $maxW
	 * @param string $maxH
	 *
	 * @return array Image Data
	 */
	public static function getImageInfo($file,$maxW,$maxH) {
		$fileProperties = $file->getProperties();
		$fileAbsPath    = \KERN23\K23Imagecrop\Service\File::getFullFilePathByTable($fileProperties['storage'].':'.$fileProperties['identifier']);
		
		$imgInfo  = \KERN23\K23Imagecrop\Utility\Image::getImageDimensions($fileAbsPath);
		$imgScale = \KERN23\K23Imagecrop\Utility\Image::getImageScale($fileAbsPath,array(
			"maxW" => $maxW,
			"maxH" => $maxH
		));
		
		$imgInfo['relFileName']   = $fileProperties['identifier'];
		$imgInfo['scaledWidth']   = $imgScale[0];
		$imgInfo['scaledHeight']  = $imgScale[1];
		$imgInfo['scaledPercent'] = ceil((100 / $imgInfo['width']) * $imgInfo['scaledWidth']);
		
		return $imgInfo;
	}
	
	/**
	 * Gets basic Information of an Image
	 *
	 * @param string $imgFile
	 *
	 * @return array Image Info
	 */
	public static function getImageDimensions($imgFile) {
		$gf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\GraphicalFunctions');
		
		list($imgInfo['width'],$imgInfo['height'],$imgInfo['ext'],$imgInfo['fileName']) = $gf->getImageDimensions($imgFile);
		
		return $imgInfo;
	}
	
	/**
	 * Adds scaling informations of an Image
	 *
	 * @param string $imgFile
	 * @param array $options
	 *
	 * @return array
	 */
	public static function getImageScale($imgFile,$options) {
		$gf          = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\GraphicalFunctions');
		$origImgInfo = $gf->getImageDimensions($imgFile);
		
		return $gf->getImageScale($origImgInfo,$origImgInfo[0],$origImgInfo[1],$options);
	}
	
	/**
	 * Gets scale info without a image file
	 *
	 * @param string $origWidth
	 * @param string $origHeight
	 * @param string $maxWidth
	 * @param string $maxHeight
	 *
	 * @return array
	 */
	public static function getScale($origWidth,$origHeight,$maxWidth,$maxHeight) {
		$origImgInfo[0] = $origWidth;
		$origImgInfo[1] = $origHeight;
		
		$options = array(
			"maxW" => &$maxWidth,
			"maxH" => &$maxHeight
		);
		
		$gf        = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\GraphicalFunctions');
		$scaleInfo = $gf->getImageScale($origImgInfo,$origImgInfo[0],$origImgInfo[1],$options);
		
		$imgInfo['width']         = $origWidth;
		$imgInfo['height']        = $origHeight;
		$imgInfo['scaledWidth']   = $scaleInfo[0];
		$imgInfo['scaledHeight']  = $scaleInfo[1];
		$imgInfo['scaledPercent'] = ceil((100 / $imgInfo['width']) * $imgInfo['scaledWidth']);
		
		return $imgInfo;
	}
	
}

?>