<?php /* SVN: $Id: admin_index.ctp 79504 2012-09-26 14:21:18Z rajeshkhanna_146ac10 $ */ ?>
<div class="js-response js-responses">
	<div class="js-search-responses">
<?php 
	if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message');
	endif;
?>
		<div>
			<ul class="clearfix filter-list">
                <li class="filter-active"><?php echo $this->Html->link(__l('Active Merchants').': '.$this->Html->cInt($active), array('controller' => 'companies', 'action' => 'index', 'filter_id' => ConstMoreAction::Active),array('title' => __l('Active Merchants'), 'escape' => false));?></li>
                <li class="filter-inactive"><?php echo $this->Html->link(__l('Inactive Merchants').': '.$this->Html->cInt($inactive), array('controller' => 'companies', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive),array('title' => __l('Inactive Merchants'), 'escape' => false)); ?></li>
                <li class="filter-online"><?php echo $this->Html->link(__l('Online Merchants').': '.$this->Html->cInt($online), array('controller' => 'companies', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Online),array('title' => __l('Online Merchants'), 'escape' => false));?></li>
                <li class="filter-offline"><?php echo $this->Html->link(__l('Offline Merchants').': '.$this->Html->cInt($offline), array('controller' => 'companies', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Offline),array('title' => __l('Offline Merchants'), 'escape' => false)); ?></li>
                <li class="filter-affiliate"><?php echo $this->Html->link(__l('Affiliate Merchants').': '.$this->Html->cInt($affiliate_user_count), array('controller' => 'companies', 'action' => 'index', 'main_filter_id' => ConstMoreAction::AffiliateUser),array('title' => __l('Affiliate Merchants'), 'escape' => false));?></li>
                <li class="filter-all"><?php echo $this->Html->link(__l('Total Merchants').': '.$this->Html->cInt($all),array('controller'=> 'companies', 'action'=>'index', 'main_filter_id' => 'all'),array('title' => __l('Total Merchants'), 'escape' => false)); ?></li>
            </ul>
		</div>
				<?php if(!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Online): ?>
					<div class="info-details">
						<p>
							<?php echo __l('"online" merchants accounts are managed by merchants themselves and they\'ll have login details.');?>
							<?php echo $this->Html->link(__l('Click here'), 'http://dev1products.dev.agriya.com/doku.php?id=groupdeal-pro#frequently_asked_questions' , array('target' => '_blank', 'title' => 'Click here', 'class' => 'company')).' '.__l('for info.');?>
						</p>
					</div>
				<?php elseif(!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Offline): ?>
					<div class="info-details">
						<p>
							<?php echo __l('"offline" merchants accounts cannot login into the site. To set amount the money paid for a offline merchants, use \'Set as Paid\'.');?>
							<?php echo $this->Html->link(__l('Click here'), 'http://dev1products.dev.agriya.com/doku.php?id=groupdeal-pro#frequently_asked_questions' , array('target' => '_blank', 'title' => 'Click here', 'class' => 'company')).' '.__l('for info.');?>
						</p>
					</div>
				<?php endif;?>
				
				
					<div class=" page-count-block  clearfix">
                    <div class="grid_left">
                        <?php echo $this->element('paging_counter');?>
					</div>
					<div class="grid_left">
						<?php echo $this->Form->create('Company', array('type' => 'post', 'class' => 'normal search-form clearfix js-ajax-form {"container" : "js-search-responses"}', 'action'=>'index')); ?>
							<?php echo $this->Form->input('q', array('label' => __l('Keyword')));?>
							<?php echo $this->Form->input('main_filter_id', array('type' => 'hidden', 'value' => !empty($this->request->params['named']['main_filter_id'])? $this->request->params['named']['main_filter_id']:'')); ?>
							<?php echo $this->Form->input('filter_id', array('type' => 'hidden', 'value' => !empty($this->request->params['named']['filter_id'])?$this->request->params['named']['filter_id']:'')); ?>
							
							<?php echo $this->Form->submit(__l('Search'),array('name' => 'data[Company][search]'));?>
							
						<?php echo $this->Form->end(); ?>
					 </div>   
					<div class="clearfix grid_right add-block1">
						<?php echo $this->Html->link(__l('Add'), array('controller' => 'companies', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
                    <?php
							echo $this->Html->link(__l('CSV'), array_merge(array('controller' => 'companies', 'action' => 'index','city' => $city_slug, 'ext' => 'csv', 'admin' => true), $this->request->params['named']), array('title' => __l('CSV'), 'class' => 'export'));
						?>
                	</div>
                	</div>
						<div class="company-list">
						
						<?php   echo $this->Form->create('Company' , array('class' => 'normal js-ajax-form {"container" : "js-search-responses"}','action' => 'update'));?>
						<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
					
						<div class="overflow-block">
						<table class="list" id="js-expand-table">
							<tr class="js-even">
								<th rowspan="2" class="select"></th>
								<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Merchant'), 'Company.name');?></div></th>
								<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></div></th>
                                <th rowspan="2"><?php echo __l('Branches');?></th>
								<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Deal') .' + '. __l('Live Deal') , 'Company.deal_count');?></div></th>
								<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Sales'), 'Company.total_sales_cleared_amount').' ('.Configure::read('site.currency').')';?></div></th>
								<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Site Revenue'), 'Company.total_site_revenue_amount').' ('.Configure::read('site.currency').')';?></div></th>
								<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Views'), 'Company.company_view_count');?></div></th>
                                <th colspan="3"><?php echo __l('Logins'); ?></th>
								<th rowspan="2"><?php echo __l('Registered on'); ?></th>
								
							</tr>
                             <tr class="js-even">
                                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Count'), 'User.user_login_count'); ?></div></th>
                                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Time'), 'User.last_logged_in_time'); ?></div></th>
                                <th><?php echo __l('IP'); ?></th>
                             </tr>
						<?php
						if (!empty($companies)):
						$i = 0;
						$j=0;
						$total_deals = $this->request->params['paging']['Company']['current'];
						foreach ($companies as $company):
						    $j++;
							$class = null;
							$active_class = '';
							if ($i++ % 2 == 0) {
								$class = 'altrow';
							}
							if(!$company['User']['is_active']){
								$active_class = ' inactive-record';
							}
							$email_active_class = ' email-not-comfirmed';
							if($company['User']['is_email_confirmed']):
								$email_active_class = ' email-comfirmed';
							endif;							
							if($company['Company']['is_company_profile_enabled']):
								$status_class = 'js-checkbox-active';
							else:
								$status_class = 'js-checkbox-inactive';
							endif;
							if($j == $total_deals){
									$class.=" last-row";
							}
						?>
							<tr class="<?php echo $class;?><?php echo $active_class; ?> expand-row js-odd">
							<td class="select-block">
                            		<div class="arrow"></div>
                                	<?php echo $this->Form->input('Company.'.$company['Company']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$company['Company']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?>
							</td>							
								<td class="dl">
								<p>
								<?php
										echo $this->Html->cText($company['Company']['name']);
								?>
								</p>
								<div class="clearfix company-info-block">
                                    <?php if (!empty($company['Company']['url'])):
    										$this->Html->cText($company['Company']['url']);
    									 endif;
    								?>
                                	<?php if (!empty($company['Company']['phone'])): ?><span class="phone"><?php echo $this->Html->cText($company['Company']['phone']);?></span><?php endif; ?>
                                </div>
                                </td>
                                <td class="dl">
								<?php echo $this->Html->cText($company['User']['username']);?>                        
                                <p class="user-img-right clearfix">
									<?php if($company['User']['is_affiliate_user']):?>
                                            <span class="affiliate"> <?php echo __l('Affiliate'); ?> </span>
                                    <?php endif; ?>
                                </p>
                                <div class="clearfix user-status-block user-info-block">
								<?php 					
								if(!empty($company['UserProfile']['Country'])):
									?>
                                    <span class="flags flag-<?php echo strtolower($company['UserProfile']['Country']['iso2']); ?>" title ="<?php echo $company['UserProfile']['Country']['name']; ?>">
                                        <?php echo $company['UserProfile']['Country']['name']; ?>
                                    </span>
									<?php
								endif; 
								?>
                                <?php if($company['User']['is_openid_register']):?>
                                        <span class="open_id" title="OpenID"> <?php echo __l('OpenID'); ?> </span>
                                <?php endif; ?>
                                <?php if($company['User']['is_gmail_register']):?>
                                        <span class="gmail" title="Gmail"> <?php echo __l('Gmail'); ?> </span>
                                <?php endif; ?>
                                <?php if($company['User']['is_yahoo_register']):?>
                                        <span class="yahoo" title="Yahoo"> <?php echo __l('Yahoo'); ?> </span>
                                <?php endif; ?>
                                <?php if($company['User']['is_facebook_register']):?>
                                        <span class="facebook" title="Facebook"> <?php echo __l('Facebook'); ?> </span>
                                <?php endif; ?>
                                <?php if($company['User']['is_twitter_register']):?>
                                        <span class="twitter" title="Twitter"> <?php echo __l('Twitter'); ?> </span>
                                <?php endif; ?>
                                <?php if($company['User']['is_iphone_user']):?>
                                        <span class="iphone" title="iPhone"> <?php echo __l('iPhone'); ?> </span>
                                <?php endif; ?>
                                <?php if( $company['User']['is_android_user']):?>
                                        <span class="android" title="Android"> <?php echo __l('Android'); ?> </span>
                                <?php endif; ?>
                                <?php if($company['User']['is_foursquare_register']):?>
                                        <span class="foursquare" title="Foursquare"> <?php echo __l('Foursquare'); ?> </span>
                                <?php endif; ?>
                                <?php if(!empty($company['User']['email'])):?>
                                        <span class="email <?php echo $email_active_class; ?>" title="<?php echo $company['User']['email'];?>"> <?php echo '..' . substr($company['User']['email'], strlen($company['User']['email'])-15, strlen($company['User']['email']));  ?> </span>
                                <?php endif; ?>
                                </div>
                                </td>
                                <td class="dr"><?php echo $this->Html->cInt(count($company['CompanyAddress']));?></td>
								<td class="dr"><?php echo $this->Html->cInt($company['Company']['deal_count']);?></td>
								<td class="dr">
							   	<?php echo $this->Html->cCurrency($company['Company']['total_sales_cleared_amount']);?>
                               </td>
								<td class="site-amount dr">
							   	<?php echo $this->Html->cCurrency($company['Company']['total_site_revenue_amount']);?>
                               </td>
							   <td class="dr">
							   	<?php echo $this->Html->cInt($company['Company']['company_view_count']);?>
                               </td>                                
                               <td class="dr">
                                    <?php echo $this->Html->cInt($company['User']['user_login_count']);?>
                                </td>
                                <td>
                                    <?php if($company['User']['last_logged_in_time'] == '0000-00-00 00:00:00' || empty($company['User']['last_logged_in_time'])){
                                        echo '-';
                                    }else{
                                        echo $this->Html->cDateTimeHighlight($company['User']['last_logged_in_time']);
                                    }?>
                                </td>
                                <td class="dl">
                                <?php if(!empty($company['User']['LastLoginIp']['ip'])): ?>
                                   <?php echo  $this->Html->link($company['User']['LastLoginIp']['ip'], array('controller' => 'users', 'action' => 'whois', $company['User']['LastLoginIp']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$company['User']['dns'], 'escape' => false));
                                    ?>
                                    <p>
								<?php 					
								if(!empty($company['User']['LastLoginIp']['Country'])):
									?>
                                    <span class="flags flag-<?php echo strtolower($company['User']['LastLoginIp']['Country']['iso2']); ?>" title ="<?php echo $company['User']['LastLoginIp']['Country']['name']; ?>">
                                        <?php echo $company['User']['LastLoginIp']['Country']['name']; ?>
                                    </span>
									<?php
								endif; 
								?>                                    
								<?php 					
								if(!empty($company['User']['LastLoginIp']['City'])):
									?>
                                    <span>
                                        <?php echo $company['User']['LastLoginIp']['City']['name']; ?>
                                    </span>
									<?php
								endif; 
								?>                                    
                                 </p>
                                <?php else: ?>
                                    <?php echo __l('N/A'); ?>
                                <?php endif; ?>    
                                </td>
                                <td><?php if($company['User']['created'] == '0000-00-00 00:00:00'){
                                        echo '-';
                                    }else{
                                        echo $this->Html->cDateTimeHighlight($company['User']['created']);
                                    }?>
                                </td>
                             </tr>
                            <tr class="hide">
                            	<td colspan="12" class="action-block">
                                <div class="action-info-block sfont clearfix">
                                    <div class="action-left-block">
                                    	<h3> <?php echo __l('Action'); ?> </h3>
                                        <ul class="clearfix">
                                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $company['Company']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $company['Company']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                                            <?php if(!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] != ConstMoreAction::Offline) { ?>
                                            <?php
                                            if(Configure::read('user.is_email_verification_for_register') and (!$company['User']['is_active'] or !$company['User']['is_email_confirmed'])):
                                            ?>
                                            <li>
                                            <?php
                                            echo $this->Html->link(__l('Resend Activation'), array('controller' => 'users', 'action'=>'resend_activation', $company['User']['id'],'type' => 'company', 'admin' => false),array('title' => __l('Resend Activation'),'class' =>'recent-activation'));
                                            ?>
                                            </li>
                                            <?php endif; ?>
                                            <li><?php echo $this->Html->link(__l('Change Password'), array('controller' => 'users', 'action'=>'admin_change_password', $company['User']['id']), array('title' => __l('Change Password'),'class' => 'password'));?></li>
                                            <?php }?>
                                            <li><?php echo $this->Html->link(__l('Transactions'), array('controller' => 'transactions', 'action'=>'admin_index','user_id' => $company['User']['id']), array('title' => __l('Transactions'),'class' => 'transaction'));?></li>
                                        </ul>
                                    </div>
                                    <div class="action-right-block clearfix">
                                    
                                        <div class="clearfix">
                                        <div class="action-right action-right1">
                                         <h3> <?php echo __l('Deal') . ' + ' . __l('Live Deal'); ?> </h3>
                                            <dl class="clearfix">
                                                <dt>
                                                <span><?php echo __l('Pending Approval');?></span>
                                                </dt>
                                                <dd>
                                                <?php echo $this->Html->cInt($company['Company']['total_pending_approval_count']);?>
                                                </dd>
                                                <dt>
                                            	<span><?php echo __l('Upcoming');?></span>
                                            	</dt>
                                            	<dd>
                                                 <?php echo $this->Html->cInt($company['Company']['total_upcoming_count']);?>
                                                </dd>
                                                 <dt>
                                            	 <span><?php echo __l('Open');?></span>
                                            	 </dt>
                                            	 <dd>
                                                  <?php echo $this->Html->cInt($company['Company']['total_open_count'] + $company['Company']['total_tipped_count']);?>
                                                  </dd>
                                            	 
                                                <dt><span><?php echo __l('Successful');?></span></dt>
                                                <dd>
                                                <?php echo $this->Html->cInt($company['Company']['total_closed_count'] + $company['Company']['total_paid_to_company_count'] );?>
                                                </dd>
                                                <dt>
                                            	 <span><?php echo __l('Unsuccessful');?></span>
                                                </dt>
                                                <dd>
                                                 <?php echo $this->Html->cInt($company['Company']['total_canceled_count'] + $company['Company']['total_expired_count']+ $company['Company']['total_refunded_count'] );?>
                                                </dd>
                                            </dl>
                                        	<div class="chart-deal">
                                        <?php
										$deal_statuses = array('draft_count', 'pending_approval_count', 'upcoming_count', 'open_count', 'tipped_count', 'closed_count', 'paid_to_company_count', 'refunded_count', 'rejected_count', 'canceled_count', 'expired_count' );
										$all = 0;
										$deal_percentage = '';
										foreach($deal_statuses as $deal_status){
											$all += $company['Company'][$deal_status];
										}
										foreach($deal_statuses as $deal_status){
											$deal_percentage .= ($deal_percentage != '') ? ',' : '';
											$deal_percentage .= round((empty($company['Company'][$deal_status])) ? 0 : ( ($company['Company'][$deal_status] / $all) * 100 ));
										}
										echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$deal_percentage.'&amp;chs=50x50&amp;chco=fa9116|be7125|c576d3|74b732|3f83b2|444444|00b0c6|deb700|e21e1e|fd66b5|929292&amp;chf=bg,s,FF000000'); ?>
                                        </div>
                                            
                                        </div>
                                        <div class="action-right">
                                        	<h3> <?php echo __l('Sales') ; ?> </h3>
                                            <dl class="clearfix">
                                            	 <dt><span><?php echo __l('Paid');?></span></dt>
                                                 <dd> <?php echo $this->Html->cInt($company['Company']['total_paid_to_company_count']);?></dd>
                                                <dt>
                                                <span><?php echo __l('Pipeline');?></span>
                                                </dt>
                                                <dd>
                                                <?php echo $this->Html->cInt($company['Company']['total_open_count'] + $company['Company']['total_tipped_count']+ $company['Company']['total_closed_count']);?>
                                                </dd>
                                                <dt>
                                                <span><?php echo __l('Wallet').' ('.Configure::read('site.currency').')';?></span>
                                                </dt>
                                                <dd>
                                                <?php echo $this->Html->siteCurrencyFormat($company['User']['available_balance_amount'],'', true);?>
                                                </dd>
                                            <dt>
                                             <span><?php echo __l('Withdrawn').' ('.Configure::read('site.currency').')';?></span>
                                             </dt>
                                             <dd>
                                              <?php echo  $this->Html->siteCurrencyFormat($company['User']['total_amount_withdrawn'],'', true);?>
                                              </dd>
                                                
                                            </dl>
                                        </div>
                                        <div class="action-right action-right3 ">
                                        	<h3> <?php echo __l('Share') ; ?> </h3>
                                          <dl class="clearfix">
                                            <dt>
                                             <span><?php echo __l('Charity').' ('.Configure::read('site.currency').')';?></span>
                                             </dt>
                                             <dd> <?php echo  $this->Html->siteCurrencyFormat($company['Company']['total_paid_for_charity_amount'],'', true);?>  </dd>
                                                <dt>
                                                <span><?php echo __l('Site Revenue').' ('.Configure::read('site.currency').')';?></span>
                                                </dt>
                                                <dd>
                                              <?php echo  $this->Html->siteCurrencyFormat($company['Company']['total_site_revenue_amount'],'', true);?>
                                              </dd>
                                            </dl>
                                       
                                        </div>
                                        </div>
                                        <div class="clearfix">
                                          <div class="action-right city-action">
                                            <dl class="clearfix">
                                               <dt class="merchant"><?php echo __l('Merchant');?></dt>
                                                <dd>
                                                 <?php
            										$chnage_user_info = $company['User'];
            										$chnage_user_info['UserAvatar'] = $company['User']['UserAvatar'];
            										$company['User']['full_name'] = (!empty($company['User']['UserProfile']['first_name']) || !empty($company['User']['UserProfile']['last_name'])) ? $company['User']['UserProfile']['first_name'] . ' ' . $company['User']['UserProfile']['last_name'] :  $company['User']['username'];
            										echo $this->Html->getUserAvatarLink($chnage_user_info, 'micro_thumb',false);
                                            		?>
            										<?php echo $this->Html->link($this->Html->cText($company['Company']['name'], false), array('controller' => 'companies', 'action' => 'view', $company['Company']['slug'], 'admin' => false), array('title' => $this->Html->cText($company['Company']['name'], false) ,'escape' => false, 'class' => 'user-name'));?>
                                                </dd>
                                            </dl>
                                          </div>
                                        </div>
                                        
                                        <?php 
										if(!empty($company['CompanyAddress']) || !empty($company['Company']['address2'])): 
											$more_addresses = array();
										?>
                                        <div class="branch-address-block">
                                        <h3> <?php echo __l('Main Branch') ; ?> </h3>
                                        <ul class="address-list">
	                                        <li class="no-mar"> <?php echo $company['Company']['address2'];?> </li>
                                        </ul>  
                                        <?php if(!empty($company['CompanyAddress'])): ?>
										<h3> <?php echo __l('Branches') ; ?> </h3>
                                        <ul class="address-list">
                                        <?php 
										$i = 0;
										foreach ($company['CompanyAddress'] as $companyaddress): 
											$i++;
											if ($i > 3) {
												$more_addresses[] = $companyaddress;
											}
											else {
										?>
                                        	<li class="no-mar"> <?php echo $companyaddress['address2'];?> </li>
                                        <?php
											}
										endforeach;
										?>
                                        </ul>
										<?php
                                        if (!empty($more_addresses)) {
                                        ?>
                                        <div class="view-more clearfix">
										<span class="js-more {container: 'js-more_companies-<?php echo $companyaddress['id']; ?>'}"><?php echo __l('View More'); ?> </span>
                                        </div>
                                        <div class="hide js-more_companies-<?php echo $companyaddress['id']; ?>">
                                        <ul>
                                        <?php
                                        foreach($more_addresses as $more_address) {
                                        ?>
                                        	<li> <?php echo $more_address['address2'];?> </li>                                        
                                        <?php } ?>
                                        </ul>
                                        </div>
                                        <?php
                                        } ?>
                                        <?php endif; ?>
                                        </div>                                        
                                        <?php endif; ?>
                                    </div>
                                </div>
                                </td>
                            </tr>
						<?php
							endforeach;
						else:
						?>
							<tr class="js-odd">
								<td colspan="10" class="notice"><?php echo __l('No Merchants available');?></td>
							</tr>
						<?php
						endif;
						?>
						</table>
						</div>
					
						<?php
						if (!empty($companies)):
						?>
							<div class="clearfix">
							<div class="admin-select-block grid_left">
							<div>
								<?php echo __l('Select:'); ?>
								<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
								<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
								<?php echo $this->Html->link(__l('Disabled'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Disabled'))); ?>
								<?php echo $this->Html->link(__l('Enabled'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Enabled'))); ?>
							</div>
								<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
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
						echo $this->Form->end();?>
					</div>
			</div>
</div>
