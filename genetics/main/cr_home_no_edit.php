<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

//get email list
$ie = $db->get_row("SELECT * FROM usr INNER JOIN (instructor INNER JOIN (classes INNER JOIN groups ON class_id = grp_class_id)  ON class_id = ins_class_id)  ON usr_id = ins_usr_id WHERE grp_id = $s_grp");
$instructor_email_html = "$ie->usr_fname $ie->usr_lname ($ie->usr_email)";
$group_email_list = "";
if ($group_emails = $db->get_results("SELECT * FROM usr u INNER JOIN (x_usr_grp x INNER JOIN groups g ON g.grp_id = x.grp_id) ON x.usr_id = u.usr_id WHERE g.grp_id = $s_grp")) {
	foreach ($group_emails as $ge) {
		$group_email_list .= "<li>$ge->usr_fname $ge->usr_lname ($ge->usr_email) </li>";
	}
}

$email_list_html = <<<htmleoq

<div id="panel_final" style="font-size: 122%;">
	<div class="bd">
		<h3>Are you sure you want to file your group final report? The report will be emailed to:</h3>
		<h3>
			The Class Instructor: 
		</h3>
		$instructor_email_html
		<h3>
			Each group member:
		</h3>
		<ul>
			$group_email_list
		</ul>
	</div>
</div>

htmleoq;

$body .= <<<htmleoq

	<h2>Case Report</h2>
	
htmleoq;

//see if there is a case report filed for this group
if ($cr = $db->get_row("SELECT * FROM case_report WHERE cr_mod_id = $s_mod AND cr_grp_id = $s_grp AND cr_status = 1")) {
	$urow = $db->get_row("SELECT * FROM usr WHERE usr_id = $cr->cr_by");
	$filed_by = $urow->usr_fname." ".$urow->usr_lname;
	
	//found report filed, show who filed and a view report button, do not show editors
	$fn = $cr->cr_pdf;
	if (file_exists($fn)) $view_report_button .= "<a href=$fn>View</a>";

	$body .= <<<htmleoq
<style>
#button_view span a {
  	padding-left: 2em;
   	background:url('img/page_white_acrobat.gif') left center no-repeat;
}
</style>
<script>
init_button_view = function () {
	button_view = new YAHOO.widget.Button("button_view");
}

YAHOO.util.Event.onContentReady('button_view', init_button_view);
</script>

	Your group's report was filed by: $filed_by on: $cr->cr_dt
	<span id="button_view"><span class="first-child">$view_report_button</span></span>

htmleoq;

} else {
	
	//show editors and preview and file buttons
	
	//get tabs from db
	$tab_list_html = "";
	$tab_content_html = "";
	$ts = $db->get_results("SELECT rsc_cat FROM report_sections_cat ORDER BY rsc_order ASC");
	foreach ($ts as $t) {
		$tab_abbrev = substr($t->rsc_cat,0,3);
		$tab_list_html .= "<li><a href=\"#$tab_abbrev\"><em>$t->rsc_cat</em></a></li>";
		
		$tab_content_html .= "<div class=\"indexbox\" id=\"$tab_abbrev\"><div class=\"subbox\">";
		//get tab contents
		
		$tcs = $db->get_results("SELECT * FROM report_sections WHERE rs_cat = '$t->rsc_cat' ORDER BY rs_order ASC");
		foreach ($tcs as $tc) {
			//get previously saved text
			$section_text = $db->get_var("SELECT rss_text FROM report_sections_saved WHERE rss_fieldid = '$tc->rs_fieldid' AND rss_grp_id = $s_grp AND rss_mod_id = $s_mod");
			$tab_content_html .= <<<htmleoq
			
				<h3>$tc->rs_title</h3>
    			<div id="$tc->rs_fieldid" class="editable">$section_text</div>
			
htmleoq;
		}
		$tab_content_html .= "</div></div>";
		//$tabs[] = array("list_html"=>$tab_list_html, "content_html"=>$tab_content_html);
	}
	$body .= <<<htmleoq

$email_list_html

<style>
#button_final span a {
  	padding-left: 2em;
   	background:url('img/email_attach.png') left center no-repeat;
}
#button_preview span a {
  	padding-left: 2em;
   	background:url('img/magnifier.gif') left center no-repeat;
}
</style>
<script>
init_button_final = function () {
	button_final = new YAHOO.widget.Button("button_final");
}
YAHOO.util.Event.onContentReady('button_final', init_button_final);

init_panel_final = function () {
	//YAHOO.panel_final = new YAHOO.widget.Panel("panel_final", { width:"800px", visible:false, constraintoviewport:true } );
	var handleYes = function() {
 		window.location='cr_final.php';
	}
	var handleNo = function() {
 		this.hide();
	}
	YAHOO.panel_final = new YAHOO.widget.SimpleDialog("panel_final", 
																			 { width: "800px",
																			   fixedcenter: true,
																			   visible: false,
																			   close: true,
																			   text: document.getElementById('panel_final').innerHTML,
																			   icon: YAHOO.widget.SimpleDialog.ICON_HELP,
																			   constraintoviewport: true,
																			   buttons: [ 	{ text:"Yes", handler:handleYes, isDefault:true },
																						  			{ text:"No",  handler:handleNo } 
																				]
																			 } );
	YAHOO.panel_final.render();
}
YAHOO.util.Event.onContentReady('panel_final', init_panel_final);

