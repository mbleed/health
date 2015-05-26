<?php
$security_needed = 0; 
include './security_check.php';

	if ($_POST["submit"] <> "") 
	{
		$s_usr_email = $_POST['usr_email'];
		if ($row = $db->get_row("SELECT usr_nm, usr_ps_reminder FROM usr WHERE usr_email = '$s_usr_email'")) {
			$mailbody  = "This is an automated response to your request at the Genetic Education in Dentistry website.\n\n";
			$mailbody .= "Username: $row->usr_nm\n";
			$mailbody .= "Password Reminder: $row->usr_ps_reminder";
			$header = "Return-Path: geneticseducation@umich.edu\n";
      			$header .= "X-Sender: geneticseducation@umich.edu\n";
      			$header .= "From: Genetics Education in Dentistry <geneticseducation@umich.edu>\n";
      			$header .= "X-Mailer:PHP 5.1\n";
      			$header .= "MIME-Version: 1.0\n";
 			mail($s_usr_email,"Automated Reminder",$mailbody,$header);
			$msg = "<h3>An email has been sent to $s_usr_email with your username and password reminder.</h3>";
		} else {
			$msg = "<h3>The email address: $s_usr_email is not listed on our database.</h3>";
		}
	} else { 

$msg .= <<<eoq

	<h3>
	Type in your email address below and we will email you your username and password reminder.  If you are still experiencing trouble,
	please feel free to contact us.
	</h3>
	<form name="forgotpass" method="post">
	<input type="text" name="usr_email" maxlength="50" size="50"><br><br>
	<input type="submit" name="submit" value="Send Reminder" border="0">
	</form>

eoq;
	}

$body .= <<<eoq
  
<div id="actionbox">

<br>
<h2>Password Reminder</h2>

<p>
$msg
</p>

</div>

eoq;
//end body

include './template.php'; //create (render) webpage 
?>