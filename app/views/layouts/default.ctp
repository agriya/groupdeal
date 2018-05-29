<?php
/* SVN FILE: $Id: default.ctp 79487 2012-09-25 10:34:08Z rajeshkhanna_146ac10 $ */
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
		echo $this->Html->css('default.cache', null, array('inline' => true));
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
    <meta content="<?php echo Configure::read('facebook.fb_user_id');?>" property="fb:admins" />
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
	<div class="content">
	<?php if(!empty($original_image)) :?>
		<div id="<?php echo $stretch_type;?>" style="backround:url('<?php echo $original_image; ?>') repeat left top;">
			<img id="bg-image" alt="[Image: Site Background]" src="<?php echo $big_thumb_image; ?>" class="{highResImage:'<?php echo $original_image; ?>'}" />
		</div>
	<?php endif; ?>
		<?php if($this->Auth->sessionValid() && $this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
		<div class="clearfix admin-wrapper">
			<h1 class="admin-site-logo">
				<?php echo $this->Html->link((Configure::read('site.name').' '.'<span>Admin</span>'), array('controller' => 'users', 'action' => 'stats', 'admin' => true), array('escape' => false, 'title' => (Configure::read('site.name').' '.'Admin')));?>
			</h1>
			<p class="logged-info"><?php echo __l('You are logged in as Admin'); ?></p>
			<ul class="admin-menu hor-space grid_right clearfix">
				<li class="logout"><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></li>
			</ul>
		</div>
		<?php endif; ?>
			
		<?php if($this->Auth->sessionValid() && $this->Auth->user('user_type_id') == ConstUserTypes::Company): ?>
		<div class="clearfix admin-wrapper">
			<h1 class="admin-site-logo">
				<?php echo $this->Html->link((Configure::read('site.name').' '.'<span>MERCHANT</span>'), array('controller' => 'companies', 'action' => 'dashboard', 'admin' => false), array('escape' => false, 'title' => (Configure::read('site.name').' '.'Merchant')));?>
			</h1>
			<p class="logged-info"><?php echo __l('You are logged in as Merchant'); ?></p>
			<ul class="admin-menu hor-space grid_right clearfix">
				<li class="logout"><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></li>
			</ul>
		</div>
		<?php endif; ?>
	<?php /*
     <div class="js-morecities1 top-wrapper hide">
			<div class="cities-wrapper">
				<?php echo $this->element('cities-index', array('cache' => array('key' => $city_id, 'config' => 'site_element_cache_20_min'))); ?>
			</div>
		</div>
		*/ ?>
		<div class="top-wrapper js-show-subscription hide">
			<div id="test" class="top-bar">
              <h2 class="sub-title textn">Subscribe To Daily Deal Newsletter</h2>
        	<div class="clearfix subscription-wrapper">
				<?php if($this->Html->isAllowed($this->Auth->user('user_type_id')) && $this->request->params['controller'] != 'subscriptions'): ?>
					<?php echo $this->element('../subscriptions/add', array('step' => 1, 'cache' => array('config' => 'site_element_cache', 'key' => $city_slug)));?>
				<?php endif; ?>
				<ul class="header-nav">
					<?php
						$cityArray = array();
						if(!empty($city_slug)):
							$tmpURL= $this->Html->getCityTwitterFacebookURL($city_slug);
							$cityArray = array('city'=>$city_slug);
						endif;
					?>
					<li class="grid_left rss"><?php echo $this->Html->link(__l('RSS'), array_merge(array('controller'=>'deals', 'action'=>'index', 'ext'=>'rss'), $cityArray), array('target' => '_blank','title'=>__l('RSS Feed'))); ?></li>
					<li class="grid_left twitter"><a href="<?php echo !empty($tmpURL['City']['twitter_url']) ? $tmpURL['City']['twitter_url'] : Configure::read('twitter.site_twitter_url'); ?>" title="<?php echo __l('Follow Us in Twitter'); ?>" target="_blank"><?php echo __l('Twitter'); ?></a></li>
					<li class="grid_left facebook"><a href="<?php echo !empty($tmpURL['City']['facebook_url']) ? $tmpURL['City']['facebook_url'] : Configure::read('facebook.site_facebook_url'); ?>" title="<?php echo __l('See Our Profile in Facebook'); ?>" target="_blank"><?php echo __l('Facebook'); ?></a></li>
				</ul>
			</div>
			<?php
				if($this->Auth->sessionValid()  and  $this->Auth->user('user_type_id') == ConstUserTypes::Company):
						$company = $this->Html->getCompany($this->Auth->user('id'));
				endif;
			?>
		</div>
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
		<?php if ($this->Session->check('Message.TransactionSuccessMessage')):?>
        	<div class="transaction-message info-details ">
			<?php 
				echo $this->Session->read('Message.TransactionSuccessMessage');
				$this->Session->delete('Message.TransactionSuccessMessage');
			?>
			</div>
        <?php endif; ?>
<div id="<?php echo $this->Html->getUniquePageId();?>"> 
	<div id="header" class="pr">
    <div class="header-inner">
      <div class="container_24 clearfix">
        <div class="clearfix">
          <h1 class="grid_6"> <?php echo $this->Html->link(Configure::read('site.name'), array('controller' => 'deals', 'action' => 'index', 'admin' => false), array('title' => Configure::read('site.name'))); ?> </h1>
          <p class="hidden-info"><?php echo __l('Collective Buying Power');?></p>
          <div class="grid_left grid_10 dealname-link omega alpha">
		  <span><?php echo __l('Choose Your City'); ?></span>
             <?php	if(!empty($city_name)):
              	echo $this->Html->link($city_name, (array('controller' => 'cities', 'action' => 'index','admin' => false, 'city' => $city_slug)), array('title' => $city_name, 'class' => 'js-thickbox-city'));
          else:
              	echo $this->Html->link($city_name, (array('controller' => 'cities', 'action' => 'index','admin' => false)), array('title' => $city_name, 'class' => 'js-thickbox-city'));

             endif; ?>
          </div>
          <div class="menu-right grid_right clearfix">
            <?php echo $this->element('menu'); ?>
            <?php if(!$this->Auth->sessionValid()){ ?>
				<div class="openid-block ver-mspace">
					<ul class="open-id-list clearfix">
						<li class="grid_left face-book">
							<?php 
								if(Configure::read('facebook.is_enabled_facebook_connect')):   
								echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook'), array('title' => __l('Sign in with Facebook'), 'escape' => false));  
								endif; 
							?>
						</li>
						<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
							<li class="grid_left twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
						<?php 
							endif; 
							if(Configure::read('foursquare.is_enabled_foursquare_connect')): ?>
								<li class="grid_left foursquare"><?php echo $this->Html->link(__l('Sign in with Foursquare'), array('controller' => 'users', 'action' => 'login',  'type'=> 'foursquare', 'admin'=>false), array('class' => 'Foursquare', 'title' => __l('Sign in with Foursquare')));?></li>
						<?php 
							endif; 
							if(Configure::read('user.is_enable_yahoo_openid')):
						?>
								<li class="grid_left yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type'=>'yahoo'), array('title' => __l('Sign in with Yahoo')));?></li>
						<?php 
							endif; 
							if(Configure::read('user.is_enable_gmail_openid')):
						?>
								<li class="grid_left gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type'=>'gmail'), array('title' => __l('Sign in with Gmail')));?></li>
						<?php 
							endif; 
							if(Configure::read('user.is_enable_openid')):
						?>
							<li class="grid_left open-id"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid'), array('class'=>'','title' => __l('Sign in with Open ID')));?></li>
						<?php endif;?>
					</ul>
				</div>
        	<?php } ?>
          </div>
        </div>
        <ul class="menu clearfix">
			<li <?php if($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'index' && !isset($this->request->params['named']['type']) && !isset($this->request->params['named']['company'])) { echo 'class="active"'; } ?>><?php echo $this->Html->link(__l('Today\'s Deals'), array('controller' => 'deals', 'action' => 'index', 'admin' => false), array('title' => __l('Today\'s Deals')));?></li>
			<?php if (Configure::read('deal.is_live_deal_enabled')) { ?>
				<li <?php if($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'live') { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('Live Deals'), array('controller' => 'deals', 'action' => 'live', 'admin' => false), array('title' => __l('Live Deals')));?></li>
			<?php } ?>
			<li <?php if($this->request->params['controller'] == 'deals' && (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'recent')) { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('Recent Deals'), array('controller' => 'deals', 'action' => 'index', 'admin' => false,'type' => 'recent'), array('title' => __l('Recent Deals')));?></li>
			<li <?php if($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view'  && $this->request->params['pass'][0] == 'how_it_works') { echo 'class="active"'; } ?>><?php echo $this->Html->link(sprintf(__l('How It Works')), array('controller' => 'pages', 'action' => 'view', 'how_it_works', 'admin' => false), array('title' => sprintf(__l('How It Works'))));?></li>
			<?php 
				if(!$this->Auth->sessionValid()):
					$url = strstr($this->request->url,"/company/user/register");?>
					<li <?php if((!empty($url)) || ($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view' &&  $this->request->params['pass'][0] == 'merchant')) { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('Business'), array('controller' => 'pages', 'action' => 'view', 'merchant', 'admin' => false), array('title' => __l('Business')));?></li>
			<?php 
				endif; 
				if($this->Auth->sessionValid()): 
					$url = Router::url(array('controller' => 'users', 'action' => 'my_stuff', 'admin' => false),true); 
				endif; 
			?>
      </ul>
      </div>
    </div>
    <div class="global-block pa top-mspace">
      <div class="container_24 clearfix">
        <ul class="global-links-r grid_right clearfix">
			<?php if($this->Html->isAllowed($this->Auth->user('user_type_id')) && $this->request->params['controller'] != 'subscriptions'): ?>
				<li class="down-arrow"><?php echo $this->Html->link(__l('Subscribe'), '#', array('title' => __l('Subscribe'), 'class' => "js-thickbox-subscribe cboxElement")); ?></li>
			<?php endif; ?>
			<?php if(Configure::read('referral.referral_enable') && Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer):
					$class = ($this->request->params['controller'] == 'pages') && ($this->request->params['pass'][0] == 'refer_a_friend') ? ' class="active"' : null; ?>
			 <li <?php echo $class;?>><?php echo $this->Html->link(__l('Refer Friends, Get').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'), false)), array('controller' => 'pages', 'action' => 'refer_a_friend', 'admin' => false), array('title' => __l('Refer Friends, Get').' '. $this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'), false))));?></li>
			<?php elseif(Configure::read('referral.referral_enable') && Configure::read('referral.referral_enabled_option') == ConstReferralOption::XRefer):
					if(Configure::read('referral.refund_type') == ConstReferralRefundType::RefundDealAmount):
						$refund_type = __l('Get a Free Deal!!!');
					else:
						$refund_type = __l('Get').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('referral.refund_amount'), false)).' '.__l('');
					endif;
					$msg = __l('Refer').' '.Configure::read('referral.no_of_refer_to_get_a_refund').' '.__l('Friends').', '.$refund_type;
					$class = ($this->request->params['controller'] == 'pages')  && ($this->request->params['pass'][0] == 'refer_friend') ? ' class="active"' : null; 
			?>
					<li <?php echo $class;?>><?php echo $this->Html->link($msg, array('controller' => 'pages', 'action' => 'refer_friend'), array('title' => $msg));?></li>
			<?php endif;
				$class = ($this->request->params['controller'] == 'contacts' && $this->request->params['action'] == 'add') ? ' class="active"' : null; ?>
				<li <?php echo $class;?>><?php echo $this->Html->link(__l('Contact Us'), array('controller' => 'contacts', 'action' => 'add', 'admin' => false), array('title' => __l('Contact Us')));?></li>
		</ul>
        <?php echo $this->element('lanaguge-change-block'); ?>
    </div>
  </div>
