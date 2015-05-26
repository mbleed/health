<?php 
	$s_mod = 10;
	include "./classes/ez_sql.php"; //include db class 
$i = 0;
if (isset($_POST['submit'])) {
	foreach ($_POST as $var=>$val) $$var = $db->escape($val);
	$af2 = "vid/".$af;
	$a_sql = "INSERT INTO answer (ans_txt, ans_media, ans_type) VALUES ('$a', '$af2', 'txt')";
	$db->query($a_sql);
//$db->debug();
	$a_id = $db->insert_id;
	$q_sql = "INSERT INTO question (que_txt, que_ans_id, que_mod_id, que_que_type_id, que_order) VALUES ('$q', $a_id, $s_mod, $t, $a_id)";	
	$db->query($q_sql);
//$db->debug();
}

	$question_types = $db->get_results("SELECT * FROM question_type ORDER BY que_type_order ASC");
//$db->debug();
    foreach ($question_types as $qt) {
		$body .= "<h2>$qt->que_type</h2><ul>";
		$qas = $db->get_results("SELECT * FROM question INNER JOIN answer ON que_ans_id = ans_id WHERE que_que_type_id = $qt->que_type_id AND que_mod_id = $s_mod ORDER BY que_order ASC");
//$db->debug();
		foreach ($qas as $qa) {
			$body .= "<li><b>$qa->que_txt</b>$qa->ans_txt";
		}
		$body .= "</ul>";
    }
	$html .= <<<eoq

<form method=post>

<select name="t">
	<option value="1">Medical History</option>
	<option value="2">Dental History</option>
	<option value="3">Treatment Planing</option>
	<option value="4">Family History</option>
	<option value="5">Chief Complaint</option>
</select>
<br /><br />

Question: <textarea name="q" rows=2 cols=100></textarea>
<br /><br />

Answer: <textarea name="a" rows=2 cols=100></textarea>
<br /><br />
file: <input type="text" name="af" />
<br><br>

<input type=submit name=submit />

</form>

$body

eoq;

	echo $html;
?>
