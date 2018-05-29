<?php
/* SVN FILE: $Id: default.ctp 78479 2012-08-14 13:56:57Z ananda_176at12 $ */
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
		echo $this->Html->css('merchant.cache', null, array('inline' => true));
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
		// For other than Facebook (facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)), wrap it in comments for XHTML validation...
		if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false):
		echo '<!--', "\n";
		endif;
    ?>
	<meta content="<?php echo Configure::read('facebook.app_id');?>" property="og:app_id" />
	<meta content="<?php echo Configure::read('facebook.app_id');?>" property="fb:app_id" />
	<?php if(!empty($meta_for_layout['deal_name'])):?>
		<meta property="og:site_name" content="<?php echo Configure::read('site.name'); ?>"/>
		<meta property='og:title' content='<?php echo $meta_for_layout['deal_name'];?>'/>
	<?php endif;?>
	<?php if(!empty($meta_for_layout['deal_image'])):?>
		<meta property="og:image" content="<?php echo $meta_for_layout['deal_image'];?>"/>
	<?php else:?>
		<meta property="og:image" content="<?php echo Router::url(array(
				'controller' => 'img',
				'action' => 'blue-theme',
				'logo-email.png',
				'admin' => false
			) , true);?>"/>
	<?php 
		endif;
		if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false):
			echo '-->', "\n";
		endif;
		echo $this->element('site_tracker', array('cache' => array('config' => 'site_element_cache'), 'plugin' => 'site_tracker')); 
	?>
</head>
<?php	
	$original_image = '';
	$big_thumb_image = '';
	if (!empty($city_attachment['id']) && empty($this->request->params['requested']) && $this->request->params['controller'] != 'images' && empty($_SESSION['city_attachment'])):
		$original_image = $this->Html->url($this->Html->getImageUrl('City', $city_attachment, array('dimension' => 'original')));	
		$big_thumb_image = $this->Html->url($this->Html->getImageUrl('City', $city_attachment, array('dimension' => 'big_thumb')));	
	endif;			
?>
<body>
	<div class="content merchant-content">
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
		<?php if ($this->Session->check('Message.TransactionSuccessMessage')):?>
        	<div class="transaction-message info-details ">
			<?php 
				echo $this->Session->read('Message.TransactionSuccessMessage');
				$this->Session->delete('Message.TransactionSuccessMessage');
			?>
			</div>
        <?php endif; ?>
<div id="<?php echo $this->Html->getUniquePageId();?>"> 
	<div id="header">
      <div class="clearfix container_24">
  	   <h1 class="merchant-logo grid_8 mega alpha">
			<?php echo $this->Html->link((Configure::read('site.name').' '.'<span>Merchant</span>'), array('controller' => 'companies', 'action' => 'dashboard'), array('escape' => false, 'title' => (Configure::read('site.name').' '.'Merchant')));?>
        </h1>
        <p class="hidden-info"><?php echo __l('Collective Buying Power');?></p>
         <ul class="user-menu merchant-menu1 grid_right">
			<li class="view-site"><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></li>
            <li class="view-site">
            <?php echo $this->Html->link(__l('View Site'), array('controller' => 'deals', 'action' => 'index' ,'admin'=>false), array('title' => __l('View Site'), 'escape' => false));
            ?>
            </li>
		   <li class="user">
		       <span class="user-img-left grid_left">
		       <?php $current_user_details = array('username' => $this->Auth->user('username'), 'user_type_id' =>  $this->Auth->user('user_type_id'), 'id' =>  $this->Auth->user('id'), 'fb_user_id' =>  $this->Auth->user('fb_user_id'));?>
    <?php $current_user_details['UserAvatar'] = $this->Html->getUserAvatar($this->Auth->user('id'));
       echo $this->Html->getUserAvatarLink($current_user_details, 'medium_thumb'); ?>
    </span>
    <span class="user-img-right grid_right">
     <?php echo $this->Html->getUserLink($current_user_details );?> <?php echo $this->Html->link(__l('My Account'), array('controller' => 'companies', 'action' => 'edit', $this->Auth->user('id')), array('class'=>'user-name1 right-space textn','title' => 'My Account', 'rel' => 'address:/' . __l('My_Account'))); ?>
    </span>
            </li>
         </ul>
    </div>
    <div class="header-inner">
      <div class="container_24 clearfix">
		<?php echo $this->element('merchant-sidebar'); ?>
       </div>
    </div>
