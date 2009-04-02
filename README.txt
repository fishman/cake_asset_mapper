/*
 * Asset Mapper CakePHP Component
 * Copyright (c) 2007 Marc Grabanski
 * http://marcgrabanski.com
 *
 * @author      Marc Grabanski <m@marcgrabanski.com>
 * @version     1.0
 * @license     MIT
 * @date		December 26th, 2007
 *
 * Built on top of Asset Packer by Matt Curry <matt@pseudocoder.com>
 */
 
 INSTRUCTIONS:
 
 1. Unpack files to new folder called, "assets" in helpers folder /app/views/helpers/asset/
 
 	asset_mapper.php
 	asset_packer.htaccess
 	asset_packer.php
 	asset_rule.php
 
 2. Unpack the vendors folder to /vendors
 
 	css_tidy
 	jsmin
 
 3. Add AssetMapper Helper to you App Controller, /app/controller/app_controller.php
 	
 	var $helpers = array('AssetMapper');
 	
 4. Define Mapper Rules in AssetRule file, /app/views/helpers/asset/asset_rule.php
	
	4a.	Create a new rule.
		
		$rule = new $this->AssetRule();
		
	4b. Set the rule properties.
	
		map->controller		: Map assets to a controller
		map->action			: Map assets to an action
		compact->css			: CSS files to compact and compress with CSS Tidy
		compact->scripts		: Scripts to compact into one file and minify with JS Min
		scripts				: Include scripts
		codeblock			: Include a codeblock
		
	4c. Render the rule.
		
		$rule->render();  
		
	NOTE: If no controller or action is set, then the files are included site-wide
		
	NOTE: The compacted scripts get rendered first, then the regular scripts and then lastly the codeblocks

 5. Output the CSS and JavaScript files in your view.
 
 	<?php echo $styles_for_layout ?> 
	<?php echo $javascript_for_layout ?> 