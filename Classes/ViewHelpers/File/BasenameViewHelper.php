<?php
namespace KERN23\K23Imagecrop\ViewHelpers\File;

class BasenameViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

     /**
      *
      * @param string $filePath
      * @param bool $unique
      * @return void
      */
     public function render($filePath = NULL, $unique = FALSE) {
		if ( $filePath === NULL ) {
			$filePath = $this->renderChildren();
		}
		
		if ( $unique != FALSE ) {
			$bfu = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\BasicFileUtility');
			$bfu->init(array(), $GLOBALS['TYPO3_CONF_VARS']['BE']['fileExtensions']);
			
			$file = basename($filePath);
			$path = realpath(dirname($filePath));
			
			$newFileName = basename($bfu->getUniqueName($file,$path));
		} else $newFileName = basename($filePath);
		
		return $newFileName;
     }
}

?>