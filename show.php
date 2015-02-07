<?php
// Include and instantiate the class.
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
 
// Any mobile device (phones or tablets).
if ($detect->isMobile()){
	include('showinfo-mobile.php');
}
else{
	include('showinfo.php');
}
?>