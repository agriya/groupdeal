<?php
/*
* Asset Packer CakePHP Component
* Copyright (c) 2008 Matt Curry
* www.PseudoCoder.com
* http://www.pseudocoder.com/archives/2007/08/08/automatic-asset-packer-cakephp-helper
*
* @author      mattc <matt@pseudocoder.com>
* @version     1.2
* @license     MIT
*
*/
class AssetHelper extends Helper
{
    //Cake debug = 0                          packed js/css returned.  $this->debug doesn't do anything.
    //Cake debug > 0, $this->debug = false    essentially turns the helper off.  js/css not packed.  Good for debugging your js/css files.
    //Cake debug > 0, $this->debug = true     packed js/css returned.  Good for debugging this helper.
    var $debug = false;
    //there is a *minimal* perfomance hit associated with looking up the filemtimes
    //if you clean out your cached dir (as set below) on builds then you don't need this.
    var $checkTS = true;
    //the packed files are named by stringing together all the individual file names
    //this can generate really long names, so by setting this option to true
    //the long name is md5'd, producing a resonable length file name.
    var $md5FileName = true;
    //you can change this if you want to store the files in a different location.
    //this is relative to your webroot/js and webroot/css paths
    var $cachePath = '';
    //set the css compression level
    //options: default, low_compression, high_compression, highest_compression
    //default is no compression
    //I like high_compression because it still leaves the file readable.
    var $cssCompression = 'high_compression';
    var $helpers = array(
        'Html',
        'Javascript'
    );
    var $viewScriptCount = 0;
    //flag so we know the view is done rendering and it's the layouts turn
    function afterRender($viewFile)
    {
        $this->viewScriptCount = count($this->_View->_scripts);
    }
    function scripts_for_layout() 
    {
        //nothing to do
        if (!$this->_View->_scripts) {
            return;
        }
        //move the layout scripts to the front
        $this->_View->_scripts = array_merge(array_slice($this->_View->_scripts, $this->viewScriptCount) , array_slice($this->_View->_scripts, 0, $this->viewScriptCount));
        return join("\n\t", $this->_View->_scripts);
        
    }
    function process($type, $data)
    {
		switch ($type) {
            case 'js':
                $path = JS;
                break;

            case 'css':
                $path = CSS;
                break;
        }
		if (!class_exists('Folder')) {
			App::import('core', 'Folder');
		}
		$folder = new Folder;
        //make sure the cache folder exists
        if (!$folder->create($path . $this->cachePath, "777")) {
            trigger_error('Could not create ' . $path . $this->cachePath . '. Please create it manually with 777 permissions', E_USER_WARNING);
        }
        //check if the cached file exists
        $names = Set::extract($data, '{n}.name');
        $folder->cd($path . $this->cachePath);
        $fileName = $folder->find($this->__generateFileName($names) . '_([0-9]{10}).' . $type);
        if ($fileName) {
            //take the first file...really should only be one.
            $fileName = $fileName[0];
        }
        //make sure all the pieces that went into the packed script
        //are OLDER then the packed version
        if ($this->checkTS && $fileName) {
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
            $ts = time();
            //merge the script
            $scriptBuffer = '';
            $scripts = Set::extract($data, '{n}.script');
            foreach($scripts as $script) {
                $buffer = file_get_contents($path . $script . '.' . $type);
                switch ($type) {
                    case 'js':
						App::import('Vendor', 'JSMin', true, array() , 'jsmin' . DS . 'jsmin.php');
						$buffer = trim(JSMin::minify($buffer));
                        break;

                    case 'css':
                        App::import('Vendor', 'csstidy', true, array() , 'csstidy' . DS . 'class.csstidy.php');
                        $tidy = new csstidy();
                        $tidy->settings['merge_selectors'] = false;
                        $tidy->load_template($this->cssCompression);
                        $tidy->parse($buffer);
                        $buffer = $tidy->print->plain();
                        break;
                }
                $scriptBuffer.= "\n/* $script.$type */\n" . $buffer;
            }
            //write the file
            $fileName = $this->__generateFileName($names) . '_' . $ts . '.' . $type;
			App::import('Core', 'File');
            $file = new File($path . $this->cachePath . $fileName);
            $file->write(trim($scriptBuffer));
        }
        if ($type == 'css') {
            //$html->css doesn't check if the file already has
            //the .css extension and adds it automatically, so we need to remove it.
            $fileName = str_replace('.css', '', $fileName);
        }
        return $fileName;
    }
    function __generateFileName($names)
    {
        $fileName = str_replace('.', '-', implode('_', $names));
        if ($this->md5FileName) {
            $fileName = md5($fileName);
        }
        return $fileName;
    }
}
?>