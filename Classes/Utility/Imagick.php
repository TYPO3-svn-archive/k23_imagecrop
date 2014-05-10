<?php

namespace KERN23\K23Imagecrop\Utility;

class Imagick {
	
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
	public static function cropImage($srcFile,$dstFile,$x,$y,$width,$height,$resize = FALSE,$resWidth = 0, $resHeight = 0) {
		self::magickCheck();
		
		$image = new \Imagick($srcFile);
		$image->setCompressionQuality(100);
		$image->cropImage($width,$height,$x,$y);
		
		if ( $resize === TRUE ) $image->thumbnailImage($resWidth,$resHeight);
		
		if ( $image->writeImage($dstFile) ) {
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
		self::magickCheck();
		
		$img1 = new \Imagick($imgFile);
		$img2 = new \Imagick($overlayImgFile);
		
		$img1->setCompressionQuality(100);
		$img1->setImageColorspace($img2->getImageColorspace()); 
		$img1->compositeImage($img2, $img2->getImageCompose(), 0, 0);
				
		if ( $img1->writeImage($dstFile) ) {
			return true;
		} else return false;
	}
	
	/**
	 * Check for magick
	 *
	 * @return void
	 */
	private static function magickCheck() {
		// Let's check whether we can perform the magick.
		if (TRUE !== extension_loaded('imagick')) {
			throw new \Exception('PHP Imagick extension is not loaded.');
		}
		
		// This check is an alternative to the previous one.
		// Use the one that suits you better.
		if (TRUE !== class_exists('\Imagick')) {
			throw new \Exception('PHP Imagick class does not exist.');
		} 
	}
	
}

?>