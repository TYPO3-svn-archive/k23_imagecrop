<?php

namespace KERN23\K23Imagecrop\Service;

class ContextMenuOptions {
	/**
	 * Adds a sample item to the CSM
	 *
	 * @param \TYPO3\CMS\Backend\ClickMenu\ClickMenu $parentObject Back-reference to the calling object
	 * @param array $menuItems Current list of menu items
	 * @param string $table Name of the table the clicked on item belongs to
	 * @param integer $uid Id of the clicked on item
	 *
	 * @return array Modified list of menu items
	 */
	public function main(\TYPO3\CMS\Backend\ClickMenu\ClickMenu $parentObject, $menuItems, $table, $uid) {
		if (strlen($table) > 0 && !empty($uid)) {
		  return $menuItems;
		}
		
		if ($parentObject->cmLevel) return $menuItems;
		list($falID,$file) = explode(":",$table);
		
		// Get informations of the selected file
		$fileFolderInfo = \KERN23\K23Imagecrop\Service\File::getFileAndFolderInfo($parentObject->iParts[0]);
		
		// Is the clicked File a registered Filetype in $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']?
		if ( $GLOBALS['BE_USER']->userTS['tx_k23imagecrop.']['enabled'] == 1 && $fileFolderInfo['folderInfo']->isWritable() == true && $fileFolderInfo['fileInfo']['permissions']['read'] == true && in_array($fileFolderInfo['fileInfo']['extension'], \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']))) {
			if(in_array(strtolower($fileFolderInfo['fileInfo']['extension']), array('gif','jpg','jpeg','png'))) {
				
				$moduleToken = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get()->generateToken('moduleCall', 'k23imagecrop');
				
				// URL for the menu item. Point to the page tree example module, passing the page id.
				$baseUrl = 'mod.php?M=k23imagecrop&moduleToken='.$moduleToken.'&tx_k23imagecrop_fileimgcrop%5Baction%5D=wizard&tx_k23imagecrop_fileimgcrop%5Bcontroller%5D=Crop';
				$baseUrl .= '&tx_k23imagecrop_fileimgcrop%5Bfile%5D='.urlencode($file).'&tx_k23imagecrop_fileimgcrop%5Bid%5D='.intval($falID);
				
				// Add new menu item with the following parameters:
				// 1) Label
				// 2) Icon
				// 3) URL
				// 4) = 1 disable item in docheader
				$menuItems[] = $parentObject->linkItem(
					$GLOBALS['LANG']->sL('LLL:EXT:k23_imagecrop/Resources/Private/Language/locallang_be.xml:cm1_title'),
					\TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('extensions-k23_imagecrop-clickmenu'),
					$parentObject->urlRefForCM($baseUrl),
					1
				);
			}
		}
		
		return $menuItems;
	}
}

?>