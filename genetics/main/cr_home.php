<?php 

$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

//get email list
if ($ie = $db->get_row("SELECT * FROM usr INNER JOIN (instructor INNER JOIN (classes INNER JOIN groups ON class_id = grp_class_id)  ON class_id = ins_class_id)  ON usr_id = ins_usr_id WHERE grp_id = $s_grp")) {
	$instructor_email_html = "<h3>The Class Instructor: </h3>$ie->usr_fname $ie->usr_lname ($ie->usr_email)";
} else $instructor_email_html = "";
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

//see if there is a case report filed for this group
if ($cr = $db->get_row("SELECT * FROM case_report WHERE cr_mod_id = $s_mod AND cr_grp_id = $s_grp AND cr_status = 1")) {
	$urow = $db->get_row("SELECT * FROM usr WHERE usr_id = $cr->cr_by");
	$filed_by = $urow->usr_fname." ".$urow->usr_lname;
	$filed_cr_html = "Your group's report was previously filed by: $filed_by on: $cr->cr_dt";
} else {
	$filed_cr_html = "";
}
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
			if (($s_mod == 11) && (($tc->rs_fieldid == 'gtr') || ($tc->rs_fieldid == 'def'))) {
				//do nothing for these fields for this mod
			} else {
			//get previously saved text
			$section_text = $db->get_var("SELECT rss_text FROM report_sections_saved WHERE rss_fieldid = '$tc->rs_fieldid' AND rss_grp_id = $s_grp AND rss_mod_id = $s_mod");
			$tab_content_html .= <<<htmleoq
			
				<h3>$tc->rs_title</h3>
    			<div id="$tc->rs_fieldid" class="editable">$section_text</div>
			
htmleoq;
			}
		}
		
		//include category specific notes into $extra_html
		include './group_filter.php'; //add in group member filter
		$tab_content_html .= $group_filter_html;

		$extra_html = "";
		$catfile = "cr_".$t->rsc_cat."_extra.php";
		if (file_exists($catfile)) include $catfile; else $extra_html = "";
		$tab_content_html .= $extra_html;
		
		$tab_content_html .= "</div></div>";
	}
	$body .= <<<htmleoq
	
<h2>Case Report</h2>

<!--<p>See an <a href="cr_example.php" target="_blank">example</a> of a case report.</p>-->

$email_list_html

<style>
#button_final span a {
  	padding-left: 2em;
   	background:url('img/email_attach.png') 3% center no-repeat;
   	text-decoration: none;
}
#button_preview span a {
  	padding-left: 2em;
   	background:url('img/magnifier.gif') 3% center no-repeat;
   	text-decoration: none;
}
#step { 
	margin-top: 20px; 
}
.yui-skin-sam .yui-toolbar-container .yui-toolbar-save span.yui-toolbar-icon {
    background-image: url( img/save.png );
    background-position: 0px 0px;
    left: 5px;
}
</style>

<script>
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

</script>

<p>$filed_cr_html</p>

<span id="button_preview"><span class="first-child"><a href="cr_preview.php">Preview Report</a></span></span>
<span id="button_final" style="margin-left: 10px;"><span class="first-child"><a href="#" onClick="YAHOO.panel_final.show();">File Final Report</a></span></span>

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
function init_tabbox() {
	var tabView = new YAHOO.widget.TabView('tabbox', { activeIndex: 0 });
}

YAHOO.util.Event.onContentReady('tabbox', init_tabbox);
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
	
	function urlencode( str ) {                            
    var histogram = {}, tmp_arr = [];
    var ret = str.toString();
    
    var replacer = function(search, replace, str) {
        var tmp_arr = [];
        tmp_arr = str.split(search);
        return tmp_arr.join(replace);
    };
    
    // The histogram is identical to the one in urldecode.
    histogram["'"]   = '%27';
    histogram['(']   = '%28';
    histogram[')']   = '%29';
    histogram['*']   = '%2A';
    histogram['~']   = '%7E';
    histogram['!']   = '%21';
    histogram['%20'] = '+';
    
    // Begin with encodeURIComponent, which most resembles PHP's encoding functions
    ret = encodeURIComponent(ret);
    
    for (search in histogram) {
        replace = histogram[search];
        ret = replacer(search, replace, ret) // Custom replace. No regexing
    }
    
    // Uppercase for full PHP compatibility
    return ret.replace(/(\%([a-z0-9]{2}))/g, function(full, m1, m2) {
        return "%"+m2.toUpperCase();
    });
    
    return ret;
}

    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event,
        editing = null;
        
    myEditor = new YAHOO.widget.Editor('editor', {
    	height: '300px',
    	width: '600px'
	});

    myEditor.on('toolbarLoaded', function() {
			//save button add
		    this.toolbar.addButtonGroup(
		    	{
					group: 'savegroup',
					label: 'Save', 
					buttons: [ {
						type: 'push',
						label: 'Save',
						value: 'save',
						disabled: false
					} ]
		    	}
		    );
        	//save on save button click
        	this.toolbar.on('saveClick', function(o) {
            	Dom.setXY(this.get('element_cont').get('element'), [-99999, -99999]);
            	Dom.removeClass(this.toolbar.get('cont').parentNode, 'yui-toolbar-container-collapsed');
            	myEditor.saveHTML();
            	editing.innerHTML = myEditor.get('element').value;
				editing.style.height = 'auto';
				var unenc_val = myEditor.get('element').value;
				//alert (unenc_val);
				var enc_val = urlencode(unenc_val);
				//alert(enc_val);
				var editor_params = "var="+editing.id+"&val="+enc_val;
				//alert(editor_params);

				var surl = 'ajax_save_editor.php';
				var callback =
				{
  					//success: function(o) { alert(o.responseText); },
  					success: function(o) { alert('Section saved.'); },
  					failure: function(o) { alert (o.responseText); }
				}
				YAHOO.util.Connect.asyncRequest('POST', surl, callback, editor_params);
            	editing = null;
        	}, myEditor, true);

    	//save on toolbar collapse upper right button
        this.toolbar.on('toolbarCollapsed', function() {
            Dom.setXY(this.get('element_cont').get('element'), [-99999, -99999]);
            Dom.removeClass(this.toolbar.get('cont').parentNode, 'yui-toolbar-container-collapsed');
            myEditor.saveHTML();
            editing.innerHTML = myEditor.get('element').value;
			editing.style.height = 'auto';
			var unenc_val = myEditor.get('element').value;
			var enc_val = urlencode(unenc_val);
			var editor_params = "var="+editing.id+"&val="+enc_val;
			var surl = 'ajax_save_editor.php';
			var callback =
			{
  				success: function(o) { alert("Section saved."); },
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

include './template.php'; //add in the standard page header 
?>