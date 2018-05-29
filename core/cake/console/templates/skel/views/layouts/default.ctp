<?php
/* SVN FILE: $Id: default.ctp 7805 2008-10-30 17:30:26Z AD7six $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(), "\n";?>
	<title><?php echo Configure::read('site.name');?> | <?php echo $this->Html->cText($title_for_layout, false);?></title>
	<?php
		echo $this->Html->meta('icon'), "\n";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n";
		$this->Html->css('reset', null, null, false);
		$this->Html->css('style', null, null, false);
		$this->Javascript->link('libs/jquery', false);
		$this->Javascript->link('common', false);
		echo $this->Asset->scripts_for_layout();
	?>
</head>
<body>
	<div id="<?php echo $this->Html->getUniquePageId();?>" class="content">
		<div id="header">
			<h1><?php echo $this->Html->link(Configure::read('site.name'), '/');?></h1>
		</div>
		<div id="sub-header">
              <ul>
    			<li><?php echo $this->Html->link(__l('Home'), '/');?></li>
    			<li><?php echo $this->Html->link(__l('About'), array('controller' => 'pages', 'action' => 'display', 'about'));?></li>
              </ul>
		</div>
		<div id="main">
			<?php
        		if ($this->Session->check('Message.error')):
        				$this->Session->flash('error');
        		endif;
        		if ($this->Session->check('Message.success')):
        				$this->Session->flash('success');
        		endif;
				if ($this->Session->check('Message.flash')):
						$this->Session->flash();
				endif;
			?>
			<?php echo $content_for_layout;?>
			
		</div>
		<div id="footer" class="clearfix">
			<div class="footer-inner clearfix">
				<div id="agriya" class="clearfix copywrite-info">
					<p>&copy;<?php echo date('Y');?> <?php echo $html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
					<p class="powered clearfix"><span>made in</span> <?php echo $html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
					<p><?php echo $html->link('CSSilized by CSSilize', 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize', 'class' => 'cssilize'));?></p>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->element('site_tracker');?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>