<?php

namespace KERN23\K23Imagecrop\Service;

class File {
	
	/**
	 * Get simple file infos. Mainly from the orig image file used in controller
	 *
	 * @param string $fileName
	 * @param int $storageId
	 * @return TYPO3\CMS\Core\Resource\File
	 */
	public function getFileData($fileName, $storageId) {
		$storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository'); // create instance to storage repository
		$storage = $storageRepository->findByUid(intval($storageId));    // get file storage with uid 1 (this should by default point to your fileadmin/ directory)
		$file = $storage->getFile($fileName); // create file object for the image (the file will be indexed if necessary)
		
		return $file;
	}
	
	/**
	 * Get the storage path
	 *
	 * @param int $storageId
	 * @return array
	 */
	public function getStoragePath($storageId) {
		$storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository'); // create instance to storage repository
		$storage = $storageRepository->findByUid(intval($storageId));    // get file storage with uid 1 (this should by default point to your fileadmin/ directory)
		$conf = $storage->getConfiguration();
		
		return $conf['basePath'];
	}
	
	/**
	 * For context menu loads file object and folder object and basepath
	 *
	 * @param string $tableInfo
	 * @return array
	 */
	public function getFileAndFolderInfo($tableInfo) {
		$origFileObject    = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->retrieveFileOrFolderObject($tableInfo);
		if ( get_class($origFileObject) != 'TYPO3\CMS\Core\Resource\File' ) return $menuItems;
		
		$retVal = array();
		
		$retVal['folderInfo'] = $origFileObject->getStorage();
		$retVal['fileInfo']   = $origFileObject->toArray();
		$retVal['basePath']   = $retVal['folderInfo']->getConfiguration();
		$retVal['basePath']   = PATH_site.$basePath['basePath'];
		
		return $retVal;
	}
	
	/**
	 * Gets a unique filename if needed
	 *
	 * @param string $fileName
	 * @return string
	 */
	public function getUniqueFilename($fileName) {
		$bfu = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\BasicFileUtility');
		$bfu->init(array(), $GLOBALS['TYPO3_CONF_VARS']['BE']['fileExtensions']);
		
		$bfu_File = basename($fileName);
		$bfu_path = realpath(dirname($fileName));
		
		return $bfu->getUniqueName($bfu_File, $bfu_path);
	}
	
	/**
	 * Get's the full file path by table definition (with storageID integrated)
	 *
	 * @param string $table
	 * @return string
	 */
	public function getFullFilePathByTable($table) {
		$fileName = \KERN23\K23Imagecrop\Service\File::getFileAndFolderInfo($table);
		$fileName = PATH_site.$fileName['fileInfo']['url'];
		
		return $fileName;
	}
	
}

?>