</div>
<div id="main" class="clearfix container_24">
<div class="clearfix welcome-block">
      <p class="merchant-welcome-info grid_10 omeag alpha">WELCOME, <a title="merchant" href="">MERCHANT</a></p>
      <?php echo $this->element('lanaguge-change-block'); ?>
      <?php //echo $this->element('merchant-cities-filter', array('cache' => array('config' => 'site_element_cache', 'key' => $this->request->params['controller'].'_'.$this->request->params['action'])));?>
</div>
    

<div class="container_24">
	<div class="admin-side1-tl ">
		<div class="admin-side1-tr">
		  <div class="admin-side1-tc page-title-info">
		  <?php
		  $class='';
		  if($this->request->params['controller']=='companies' && $this->request->params['action']=='dashboard')
		  {
		  	$class="dashboard";
		  }
		  elseif($this->request->params['controller']=='companies' && $this->request->params['action']=='edit')
		  {
		  	$class="merchants";
		  }
		  elseif($this->request->params['controller']=='company_addresses' && $this->request->params['action']=='index')
		  {
		  	$class="branches";
		  }
		  elseif($this->request->params['controller']=='deals' && (($this->request->params['action']=='index' && !$this->request->params['named']['view']) || $this->request->params['action']=='add'))
		  {
		  	$class="deals";
		  }
		  elseif($this->request->params['controller']=='transactions' && $this->request->params['action']=='index')
		  {
		  	$class="transaction";
		  }
		  elseif($this->request->params['controller']=='money_transfer_accounts' && $this->request->params['action']=='index')
		  {
		  	$class="transfer";
		  }
		   elseif($this->request->params['controller']=='user_cash_withdrawals' && $this->request->params['action']=='index')
		  {
		  	$class="wallet";
		  }
		  elseif($this->request->params['controller']=='deals' && $this->request->params['action']=='live_add')
		  {
		  	$class="live-deals textn no-mar";
		  }
		  elseif($this->request->params['controller']=='deals' && $this->request->params['named']['view'])
		  {
		  	$class="live-deals textn no-mar";
		  }
		  ?>
			<h2 class="<?php echo $class;?>"><?php echo $this->pageTitle;?></h2>
		  </div>
	  		  </div>
	  		  </div>
				<?php 
					if (!($this->request->params['controller'] == 'deals' && ($this->request->params['action'] == 'live' ||$this->request->params['action'] == 'view' || ($this->request->params['action'] == 'index' && empty($this->request->params['named']['company']))) or(($this->request->params['controller'] == 'companies' && $this->request->params['action'] == 'dashboard')))): 
				?>
    		<div class="admin-center-block clearfix">
                <?php 
					endif; 
					echo $content_for_layout;
					if (!($this->request->params['controller'] == 'deals' && ($this->request->params['action'] == 'live' || $this->request->params['action'] == 'view' || ($this->request->params['action'] == 'index' && empty($this->request->params['named']['company'])))or(($this->request->params['controller'] == 'companies' && $this->request->params['action'] == 'dashboard')))): 
				?>
		    </div>
			<?php
					endif; 

					if ($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'buy'):
				?>
						<div class="side2">
							<div class="side1-tl">
								<div class="side1-tr">
									<div class="side1-tm"> </div>
								</div>
							</div>
							<div class="side1-cl">
								<div class="side1-cr">
									<div class="block1-inner">
										<?php echo $this->element('deal-faq', array('cache' => array('config' => 'site_element_cache_1_week')));?>
									</div>
								</div>
							</div>
							<div class="side1-bl">
								<div class="side1-br">
									<div class="side1-bm"> </div>
								</div>
							</div>
						</div>
				<?php 
					endif;
				?>
			</div>
</div>

 <div id="footer">
		<div class="footer-inner container_24">
		<div class="footer-wrapper-inner clearfix">
		<div id="agriya" class="ver-space clearfix">
          <p class="copy grid_left">&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
		  <p class="powered grid_left"><span><a href="<?php echo (env('HTTPS') )? '#' :  'http://groupdeal.dev.agriya.com/'; ?>" title="<?php echo __l('Powered by GroupDeal');?>" target="_blank" class="powered"><?php echo __l('Powered by GroupDeal');?></a>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', (env('HTTPS') )? '#' : 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
		  <p class="grid_left"><?php echo $this->Html->link('CSSilized by CSSilize, PSD to XHTML Conversion', (env('HTTPS') )? '#' : 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize, PSD to XHTML Conversion', 'class' => 'cssilize'));?></p>
      </div>
		</div>
        </div>
	</div>
	</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>