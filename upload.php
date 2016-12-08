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

function encrypt_decrypt_short_string($key, $filename)
{
	$encrypted = "";
	$len_filename = strlen($filename);
	$len_key = strlen($key);
	for($i = 0; $i < $len_filename;)
	{
		for($j = 0; $j < $len_key; $j++, $i++)
		{
			$encrypted .= $filename{$i} ^ $key{$j};
		}
	}
	return $encrypted;
}

$target_dir = "/var/www/html/uploads";
$passphrase = $_POST['passphrase'];
$target_file = "";

if (!file_exists($target_dir))
{
    mkdir($target_dir, 0755, true);
}

if(hasValue($passphrase))
{
	$target_file = $target_dir . '/' .  $passphrase . '_' . basename($_FILES["fileToUpload"]["name"]);
}
else
{
	$target_file = $target_dir . '/_' . basename($_FILES["fileToUpload"]["name"]);
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
if ($_FILES["fileToUpload"]["size"] > 1000000)
{
	echo "Sorry, your file is too large, files 1 MB or less are allowed<br/><br/>";
	$uploadOk = 0;
}

if ($uploadOk == 0)
{
        echo "No file was uploaded<br/><br/>";
}
else
{
	$url = '';
        $time_now = strval(time());
	$encrypted_filename = urlencode(encrypt_decrypt_short_string($time_now, basename($_FILES["fileToUpload"]["name"])));

	if(hasValue($passphrase))
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . "/download.php?x=" . $time_now . "&file=" . $encrypted_filename . '&' . "password=";
	}
	else
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . "/download.php?x=" . $time_now . "&file=" . $encrypted_filename;
	}

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
	{
		echo "The file has been successfully uploaded<br/><br/>";
		if(hasValue($passphrase))
		{
			echo "You can now share this link for downloading (<i>see note below</i>) -  <a href='$url'>$url</a><br/><br/>";
			echo "<b>";
			echo "CAUTION: Because you selected a password, the link above will NOT work unless the password is appended at the end of the link!<br/><br/>";
			echo "The link is shown WITHOUT the actual password. Anyone in possession of the link must copy and paste the link on their browser address box,<br/>";
			echo "followed by the password immediately after the link, which you must share with them.<br/><br/>";
			echo "</b>";
			echo "Please make a note of your password, the system does not store it, so we cannot retrieve if for you if you lose it:<br/>";
			echo $passphrase;
		}
		else
		{
			echo "You can now share this link for downloading: <a href='$url'>$url</a>";
		}
	}
	else
	{
		echo "There was an error uploading your file";
	}
}
?>
