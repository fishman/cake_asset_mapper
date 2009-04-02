<?php
    /* Define Rules
	 * 
	 * STEP 1: Create a new rule.
	 * $rule = new $this->AssetRule();
	 * 
	 * STEP 2: Set the rule properties.
	 * controller		: Map assets to a controller
	 * action			: Map assets to an action
	 * compact->css			: CSS files to compact and compress with CSS Tidy
	 * compact->scripts		: Scripts to compact into one file and minify with JS Min
	 * scripts				: Include scripts
	 * codeblock			: Include a codeblock
	 * 
	 * STEP 3: Render the rule.
	 * $rule->render();  
	 * 
	 * @note: If no controller or action is set, then the files are included site-wide
	 * @note: The compacted scripts get rendered first, then the regular scripts and then lastly the codeblocks
	 * 

EXAMPLE:
	$this->AssetRule->create(); 
	$this->AssetRule->compact->css = array('site'); 
	$this->AssetRule->compact->scripts = array('jquery', 'ui.datepicker'); 
	$this->AssetRule->runRule(); 
 
EXAMPLE:
	$this->AssetRule->create(); 
	$this->AssetRule->action = 'admin_edit'; 
	$this->AssetRule->scripts = array('tiny_mce/tiny_mce'); 
	$this->AssetRule->codeblock = 'tinyMCE.init({ 
	    mode : "textareas", 
	    theme : "advanced", 
	    plugins : "media", 
	    media_external_list_url : "media/list.js", 
	    theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,outdent,indent,redo,link,unlink", 
	    theme_advanced_buttons2 : "", 
	    theme_advanced_buttons3 : "", 
	    theme_advanced_resizing : true, 
	    theme_advanced_toolbar_location : "top", 
	    theme_advanced_toolbar_align : "left", 
	    theme_advanced_statusbar_location : "bottom", 
	    extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]" 
	});'; 
	$this->AssetRule->runRule();    

EXAMPLE:
	$this->AssetRule->create();
	$this->AssetRule->compact->css = array('site');
	$this->AssetRule->compact->scripts = array('jquery');
	$this->AssetRule->runRule();

EXAMPLE:
	$this->AssetRule->controller = 'properties';
	$this->AssetRule->action = array('edit_details','edit_photos');
	$this->AssetRule->scripts = array('controller/properties/edit');
	$this->AssetRule->runRule();
	
	 */
?>