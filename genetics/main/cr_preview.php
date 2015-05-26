<?php 

$security_needed = 1; 
include './security_check.php';

require_once("./classes/dompdf/dompdf_config.inc.php");

//Gather data to be displayed in the PDF
include 'cr_pdf_data.php';

//bring in pdf layout, stored in $html
include 'cr_pdf_layout.php';

//create PDF	
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("casereport.pdf", array("Attachment" => 0));

?>