function confirm_final() {
		YAHOO.panel_final.show();
}

init_button_preview = function () {
	button_preview = new YAHOO.widget.Button("button_preview");
}

YAHOO.util.Event.onContentReady('button_preview', init_button_preview);
</script>

<span id="button_preview"><span class="first-child"><a href="cr_preview.php">Preview Report</a></span></span>
<span id="button_final"><span class="first-child"><a href="#" onClick="confirm_final();return false;">File Final Report</a></span></span>

<style>
    .yui-editor-container {
        position: absolute;
        top: -9999px;
        left: -9999px;
        z-index: 999;
    }
    #editor {
        visibility: hidden;
        position: absolute;
    }
    .editable {
        border: 1px dashed black;
        margin: 0;
        width: 95%;
        height: 50px;
        overflow: auto;
		padding: 5px;
    }
	.subbox {
		padding: 7px;
		background-color: #EFE1C3;
	}
	.subbox h3 { margin-top: 15px; }
/* tab labels */
.yui-nav li a, .yui-nav li a:visited { 
	font-size: 92%;
	font-weight: bolder;
	color: #880022;
	text-decoration: none; 
	}
.yui-nav li a:hover { 
	text-decoration: none; 
	}
#tabbox {
	margin: 20px 0px;	
}
</style>

<script>
//init tabs
	var tabView = new YAHOO.widget.TabView('tabbox', { activeIndex: 0 } );
</script>

	<textarea id="editor"></textarea>
	<div id="editable_cont">

	<div id="tabbox" class="yui-navset">

    <ul class="yui-nav">
		$tab_list_html
	</ul>

	<div class="yui-content">
		$tab_content_html
	</div>

	</div>
	</div>

<script>

(function() {
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event,
        editing = null;
    
    var myConfig = {
        height: '250px',
        width: '600px',
        animate: true,
        autoHeight: true,
        toolbar: {
            titlebar: 'Editor',
            limitCommands: true,
            collapse: true,
            buttons: [
                { group: 'textstyle', label: 'Font Style',
                    buttons: [
                        { type: 'push', label: 'Bold', value: 'bold' },
                        { type: 'push', label: 'Italic', value: 'italic' },
                        { type: 'push', label: 'Underline', value: 'underline' },
                        { type: 'separator' },
                        { type: 'select', label: 'Arial', value: 'fontname', disabled: true,
                            menu: [
                                { text: 'Arial', checked: true },
                                { text: 'Arial Black' },
                                { text: 'Comic Sans MS' },
                                { text: 'Courier New' },
                                { text: 'Lucida Console' },
                                { text: 'Tahoma' },
                                { text: 'Times New Roman' },
                                { text: 'Trebuchet MS' },
                                { text: 'Verdana' }
                            ]
                        },
                        { type: 'spin', label: '12', value: 'fontsize', range: [ 9, 75 ], disabled: true },
                        { type: 'separator' },
                        { type: 'color', label: 'Font Color', value: 'forecolor', disabled: true },
                        { type: 'color', label: 'Background Color', value: 'backcolor', disabled: true }
                    ]
                }
            ]
        }
    };

    YAHOO.widget.Toolbar.prototype.STR_COLLAPSE = 'Click to close the editor and save your work.';
    myEditor = new YAHOO.widget.Editor('editor', myConfig);
    myEditor.on('toolbarLoaded', function() {
        this.toolbar.on('toolbarCollapsed', function() {
            Dom.setXY(this.get('element_cont').get('element'), [-99999, -99999]);
            Dom.removeClass(this.toolbar.get('cont').parentNode, 'yui-toolbar-container-collapsed');
            myEditor.saveHTML();
            editing.innerHTML = myEditor.get('element').value;
		editing.style.height = 'auto';
		var editor_params = "var="+editing.id+"&val="+myEditor.get('element').value;
		var enc_params = escape(editor_params);
		var surl = 'ajax_save_editor.php';
		var callback =
		{
  			success: function(o) { alert (o.responseText); },
  			failure: function(o) { alert (o.responseText); }
		}
		YAHOO.util.Connect.asyncRequest('POST', surl, callback, editor_params);
            editing = null;
        }, myEditor, true);
    }, myEditor, true);
    myEditor.render();

    Event.on('editable_cont', 'click', function(ev) {
        var tar = Event.getTarget(ev);
        if (Dom.hasClass(tar, 'editable')) {
            if (editing !== null) {
                myEditor.saveHTML();
			//put in AJAX call to save to database
                editing.innerHTML = myEditor.get('element').value;
            }
            var xy = Dom.getXY(tar);
            myEditor.setEditorHTML(tar.innerHTML);
            Dom.setXY(myEditor.get('element_cont').get('element'), xy);
            editing = tar;
        }
    });

})();
</script>

htmleoq;
//end body

} //end if rpt not already filed

include './template.php'; //add in the standard page header 
?>