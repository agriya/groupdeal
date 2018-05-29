<?php
/* SVN FILE: $Id: default.ctp 17321 2010-08-03 15:43:55Z aravindan_111act10 $ */
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
		
		echo $this->Html->css('subscriptions.cache', null, array('inline' => true));
		$js_inline = "document.documentElement.className = 'js';";
		$js_inline .= 'var cfg = ' . $this->Javascript->object($js_vars_for_layout) . ';';
		$js_inline .= "(function() {";
		$js_inline .= "var js = document.createElement('script'); js.type = 'text/javascript'; js.async = true;";
		if (!$_jsPath = Configure::read('cdn.js')) {
			$_jsPath = Router::url('/', true);
		}
		$js_inline .= "js.src = \"" . $_jsPath . 'js/default.cache.js' . "\";";
		$js_inline .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(js, s);";
		$js_inline .= "})();";
		echo $this->Javascript->codeBlock($js_inline, array('inline' => true));

	?>
</head>
<?php
	$original_subscription_image = '';
	$big_thumb_subscription_image = '';
    $subscriptionAttachment = $this->Html->getSubscriptionAttachment();
	if(!empty($subscriptionAttachment)):
		$original_subscription_image = $this->Html->url($this->Html->getImageUrl('PageLogo', $subscriptionAttachment['Attachment'], array('dimension' => 'original')));
		$big_thumb_subscription_image = $this->Html->url($this->Html->getImageUrl('PageLogo', $subscriptionAttachment['Attachment'], array('dimension' => 'big_thumb')));
		$subscription_stretch_type = Configure::read('subscription.stretch_type');
	endif;
?>
<body class="subscription">
	<div class="content">
    <?php if(!empty($original_subscription_image)):?>
        <div id="<?php echo $subscription_stretch_type;?>" style="background:url('<?php echo $original_subscription_image; ?>') repeat left top;">
           <img id="bg-image" alt="[Image: Site Background]" src="<?php echo $big_thumb_subscription_image; ?>" class="{highResImage:'<?php echo $original_subscription_image; ?>'}" />
        </div>
    <?php endif; ?>
	<div id="<?php echo $this->Html->getUniquePageId();?>">
            <h1 class="hidden-info">
				<?php echo Configure::read('site.name'); ?>
			</h1>
        <div id="main" class="clearfix">
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
			<?php echo $content_for_layout;?>
		</div>
		<div id="footer">
  	<div class="footer-wrapper-inner clearfix">
				<div id="agriya" class="clearfix">
				    <p>&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
					<p class="privacy"> <?php echo $this->Html->link(__l('Privacy Policy'), array('controller' => 'pages', 'action' => 'view', 'privacy_policy', 'admin' => false), array('title' => __l('Privacy Policy')));?></p>
				    <p class="powered"><span><a href="http://groupdeal.dev.agriya.com/" title="<?php echo __l('Powered by GroupDeal');?>" target="_blank" class="powered"><?php echo __l('Powered by GroupDeal');?></a>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
					<p class="cssilize"><?php echo $this->Html->link('CSSilized by CSSilize, PSD to XHTML Conversion', 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize, PSD to XHTML Conversion', 'class' => 'cssilize'));?></p>

                </div>

  	</div>
		</div>
		</div>
	</div>
	<?php echo $this->element('site_tracker', array('cache' => array('config' => 'site_element_cache'), 'plugin' => 'site_tracker')); ?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>