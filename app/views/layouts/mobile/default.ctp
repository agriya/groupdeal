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
      <div id="header">
         <h1>
				<?php
					echo $this->Html->link($this->Html->Image('mobile/logo-blue.png', array('alt' => sprintf(__l('[Image: %s]'), Configure::read('site.name')), 'title' => Configure::read('site.name'), 'type' => 'png')), array('controller' => 'deals', 'action' => 'index', 'admin' => false), array('escape' => false));
				?>
			</h1>
        <div class="clearfix">
		<?php if(!empty($city_name)): ?>
			<div class="header-bot-l">
				<h3><?php echo __l('Daily Deals on the Best in'); ?>
					<?php
						if (Cache::read('site.city_url', 'long') == 'prefix') {
							echo $this->Html->link($this->Html->cText($city_name), array('controller' => 'deals', 'action' => 'index', 'city' => $city_slug), array('title' => $this->Html->cText($city_name, false), 'escape' => false));
						} elseif (Cache::read('site.city_url', 'long') == 'subdomain') {
							$subdomain = substr(env('HTTP_HOST'), 0, strpos(env('HTTP_HOST'), '.'));
							$sitedomain = substr(env('HTTP_HOST'), strpos(env('HTTP_HOST'), '.'));
							if (strlen($subdomain) > 0) {
					?>
								<a href="http://<?php echo $city_slug . $sitedomain; ?>" title="<?php echo $this->Html->cText($city_name, false); ?>"><?php echo $this->Html->cText($city_name); ?></a>
					<?php
							} else {
								echo $this->Html->link($this->Html->cText($city_name), array('controller' => 'deals', 'action' => 'index', 'city' => $city_slug), array('title' => $this->Html->cText($city_name, false), 'escape' => false));
							}
						}
					?>
				</h3>
				<?php echo $this->Html->link(__l('Visit More Cities'),array('controller' => 'cities', 'action' => 'index'),array('title'=>__l('Visit More Cities'))); ?>
		
			<?php endif;?>
		
            	</div>
			
             <?php if($this->Auth->sessionValid()): ?>
                    <div class="header-bot-r grid_right">
                    	<?php echo $this->element('lanaguge-change-block', array('cache' => array('config' => 'site_element_cache')));?>
                        <dl class="total-list clearfix">
                        	<dt class="grid_left"><?php echo __l('Balance: '); ?></dt>
                            <dd class="grid_left"><span><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($user_available_balance)); ?></span></dd>
                        </dl>
						<?php if ((Configure::read('company.is_user_can_withdraw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::Company) || (Configure::read('user.is_user_can_with_draw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::User)) { ?>
                            <p class="add-amount">
                            <?php echo $this->Html->link(__l('Withdraw Fund Request'), array('controller' => 'user_cash_withdrawals', 'action' => 'index'), array('title' => __l('Withdraw Fund Request'),'class'=>'width-draw'));?>
                            </p>
                        <?php } ?>
                        <?php if($this->Html->isAllowed($this->Auth->user('user_type_id'))): ?>
                            <p class="add-amount"><?php echo $this->Html->link(__l('Add amount to wallet'), array('controller' => 'users', 'action' => 'add_to_wallet'), array('class' => 'add add-wallet', 'title' => __l('Add amount to wallet'))); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                </div>
			<div class="menu-block clearfix">
          	<ul class="menu clearfix">
          		<?php if($this->Html->isAllowed($this->Auth->user('user_type_id'))): ?>
                    <li <?php if($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'index' && !isset($this->request->params['named']['type']) && !isset($this->request->params['named']['company'])) { echo 'class="active"'; } ?>><?php echo $this->Html->link(__l('Today\'s Deals'), array('controller' => 'deals', 'action' => 'index', 'admin' => false), array('title' => __l('Today\'s Deals')));?></li>
                    <li <?php if($this->request->params['controller'] == 'deals' && (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'recent')) { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('Recent Deals'), array('controller' => 'deals', 'action' => 'index', 'admin' => false,'type' => 'recent'), array('title' => __l('Recent Deals')));?></li>
               <?php endif; ?>
				<li <?php if($this->request->params['controller'] == 'topics' && $this->request->params['action'] == 'index') { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('Discussion'), array('controller' => 'topics', 'action' => 'index'), array('title' => __l('Discussion')));?></li>
				
				<li <?php if($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view'  && $this->request->params['pass'][0] == 'how_it_works') { echo 'class="active"'; } ?>><?php echo $this->Html->link(sprintf(__l('How')." ".'%s'." " .__l('Works'), Configure::read('site.name')), array('controller' => 'pages', 'action' => 'view', 'how_it_works'), array('title' => sprintf(__l('How')." ".'%s'." ".__l(' Works'), Configure::read('site.name'))));?></li>

				<?php if(!$this->Auth->sessionValid()):?>	
					<li <?php if($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view' &&  $this->request->params['pass'][0] == 'company') { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('Business'), array('controller' => 'pages', 'action' => 'view', 'merchant', 'admin' => false), array('title' => __l('Business')));?></li>
				<?php endif; ?>
			</ul>            
            <div class="menu-right">                 
            <p class="user-login-info">
                    <span class="user">            
							<?php
						$reg_type_class='normal';
						if (!$this->Auth->sessionValid()):							
                            echo __l('Hi, Guest');
                            ?>
           					<span class="welcome-info no-mar">
            				   <span <?php if($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'login') { echo 'class="active"'; } ?>><?php echo $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Login'),'class'=>'login-link'));?></span>
    	           			   / <span <?php if($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'register') { echo 'class="active"'; } ?>><?php echo $this->Html->link(__l('Register'), array('controller' => 'users', 'action' => 'register', 'admin' => false), array('title' => __l('Register'),'class'=>'login-link'));?></span>
            				 </span>
                            <?php
                          else:
								if($this->Auth->user('is_openid_register')):
									$reg_type_class='open-id';
								endif;
								if($this->Auth->user('fb_user_id')):
									$reg_type_class='facebook';
								endif;
								?>
							<?php
							$current_user_details = array(
								'username' => $this->Auth->user('username'), 
								'user_type_id' =>  $this->Auth->user('user_type_id'), 
								'id' =>  $this->Auth->user('id'),
								'fb_user_id' =>  $this->Auth->user('fb_user_id')
							);
                            if($this->Auth->user('user_type_id') != ConstUserTypes::Admin):
                                    echo __l('Hi, '); ?>
										<span class="<?php echo $reg_type_class; ?>">
											<?php echo $this->Html->getUserLink($current_user_details);?>
										</span> 
									<?php
									$current_user_details['UserAvatar'] = $this->Html->getUserAvatar($this->Auth->user('id'));
									echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb');
                            else:?>
								<span class="<?php echo $reg_type_class; ?>">
									<?php echo $this->Html->getUserLink($current_user_details);?>
								</span> 
                            <?php
							endif;
                        endif;
                    ?>
                
				<?php if($this->Auth->sessionValid()): ?>
                        <?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('class' => 'logout-link', 'title' => __l('Logout')));?>
				<?php endif; ?>
				</span>
            </p>
            <?php if($this->Auth->sessionValid()): ?>
              	<ul class="user-menu">
								<?php 
									$user = $this->Html->getCompany($this->Auth->user('id'));
								?>					
						<?php if($this->Auth->sessionValid()):?>
							<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
									<li <?php if($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'my_stuff') { echo 'class="active"'; } ?>>
										<?php  echo $this->Html->link(__l('My Stuff'), array('controller' => 'users', 'action' => 'my_stuff'), array('title' => __l('My Stuff')));?>
									</li>
							<?php elseif($this->Auth->user('user_type_id') == ConstUserTypes::Company): ?>							
									<?php if($this->Html->isAllowed($this->Auth->user('user_type_id'))): ?>
										<li <?php if($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'my_stuff') { echo 'class="active"'; } ?>>
											<?php  echo $this->Html->link(__l('My Stuff'), array('controller' => 'users', 'action' => 'my_stuff'), array('title' => __l('My Stuff')));?>
										</li>
									<?php else: ?>
										 <li <?php if($this->request->params['controller'] == 'companies' && $this->request->params['action'] == 'edit') { echo 'class="active"'; } ?>>
											 <?php echo $this->Html->link(__l('My Merchant'), array('controller' => 'companies', 'action' => 'edit',$user['Company']['id']), array('title' => __l('My Account'))); ?>
										 </li>
									<?php endif; ?>
								<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company && !empty($user['Company'])):?>
									<li <?php if($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'index' && !empty($this->request->params['named']['company'])) { echo 'class="active"'; } else { echo 'class=""';}?>><?php echo $this->Html->link(__l('My Deals'), array('controller' => 'deals', 'action' => 'index', 'company' => $user['Company']['slug'] ), array('title' => __l('My Deals')));?></li>
								<?php endif; ?>
								
								<li <?php if($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'add') { echo 'class="active"'; } ?>><?php echo $this->Html->link(__l('Add Deal'), array('controller' => 'deals', 'action' => 'add'), array('class'=>'add-deal', 'title' => __l('Add Deal')));?></li>
							<?php endif; ?>	
						<?php endif; ?>
						<?php $url = Router::url(array('controller' => 'users', 'action' => 'my_stuff', 'admin' => false),true); ?>
					
              </ul>
            <?php endif; ?>
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
			<div id="agriya" class="clearfix">
				<p>&copy;<?php echo date('Y');?>
                <?php echo $this->Html->link(Configure::read('site.name'), Router::url('/', true));?>. <?php echo __l('All rights reserved');?>.
                </p>
				<p class="powered clearfix"><span><a href="http://groupdeal.dev.agriya.com/" title="<?php echo __l('Powered by GroupDeal');?>" target="_blank" class="powered"><?php echo __l('Powered by GroupDeal');?></a>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?> <span><?php echo Configure::read('site.version');?></span></p>
				<p><?php echo $this->Html->link('CSSilized by CSSilize', 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize', 'class' => 'cssilize'));?></p>
			</div>
			<?php
			 	$parsed_url = parse_url(Router::url('/', true));
				$mobile_site_url = str_ireplace("m.", '', Router::url('/', true)) . '?mobile=false';
			 	echo $this->Html->link(__l('Regular Version'), $mobile_site_url);
			 ?>
		</div>
	</div>
</body>
</html>