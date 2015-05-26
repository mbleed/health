<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./shared/topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./shared/pi_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('./shared/toolmenu.php');

	$que_id = $_GET['que'];
	if ($que_id > 0) {
		$qa = $db->get_row("SELECT * FROM question INNER JOIN answer ON que_ans_id = ans_id WHERE que_id = $que_id");
		$selected_que_type_id = $db->get_var("SELECT que_type_id FROM question_type qt INNER JOIN question q ON q.que_que_type_id = qt.que_type_id WHERE q.que_id = $que_id");
		$save_res_url = "../m$s_mod/$qa->ans_media";
		$save_res_type = $qa->ans_type; 
		//$save_text = "<em>$qa->que_txt</em><br>$qa->ans_txt";
		$save_text = '';
		//if que selected, save type for tree auto expand
		$expand_to_que_type = $qa->que_que_type_id;
	}
	//echo "<pre>"; print_r($qa); echo "</pre>";

if ($que_id > 0) {
	//check if coming from this page or the save routine to know whether to autoplay the media clip.
	if ($_GET['start'] == 'false') $autostart = '';
	else $autostart = 'autoplay="autoplay"';
	if ($qa->ans_type == 'vid') {
$video_player .= <<<eoq
		<video controls="controls" $autostart>
			<source src="/genetics/main/$s_path/$qa->ans_media" />
		</video>
eoq;
	}
	if ($qa->ans_type == 'txt') {
		$dir = "$s_path/img/interview";
		$images = scandir($dir);
		$getridofperiods = array_shift($images);
		$getridofperiods = array_shift($images);
		$random_key = array_rand($images);
		$img = $images[$random_key];
		$video_player .= "<img src=$dir/$img class='image-polaroid'>";
	}
}

	if (isset($_POST["submit"])) {
		$search_html .= "<div id=step>";
		$search .= "<h3>Search Results:</h3>";
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
			$search_html .= "<ul>";
			foreach($rows as $row) {
				$search_html .= "<li><a href=pi_qa_new.php?que=$row->que_id>$row->que_txt</a></li>";
			}
			echo "</ul>";
		} else { $search_html .= "No questions with that keyword found, try again."; }
	}
    
	//get data from database to create drilldown menu
	$question_types = $db->get_results("SELECT * FROM question_type ORDER BY que_type_order ASC");
	//print_r($question_types);
    	foreach ($question_types as $qt) {
		$accordion_open = ($selected_que_type_id == $qt->que_type_id) ? 'in' : '';
$questions_html .= <<<htmleoq
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_questions" href="#collapse_$qt->que_type_id">
					$qt->que_type
				</a>
			</div>
			<div id="collapse_$qt->que_type_id" class="accordion-body collapse $accordion_open">
				<div class="accordion-inner">
				<ul>
htmleoq;

			$questions = $db->get_results("SELECT * FROM question WHERE que_que_type_id = $qt->que_type_id AND que_mod_id = $s_mod ORDER BY que_order ASC");
			foreach ($questions as $q) {
				$questions_html .= "<li><a href='pi_qa_new.php?que=$q->que_id'>$q->que_txt</a></li>";
			}

$questions_html .= <<<htmleoq
				</ul>
				</div>
			</div>
		</div>
htmleoq;
	}
	
	$body .= <<<eoq
	
<div class="fluid-row">
<div class="span6 well">

<form name="frm1" method="post" action="pi_qa_new.php">
	<div class="alert alert-info">Type in a keyword of a question you would like to ask the patient.  Or choose a question from the categories below.</div>	
	<input type="text" name="keyword">
	<input type="submit" name="submit" value="Find Question" class="btn btn-primary">
</form>
$search_html

<hr />

<div class="accordion" id="accordion_questions">
	$questions_html
</div><!-- end accordion -->

</div>

<div class="span6">

	<div id="movie_transcript">
		<i>$qa->que_txt</i>
		<br>
		$qa->ans_txt
	</div>
	
	$video_player

</div>

</div>

eoq;
//end body

include './shared/template.php'; //add in the standard page header 
?>