<?php 
$security_needed = 1; 
include './security_check.php';

$dc = $_GET['dc'];
if ($row = $db->get_row("SELECT * FROM decisions_chosen WHERE dc_id = $dc AND dc_usr_id = $s_usr")) {
	$sql = "DELETE FROM decisions_chosen WHERE dc_id = $dc";
	$db->query($sql);
}
header ("Location: cd_home.php"); 

?>