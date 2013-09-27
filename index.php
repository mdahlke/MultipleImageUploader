<?php
$uploadOutput = "";
if (isset($_POST['submitForm'])) {
	require 'classes/UploadImage.class.php';

	$imageUploader = new UploadImage();
	$imageUploader->setOriginalFilePath('ImageUpload/FormUpload');
	$imageUploader->setPrependToFileName('testingScript');
	$imageUploader->setNumberOfAllowedFilesToUpload(20);
	$imageUploader->setNumberOfFilesPerDirectory(20);
	$imageUploader->upload($_FILES['image']);

	if ($imageUploader->getNumberOfErrorsDuringUpload() > 0) {
		echo '<pre>' . $imageUploader->returnFormattedErrors() . '</pre>';
	}

	for ($i = 0; $i < $imageUploader->getNumberOfSuccessfulUploads(); $i++) {
		$uploadOutput .= '
						<div class="imageWrapper">
							<a href="' . $imageUploader->getFilePathFullAtIndex($i) .
				$imageUploader->getFileNameFullAtIndex($i) . '">
								<img src="' . $imageUploader->getFilePathSmallAtIndex($i) .
				$imageUploader->getFileNameSmallAtIndex($i) . '"/>
							</a>
						</div>';
	}
} else if (isset($_POST['submitURL'])) {
	require 'classes/SingleURLImageUploader.class.php';

	$URLUploader = new SingleURLImageUploader();
	$URLUploader->setOriginalFilePath('ImageUpload/URLUpload/');
	$URLUploader->setPrependToFileName('testingScript');
	$URLUploader->setNumberOfFilesPerDirectory(20);
	$URLUploader->uploadSingleURLImage($_POST['url']);

	if ($URLUploader->getNumberOfErrorsDuringUpload() > 0) {
		echo '<pre>' . $URLUploader->returnFormattedErrors() . '</pre>';
	}

	for ($i = 0; $i < $URLUploader->getNumberOfSuccessfulUploads(); $i++) {
		$uploadOutput .= '
						<a href="' . $URLUploader->getFilePathFullAtIndex($i) .
				$URLUploader->getFileNameFullAtIndex($i) . '">
							<img src="' . $URLUploader->getFilePathSmallAtIndex($i) .
				$URLUploader->getFileNameSmallAtIndex($i) . '"/>
						</a>';
	}
} else if (isset($_POST['submitAudio'])) {
	require 'classes/UploadAudio.class.php';

	if (!empty($_POST['newDirectory'])) {
		$directory = $_POST['newDirectory'];
	} else {
		$directory = 'AudioUpload/';
	}

	$audioUploader = new UploadAudio();
	$audioUploader->setOriginalFilePath($directory);
	$audioUploader->setPrependToFileName('testingScript');
	$audioUploader->setNumberOfAllowedFilesToUpload(20);
	$audioUploader->upload($_FILES['audio']);

	if ($audioUploader->getNumberOfErrorsDuringUpload() > 0) {
		echo '<pre>' . $audioUploader->returnFormattedErrors() . '</pre>';
	}

	for ($i = 0; $i < $audioUploader->getNumberOfSuccessfulUploads(); $i++) {
		$uploadOutput .= "
			<div class='audioWrapper'>"
				. $audioUploader->getFileNameAtIndex($i)
				. "	<br />
				<audio controls preload='none'
					src='" . $audioUploader->getFilePathAtIndex($i) .
				$audioUploader->getFileNameAtIndex($i) . "' 
					type='" . $audioUploader->getFullFileTypeAtIndex($i) . "'>
					Your browser does not support HTML audio
				</audio>
			</div>
		";
	}
} else if (isset($_POST['submitDocument'])) {
	require 'classes/UploadDocument.class.php';

	$documentUploader = new UploadDocument();

	if (!empty($_POST['newDirectory'])) {
		$documentUploader->setOriginalFilePath($_POST['newDirectory']);
	}

	$documentUploader->setPrependToFileName('testingScript');
	$documentUploader->setNumberOfAllowedFilesToUpload(20);
	$documentUploader->upload($_FILES['document']);

	if ($documentUploader->getNumberOfErrorsDuringUpload() > 0) {
		echo '<pre>' . $documentUploader->returnFormattedErrors() . '</pre>';
	}

	for ($i = 0; $i < $documentUploader->getNumberOfSuccessfulUploads(); $i++) {
		$uploadOutput .= "
			<div class='audioWrapper'>
                <br />
				<pre>" . $documentUploader->getFilePathAtIndex($i) . $documentUploader->getFileNameAtIndex($i) . "</pre>
			</div>
		";
		
		$details.$i = new FileDetails();
		$details.$i->setCurrentDir($documentUploader->getFilePathAtIndex($i));
		$details.$i->setFile($documentUploader->getFileNameAtIndex($i));
		$details.$i->printFileDetails();
		
		$doc = fopen($documentUploader->getFilePathAtIndex($i) . $documentUploader->getFileNameAtIndex($i), 'r');
		
		if( true ){
			$content = fread($doc, filesize($doc));
		}
		else {
			echo $documentUploader->getFilePathAtIndex($i) . $documentUploader->getFileNameAtIndex($i) . '<br />';
		}
		
		echo strip_tags(nl2br($content));
	}
	
}

?>

<!DOCTYPE html>
<head>
	<title>Image Class Testing Script</title>
	<link href="css/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<article>
		<h1 class="outline">PHP File Uploader</h1>

		<form action="" method="post" enctype="multipart/form-data">
			<h3 class="outline"><label for="image">Select Image(s):</label></h3>


			<input type='file' name='image[]' multiple/>
			<input type="submit" name="submitForm" value="Upload Image(s)"/>

			<br />
		</form>

		<form action="" method="post" enctype="multipart/form-data">
			<h3 class="outline"><label for="image">Enter URL of image:</label></h3>


			<input type='url' name='url' placeholder="Enter URL"/>
			<input type="submit" name="submitURL" value="Upload URL"/>
		</form>

		<hr class="clear thinGrayHR" />

		<form action="" method="post" enctype="multipart/form-data">
			<h3 class="outline"><label for="image">Select Audio:</label></h3>


			<input type='file' name='audio[]' multiple/>
			<br />
			<br />
			<label>New directory (ex. audio/playlists/Eminem/)</label>
			<br />
			<input type="text" name="newDirectory" />
			<br />
			<small>(May be left blank for default upload location)</small>
			<br />
			<input type="submit" name="submitAudio" value="Upload Audio"/>
		</form>

		<form action="" method="post" enctype="multipart/form-data">
			<h3 class="outline"><label for="image">Select Document:</label></h3>


			<input type='file' name='document[]' multiple />
			<br />
			<br />
			<label>New directory (ex. documents/school/fall-2013/)</label>
			<br />
			<input type="text" name="newDirectory" />
			<br />
			<small>(May be left blank for default upload location)</small>
			<br />
			<input type="submit" name="submitDocument" value="Upload Document"/>
		</form> 
	</article>
	<article>
<?php
if (!empty($uploadOutput)) {
	echo "
                                <hr class='clear thinGrayHR' />
                                <div id='uploadOutput'>
                                    <h2>Your uploaded file(s):</h2>
                                    $uploadOutput
                                </div>
                            ";
}

?>
	</article>
	<footer>
		Created By: <a href="https://github.com/mdahlke" target="_blank">Michael Dahlke</a>
	</footer>
</body>
</html>