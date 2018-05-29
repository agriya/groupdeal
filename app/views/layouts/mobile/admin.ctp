<?php
/* SVN FILE: $Id: admin.ctp 6910 2010-06-04 07:28:35Z sreedevi_140ac10 $ */
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
	<title><?php echo Configure::read('site.name');?> | <?php echo sprintf(__l('Admin - %s'), $this->Html->cText($title_for_layout, false)); ?></title>
	<?php
		echo $this->Html->meta('icon'), "\n";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n";
		
		echo $this->Html->css('mobile/mobile.cache', null, array('inline' => true));
	?>
</head>
<body class="admin">
	<div id="<?php echo $this->Html->getUniquePageId();?>" class="content admin-content js-auto-submit-over-block">
		<div id="header" class="clearfix">
		    <div id="header-content">
			<div class="clearfix">
			<h1>
				<?php
					echo $this->Html->link($this->Html->Image('mobile/logo-blue.png', array('alt' => sprintf(__l('[Image: %s]'), Configure::read('site.name')), 'title' => Configure::read('site.name'), 'type' => 'png')), array('controller' => 'deals', 'action' => 'index', 'admin' => false), array('escape' => false));
				?>
			</h1>
			 <?php
                    $languages = $this->Html->getLanguage();
                    if(Configure::read('user.is_allow_user_to_switch_language') && !empty($languages)) :
                        echo $this->Form->create('Language', array('url' => array('action' => 'change_language','admin' => false), 'class' => 'language-form'));
                        echo $this->Form->input('language_id', array('label' => __l('Language'),'class' => 'js-autosubmit', 'options' => $languages, 'value' => Configure::read('lang_code')));
                        echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
                        ?>
                        <div class="hide">
                            <?php echo $this->Form->submit('Submit');  ?>
                        </div>
                        <?php
                        echo $this->Form->end();
                    endif;
                ?>
			 <div class="admin-bar clearfix">
                <div class="clearfix"><h3><?php echo __l('Current time: '); ?></h3><?php echo $this->Html->cTime(date(Configure::read('site.datetime.format'))); ?></div>
        		<div class="clearfix"><h3><?php echo __l('Last login: '); ?></h3><?php echo $this->Html->cDateTimeHighlight($this->Auth->user('last_logged_in_time')); ?></div>
    		</div>
			<div id="sub-header" class="admin-sub-header">
			 <p class="admin-welcome-info"><?php echo __l('Welcome, ').$this->Html->link($this->Auth->user('username'), array('controller' => 'users', 'action' => 'stats', 'admin' => true),array('title' => $this->Auth->user('username'))); ?></p>
				
			</div>
	
			</div>
			<ul class="menu">
				  <li><?php echo $this->Html->link(__l('Home'), array('controller' => 'deals', 'action' => 'index','admin' => false), array('escape' => false, 'title' => __l('Home')));?></li>
				   <?php $class = (($this->request->params['controller'] == 'user_profiles') && ($this->request->params['action'] == 'my_account')) ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('My Account'), array('controller' => 'user_profiles', 'action' => 'user_account', $this->Auth->user('id')), array('title' => __l('My Account')));?></li>
					<li><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></li>
				</ul>
		</div>
		</div>
		<div id="main" class="clearfix">
			<?php
        		if ($this->Session->check('Message.error')):
        				echo $this->Session->flash('error');
        		endif;
        		if ($this->Session->check('Message.success')):
        				echo $this->Session->flash('success');
        		endif;
				if ($this->Session->check('Message.flash')):
						echo $this->Session->flash();
				endif;
			?>
            <div class="admin-sideone js-corner round-10">
                <?php
                    echo $this->element('admin-sidebar', array('cache' => array('config' => 'site_element_cache') ));
                ?>
            </div>
            <div class="admin-sidetwo js-corner round-10">
    			<?php echo $content_for_layout;?>
			</div>
		</div>
		<div id="footer">
			<div id="agriya" class="clearfix">
				<p>&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), '/');?>. <?php echo __l('All rights reserved');?>.</p>
				<p class="powered clearfix"><span><a href="http://groupdeal.dev.agriya.com/" title="<?php echo __l('Powered by GroupDeal');?>" target="_blank" class="powered"><?php echo __l('Powered by GroupDeal');?></a>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?> <span><?php echo Configure::read('site.version');?></span></p>
				<p><?php echo $this->Html->link('CSSilized by CSSilize', 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize', 'class' => 'cssilize'));?></p>
			</div>
		</div>
	</div>
	<?php echo $this->element('site_tracker');?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
