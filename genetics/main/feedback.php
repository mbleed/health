<?php
if ($_SERVER['HTTP_REFERER'] == 'feedback_process.php') {

$feedback .= <<<eoq

<div class="feedback">
<h3>Thank you for entering an implementation idea!</h3>
</div>

eoq;

} else {

$body .= <<<eoq

<div class="feedback" style="border: 1px solid #AA0000; padding: 6px; background-color:#EFEFEF">
<h4 style="color: #AA0000; margin: 0px;">Please enter any implementation ideas you may have for this application.</h4>
<form action=feedback_process.php method=POST>
<textarea name=feedback rows=2 cols=50></textarea>
<input type=hidden name=fb_type value="Implementation Idea">
<input type=submit value="Send Idea">
</form>
</div>

eoq;

}

?>