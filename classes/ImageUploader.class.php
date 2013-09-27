<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImageUploader
 *
 * @author Michael Dahlke <Michael.Dahlke at RummageCity.com>
 */
require ('FileUploader.class.php');
require ('interfaces/iFileUpload.php');

class ImageUploader extends FileUploader implements iFileUpload {

	protected $originalFilePath = 'upload/Images';
	protected $fileNameFull = array();
	protected $fileNameSmall = array();
	protected $fileNameThumb = array();
	protected $filePathFull = array();
	protected $filePathSmall = array();
	protected $filePathThumb = array();
	protected $fileTypeForUpload = 'image';
	protected $smallImageWidth = 500;
	protected $smallImageHeight = 500;
	protected $thumbImageWidth = 200;
	protected $thumbImageHeight = 200;
	protected $fullImageQuality = 90;
	protected $smallImageQuality = 75;
	protected $thumbImageQuality = 65;
	// Static variables
	protected static $allowGIFImages = true;

	// Constants

	const JPG = 'jpg';
	const PNG = 'png';
	const GIF = 'gif';

	/**
	 * Getters and Setters
	 */
	public function getOriginalFilePath() {
		return $this->originalFilePath;
	}

	public function setOriginalFilePath($input) {
		if ($this->inParentDirectory) {
			$input = '../' . $input;
		}
		if (substr($input, -1) !== '/') {
			$input .= '/';
		}
		$subFolders = explode('/', $input);
		$subFoldersCount = count($subFolders) - 2;
		$lastSubFolder = $subFolders[$subFoldersCount];

		if (strtolower($lastSubFolder) !== 'full') {
			$input .= 'full/';
		}
		if (!file_exists($input)) {
			$input = $this->createUploadingDirectory($input);
		}

		$this->originalFilePath = $input;
	}

	public function getFileNameFull() {
		return $this->fileNameFull;
	}

	public function setFileNameFull($input) {
		$this->fileNameFull[] = $input;
	}

	public function getFileNameSmall() {
		return $this->fileNameSmall;
	}

	public function setFileNameSmall($input) {
		$this->fileNameSmall[] = $input;
	}

	public function getFileNameThumb() {
		return $this->fileNameThumb;
	}

	public function setFileNameThumb($input) {
		$this->fileNameThumb[] = $input;
	}

	public function getFilePathFull() {
		return $this->filePathFull;
	}

	protected function setFilePathFull($input) {
		if (substr($input, -1) !== '/') {
			$input .= '/';
		}
		$this->filePathFull[] = $input;
	}

	public function getFilePathSmall() {
		return $this->filePathSmall;
	}

	protected function setFilePathSmall($input) {
		if (substr($input, -1) !== '/') {
			$input .= '/';
		}
		$this->filePathSmall[] = $input;
	}

	public function getFilePathThumb() {
		return $this->filePathThumb;
	}

	protected function setFilePathThumb($input) {
		if (substr($input, -1) !== '/') {
			$input .= '/';
		}
		$this->filePathThumb[] = $input;
	}

	public function getSmallImageWidth() {
		return $this->smallImageWidth;
	}

	public function setSmallImageWidth($input) {
		$this->smallImageWidth = $input;
	}

	public function getSmallImageHeight() {
		return $this->smallImageHeight;
	}

	public function setSmallImageHeight($input) {
		$this->smallImageHeight = $input;
	}

	public function getThumbImageWidth() {
		return $this->thumbImageWidth;
	}

	public function setThumbImageWidth($input) {
		$this->thumbImageWidth = $input;
	}

	public function getThumbImageHeight() {
		return $this->thumbImageHeight;
	}

	public function setThumbImageHeight($input) {
		$this->thumbImageHeight = $input;
	}

	public function getFullImageQuality($imageType) {
		if ($imageType == $this::PNG) {
			return substr($this->fullImageQuality, 0, 1);
		}
		return $this->fullImageQuality;
	}

	public function setFullImageQuality($input) {
		$this->fullImageQuality = $input;
	}

	public function getSmallImageQuality($imageType) {
		if ($imageType == $this::PNG) {
			return substr($this->smallImageQuality, 0, 1);
		}
		return $this->smallImageQuality;
	}

	public function setSmallImageQuality($input) {
		$this->smallImageQuality = $input;
	}

	public function getThumbImageQuality($imageType) {
		if ($imageType == $this::PNG) {
			return substr($this->thumbImageQuality, 0, 1);
		}
		return $this->thumbImageQuality;
	}

	public function setThumbImageQuality($input) {
		$this->thumbImageQuality = $input;
	}

