<?php
	$this->Html->css('reset', null, array('inline' => false));
	$this->Html->css('widget', null, array('inline' => false));	
	$this->Html->css('style', null, array('inline' => false));
	
		$this->Javascript->codeBlock('var cfg = ' . $this->Javascript->object($js_vars_for_layout) , array('inline' => false));
		$this->Javascript->link('libs/jquery-1.6.1', false);
		$this->Javascript->link('libs/jquery.metadata', false);
		$this->Javascript->link('libs/jcarousellite_1.0.1', false);
		$this->Javascript->link('libs/affiliate_widget.js', false);
?>