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

$encrypted_filename = $_GET['file'];
$key = $_GET['x'];
$password = $_GET['password'];

if(time() - intval($key) > 86400)
{
	echo "Sorry, you are no longer have access to this file or it has been removed. Please contact the file owner.<br/><br/>";
	exit;
}

$uploaded_filename = encrypt_decrypt_short_string($key, urldecode($encrypted_filename));
$full_filename = '';
if(hasValue($password))
{
	$full_filename = $password . '_' . $uploaded_filename;
}
else
{
	$full_filename = '_' . $uploaded_filename;
}

$path = '/var/www/html/uploads/';
$file_path = $path . $full_filename;

if(!file_exists($file_path))
{
	echo "Sorry, you are no longer have access to this file or it has been removed. Please contact the file owner.<br/><br/>";
	exit;
}

$fp = fopen($file_path, 'rb');
header("Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"" . $uploaded_filename . "\""); 
fpassthru($fp);
fclose($fp);
exit;

?>
