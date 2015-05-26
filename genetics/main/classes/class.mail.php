<?php
require_once("class.phpmailer.php");
require_once("class.smtp.php");

class my_phpmailer extends PHPMailer {
    // Set default variables for all new objects
	public $From = "healtheducation@umich.edu";
    public $FromName = "HEALth System Automated Response - Do Not Respond";
    public $Host     = "localhost";
    public $Mailer   = "smtp";                         // Alternative to IsSMTP()
    public $WordWrap = 100;

function error_handler($msg) { 
	print("Mail Error"); 
	print("Description:"); 
	printf("%s", $msg); 
	exit;
} 

}
?>