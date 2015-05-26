<form method=post>
<select name=fb_type>
	<option value="Implementation Idea">Implementation Ideas</option>
</select>
<input type=submit name=submit value="Show List">
</form>
<?php
include_once('./classes/ez_sql.php');

if ($_POST['submit'] == 'Show List') {
	$selected_type = $_POST['fb_type'];
	$sql = "SELECT * FROM feedback INNER JOIN usr on fb_usr_id = usr_id WHERE fb_type = '$selected_type' ORDER BY fb_dt DESC";

	$rs = $db->get_results($sql);

	echo "<h2>".$selected_type."s</h2>";
	echo "<table>";
	echo "<tr> <th>Date</th> <th>From</th> <th>Feedback</th> </tr>";

	foreach ($rs as $r) {
		echo "<tr>";
		echo "<td>$r->fb_dt</td>";
		echo "<td>$r->usr_fname $r->usr_lname</td>";
		echo "<td>$r->fb_txt</td>";
		echo "</tr>";
	}

	echo "</table>";
}
?>