	/**
	 * Set the desired height and width of the small images
	 * in two functions instead of using the setters
	 * 
	 * @param int $w Desired width of small image
	 * @param int $h Desired height of small image
	 */
	public function setSmallImageWidthAndHeight($w, $h) {
		$this->setSmallImageWidth($w);
		$this->setSmallImageHeight($h);
	}

	/**
	 * Set the desired height and width of the thumb images
	 * in two functions instead of using the setters
	 * 
	 * @param int $w Desired width of thumb image
	 * @param int $h Desired height of thumb image
	 */
	public function setThumbImageWidthAndHeight($w, $h) {
		$this->setThumbImageWidth($w);
		$this->setThumbImageHeight($h);
	}

	/**
	 * 
	 * @param int $index Desired fileNameFull index to return
	 * @return string returns fileNameFull at desired index
	 */
	public function getFileNameFullAtIndex($index) {
		return $this->fileNameFull[$index];
	}

	/**
	 * 
	 * @param int $index Desired fileNameSmall index to return
	 * @return string returns fileNameSmall at desired index
	 */
	public function getFileNameSmallAtIndex($index) {
		return $this->fileNameSmall[$index];
	}

	/**
	 * 
	 * @param int $index Desired fileNameThumb index to return
	 * @return string returns fileNameThumb at desired index
	 */
	public function getFileNameThumbAtIndex($index) {
		return $this->fileNameThumb[$index];
	}

	/**
	 * @return string returns all file names of images
	 */
	public function echoAllFileNames() {
		echo "<pre>";
		print_r($this->fileNameFull);
		print_r($this->fileNameSmall);
		print_r($this->fileNameThumb);
		echo "</pre>";
	}

	/**
	 * @param int $index desired index of filePathfull
	 * @return string FilePathFull at index number
	 */
	public function getFilePathFullAtIndex($index) {
		return $this->filePathFull[$index];
	}

	/**
	 * @param int $index desired index of filePathSmall
	 * @return string FilePathSmall at index number
	 */
	public function getFilePathSmallAtIndex($index) {
		return $this->filePathSmall[$index];
	}

	/**
	 * @param int $index desired index of filepathThumb
	 * @return string FilePathThumb at index number
	 */
	public function getFilePathThumbAtIndex($index) {
		return $this->filePathThumb[$index];
	}

	/**
	 * @return string return all filePaths
	 */
	public function echoAllPathNames() {
		echo "<pre>";
		print_r($this->filePathFull);
		print_r($this->filePathSmall);
		print_r($this->filePathThumb);
		echo "</pre>";
	}

	/**
	 * Creates the uploading directory and all children directories
	 *  if they do not exist
	 * 
	 * @param string $input File path for the start of the uploading directory
	 * 
	 * @return string returns the full folder file path for the uploading directory
	 */
	protected function createUploadingDirectory($input) {
		$filePathDirectories = explode('/', $input);
		$filePathDirectoriesCount = count($filePathDirectories) - 2;
		$lastNestedFolder = $filePathDirectories[$filePathDirectoriesCount];

		if (strtolower($lastNestedFolder) !== 'full') {
			$input .= 'full/';
		}
		if (!file_exists($input)) {

			try {
				if (!mkdir($input . '1/', 0777, true)) {
					throw new Exception('Failed to make directory.', '2001');
				}
			} catch (Exception $e) {
				$this->allUploadErrorMessages[] = $e->getMessage();
				$this->allUploadErrorCodes[] = $e->getCode();
				$this->allUploadErrorsLineNumber[] = $e->getLine();
				$this->allUploadErrorsFileName[] = $e->getFile();
			}
			try {
				if (!mkdir(str_replace('full/', 'small/1/', $input), 0777, true)) {
					throw new Exception('Failed to make directory.', '2001');
				}
			} catch (Exception $e) {
				$this->allUploadErrorMessages[] = $e->getMessage();
				$this->allUploadErrorCodes[] = $e->getCode();
				$this->allUploadErrorsLineNumber[] = $e->getLine();
				$this->allUploadErrorsFileName[] = $e->getFile();
			}
			try {
				if (!mkdir(str_replace('full/', 'thumbnail/1/', $input), 0777, true)) {
					throw new Exception('Failed to make directory.', '2001');
				}
			} catch (Exception $e) {
				$this->allUploadErrorMessages[] = $e->getMessage();
				$this->allUploadErrorCodes[] = $e->getCode();
				$this->allUploadErrorsLineNumber[] = $e->getLine();
				$this->allUploadErrorsFileName[] = $e->getFile();
			}
		}
		return $input;
	}

