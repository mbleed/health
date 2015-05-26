<?php 
$security_needed = 0;
include('./security_check_info.php');

//include topmenu, is stored in the $topmenu variable
include ('./info_topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./info_pi_menu.php');
$body .= $topmenu2;

	$que_id = $_GET['que'];
	if ($que_id > 0) {
		$qa = $db->get_row("SELECT * FROM question INNER JOIN answer ON que_ans_id = ans_id WHERE que_id = $que_id");
		$save_res_url = "../m$s_mod/$qa->ans_media"; 
		$save_res_type = $qa->ans_type; 
		//$save_text = "<em>$qa->que_txt</em><br>$qa->ans_txt";
		$save_text = '';
	}

$body .= <<<eoq

<script language="JavaScript" type="text/javascript" src="src/AC_QuickTime.js"></script>

<style>
	.videoplayer {
		position: relative;
		top: 0;
		right: 0;
		width: 320px;
	}
</style>

<div id="actionbox">

<form name="frm1" method="post" action="info_pi_qa.php">
<div id=step>
	<h1>Type in a keyword of a question you would like to ask the patient.  Or choose a question from the categories below.</h1> 

<div class="videoplayer" id="videoplayer">

eoq;

if ($que_id > 0) {
	//check if coming from this page or the save routine to know whether to autoplay the media clip.
	if ($_GET['start'] == 'false') $autostart = 'false';
	else $autostart = 'true';
	if ($qa->ans_type == 'vid') {
		$body .= <<<eoq

<script language="JavaScript" type="text/javascript" >
QT_WriteOBJECT(
  	"$s_path/$qa->ans_media", "320", "256", "",
  	"autoplay","$autostart",
  	"align","middle"
);
</script>

eoq;

	}
	if ($qa->ans_type == 'txt') {
		$dir = "$s_path/img/interview";
		$images = scandir($dir);
		$getridofperiods = array_shift($images);
		$getridofperiods = array_shift($images);
		$random_key = array_rand($images);
		$img = $images[$random_key];
		$body .= "<img src=$dir/$img>";
	}
}
	$body .= <<<eoq

	<div id="movie_transcript">
	<i>$qa->que_txt</i>
	<br>
	$qa->ans_txt
	</div>
</div>

	<h3>Question Keyword:</h3>
	<input type=text name=keyword>
	<input type=submit name=submit value="Find Question">

eoq;

	if ($_POST["submit"] <> "") {
		$body .= "<div id=step>";
		$body .= "<h1>Click the question most like the one you want to ask and the patient will respond with an answer.</h1>";
		$body .= "<h3>Questions:</h3>";
		$keyword = $_POST["keyword"];
		$where = "que_txt LIKE '%$keyword%'";
		if ($syn_string = $db->get_var("SELECT syn FROM synonym WHERE word = '$keyword'")) {
		  	$synonyms = explode(',',$syn_string);
			foreach ($synonyms as $synonym) {
				$where .= " OR que_txt LIKE '%$synonym%'";
			}
		}
		$sql = "SELECT * FROM question WHERE (($where) AND que_mod_id = $s_mod)";
		if ($rows = $db->get_results($sql)) {
			$body .= "<ul>";
			foreach($rows as $row) {
				$body .= "<li><a href=info_pi_qa.php?que=$row->que_id>$row->que_txt</a></li>";
			}
			echo "</ul>";
		} else { $body .= "No questions with that keyword found, try again."; }
	}
    
    	$body .= "<h3>Questions by Category:</h3>";
	//get data from database to create drilldown menu
	$question_types = $db->get_results("SELECT * FROM question_type ORDER BY que_type_order ASC");

	$body .= <<<eoq

<script type="text/javascript">
var tree;
function treeInit() {
	tree = new YAHOO.widget.TreeView("que_treeDiv");
   	var root = tree.getRoot();

eoq;
    	foreach ($question_types as $qt) {
		$body .= "var node_$qt->que_type_id = new YAHOO.widget.TextNode(\"$qt->que_type\", root, false); \n";
		$questions = $db->get_results("SELECT * FROM question WHERE que_que_type_id = $qt->que_type_id AND que_mod_id = $s_mod ORDER BY que_order ASC");
		foreach ($questions as $q) {
	   		$body .= "var q_$q->que_id = new YAHOO.widget.HTMLNode(\"<a href='info_pi_qa.php?que=$q->que_id'>$q->que_txt</a>\", node_$qt->que_type_id, false); \n";
			//link = info_pi_qa.php?que=$q->que_id
		}
	}
	$body .= <<<eoq

   	tree.draw();
}

YAHOO.util.Event.addListener(window, "load", treeInit);
</script>
<div id="que_treeDiv"></div>

</div>

</form>

</div>

eoq;
//end body

include './template_info.php'; //add in the standard page header 
?>