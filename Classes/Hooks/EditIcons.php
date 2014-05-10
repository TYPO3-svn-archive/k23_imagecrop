<?php

class EditIcons implements \TYPO3\CMS\Filelist\FileListEditIconHookInterface {
	
	var $extKey = 'k23_imagecrop';
	
	/**
	 * modifies edit icon array
	 *
	 * @param	array		array of edit icons
	 * @param	fileList		parent object
	 * @return	void
	 */
	public function manipulateEditIcons(&$cells, &$parentObject) {
		if ($GLOBALS['BE_USER']->userTS['tx_k23imagecrop.']['enabled'] == 1 && $cells['info']) {
			if(preg_match("/launchView\(\s*'_FILE'\s*,\s*'([^']*?)'/ms", $cells['info'], $matches)) {
				$icon = $this->getIcon();
				$file = pathinfo($matches[1]);
				$action = $this->getCropAction(array(
					'ext' => $file['extension'],
					'id' => $matches[1]
				));
			}
		} elseif($GLOBALS['BE_USER']->userTS['tx_k23imagecrop.']['enabled'] == 1 && $parentObject->totalbytes) {
			$keys = array_keys($parentObject->files['sorting']);
			$file = $parentObject->files['files'][$keys[$parentObject->eCounter->$parentObject->dirCounter]];
			$action = $this->getCropAction(array(
				'ext' => $file['fileext'],
				'id' => $file['path'].$file['file']
			));
		}
		if($action) {
			$cells['k23_imagecrop_crop'] = $action;
		}
	}
	
	protected function getCropAction($file) {
		$icon = $this->getIcon();
		
		if(in_array(strtolower($file['ext']), array('gif','jpg','jpeg','png'))) {
			$moduleToken = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get()->generateToken('moduleCall', 'k23imagecrop');

			$baseUrl = 'mod.php?M=k23imagecrop&moduleToken='.$moduleToken.'&tx_k23imagecrop_fileimgcrop%5Baction%5D=wizard&tx_k23imagecrop_fileimgcrop%5Bcontroller%5D=Crop';
			$baseUrl .= '&tx_k23imagecrop_fileimgcrop%5Bfile%5D='.urlencode(substr($file['id'],2)).'&tx_k23imagecrop_fileimgcrop%5Bid%5D='.intval(\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id'));
			
			$editOnClick = 'top.content.list_frame.location.href=top.TS.PATH_typo3+\''.$baseUrl.'\'';
			
			return '<a href="#" onclick="' . $editOnClick . '" title="'.$GLOBALS['LANG']->sL('LLL:EXT:k23_imagecrop/Resources/Private/Language/locallang_be.xml:cm1_title', TRUE).'">'.$icon.'</a>';
		}
		
		return false;
	}
	
	protected function getIcon() {
		return \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('extensions-'.$this->extKey.'-clickmenu');
	}
	
}

?>