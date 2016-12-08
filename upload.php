<?php

function hasValue($input)
{
	$strTemp = trim($input);
	if($strTemp !== '')
	{
		return true;
	}
	return false;
}

$target_dir = "/var/www/html/uploads/";
$passphrase = $_POST['passphrase'];
$target_file = "";

if(hasValue($passphrase))
{
	$target_file = $target_dir . $passphrase . '_' . basename($_FILES["fileToUpload"]["name"]);
}
else
{
	$target_file = $target_dir . '_' . basename($_FILES["fileToUpload"]["name"]);
}

$uploadOk = 1;

if(!hasValue($_FILES["fileToUpload"]["name"]))
{
	echo "Sorry, please select a file to upload<br/><br/>";
	$uploadOk = 0;
}

if(!isset($_POST["submit"]))
{
	echo "Sorry, please select a file to upload<br/><br/>";
	$uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 1000000) {
    echo "Sorry, your file is too large, files 1 MB or less are allowed<br/><br/>";
    $uploadOk = 0;
}
if ($uploadOk == 0)
{
    echo "No file was uploaded<br/><br/>";
}
else
{
    $base_file_name = basename($target_file);
    $url = 'http://' . $_SERVER['SERVER_NAME'] . "/download.php?file=" . basename($_FILES["fileToUpload"]["name"]) . '&' . "password=";
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
	{
		echo "The file has been successfully uploaded<br/><br/>";
		echo "You can now share this URL: <a href='$url'>$url</a>";
	}
	else
	{
		echo "There was an error uploading your file";
	}
}
?>
