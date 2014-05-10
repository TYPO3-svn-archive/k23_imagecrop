<?php

namespace KERN23\K23Imagecrop\Utility;

class Gmagick {
	
	private static function getGfInstance() {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\GraphicalFunctions');
	}
	
	/**
	 * Gets scale info without a image file
	 *
	 * @param string $srcFile
	 * @param string $dstFile
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @param int $height
	 * @param bool $resize
	 * @param int $resWidth
	 * @param int $resHeight
	 *
	 * @return bool
	 */
	public static function cropImage($srcFile, $dstFile, $x, $y, $width, $height, $resize = FALSE, $resWidth = 0, $resHeight = 0) {
		$gf    = self::getGfInstance();
		$image = $gf->imageCreateFromFile($srcFile);
		
		if ( !$image ) return false;
		
		$gf->crop($image, array(
			'crop' => $x.','.$y.','.$width.','.$height,
			'backColor' => ''
		));
		
		if ( $resize === TRUE ) {
		    $resizedImg = imagecreatetruecolor($resWidth, $resHeight);
		    $gf->imagecopyresized($resizedImg, $image, 0, 0, 0, 0, $resWidth, $resHeight, $width, $height);
		    $image = $resizedImg;
		    unset($resizedImg);
		}
		
		if ( $gf->ImageWrite($image, $dstFile, 100) ) {
			//$gf->destroy(); # DEBUG: doesn't work in 6.2 (BUG?)
			return true;
		} else return false;
	}
	
	/**
	 * Combines two images
	 *
	 * @param string $imgFile
	 * @param string $overlayImgFile
	 * @param string $dstFile
	 *
	 * @return bool
	 */
	public static function compositeImage($imgFile,$overlayImgFile,$dstFile) {
		$gf      = self::getGfInstance();
		$image   = $gf->imageCreateFromFile($imgFile);
		$imgSize = $gf->getImageDimensions($imgFile);
		
		$gf->copyImageOntoImage($image, array(
			'file'  => $overlayImgFile,
			'align' => '0,0'
		), array(0, 0, $imgSize[0], $imgSize[1]));
		
		if ( $gf->ImageWrite($image, $dstFile, 100) ) {
			//$gf->destroy(); # DEBUG: doesn't work in 6.2 (BUG?)
			return true;
		} else return false;
	}
	
}

?>