	/**
	 * Retain the image transparency for gif and png images
	 * 
	 * @param string $image image resource
	 */
	protected function keepImageTransparency($image) {
		imagealphablending($image, false);

		try {
			if (!imagesavealpha($image, true)) {
				throw new Exception('Image alpha could not be saved', 1005);
			}
		} catch (Exception $e) {
			$this->allUploadErrorMessages[] = $e->getMessage();
			$this->allUploadErrorCodes[] = $e->getCode();
			$this->allUploadErrorsLineNumber[] = $e->getLine();
			$this->allUploadErrorsFileName[] = $e->getFile();
		}
	}

	/**
	 * Create a standard MySQL query for quick access to a query
	 * 
	 * This may not suit everyone ones needs so it may be ignored
	 * 
	 * The order of the query goes as follows.....
	 * (
	 * 	fileNameFull, fileNameSmall, fileNameThumb,
	 * 	filePathFull, filePathSmall, filePathThumb
	 * )
	 * 
	 * These functions have been "overloaded" to allow for one or two more fields
	 * 		to be applied to the beginning of the query so it could be something
	 * 		like the following....
	 * 	(
	 * 	primaryKey, fileNameFull, fileNameSmall, fileNameThumb,
	 * 	filePathFull, filePathSmall, filePathThumb
	 * 	)
	 * or....
	 * 	(
	 * 	primaryKey, userID, fileNameFull, fileNameSmall, fileNameThumb,
	 * 	filePathFull, filePathSmall, filePathThumb
	 * 	)
	 * 
	 * @return string returns a MySQL formatted query
	 */
	public function returnMysqlQuery() {
		$q = '';
		if (func_num_args() === 0) {
			for ($i = 0; $i < $this->getNumberOfSuccessfulUploads(); $i++) {
				$q .= '("' . $this->getFileNameFullAtIndex($i) . '", "' . $this->getFileNameSmallAtIndex($i) . '",
						"' . $this->getFileNameThumbAtIndex($i) . '", "' . $this->getFilePathFullAtIndex($i) . '",
						"' . $this->getFilePathSmallAtIndex($i) . '", "' . $this->getFilePathThumbAtIndex($i) . '"
						),';
			}
		} else if (func_num_args() === 1) {
			for ($i = 0; $i < $this->getNumberOfSuccessfulUploads(); $i++) {
				$q .= '("' . func_get_arg(0) . '", 
						"' . $this->getFileNameFullAtIndex($i) . '", "' . $this->getFileNameSmallAtIndex($i) . '",
						"' . $this->getFileNameThumbAtIndex($i) . '", "' . $this->getFilePathFullAtIndex($i) . '",
						"' . $this->getFilePathSmallAtIndex($i) . '", "' . $this->getFilePathThumbAtIndex($i) . '"
						),';
			}
		} else if (func_num_args() === 2) {
			for ($i = 0; $i < $this->getNumberOfSuccessfulUploads(); $i++) {
				$q .= '("' . func_get_arg(0) . '", "' . func_get_arg(1) . '", 
						"' . $this->getFileNameFullAtIndex($i) . '", "' . $this->getFileNameSmallAtIndex($i) . '",
						"' . $this->getFileNameThumbAtIndex($i) . '", "' . $this->getFilePathFullAtIndex($i) . '",
						"' . $this->getFilePathSmallAtIndex($i) . '", "' . $this->getFilePathThumbAtIndex($i) . '"
						),';
			}
		}

		return substr($q, 0, -1);
	}

	/**
	 * @return string returns a MySQL query for an image uploaded from a URL
	 */
	public function returnURLMySQLQuery() {
		$q = "";
		if (func_num_args() === 0) {
			for ($i = 0; $i < $this->getNumberOfSuccessfulUploads(); $i++) {
				$q .= '("' . $this->getFileNameFullAtIndex($i) . '", "' . $this->getFilePathFullAtIndex($i) . '"),';
			}
		} else if (func_num_args() === 1) {
			for ($i = 0; $i < $this->getNumberOfSuccessfulUploads(); $i++) {
				$q .= '("' . func_get_arg(0) . '", "' . $this->getFileNameFullAtIndex($i) . '", "' . $this->getFilePathFullAtIndex($i) . '"),';
			}
		} else if (func_num_args() === 2) {
			for ($i = 0; $i < $this->getNumberOfSuccessfulUploads(); $i++) {
				$q .= '("' . func_get_arg(0) . '", "' . func_get_arg(1) . '", "' . $this->getFileNameFullAtIndex($i) . '", "' . $this->getFilePathFullAtIndex($i) . '"),';
			}
		}

		return substr($q, 0, -1);
	}

}

?>
