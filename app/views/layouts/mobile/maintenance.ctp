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
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<?php echo $this->Html->charset(), "\n";?>
	<title><?php echo Configure::read('site.name');?> | <?php echo $this->Html->cText($title_for_layout, false);?></title>
	<?php
		echo $this->Html->meta('icon'), "\n";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n";

		echo $this->Html->css('mobile/mobile.cache', null, array('inline' => true));

	?>
</head>
<body>
	<div id="<?php echo $this->Html->getUniquePageId();?>" class="content">
		<div id="header" class="clearfix">
			<h1><?php echo $this->Html->link(Configure::read('site.name'), '/');?></h1>
			<div id="sub-header">
				<p class="welcome-block clearfix">
					<span><?php	echo __l('Welcome, Guest'); ?></span>
				</p>
                <div class="menu-block clearfix">
                    <ul class="menu clearfix">
                        <li <?php if($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'index' && !isset($this->request->params['named']['type']) && !isset($this->request->params['named']['company'])) { echo 'class="active"'; } ?>><?php echo $this->Html->link(__l('Today\'s Deals'), array('controller' => 'deals', 'action' => 'index', 'admin' => false), array('title' => __l('Today\'s Deals')));?></li>
                        <li <?php if($this->request->params['controller'] == 'deals' && (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'recent')) { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('Recent Deals'), array('controller' => 'deals', 'action' => 'index', 'admin' => false,'type' => 'recent'), array('title' => __l('Recent Deals')));?></li>
                            <li <?php if($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'register' && !empty($type)) { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('Merchant'), array('controller' => 'pages', 'action' => 'view', 'company-register', 'admin' => false), array('title' => __l('Merchant')));?></li>
                            
                            <li <?php if($this->request->params['controller'] == 'topics' && $this->request->params['action'] == 'topic_discussions') { echo 'class="active"'; } ?>><?php echo $this->Html->link(__l('Discussion'), array('controller' => 'topics', 'action' => 'index'), array('title' => __l('Discussion')));?></li>
                        <li <?php if($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view'  && $this->request->params['pass'][0] == 'how_it_works') { echo 'class="active"'; } ?>><?php echo $this->Html->link(sprintf(__l('How %s Works'), Configure::read('site.name')), array('controller' => 'pages', 'action' => 'view', 'how_it_works'), array('title' => sprintf(__l('How %s Works'), Configure::read('site.name'))));?></li>
                            <li <?php if($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'login') { echo 'class="active"'; } ?>><?php echo $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Login')));?></li>
                </ul>
               	</div>
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
			<?php echo $content_for_layout;?>

		</div>
		<div id="footer">
			<div class="footer-wrapper-inner">
				<div class="footer-section1">
					<h6><?php echo __l('Merchant'); ?></h6>
					<ul class="footer-nav">
						<li><?php echo $this->Html->link(__l('About'), array('controller' => 'pages', 'action' => 'view', 'about', 'admin' => false), array('title' => __l('About')));?> </li>
						<li><?php echo $this->Html->link(__l('Contact us'), array('controller' => 'contacts', 'action' => 'add', 'admin' => false), array('title' => __l('Contact us')));?></li>
						<li><?php echo $this->Html->link(__l('Terms & Policies'), array('controller' => 'pages', 'action' => 'view', 'term-and-conditions', 'admin' => false), array('title' => __l('Terms, Privacy, Returns, Etc.')));?></li>
					</ul>
				</div>
				<div class="footer-section2">
					<h6><?php echo __l('Learn More'); ?></h6>
					<ul class="footer-nav">
						<li><?php echo $this->Html->link(__l('FAQ'), array('controller' => 'pages', 'action' => 'view', 'faq', 'admin' => false), array('title' => __l('FAQ')));?></li>
					</ul>
				</div>
				<div class="footer-section4">
					<h6><?php echo __l('Follow Us'); ?></h6>
					<ul class="footer-nav">
						<?php $tmpURL= $this->Html->getCityTwitterFacebookURL($city_slug); ?>
						<li><a href="<?php echo ($tmpURL['City']['twitter_url']) ? $tmpURL['City']['twitter_url'] : Configure::read('twitter.site_twitter_url'); ?>" title="<?php echo __l('Follow Us in Twitter'); ?>" target="_blank"><?php echo __l('Twitter'); ?></a></li>
						<li><a href="<?php echo ($tmpURL['City']['facebook_url']) ? $tmpURL['City']['facebook_url'] : Configure::read('facebook.site_facebook_url'); ?>" title="<?php echo __l('See Our Profile in Facebook'); ?>" target="_blank"><?php echo __l('Facebook'); ?></a></li>
						<li><?php echo $this->Html->link(__l('Subscribe to Daily Email'), array('controller' => 'subscriptions', 'action' => 'add', 'admin' => false), array('title' => __l('Subscribe to Daily Email'))); ?></li>
						<li><?php echo $this->Html->link(__l('Topics'), array('controller' => 'topics', 'action' => 'index', 'admin' => false), array('title' => __l('Topics'))); ?></li>
					</ul>
				</div>
				<h6 class="logo"><?php echo $this->Html->link(Configure::read('site.name'), array('controller' => 'deals', 'action' => 'index'), array('title' => Configure::read('site.name')))?></h6>
				<p class="caption"><?php echo __l('Collective Buying Power');?></p>				
			</div>
			<div id="agriya" class="clearfix">
				<p>&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), '/');?>. <?php echo __l('All rights reserved');?>.</p>
				<p class="powered clearfix"><span><a href="http://groupdeal.dev.agriya.com/" title="<?php echo __l('Powered by GroupDeal');?>" target="_blank" class="powered"><?php echo __l('Powered by GroupDeal');?></a>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?> <span><?php echo Configure::read('site.version');?></span></p>
				<p><?php echo $this->Html->link('CSSilized by CSSilize', 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize', 'class' => 'cssilize'));?></p>
			</div>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>