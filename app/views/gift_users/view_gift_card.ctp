<?php /* SVN: $Id: add.ctp 756 2010-04-13 06:38:30Z preethi_083at09 $ */ ?>
<div class="giftUser">
	<?php if(empty($this->request->params['isAjax'])):?>
		<h2><?php echo __l('Gift Card');?></h2>	
	<?php endif; ?>
		<div class="clearfix <?php echo ($this->request->params['isAjax'])?'gift-card':'giftcard-bg pr';?>">
			<div class="gift-card clearfix">
            <div class="clearfix">
            	<div class="gift-side1">
                    <h3 class="gift-title"><span id="js-gift-from"><?php echo !empty($giftUser['GiftUser']['from']) ? $giftUser['GiftUser']['from'] : $giftUser['User']['username']; ?></span></h3>
                    <p> <?php echo __l('has given you'); ?></p>
                    <p class="card-amount"><span id="js-gift-amount"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($giftUser['GiftUser']['amount'])); ?></span></p>
                    <p class="sitename-info"><?php echo sprintf(__l('credit to %s '), Configure::read('site.name')); ?></p>
                  
                </div>		
                <div class="gift-side2">
                    <dl class="card-info clearfix">
                    <dt><?php echo __l('to'); ?></dt>
                    <dd id="js-gift-to"><?php echo $giftUser['GiftUser']['friend_name']; ?></dd>
                    </dl>
                    <p id="js-gift-message" class="card-message">
					<?php echo $giftUser['GiftUser']['message']; ?>
                </p>
                </div>
                </div>
                  <div class="remeber-block">
                        <p class="redemption-code-left"><?php echo __l('Redemption Code:'); ?></p>
                        <p class="code-info textb redemption-code-right">
                            <?php echo $giftUser['GiftUser']['coupon_code']; ?>
                        </p>
                    </div>
			</div>
            <?php if(!$this->Auth->user('id')): ?>
				<div class="gift-login login-right-block top-mspace pa">
                     <?php echo $this->Html->link(__l('Login'), '#', array('title' => __l('Login to Site'), 'class' => "register-link textb hor-mspace js-toggle-show {'container':'js-gift-card-login', 'hide_container': 'js-gift-card-register'}"));?>
					 <?php echo $this->Html->link(__l('Register'), '#', array('title' => __l('Register to Site'), 'class' => "register-link hor-mspace js-toggle-show {'container':'js-gift-card-register', 'hide_container':'js-gift-card-login'}"));?>
                    	<div class="js-gift-card-login hide">
    						<?php echo $this->element('users-login', array('f' => 'gift_users/redeem/'.$giftUser['GiftUser']['coupon_code'], 'cache' => array('config' => 'site_element_cache')));?>
                        </div>
                	<div class="js-gift-card-register hide">
						<?php echo $this->element('users-register', array('f' => 'gift_users/redeem/'.$giftUser['GiftUser']['coupon_code'], 'cache' => array('config' => 'site_element_cache')));?>
                    </div>
                 </div>
            <?php elseif(!$giftUser['GiftUser']['is_redeemed'] && $this->Auth->user('user_type_id') != ConstUserTypes::Admin && $giftUser['GiftUser']['friend_mail'] == $this->Auth->user('email')): ?>
				<div class="reedeem-block pa">
					 <?php echo $this->Html->link(__l('Redeem'), array('controller'=> 'gift_users', 'action'=>'redeem', $giftUser['GiftUser']['coupon_code']), array('class'=>'round-5 dc textb', 'title'=>__l('Redeem'),'escape' => false));?>
				 </div>
            <?php endif; ?>
			</div>
		</div>
