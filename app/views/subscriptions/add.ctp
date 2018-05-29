<?php /* SVN: $Id: add.ctp 79501 2012-09-26 14:01:22Z rajeshkhanna_146ac10 $ */ ?>
<?php if(Configure::read('site.is_in_prelaunch_mode') && (!empty($this->request->params['named']['is_from_pages']) || $this->request->data['Subscription']['is_from_pages'])):?>
		<div class="form js-responses">
			<?php echo $this->Form->create('Subscription', array('id' => 'homeSubscriptionFrom', 'class' => 'normal js-ajax-form'));?>
            	<div class="subscriptions-content-form step-content-info round-10" style="margin:9% auto 0 auto;">
            	  <div class="clearfix">
            	<?php echo $this->Form->input('email',array('id' => 'homeEmail', 'label' => __l('Email address:'))); ?>
			
              
                	<?php echo $this->Form->input('city_id',array('id' => 'homeCityId', 'label' => __l('Choose your city:'), 'options' => $cities)); ?>
    				<?php echo $this->Form->input('is_from_pages',array('type' => 'hidden', 'value' => (!empty($this->request->params['named']['is_from_pages']) ? $this->request->params['named']['is_from_pages'] : $this->request->data['Subscription']['is_from_pages']))); ?>
                </div>
                	<p class="subcription-info-second sfont hor-space">(<?php echo __l('We\'ll never share your e-mail address)');?></p>
            	<div class="submit-block clearfix">
					<?php echo $this->Form->submit(__l('Subscribe'));?>
				</div>
				</div>
				<?php echo $this->Form->end(); ?>
		</div>
	<?php else:?>
		<?php if(preg_match('/subscribe/s',$this->request->url) && Configure::read('site.enable_three_step_subscription') && !$this->Auth->sessionValid() && empty($this->layoutPath) && !isset($this->request->data['Subscription']['is_from_submit'])): ?>
			
			<?php echo $this->Form->create('Subscription', array('id' => 'homeSubscriptionFrom', 'class' => 'normal form js-grouponpro_sub_form {Currentstep:'.$Currentstep.'}'));?>
						<div class="js-step_one step-one js-form_step">
							<div class="step-content-info round-10" style="margin:9% auto 0 auto;">
							<?php echo $this->Form->input('city_id',array('id' => 'homeCityId', 'label' => __l('Choose your city:'), 'options' => $cities)); ?>				
							<div class="js-buttons clearfix">
        <?php echo $this->Form->submit(__l('Continue'), array('type'=>'button','id' => 'step_one', 'class' => 'js-button js-continue'));?>
									<ul class="sign-in">
										<?php if(!$this->Auth->sessionValid()): ?>
										<li>
										   <?php echo $this->Html->link(__l('Sign in'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Sign in'),'class'=>'login-link'));?>
                                        </li>
                                            <?php elseif($this->Auth->user('user_type_id') == ConstUserTypes::User):?>
                                            <li>
                                            <?php echo $this->Html->link(__l('My Stuff'), array('controller' => 'users', 'action' => 'my_stuff'), array('title' => __l('My Stuff')));?>
                                            </li>
                                        <?php elseif($this->Auth->user('user_type_id') == ConstUserTypes::Company):?>
                                            <li>
											<?php echo $this->Html->link(__l('My Deals'), array('controller' => 'deals', 'action' => 'index', 'company' => $company['Company']['slug'] ), array('title' => __l('My Deals')));?>
                                            </li>
                                        <?php elseif($this->Auth->user('user_type_id') == ConstUserTypes::Admin):?>
                                            <li>
                                            <?php echo $this->Html->link(__l('Admin'), array('controller' => 'users' , 'action' => 'stats' , 'admin' => true), array('title' => __l('Admin'))); ?>
                                            </li>
										<?php endif;?>
                                        <?php if(configure::read('site.subscription_skip_option_enabled')):?>
                                            <li>
											<?php echo $this->Html->link(__l('Already Registered').'?', array('controller' => 'subscriptions', 'action' => 'skip'), array('title' => __l('Already Registered').'?', 'class'=>'login-link'));?>
                                            </li>
                                        <?php endif; ?>    
									</ul>
							</div>
						</div>
						</div>
						<div class="js-step_two step-one step-two js-form_step hide">
						<div class="step-content-info round-10" style="margin:9% auto 0 auto;">
							<?php echo $this->Form->input('email',array('id' => 'homeEmail', 'label' => __l('Enter your email address:'),
							'after'=>'<p class="dl sfont email_never_share"> Don\'t worry, your email is safe and secure with us.</p>')); ?>	
								 <div class="clearfix">
									<?php echo $this->Form->submit(__l('Subscribe'));?>
							
									<ul class="sign-in">
										<?php if(!$this->Auth->sessionValid()): ?>
										<li>
											<?php echo $this->Html->link(__l('Sign in'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Sign in'),'class'=>'login-link')) ;?>
                                            </li>
                                            <?php elseif($this->Auth->user('user_type_id') == ConstUserTypes::User):?>
                                            <li>
                                            <?php echo $this->Html->link(__l('My Stuff'), array('controller' => 'users', 'action' => 'my_stuff'), array('title' => __l('My Stuff')));?>
                                            </li>
                                        <?php elseif($this->Auth->user('user_type_id') == ConstUserTypes::Company):?>
                                            <li>
                                        	<?php echo $this->Html->link(__l('My Deals'), array('controller' => 'deals', 'action' => 'index', 'company' => $company['Company']['slug'] ), array('title' => __l('My Deals')));?>
                                            </li>
                                        <?php elseif($this->Auth->user('user_type_id') == ConstUserTypes::Admin):?>
                                            <li>
                                            <?php echo $this->Html->link(__l('Admin'), array('controller' => 'users' , 'action' => 'stats' , 'admin' => true), array('title' => __l('Admin'))); ?>
                                            </li>
                                    	<?php endif;?>
                                         <?php if(configure::read('site.subscription_skip_option_enabled')):?>
                                        <li>
											<?php echo $this->Html->link(__l('Already Registered').'?', array('controller' => 'subscriptions', 'action' => 'skip'), array('title' => __l('Already Registered').'?', 'class'=>'login-link'));?>
                                         </li>
                                          <?php endif; ?>    
									</ul>
										</div>
						</div>
						</div>
				<?php echo $this->Form->end(); ?>
		<?php elseif(preg_match('/subscribe/s',$this->request->url) && !isset($this->request->data['Subscription']['is_from_submit'])): ?>
		<h2 class="subscribe-title"><?php echo $city_name ;?> <?php echo __l('Deal of the Day'); ?></h2>
			<div class="form subscriptions-add">
				<?php echo $this->Form->create('Subscription', array('id' => 'homeSubscriptionFrom', 'class' => 'normal'));?>
					<h2 class="welcome-head textn"> <?php echo __l('Welcome to').' ';?><span><?php echo Configure::read('site.name'); ?></span></h2>
						<div class="subscriptions-content">
							<?php echo sprintf(__l('Every day, %s e-mails you one exclusive offer to do, see, taste, or experience something amazing in').' '.$city_name.' '.__l('at an unbeatable price.'),Configure::read('site.name')); ?>
						</div>

						<div class="subscriptions-content-form round-10 clearfix">
						<div class="signup-content dc"><?php echo __l('Sign up now for free, and prepare to discover') . ' ' . $city_name . ' ' . __l('at 40% to 90% off! '); ?></div>
					        <div class="clearfix">
							<?php echo $this->Form->input('email',array('id' => 'homeEmail', 'label' => __l('Enter your Email address:'))); ?>
							<?php echo $this->Form->input('city_id',array('id' => 'homeCityId', 'label' => __l('Choose your city:'), 'options' => $cities)); ?>
                            </div>
                            <p class="subcription-info sfont hor-space"><?php echo __l('(We\'ll never share your e-mail address) '); ?></p>
                            <div class="clearfix">
							<?php echo $this->Form->submit(__l('Subscribe'));?>
							</div>
					
						
						</div>

						<?php echo $this->Form->end(); ?>
							<div class="subscriptions-content subscriptions-offer-info">
						<?php echo __l('Our daily offers are for:'); ?>
						<?php echo __l('Restaurants, Spas, Concerts, Bars, Sporting Events, Classes, Salons,Adventures and so much more... '); ?>
						</div>
			</div>
		<?php else: ?>
			<div class="subscriptions form clearfix js-responses">            			
				<?php echo $this->Form->create('Subscription', array('class' => 'subscriptions normal clearfix js-ajax-form', 'id' => 'SubscriptionAddForm'.$step ));?>
					<?php
						if(!empty($city_id)):
							$this->request->data['Subscription']['city_id'] = $city_id;
						endif;
					?>
					<?php echo $this->Form->input('email',array('label' => __l('Email Address'), 'class' => 'emailsubscription', 'id' => 'SubscriptionEmail'.$step)); ?>
					<?php 
					if(!empty($this->request->data['Subscription']['city_id'])):
						echo $this->Form->input('city_id',array('type' => 'hidden', 'value' => $this->request->data['Subscription']['city_id'], 'id' => 'SubscriptionCityId'.$step)); 
					else:
						echo $this->Form->input('city_id',array('type' => 'hidden', 'id' => 'SubscriptionCityId'.$step)); 
					endif;			
					?>
                    <?php echo $this->Form->input('is_from_submit',array('type' => 'hidden', 'value' => 'sub_add')); ?>
						<div class="submit-block clearfix">
						<?php echo $this->Form->submit(__l('Subscribe'));?>
						</div>
				<?php echo $this->Form->end();?>
			</div>
		<?php endif; ?>
<?php endif; ?>