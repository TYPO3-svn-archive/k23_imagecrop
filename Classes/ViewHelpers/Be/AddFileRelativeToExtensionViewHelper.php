<?php
namespace KERN23\K23Imagecrop\ViewHelpers\Be;

class AddFileRelativeToExtensionViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper {

     /**
      *
      * @param string $extensionKey
      * @param string $pathToJsFile
	  * @param string $basePath
      * @return void
      */
     public function render($fileType = NULL, $pathToFile = NULL, $basePath = NULL) {
         if (NULL === $pathToFile) {
             $pathToJsFile = $this->renderChildren();
         }
             //if fileType is not defined, find it by file extension
         if (NULL === $fileType) {
             $fileType = substr(strrchr($pathToFile,'.'), 1);
         }
		 
		 if ( NULL === $basePath ) {
			$basePath     = '../'.\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('k23_imagecrop').'Resources/Public/';
		 }
		 
		 $pathToFile   = $basePath.$pathToFile;
         $pageRenderer = $this->getDocInstance()->getPageRenderer();
		 
             /* @var $pageRenderer t3lib_PageRenderer */
         if($fileType == 'js') {
             $pageRenderer->addJsFile($pathToFile);
         } else if($fileType = 'css') {
             $pageRenderer->addCssFile($pathToFile);
         }
     }
}

?>