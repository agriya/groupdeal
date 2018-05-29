<?php /* SVN: $Id: admin_index.ctp 75850 2012-02-15 08:27:10Z hariharan_194ac11 $ */ ?>
<div class="users index js-response js-responses js-moreaction-responses">
         	<div class="js-search-responses">
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
	<div>
    <ul class="clearfix filter-list">
            <li class="filter-active"><?php echo $this->Html->link(__l('Active Users').': '.$this->Html->cInt($active), array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Active,'stat' => (!empty($this->request->params['named']['stat']) ? $this->request->params['named']['stat'] : '')),array('title' => __l('Active Users'), 'escape' => false));?></li>
            <li class="filter-inactive"><?php echo $this->Html->link(__l('Inactive Users').': '.$this->Html->cInt($inactive), array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive,'stat' => (!empty($this->request->params['named']['stat']) ? $this->request->params['named']['stat'] : '')),array('title' => __l('Inactive Users'), 'escape' => false)); ?></li>
			<?php if(Configure::read('user.is_enable_openid')): ?>
            <li class="filter-openid"><?php echo $this->Html->link(__l('OpenID Users').': '.$this->Html->cInt($openid_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::OpenID),array('title' => __l('OpenID Users'), 'escape' => false));?></li>
			<?php endif; ?>
			<?php if(Configure::read('facebook.is_enabled_facebook_connect')): ?>
            <li class="filter-facebook"><?php echo $this->Html->link(__l('Facebook Users').': '.$this->Html->cInt($facebook_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::FaceBook),array('title' => __l('FaceBook Users'), 'escape' => false));?></li>
			<?php endif; ?>
            <li class="filter-twitte"><?php echo $this->Html->link(__l('Twitter Users').': '.$this->Html->cInt($twitter_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Twitter),array('title' => __l('Twitter Users'), 'escape' => false));?></li>
			<li class="filter-foursquare"><?php echo $this->Html->link(__l('Foursquare Users').': '.$this->Html->cInt($foursquare_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Foursquare),array('title' => __l('Foursquare Users'), 'escape' => false));?></li>
            <li class="filter-gmail"><?php echo $this->Html->link(__l('Gmail Users').': '.$this->Html->cInt($gamil_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Gmail),array('title' => __l('Gmail Users'), 'escape' => false));?></li>
            <li class="filter-yahoo"><?php echo $this->Html->link(__l('Yahoo Users').': '.$this->Html->cInt($yahoo_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Yahoo),array('title' => __l('Yahoo Users'), 'escape' => false));?></li>
            <li class="filter-iphone"><?php echo $this->Html->link(__l('iPhone Users').': '.$this->Html->cInt($iphone_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::iPhone),array('title' => __l('iPhone Users'), 'escape' => false));?></li>
            <li class="filter-android"><?php echo $this->Html->link(__l('Android Users').': '.$this->Html->cInt($android_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Android),array('title' => __l('Android Users'), 'escape' => false));?></li>
            <li class="filter-giftcard"><?php echo $this->Html->link(__l('Registered Through Gift Card').': '.$this->Html->cInt($gift_card_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => 'gift_card'),array('title' => __l('Registered Through Gift Card'), 'escape' => false));?></li>
            <li class="filter-affiliate"><?php echo $this->Html->link(__l('Affiliate Users').': '.$this->Html->cInt($affiliate_user_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::AffiliateUser),array('title' => __l('Affiliate Users'), 'escape' => false));?></li>
            <li class="filter-admin"><?php echo $this->Html->link(__l('Admin').': '.$this->Html->cInt($admin_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstUserTypes::Admin),array('title' => __l('Admin'), 'escape' => false));?></li>
            <li class="filter-all"><?php echo $this->Html->link(__l('Total Users').': '.$this->Html->cInt($users_without_company_count), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => 'all'),array('title' => __l('Total Users'), 'escape' => false));?></li>
    </ul>
    </div>
           

            <div class="page-count-block clearfix">
            	<div class="grid_left">
	                <?php echo $this->element('paging_counter'); ?>
	             </div>
            <div class="grid_left">
                <?php echo $this->Form->create('User', array('type' => 'post', 'class' => 'normal search-form clearfix js-ajax-form {"container" : "js-search-responses"}', 'action'=>'index')); ?>
               
                            <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
                            <?php echo $this->Form->input('main_filter_id', array('type' => 'hidden', 'value' => !empty($this->request->params['named']['main_filter_id'])? $this->request->params['named']['main_filter_id']:'')); ?>
                            <?php echo $this->Form->input('filter_id', array('type' => 'hidden', 'value' => !empty($this->request->params['named']['filter_id'])?$this->request->params['named']['filter_id']:'')); ?>
                            <?php echo $this->Form->input('tab_check', array('type' => 'hidden', 'value' => '1')); ?>
                     
                            <?php echo $this->Form->submit(__l('Search'),array('name' => "data['User']['search']"));?>
                  
                <?php echo $this->Form->end(); ?>
                </div>
                   <div class="clearfix grid_right add-block1">
                    <?php echo $this->Html->link(__l('Add'), array('controller' => 'users', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
                    <?php
                        echo $this->Html->link(__l('CSV'), array_merge(array('controller' => 'users', 'action' => 'index','city' => $city_slug, 'ext' => 'csv', 'admin' => true), $this->request->params['named']), array('title' => __l('CSV'), 'class' => 'export'));
                    ?>
                  
	              </div>
            </div>
                <?php echo $this->Form->create('User' , array('class' => 'normal js-ajax-form {"container" : "js-moreaction-responses"}','action' => 'update'));  ?>
                <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
	        
                <div class="overflow-block">
             	<div class="">
                <table class="list">
                    <tr>
                        <th rowspan="2" class="select"></th>
                        <th rowspan="2" class="actions"><?php echo __l('Action'); ?></th>
                        <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username'); ?></div></th>
                        <th colspan="2"><div class="js-pagination"><?php echo __l('Purchases'); ?></div></th>
                        <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Available Balance'), 'User.available_balance_amount').' ('.Configure::read('site.currency').')'; ?></div></th>
                        <th colspan="3"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Logins'), 'User.user_login_count'); ?></div></th>
                        <th rowspan="2"><div class="js-pagination"><?php echo __l('Registered On'); ?></div></th>
                    </tr>
					 <tr>
                        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Count'), 'User.total_deal_purchase_count'); ?></div></th>
						<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Amount'), 'User.total_purchased_amount').' ('.Configure::read('site.currency').')'; ?></div></th>
                        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Count'), 'User.user_login_count'); ?></div></th>
						<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Time'), 'User.last_logged_in_time'); ?></div></th>
                        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'LastLoginIp.ip'); ?></div></th>
					 </tr>
					          <?php
                if (!empty($users)):
                $i = 0;
                foreach ($users as $user):
                    $class = null;
					$active_class = '';
                    if ($i++ % 2 == 0):
                        $class = 'altrow';
                    endif;
					$email_active_class = ' email-not-comfirmed';
					if($user['User']['is_email_confirmed']):
						$email_active_class = ' email-comfirmed';
					endif;
                    if($user['User']['is_active']):
                        $status_class = 'js-checkbox-active';
                    else:
						$active_class = ' inactive-record';
                        $status_class = 'js-checkbox-inactive';
                    endif;
                    $online_class = 'offline';
                    if (!empty($user['CkSession']['user_id'])) {
                        $online_class = 'online';
                    }
                ?>
                    <tr class="<?php echo $class.$active_class;?>">
                      <td class="select">
                            <?php
                             if($user['User']['user_type_id'] != ConstUserTypes::Admin):
                              echo $this->Form->input('User.'.$user['User']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$user['User']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list'));
                             endif;
                             ?>
                       </td>
                        <td class="actions">
                        
                        <div class="action-block">
                        <span class="action-information-block">
                            <span class="action-left-block">&nbsp;&nbsp;</span>
                                <span class="action-center-block">
                                    <span class="action-info">
                                        <?php echo __l('Action');?>
                                     </span>
                                </span>
                            </span>
                            <div class="action-inner-block">
                            <div class="action-inner-left-block">
                                <ul class="action-link clearfix">
                                    <li>
                                       <span><?php echo $this->Html->link(__l('Edit'), array('controller' => 'user_profiles', 'action'=>'edit', $user['User']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span>
                                    </li>
                                   <?php if($user['User']['user_type_id'] != ConstUserTypes::Admin){ ?>
                                         <li>
                                            <span><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $user['User']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span>
                                        </li>
                                      <?php } ?>
        					       <li><?php echo $this->Html->link(__l('Ban Signup IP'), array('controller'=> 'banned_ips', 'action' => 'add', $user['Ip']['ip']), array('class' => 'network-ip','title'=>__l('Ban Sign up IP'), 'escape' => false));?></li>
                                       <?php if(Configure::read('user.is_email_verification_for_register') and !$user['User']['is_email_confirmed']):
                                          ?>
                                          <li>
                                          <?php      echo $this->Html->link(__l('Resend Activation'), array('controller' => 'users', 'action'=>'resend_activation', $user['User']['id'], 'admin' => false),array('title' => __l('Resend Activation'),'class' =>'recent-activation'));
                                            ?>
                                            </li>
                                            <?php
                                          endif;
                                    ?>
                                    <?php if(!$user['User']['is_facebook_register'] && !$user['User']['is_openid_register'] && !$user['User']['is_twitter_register'] && !$user['User']['is_foursquare_register'] && !$user['User']['is_gmail_register'] && !$user['User']['is_yahoo_register']){?>
                                        <li><?php echo $this->Html->link(__l('Change password'), array('controller' => 'users', 'action'=>'admin_change_password', $user['User']['id']), array('title' => __l('Change password'),'class' => 'password'));?></li>
                                      <?php  }?>
                                     <li><?php echo $this->Html->link(__l('Transactions'), array('controller' => 'transactions', 'action'=>'admin_index','user_id' => $user['User']['id']), array('title' => __l('Transactions'),'class' => 'transaction'));?></li>
        							 <?php if($user['User']['user_type_id'] == ConstUserTypes::User): ?>
        							 <li><?php echo $this->Html->link(__l('Add Fund'), array('controller' => 'users', 'action'=>'add_fund', $user['User']['id']), array('class' => 'add-fund', 'title' => __l('Add Fund')));?></li>
        							 <li><?php echo $this->Html->link(__l('Deduct Fund'), array('controller' => 'users', 'action'=>'deduct_fund', $user['User']['id']), array('class' => 'deduct-fund', 'title' => __l('Deduct Fund')));?></li>
        							 <?php endif; ?>
        							 </ul>
        							</div>
        						<div class="action-bottom-block"></div>
							  </div>
							 
							 </div>
							 
                        </td>
                        <td class="dl">
                        <div class="clearfix user-info-block">
                        <p class="user-img-left grid_left">
                        	<?php
						$chnage_user_info = $user['User'];
						$chnage_user_info['UserAvatar'] = $user['UserAvatar'];
						$user['User']['full_name'] = (!empty($user['UserProfile']['first_name']) || !empty($user['UserProfile']['last_name'])) ? $user['UserProfile']['first_name'] . ' ' . $user['UserProfile']['last_name'] :  $user['User']['username'];
						echo $this->Html->getUserAvatarLink($chnage_user_info, 'micro_thumb',false);
						?>
                            <?php

                                 echo $this->Html->getUserLink($user['User']);
                            ?>
                            </p>
                              <p class="user-img-right clearfix grid_right">
                      
                        <?php if($user['User']['is_affiliate_user']):?>
								<span class="affiliate"> <?php echo __l('Affiliate'); ?> </span>
						<?php endif; ?>
						  <?php if($user['User']['user_type_id'] == ConstUserTypes::Admin):?>
								<span class="admin"> <?php echo __l('Admin'); ?> </span>
						<?php endif; ?>
						</p>
                        </div>
                        <div class="clearfix user-status-block user-info-block">
                        <?php 					
							if(!empty($user['UserProfile']['Country'])):
								?>
                                <span class="flags flag-<?php echo strtolower($user['UserProfile']['Country']['iso2']); ?>" title ="<?php echo $user['UserProfile']['Country']['name']; ?>">
									<?php echo $user['UserProfile']['Country']['name']; ?>
								</span>
                                <?php
	                        endif; 
						?>    
                        <?php if($user['User']['is_openid_register']):?>
								<span class="open_id" title="OpenID"> <?php echo __l('OpenID'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_gmail_register']):?>
								<span class="gmail" title="Gmail"> <?php echo __l('Gmail'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_yahoo_register']):?>
								<span class="yahoo" title="Yahoo"> <?php echo __l('Yahoo'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_facebook_register']):?>
								<span class="facebook" title="Facebook"> <?php echo __l('Facebook'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_twitter_register']):?>
								<span class="twitter" title="Twitter"> <?php echo __l('Twitter'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_iphone_user']):?>
								<span class="iphone" title="iPhone"> <?php echo __l('Iphone'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_android_user']):?>
								<span class="android" title="Android"> <?php echo __l('Android'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_foursquare_register']):?>
								<span class="foursquare" title="Foursquare"> <?php echo __l('Foursquare'); ?> </span>
						<?php endif; ?>
                        <?php if(!empty($user['User']['email'])):?>
								<span class="email <?php echo $email_active_class; ?>" title="<?php echo $user['User']['email']; ?>">
								<?php 
								if(strlen($user['User']['email'])>20) :
									echo '..' . substr($user['User']['email'], strlen($user['User']['email'])-15, strlen($user['User']['email'])); 
								else:
									echo $user['User']['email']; 
								endif; 
								?> 
                                </span>
						<?php endif; ?>
						</div>
                        </td>
                        <td class="dr">
                        <?php echo $this->Html->cInt($user['User']['total_deal_purchase_count']);?>
                        </td>
                        <td class="dr">
                       <?php echo $this->Html->cCurrency($user['User']['total_purchased_amount']);?>
                        </td>
                        <td class="dr"><?php echo $this->Html->cCurrency($user['User']['available_balance_amount']);?></td>
                        <td class="dr">
                        	<?php echo $this->Html->link($this->Html->cInt($user['User']['user_login_count']), array('controller' => 'user_logins', 'action' => 'index', 'username' => $user['User']['username']), array('escape' => false));?>
                        </td>
						<td class="dc">
                        	<?php if($user['User']['last_logged_in_time'] == '0000-00-00 00:00:00' || empty($user['User']['last_logged_in_time'])){
                                echo '-';
                            }else{
                                echo $this->Html->cDateTimeHighlight($user['User']['last_logged_in_time']);
                            }?>
						</td>
						<td class="dl">
                        <?php if(!empty($user['LastLoginIp']['ip'])): ?>							  
                            <?php echo  $this->Html->link($user['LastLoginIp']['ip'], array('controller' => 'users', 'action' => 'whois', $user['LastLoginIp']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$user['User']['dns'], 'escape' => false));								
							?>
							<p>
							<?php 					
                            if(!empty($user['LastLoginIp']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($user['LastLoginIp']['Country']['iso2']); ?>" title ="<?php echo $user['LastLoginIp']['Country']['name']; ?>">
									<?php echo $user['LastLoginIp']['Country']['name']; ?>
								</span>
                                <?php
                            endif; 
							 if(!empty($user['LastLoginIp']['City'])):
                            ?>             
                            <span> 	<?php echo $user['LastLoginIp']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>    
						</td>
                        <td class="dc"><?php if($user['User']['created'] == '0000-00-00 00:00:00'){
                                echo '-';
                            }else{
                                echo $this->Html->cDateTimeHighlight($user['User']['created']);
                            }?>
                        </td>
                    </tr>
                <?php
                    endforeach;
                else:
                ?>
                    <tr>
                        <td colspan="17" class="notice"><?php echo __l('No users available');?></td>
                    </tr>
                <?php
                endif;
                ?>
                </table>
                </div>
                </div>
                <?php
                if (!empty($users) && $this->request->params['named']['main_filter_id'] != ConstUserTypes::Admin):
                ?>
                	<div class="clearfix">
                    <div class="admin-select-block grid_left">
                    <div>
                     
					<?php echo __l('Select:'); ?>
                    <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
                    <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None')));?> 
                    <?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Inactive'))); ?>
                    <?php echo $this->Html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Active'))); ?>

                    </div>
                        <div class="admin-checkbox-button"> 
						<?php 
						    $moreActionTypes = $moreActions;
							if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] !='all'){
							    unset($moreActionTypes[$this->request->params['named']['filter_id']]);
							  }
							  if(!($this->request->params['named']['main_filter_id'] == 1)):
					           	echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'), 'options' => $moreActionTypes));
                              endif;
                        ?></div>
                        </div>
                    <div class="js-pagination grid_right">
                        <?php echo $this->element('paging_links'); ?>
                    </div>
                	</div>
                    <div class="hide">
                        <?php echo $this->Form->submit('Submit'); ?>
                    </div>
                <?php
                endif;
                echo $this->Form->end();
                ?>
             </div>
            </div>
