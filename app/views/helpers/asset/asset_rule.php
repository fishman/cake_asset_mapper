<?php
/*
 * Asset Rule, Part of Asset Mapper CakePHP Component
 * Copyright (c) 2007 Marc Grabanski
 * http://marcgrabanski.com
 *
 * @author      Marc Grabanski <m@marcgrabanski.com>
 * @version     1.0
 * @license     MIT
 *
 * Built on top of Asset Packer by Matt Curry <matt@pseudocoder.com>
 */
class AssetRuleHelper extends Helper 
{
	var $helpers = array('AssetPacker');
	
	var $_controller; // Controller name set by AssetMap
	var $_action; // Action name set by AssetMap
	
	// Create an empty rule
	function create() {
		$this->controller = null; // Map assets to a controller
		$this->action = null; // Map assets to an action
		
		$this->compact->css = null; // CSS files to compact and compress with CSS Tidy
		$this->compact->scripts = null; // Scripts to compact into one file and minify with JS Min
		$this->scripts = null;
		$this->codeblock = null;
	}
	
	function runRule() {
		if (empty($this->controller) || $this->controller === $this->_controller || 
				(is_array($this->controller) && in_array($this->_controller, $this->controller) !== false)
			) {
			if (empty($this->action) || $this->action === $this->_action || 
				(is_array($this->action) && in_array($this->_action, $this->action) !== false)) {
				// If rule criteria is satisfied where we are, now process the rule
				$this->processRule();
			}
		}
	}
	
	function processRule() {
		if (isset($this->compact->css)) {
			$this->processCompactCSS();
		}
		if (isset($this->compact->scripts)) {
			$this->processCompactScripts();
		}
		if (isset($this->scripts)) {
			$this->processScripts();
		}
		if (isset($this->codeblock)) {
			$this->processCodeblock();
		}
	}
	
	/* Process and send to AssetPacker buffer */
	
	function processCompactCSS() {
		if (is_array($this->compact->css)) {
			foreach ($this->compact->css as $cssfile) {
				$this->AssetPacker->buffer['css'][] = $cssfile;
			}
		} else {
			$this->AssetPacker->buffer['css'][] = $this->compact->css;
		}
	}
	
	function processCompactScripts() {
		if (is_array($this->compact->scripts)) {
			foreach ($this->compact->scripts as $script) {
				$this->AssetPacker->buffer['compactScripts'][] = $script;
			}
		} else {
			$this->AssetPacker->buffer['compactScripts'][] = $this->compact->scripts;
		}
	}
	
	function processScripts() {
		if (is_array($this->scripts)) {
			foreach ($this->scripts as $script) {
				$this->AssetPacker->buffer['scripts'][] = $script;
			}
		} else {
			$this->AssetPacker->buffer['scripts'][] = $this->scripts;
		}
	}
	
	function processCodeBlock() {
		$this->AssetPacker->buffer['codeblock'][] = $this->codeblock;
	}
	
}
?>