</div>
<div id="main" class="clearfix container_24 pr">
<div class="grid_24">
			 	<?php
					if (!($this->request->params['controller'] == 'deals' && ($this->request->params['action'] == 'live' ||$this->request->params['action'] == 'view' || ($this->request->params['action'] == 'index' && empty($this->request->params['named']['company']))) or(($this->request->params['controller'] == 'companies' && $this->request->params['action'] == 'dashboard')))): 
				?>
			             	<div class="side1-tl">
								<div class="side1-tr">
									<div class="side1-tm"> </div>
								</div>
							</div>
							<div class="side1-cl">
								<div class="side1-cr">
									<div class="block1-inner clearfix">
                <?php 
					endif; 
					echo $content_for_layout;
					if (!($this->request->params['controller'] == 'deals' && ($this->request->params['action'] == 'live' || $this->request->params['action'] == 'view' || ($this->request->params['action'] == 'index' && empty($this->request->params['named']['company'])))or(($this->request->params['controller'] == 'companies' && $this->request->params['action'] == 'dashboard')))): 
				?>
									</div>
								</div>
							</div>
									<div class="side1-bl">
										<div class="side1-br">
											<div class="side1-bm"> </div>
										</div>
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
  <div id="footer" class="sfont">
    <div class="footer-inner container_24">
      <div class="footer-wrapper-inner clearfix">
        <div class="footer-section footer-section1 grid_3 omega alpha">
          <ul class="footer-nav top-space">
			<?php $class = ($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view' && $this->request->params['pass'][0] == 'about') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $this->Html->link(__l('About'), array('controller' => 'pages', 'action' => 'view', 'about', 'admin' => false), array('title' => __l('About')));?> </li>
			<?php $class = ($this->request->params['controller'] == 'contacts' && $this->request->params['action'] == 'add') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Contact Us'), array('controller' => 'contacts', 'action' => 'add', 'admin' => false), array('title' => __l('Contact Us')));?></li>
			<?php $class = ($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view' && $this->request->params['pass'][0] == 'term-and-conditions') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Terms & Conditions'), array('controller' => 'pages', 'action' => 'view', 'term-and-conditions', 'admin' => false), array('title' => __l('Terms & Conditions')));?></li>
			 <?php $class = ($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view' && $this->request->params['pass'][0] == 'privacy_policy') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Privacy Policy'), array('controller' => 'pages', 'action' => 'view', 'privacy_policy', 'admin' => false), array('title' => __l('Privacy Policy')));?></li>
         </ul>
        </div>
        <div class="footer-section footer-section2 grid_4 alpha omega">
		<?php $user_type = $this->Auth->user('user_type_id');?>
		<ul class="footer-nav top-space">
			<?php $class = ($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view' && $this->request->params['pass'][0] == 'faq') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $this->Html->link(__l('FAQ'), array('controller' => 'pages', 'action' => 'view', 'faq', 'admin' => false), array('title' => __l('FAQ')));?></li>
			<?php $class = ($this->request->params['controller'] == 'business_suggestions' && $this->request->params['action'] == 'add') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Suggest a business'), array('controller' => 'business_suggestions', 'action' => 'add', 'admin' => false), array('title' => __l('Suggest a business'))); ?></li>
			<?php if(!$this->Auth->sessionValid()):
				$url = strstr($this->request->url,"/company/user/register"); ?>
					<li <?php if((!empty($url)) || ($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view' &&  $this->request->params['pass'][0] == 'company')) { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(Configure::read('site.name').' '.__l('for Your Business'), array('controller' => 'pages', 'action' => 'view', 'merchant', 'admin' => false), array('title' => Configure::read('site.name').' '.__l('for Your Business')));?></li>
			<?php endif;
				if(Configure::read('affiliate.is_enabled')):
					$class = ($this->request->params['controller'] == 'affiliates') ? ' class="active"' : null; ?>
					<li <?php echo $class;?>><?php echo $this->Html->link(__l('Affiliates'), array('controller' => 'affiliates', 'action' => 'index'),array('title' => __l('Affiliates'))); ?></li>
			<?php endif;
				if($this->Auth->sessionValid() && $this->Auth->user('user_type_id') == ConstUserTypes::Company):  ?>
					<li <?php if($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view'  && $this->request->params['pass'][0] == 'how_it_works') { echo 'class="active"'; } ?>><?php echo $this->Html->link(sprintf(__l('How It Works')), array('controller' => 'pages', 'action' => 'view', 'how_it_works', 'admin' => false), array('title' => sprintf(__l('How It Works'))));?></li>
			<?php endif;?>
		</ul>
        </div>
        <div class="footer-section3 grid_9 omega alpha">
          <div class="mobile-content clearfix">
            <ul>
              <li class="mobile-pda grid_left">
				<?php if (Configure::read('site.is_mobile_app')):
						$url = (env('HTTPS') )? 'https://m.' : 'http://m.';
						$url = $url . str_replace('www.', '', env('HTTP_HOST')) . str_replace('/index.php', '', env('SCRIPT_NAME')).'?mobile=true';
						echo $this->Html->link(__l('WAP/PDA'), $url);
					endif; ?>
             </li>
              <li class="touch grid_left">
               <?php if (Configure::read('site.is_touch_app')):
					$url = (env('HTTPS') )? 'https://touch.' : 'http://touch.';
					$url = $url . str_replace('www.', '', env('HTTP_HOST')) . str_replace('/index.php', '', env('SCRIPT_NAME')).'?mobile=true';
					echo $this->Html->link(__l('Touch'), $url);
				endif;?>
             </li>
            </ul>
            <ul class="iphone-anroid grid_left">
            <li class="add-store"> <a href="http://itunes.apple.com/us/app/igroupdeal/id445358823?ls=1&amp;mt=8" target="_blank" title="App Store" class="add-store">App Store</a></li>
            <li class="android-market"> <a class="android-market" href="http://market.android.com/details?id=com.groupdeal.GroupDeal" target="_blank" title="Andorid Market">Andorid Market</a></li>
            </ul>
          </div>
        </div>
        <div class="footer-section4 grid_5">
          <h6><?php echo __l('Follow Us'); ?></h6>
          <ul class="footer-nav1 bot-space clearfix">
	  	   <?php if(!empty($city_slug)):
					$tmpURL= $this->Html->getCityTwitterFacebookURL($city_slug);
				endif; ?>
			<li class="tweet2 grid_left"><a href="<?php echo !empty($tmpURL['City']['twitter_url']) ? $tmpURL['City']['twitter_url'] : '#'; ?>" title="<?php echo __l('Follow Us in Twitter'); ?>" target="_blank"><?php echo __l('Twitter'); ?></a></li>
			<li class="face2 grid_left"><a href="<?php echo !empty($tmpURL['City']['facebook_url']) ? $tmpURL['City']['facebook_url'] : '#'; ?>" title="<?php echo __l('See Our Profile in Facebook'); ?>" target="_blank"><?php echo __l('Facebook'); ?></a></li>
        </ul>
		<?php echo $this->element('total_saved');?>
       </div>
      </div>
      <div id="agriya" class="ver-space clearfix">
          <p class="copy grid_left dc">&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
		  <p class="powered grid_left dc"><span><a href="<?php echo (env('HTTPS') )? '#' :  'http://groupdeal.dev.agriya.com/'; ?>" title="<?php echo __l('Powered by GroupDeal');?>" target="_blank" class="powered"><?php echo __l('Powered by GroupDeal');?></a>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', (env('HTTPS') )? '#' : 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
		  <p class="grid_left dc"><?php echo $this->Html->link('CSSilized by CSSilize, PSD to XHTML Conversion', (env('HTTPS') )? '#' : 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize, PSD to XHTML Conversion', 'class' => 'cssilize'));?></p>
      </div>
    </div>
  </div>
</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>