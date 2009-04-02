<?php
/*
 * Asset Mapper CakePHP Component
 * Copyright (c) 2007 Marc Grabanski
 * http://marcgrabanski.com
 *
 * @author      Marc Grabanski <m@marcgrabanski.com>
 * @version     1.0
 * @license     MIT
 *
 * Built on top of Asset Packer by Matt Curry <matt@pseudocoder.com>
 */
class AssetMapperHelper extends Helper
{
	var $helpers = array('AssetRule','AssetPacker');
	function beforeRender() {
		// Pass the controller name to AssetRule
		$this->AssetRule->_controller = isset($this->params['controller']) ? $this->params['controller'] : null;
		// Pass the action action to AssetRule
		$this->AssetRule->_action = isset($this->params['action']) ? $this->params['action'] : null;
		// Run the rules definition
		$this->defineRules(); 		if (Configure::read('debug') > 0) {			$this->AssetPacker->developmentMode = true;		}
	}
	function defineRules() {		include('asset_map.php');
	}
	function afterRender() {
		// Get the view so we can output variables to it
		$this->view =& ClassRegistry::getObject('view');
		// Get the buffer from the AssetPacker
		$this->view->viewVars['styles_for_layout'] = $this->AssetPacker->generateCSS();
		$this->view->viewVars['javascript_for_layout'] = $this->AssetPacker->generateJS();
	}
}
?>