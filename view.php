<?php

// open the file in a binary mode
$name = $_GET['file'];
$path = '/var/www/html/uploads/';
$file_path = $path . $name;
error_log($file_path);
$fp = fopen($file_path, 'rb');
// send the right headers
header("Content-Type: image/jpg");
header("Content-Length: " . filesize($file_path));
# old
/*

# new
header("Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"downloaded_file\""); 
*/
// dump the picture and stop the script
fpassthru($fp);
exit;

?>
