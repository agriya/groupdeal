<?php /* SVN: $Id: auto_load_page_specific.php 177 2009-02-05 12:08:47Z rajesh_04ag02 $ */
// by grigri
class AutoLoadPageSpecificHelper extends AppHelper
{
    var $helpers = array(
        'Html',
        'Javascript'
    );
    function beforeRender($viewFile)
    {
        // Load controller-specific css and js if exists
        $this->_loadIfExistsJs($this->params['controller']);
        /*
        $this->_loadIfExistsCss($this->params['controller']);
        // Load action-specific css and js if exists
        $this->_loadIfExistsJs($this->params['action']);
        $this->_loadIfExistsCss($this->params['action']);
        // Load controller+action-specific css and js if exists
        $this->_loadIfExistsJs($this->params['controller'], $this- > params['action']);
        $this->_loadIfExistsCss($this->params['controller'], $this- > params['action']);
        */
    }
    function _loadIfExistsJs()
    {
        $bits = func_get_args();
        $file = JS . implode(DS, $bits) . '.js';
        if (file_exists($file)) {
            $this->Javascript->link(implode('/', $bits) . '.js', false);
        }
    }
    function _loadIfExistsCss()
    {
        $bits = func_get_args();
        $file = CSS . implode(DS, $bits) . '.css';
        if (file_exists($file)) {
            $this->Html->css(implode('/', $bits) . '.css', null, null, false);
        }
    }
}
?>