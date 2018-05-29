<?php
/* SVN FILE: $Id: default.ctp 52717 2011-05-05 11:59:08Z lakshmi_150act10 $ */
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
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<?php echo $this->Html->charset(), "\n";?>
	<title><?php echo Configure::read('site.name');?> | <?php echo $this->Html->cText($title_for_layout, false);?></title>
	<?php
		echo $this->Html->meta('icon'), "\n";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n";
	?>
	<link href="<?php echo Router::url('/', true) . $this->request->params['named']['city'] .'.rss';?>" type="application/rss+xml" rel="alternate" title="RSS Feeds" target="_blank" />
	<?php		
		echo $this->Html->css('mobile/mobile.cache', null, array('inline' => true));
	?>
</head>
<?php
	if (!empty($background_attachment['Attachment']['id']) && empty($this->request->params['requested']) && $this->request->params['controller'] != 'images'):
		$_SESSION['prelaunch_attachment'] =  $this->Html->url($this->Html->getImageUrl('PageLogo', $background_attachment['Attachment'], array('dimension' => 'original')));
	endif; 		
?>
<body>
	<div class="">
	<?php
		if($this->Auth->sessionValid()  and  $this->Auth->user('user_type_id') == ConstUserTypes::Company):
				$company = $this->Html->getCompany($this->Auth->user('id'));
		endif;
	?>
	</div>
	<div class="js-flash-message flash-message-block">
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
    </div>
	<div id="<?php echo $this->Html->getUniquePageId();?>" class="content">

		<h1 class="hidden-info">
			<?php echo Configure::read('site.name'); ?>
		</h1>
            <?php if($this->Auth->sessionValid() && $this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
            <div class="admin-bar">
                <h3><?php echo __l('You are logged in as '); ?><?php echo $this->Html->link(__l('Admin'), array('controller' => 'users' , 'action' => 'stats' , 'admin' => true), array('title' => __l('Admin'))); ?></h3>
                <div><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users' , 'action' => 'logout', 'admin' => true), array('title' => __l('Logout'))); ?></div>
            </div>
     <?php endif; ?>
        
    
        <div id="main" class="clearfix">
   			<?php echo $content_for_layout;?>
		</div>

         <div id="footer">
                 	<div class="footer-wrapper-inner clearfix">
    				<div id="agriya" class="clearfix">
    				    <p>&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>

    				    <p class="powered"><span><a href="http://groupdeal.dev.agriya.com/" title="<?php echo __l('Powered by GroupDeal');?>" target="_blank" class="powered"><?php echo __l('Powered by GroupDeal');?></a>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
    					<p class="cssilize"><?php echo $this->Html->link('CSSilized by CSSilize, PSD to XHTML Conversion', 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize, PSD to XHTML Conversion', 'class' => 'cssilize'));?></p>
                   </div>
             	</div>
	   
       </div>
</div>
	<?php echo $this->element('site_tracker', array('cache' => array('config' => 'site_element_cache'), 'plugin' => 'site_tracker')); ?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>