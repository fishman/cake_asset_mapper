<?php
/*
 * Asset Packer CakePHP Component
 * Copyright (c) 2007 Matt Curry
 * www.PseudoCoder.com
 *
 * @author      mattc <matt@pseudocoder.com>
 * @version     1.0
 * @license     MIT
 * 
 * Modified for Asset Map CakePHP Component
 * Marc Grabanski
 * http://MarcGrabanski.com
 *
 */

class AssetPackerHelper extends Helper 
{
	var $helpers = array('Html', 'Javascript');
	
	var $developmentMode = false;
	
    //there is a  *minimal* perfomance hit associated with looking up the filemtimes
    //if you clean out your cached dir (as set below) on builds then you don't need this.
    var $checkTS = false;
	
    var $viewScriptCount = 0;

    //you can change this if you want to store the files in a different location
    var $cachePath = '../packed/';

    //options: default, low_compression, high_compression, highest_compression
    var $cssCompression = 'highest_compression';

    //flag so we know the view is done rendering and it's the layouts turn
    function beforeRender() {
        $view =& ClassRegistry::getObject('view');
        $this->viewScriptCount = count($view->__scripts);
    }
	
	function style_for_layout() {
		$view =& ClassRegistry::getObject('view');

        //nothing to do
        if (!$view->__scripts) {
            return;
        }

        //move the layout scripts to the front
        $view->__scripts = array_merge(
                               array_slice($view->__scripts, $this->viewScriptCount),
                               array_slice($view->__scripts, 0, $this->viewScriptCount)
                           );

        //split the scripts into js and css
        foreach ($view->__scripts as $i => $script) {
            if (preg_match('/css\/(.*).css/', $script, $match)) {
                $temp = array();
                $temp['script'] = $match[1];
                $temp['name'] = basename($match[1]);
                $css[] = $temp;

                //remove the script since it will become part of the merged script
                unset($view->__scripts[$i]);
            }
        }

        $style_for_layout = '';
		
        if (!empty($css)) {
            $style_for_layout .= $this->Html->css($this->cachePath . $this->process('css', $css));
            $style_for_layout .= "\n\t";
        }

        return $style_for_layout;
	}

    function scripts_for_layout() {
        $view =& ClassRegistry::getObject('view');

        //nothing to do
        if (!$view->__scripts) {
            return;
        }

        //move the layout scripts to the front
        $view->__scripts = array_merge(
                               array_slice($view->__scripts, $this->viewScriptCount),
                               array_slice($view->__scripts, 0, $this->viewScriptCount)
                           );

        //split the scripts into js and css
        foreach ($view->__scripts as $i => $script) {
            if (preg_match('/js\/(.*).js/', $script, $match)) {
                $temp = array();
                $temp['script'] = $match[1];
                $temp['name'] = basename($match[1]);
                $js[] = $temp;

                //remove the script since it will become part of the merged script
                unset($view->__scripts[$i]);
            }
        }

        $script_for_layout = '';

        //then the js
        if (!empty($js)) {
            $script_for_layout .= $this->Javascript->link($this->cachePath . $this->process('js', $js));
        }

        return $script_for_layout;
    }


    function process($type, $data) {
        switch($type) {
            case 'js':
                $path = JS;
                break;
            case 'css':
                $path = CSS;
                break;
        }

        $folder = new Folder;

        //make sure the cache folder exists
        $folder->mkdirr($path . $this->cachePath);

        //check if the cached file exists
        $names = Set::extract($data, '{n}.name');

        $folder->cd($path . $this->cachePath);
        $fileName = $folder->find(implode('_', $names) . '.' . $type);

        if ($fileName) {
            //take the first file...really should only be one.
            $fileName = $fileName[0];
        }

        //make sure all the pieces that went into the packed script
        //are OLDER then the packed version
        if($this->checkTS && $fileName) {
            $packed_ts = filemtime($path . $this->cachePath . $fileName);

            $latest_ts = 0;
            $scripts = Set::extract($data, '{n}.script');
            foreach($scripts as $script) {
                $latest_ts = max($latest_ts, filemtime($path . $script . '.' . $type));
            }

            //an original file is newer.  need to rebuild
            if ($latest_ts > $packed_ts) {
                unlink($path . $this->cachePath . $fileName);
                $fileName = null;
            }
        }

        //file doesn't exist.  create it.
        if (!$fileName) {

            //merge the script
            $scriptBuffer = '';
            $scripts = Set::extract($data, '{n}.script');
            foreach($scripts as $script) {
                $scriptBuffer .= file_get_contents($path . $script . '.' . $type);
            }

            switch($type) {
                case 'js':
                    if (PHP5) {
                        vendor('jsmin/jsmin');
                        $scriptBuffer = JSMin::minify($scriptBuffer);
                    }
                    break;

                case 'css':
                    vendor('css_tidy/class.csstidy');
                    $tidy = new csstidy();
                    $tidy->load_template($this->cssCompression);
                    $tidy->parse($scriptBuffer);
                    $scriptBuffer = $tidy->print->plain();
                    break;

            }

            //write the file
            $fileName = implode($names, '_') . '.' . $type;
            $file = new File($path . $this->cachePath . $fileName);
            $file->write(trim($scriptBuffer));
        }

        if ($type == 'css') {
            $fileName = str_replace('.css', '', $fileName);
        }

        return $fileName;
    }
	
	/* Process the CSS buffer and send the CSS to Asset Mapper */
	function generateCSS() {
		if ($this->developmentMode) {
			$out = '';
			if(isset($this->buffer['css'])) {
				foreach($this->buffer['css'] as $css) {
					$out .= $this->Html->css($css);
				}
			}
			return $out;
		} else {
			if(isset($this->buffer['css'])) {
				foreach($this->buffer['css'] as $css) {
					$this->Html->css($css,null,null,false);
				}
			}
			return $this->style_for_layout();
		}
	}
	
	/* Process the JavaScript buffers and send the JavaScript to Asset Mapper */
	function generateJS() {
		
		if ($this->developmentMode) {
			$out = '';
			// create javascript links with the compactscripts buffer
			if(isset($this->buffer['compactScripts'])) {
				foreach($this->buffer['compactScripts'] as $compactScript) {
					$out .= $this->Javascript->link($compactScript);
				}
			}
		} else {
			// create javascript links with the compactscripts buffer
			if(isset($this->buffer['compactScripts'])) {
				foreach($this->buffer['compactScripts'] as $compactScript) {
					$this->Javascript->link($compactScript, false);
				}
			}
			// compact the scripts
			$out = $this->scripts_for_layout();
		}
		
		// output regular javascript links with the scripts buffer
		if(isset($this->buffer['scripts'])) {
			foreach($this->buffer['scripts'] as $script) {
				$out .= $this->Javascript->link($script);
			}
		}
		
		// Concattenate all of the codeblocsk together
		$codeblocks = '';
		if(isset($this->buffer['codeblock'])) {
			foreach($this->buffer['codeblock'] as $codeblock) {
				$codeblocks .= $codeblock;
			}
		}
		// output as one codeblock
		$out .= $this->Javascript->codeblock($codeblocks);
		
		return $out;
	}
}
?>