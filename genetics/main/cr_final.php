<?php
//error_reporting(E_ALL); 
$security_needed = 1; 
include './security_check.php';
require_once("./classes/dompdf/dompdf_config.inc.php");

//pdf path
$reportpath = "../reports/";
$pdffilename = $reportpath."m".$s_mod."_g".$s_grp.".pdf";
if (file_exists($pdffilename)) unlink($pdffilename);
//update status of case report to filed
if ($cr = $db->get_row("SELECT * FROM case_report WHERE cr_grp_id = $s_grp AND cr_mod_id = $s_mod")) {
	$db->query("UPDATE case_report SET cr_status = 1, cr_by = $s_usr, cr_dt = now() WHERE cr_id = $cr->cr_id");
} else {
	$db->query("INSERT INTO case_report (cr_mod_id, cr_grp_id, cr_dt, cr_by, cr_pdf, cr_status) VALUES ($s_mod, $s_grp, now(), $s_usr, '$pdffilename', 1)");
	$cr = $db->get_row("SELECT * FROM case_report WHERE cr_grp_id = $s_grp AND cr_mod_id = $s_mod");
} 
//Gather data to be displayed in the PDF
include 'cr_pdf_data.php';

//bring in pdf layout, stored in $html
include 'cr_pdf_layout.php';

//create PDF	
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$pdf = $dompdf->output();
file_put_contents($pdffilename, $pdf);
$dompdf->stream($pdffilename, array('Attachment' => 0));

//email the file
$emails_to = array();
if ($ie = $db->get_row("SELECT * FROM usr INNER JOIN (instructor INNER JOIN (classes INNER JOIN groups ON class_id = grp_class_id)  ON class_id = ins_class_id)  ON usr_id = ins_usr_id WHERE grp_id = $s_grp")) 
	$emails_to[] = $ie->usr_email;
if ($group_emails = $db->get_results("SELECT * FROM usr u INNER JOIN (x_usr_grp x INNER JOIN groups g ON g.grp_id = x.grp_id) ON x.usr_id = u.usr_id WHERE g.grp_id = $s_grp")) {
	foreach ($group_emails as $ge) {
		$emails_to[] = $ge->usr_email;
	}
}
include './classes/class.mail.php';
$mail = new my_phpmailer;
$mail->ClearAddresses();
$mail->Subject = "Case Report filed for HEALth";
$mail->Body = "A PDF is attached with your case report that was filed for the Health Education through Active Learning website.";
foreach ($emails_to as $e) $mail->AddAddress($e);
//$mail->AddAddress('mbleed@umich.edu');
$mail->AddAttachment($pdffilename);
//$mail->Send();

//create HTML files
$htmlfilename = $reportpath."m".$s_mod."_g".$s_grp.".html";
$fh1 = fopen($htmlfilename, 'w+');
fwrite($fh1, $html);
fclose($fh1);

//create Text Files
$usertxt = strip_tags(str_replace('<br>', '\n', $html));

$txtfilename = $reportpath."m".$s_mod."_g".$s_grp.".txt";
$fh2 = fopen($txtfilename, 'w+');
fwrite($fh2, $usertxt);
fclose($fh2);

//forward to docs screen so user can view created docs
header("Location: cr_docs.php"); 

?>