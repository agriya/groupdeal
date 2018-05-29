<div class="js-response js-clone">
<?php if (!empty($setting_categories['SettingCategory']['description'])):?>
	<div class=" info-details"><?php 
	if(stristr($setting_categories['SettingCategory']['description'], '##PAYMENT_SETTINGS_URL##') === FALSE) {
		echo $setting_categories['SettingCategory']['description'];
	} else {
		echo $category_description = str_replace('##PAYMENT_SETTINGS_URL##',Router::url('/', true).$this->request->params['named']['city'].'/admin/payment_gateways',$setting_categories['SettingCategory']['description']);
	}	
	
	?> 
    </div>
<?php endif;?>
<?php
	$currency = $this->Html->getConversionCurrency();
	if (!empty($settings)):
		echo $this->Form->create('Setting', array('action' => 'edit', 'class' => 'normal setting-add-form add-live-form'));
			echo $this->Form->input('setting_category_id', array('label' => __l('Setting Category'),'type' => 'hidden'));
		// hack to delete the thumb folder in img directory
		$inputDisplay = 0;
		$is_changed = $prev_cat_id = 0;
    	foreach ($settings as $setting):
				if($setting['Setting']['name'] == 'site.language'):
					$empty_language = 0;
					$get_language_options = $this->Html->getLanguage();
					if(!empty($get_language_options)):
						$options['options'] = $get_language_options;
					else:
						$empty_language = 1;
					endif;
				endif;
				$field_name = explode('.', $setting['Setting']['name']);
				if(isset($field_name[2]) && ($field_name[2] == 'is_not_allow_resize_beyond_original_size' || $field_name[2] == 'is_handle_aspect')){
					continue;
				}
				$options['type'] = $setting['Setting']['type'];
				$options['value'] = $setting['Setting']['value'];
				$options['div'] = array('id' => "setting-{$setting['Setting']['name']}");
				if($options['type'] == 'checkbox' && $options['value']):
					$options['checked'] = 'checked';
				endif;
				if($options['type'] == 'select'):
					$selectOptions = explode(',', $setting['Setting']['options']);
					$setting['Setting']['options'] = array();
					if(!empty($selectOptions)):
						foreach($selectOptions as $key => $value):
							if(!empty($value)):
								$setting['Setting']['options'][trim($value)] = trim($value);
							endif;
						endforeach;
					endif;
					$options['options'] = $setting['Setting']['options'];
					elseif ($options['type'] == 'radio'):
						$selectOptions = explode(',', $setting['Setting']['options']);
						$setting['Setting']['options'] = array();
						$options['legend'] = false;
						if(!empty($selectOptions)):
							foreach($selectOptions as $key => $value):
								if(!empty($value)):
									$setting['Setting']['options'][trim($value)] = trim($value);
								endif;
							endforeach;
						endif;
						$options['options'] = $setting['Setting']['options']; ?>
						<fieldset class="fields-block revnue-block round-5 settings-radio">
							<legend><?php echo $setting['Setting']['label']; ?></legend>
							<?php
				endif;	
				?>
				<?php
					if(empty($prev_cat_id)){
						$prev_cat_id = $setting['SettingCategory']['id'];
						$is_changed = 1;
					} else {
						$is_changed = 0;
						if($setting_categories['SettingCategory']['id'] != 16 && $setting['SettingCategory']['id'] != $prev_cat_id ){ ?>
							</fieldset>
						<?php
							$is_changed = 1;
							$prev_cat_id  = $setting['SettingCategory']['id'];	
						}				
					}
				?>
				<?php
					if(!empty($is_changed)):
						 if($setting_categories['SettingCategory']['id'] != 12) :
					?>
					<fieldset  class="form-block">
					<h3 id="<?php echo str_replace(' ','',$setting['SettingCategory']['name']); ?>"> <?php echo $setting['SettingCategory']['name']; ?></h3>
					<?php if (!empty($setting['SettingCategory']['description']) && $setting_categories['SettingCategory']['id'] != 16):?>
						<div class=" info-details"><?php	
							$edit_url = Router::url(array(
							'controller' => 'settings',
							'action' => 'edit',
							14,
							), true);
							$edit_url = $edit_url . "#Media";
							$findReplace = array(
								'##TRANSLATIONADD##' => $this->Html->link(Router::url('/', true).$this->request->params['named']['city'].'/admin/translations/add', Router::url('/', true).$this->request->params['named']['city'].'/admin/translations/add', array('title' => __l('Translations add'))),
								'##SUBSCRIPTION_CUSTOMIZE##' => $this->Html->link(__l('Click here'), array('controller' => 'subscriptions', 'action' => 'subscription_customise'), array('title' => __l('Click here'))),
								'##MAIL_CHIMP_LINK##' => $this->Html->link(__l('Click here'), array('controller' => 'mail_chimp_lists'), array('title' => __l('Click here'))),
								'##APPLICATION_KEY##' => $this->Html->link(Router::url('/', true).$this->request->params['named']['city'].'/admin/settings/edit/14', $edit_url, array('title' => __l('Click here'))),
								'##CATPCHA_CONF##' => $this->Html->link(Router::url('/', true).$this->request->params['named']['city'].'/admin/settings/edit/1', array('controller' => 'settings','action'=>'edit',1), array('title' => __l('Click here'))),
								'##API_CONF##'=> $this->Html->link('http://dev1products.dev.agriya.com/doku.php?id=groupdeal-pro-install#configure_captcha', 'http://dev1products.dev.agriya.com/doku.php?id=groupdeal-pro-install#configure_captcha', array('title' => __l('Click here'))),
							);
													
						 $setting['SettingCategory']['description'] = strtr($setting['SettingCategory']['description'], $findReplace);
						 echo $setting['SettingCategory']['description'];
						
						
						?> </div>
					<?php endif;?>
	
				<?php	
					endif;
					endif;
				?>
<?php				if(in_array( $setting['Setting']['id'], array(128, 132, 134, 136, 138, 140, 143, 145, 147) ) ) : ?>
                     
                        <h3>
                           <?php echo (in_array($setting['Setting']['id'], array('128', 136, 143) ) )? __l('Application Info') : ''; ?>
                           <?php echo (in_array($setting['Setting']['id'], array('132', 138, 145) ) )? __l('Credentials') : ''; ?>
                           <?php echo (in_array($setting['Setting']['id'], array('134', 140, 147) ) )? __l('Other Info') : ''; ?>
                        </h3>
						<?php if(in_array( $setting['Setting']['id'], array(132, 138, 145))):?>
                            <div class=" info-details">
                                <?php 
                                    if($setting['Setting']['id'] == 132) : 
                                        echo __l('Here you can update Facebook credentials . Click \'Update Facebook Credentials\' link below and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.');
                                    elseif($setting['Setting']['id'] == 138) :
                                        echo __l('Here you can update Twitter credentials like Access key and Accss Token. Click \'Update Twitter Credentials\' link below and Follow the steps. Please make sure that you have updated the Consumer Key and  Consumer secret before you click this link.');
                                    elseif($setting['Setting']['id'] == 145) : 
                                        echo __l('Here you can update Foursquare credentials . Click  \'Update Foursquare Credentials\' link below and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.');
                                    endif;
                                ?>
                            </div>
                        <?php endif;?>             
						<?php 
							if($setting['Setting']['id'] == 132) : ?>
							
							<div class="clearfix credentials-info-block">
							<div class="credentials-left">
						      	<div class="credentials-right">
        							<?php	echo $this->Html->link(__l('<span>Update Facebook Credentials</span>'), $fb_login_url, array('escape'=>false,'class' => 'facebook-link', 'title' => __l('Here you can update Facebook credentials . Click this link and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.')));
                                    ?>
                                </div>
                            </div>
                            <div class="credentials-right-block">
                            <?php
                            elseif($setting['Setting']['id'] == 138) :
                            ?>
                            <div class="clearfix credentials-info-block">
                            <div class="credentials-left">
						      	<div class="credentials-right">
                                    <?php
                                    	echo $this->Html->link(__l('<span>Update Twitter Credentials</span>'), $tw_login_url, array('escape'=>false,'class' => 'twitter-link', 'title' => __l('Here you can update Twitter credentials like Access key and Accss Token. Click this link and Follow the steps. Please make sure that you have updated the Consumer Key and  Consumer secret before you click this link.')));
                                    ?>
                                </div>
                             </div>
                             <div class="credentials-right-block">
                            <?php
                        	elseif($setting['Setting']['id'] == 145) : 
                            ?>
                            <div class="clearfix credentials-info-block">
                             <div class="credentials-left">
						      	<div class="credentials-right">
                                    <?php
                                        echo $this->Html->link(__l('<span>Update Foursquare Credentials</span>'), $fs_login_url, array('escape'=>false,'class' => 'foursquare-link', 'title' => __l('Here you can update Foursquare credentials . Click this link and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.')));
                                    ?>
                                 </div>
                             </div>
                             <div class="credentials-right-block">
                            <?php
                        	endif;
						?>
<?php 				endif; ?>                        
                
				<?php
					if($setting['Setting']['name'] == 'site.is_ssl_for_deal_buy_enabled' && !($ssl_enable)){
						$options['disabled'] = 'disabled';
					}
				?>
				<?php
				if($setting['Setting']['name'] == 'affiliate.commission_on_every_deal_purchase'):
				?>
					<div class="add-block affiliate-links">
					<?php
					echo $this->Html->link(__l('Commission Settings'), array('controller' =>'affiliate_types', 'action' => 'edit'), array('class' => 'affiliate-settings', 'title' => __l('Here you can update and modify affiliate types')));
					?>
					</div>
				<?php
				endif;
				?>
	
				<?php
					if($setting['Setting']['name'] == 'twitter.site_user_access_key' || $setting['Setting']['name'] == 'twitter.site_user_access_token' || $setting['Setting']['name'] == 'facebook.fb_access_token' || $setting['Setting']['name'] == 'facebook.fb_user_id' || $setting['Setting']['name'] == 'foursquare.site_user_fs_id' || $setting['Setting']['name'] == 'foursquare.site_user_access_token'):
					$options['readonly'] = TRUE;
					$options['class'] = 'disabled';		
					endif;				
					if($setting['Setting']['name'] == 'site.language'):
						$options['options'] = $this->Html->getLanguage();				
					endif;
					if($setting['Setting']['name'] == 'site.timezone_offset'):
						$options['options'] = $timezoneOptions;				
					endif;
					if($setting['Setting']['name'] == 'site.city'):
						$options['options'] = $cityOptions;
					endif;
					if($setting['Setting']['name'] == 'site.currency_id'):
						$options['options'] = $this->Html->getCurrencies();	
					endif;
					if($setting['Setting']['name'] == 'site.paypal_currency_converted_id'):
						$options['options'] = $this->Html->getSupportedCurrencies();	
					endif;					
					$options['label'] = $setting['Setting']['label'];
					if (($setting['SettingCategory']['id'] == 46 || $setting['SettingCategory']['id'] == 43) && $setting['Setting']['id'] != 103  && $inputDisplay == 0):
						$options['class'] = 'image-settings';
						echo '<div class="outer-image-settings clearfix">';
					elseif(($setting['SettingCategory']['id'] == 46 || $setting['SettingCategory']['id'] == 43) && $setting['Setting']['id'] != 103):
						$options['class'] = 'image-settings image-settings-height';
					endif;
					//barcode
					if($setting['Setting']['name'] == 'barcode.symbology'):
						$options['options'] = $barcodeSymbologies;
					endif;
					// if ($setting['Setting']['name'] == 'user.referral_deal_buy_time' || $setting['Setting']['name'] == 'user.referral_cookie_expire_time'):
					if(in_array($setting['Setting']['name'], array('user.referral_deal_buy_time', 'user.referral_cookie_expire_time', 'affiliate.referral_cookie_expire_time'))):
						$options['after'] = __l('hrs') . '<span class="info sfont">' . $setting['Setting']['description'] . '</span>';
					endif;
					if( in_array( $setting['Setting']['name'], array('wallet.min_wallet_amount', 'wallet.max_wallet_amount', 'user.minimum_withdraw_amount', 'user.maximum_withdraw_amount', 'company.minimum_withdraw_amount', 'company.maximum_withdraw_amount', 'affiliate.payment_threshold_for_threshold_limit_reach', 'user.referral_amount', 'referral.refund_amount'))):
						$options['after'] = $currency['conv_currency_symbol']. '<span class="info sfont">' . $setting['Setting']['description'] . '</span>';
					endif;
					$pre_launch_static = Router::url(array(
						'controller' => 'pages',
						'action' => 'edit',
						'23',
						'admin' => true,
					) , true);
					$findReplace = array(
								'##SITE_NAME##' => Configure::read('site.name'),
								'##MASTER_CURRENCY##' => $this->Html->link(Router::url('/', true).$this->request->params['named']['city'].'/admin/currencies', Router::url('/', true).$this->request->params['named']['city'].'/admin/currencies', array('title' => __l('Currencies'))),
								'##USER_LOGIN##' => $this->Html->link(Router::url('/', true).$this->request->params['named']['city'].'/admin/user_logins', Router::url('/', true).$this->request->params['named']['city'].'/admin/user_logins', array('title' => __l('User Logins'))),
								'##COMPANYADD##' => $this->Html->link('Add', Router::url('/', true).$this->request->params['named']['city'].'/admin/companies/add', array('title' => __l('Merchant add'))),
								'##COMPANYEDIT##' => $this->Html->link('Edit', '#', array('title' => __l('Merchant edit'))),
								'##COMPANYBRANCHADD##' => $this->Html->link('Add', '#', array('title' => __l('Merchant branch add'))),
								'##COMPANYBRANCHEDIT##' => $this->Html->link('Edit', '#', array('title' => __l('Merchant branch edit'))),
								'##LIVEDEALSEARCH##' => $this->Html->link('Live deals', Router::url('/', true).$this->request->params['named']['city'].'/live', array('title' => __l('Live Deals'))),
								'##REGISTER##' => $this->Html->link('registration', '#', array('title' => __l('registration'))),
								'##PRE_LAUNCH_MODE_LINK##' => $this->Html->link($pre_launch_static, $pre_launch_static, array('title' => __l('Manage PreLaunchMode'))),
					);
													
					$setting['Setting']['description'] = strtr($setting['Setting']['description'], $findReplace);
					if (!empty($setting['Setting']['description']) && empty($options['after'])):
						$options['help'] = "{$setting['Setting']['description']}";
					endif;
					if($setting['Setting']['name'] == 'Site.logo'):
						 $options['after'] = '<div class="settings-site-logo">'.$this->Html->showImage('SiteLogo', $attachment['SiteLogo'], array('full_url' => true,'dimension' => 'site_logo_thumb', 'alt' => sprintf(__l('[Image: %s]'), "SiteLogo"), 'title' =>  __l('SiteLogo'), 'type' => 'png', 'class' => 'siteLogo')).'</div>';
					endif;
					//default account
					if($is_module){
						if(!in_array($setting['Setting']['id'], array(ConstModuleEnableFields::Affiliate, ConstModuleEnableFields::Charity, ConstModuleEnableFields::Friends, ConstModuleEnableFields::Referral) )){
							$options['class'] = 'js-disabled-inputs';
						}
						else{
							$options['class'] = 'js-disabled-inputs-active';						
						}
						if(!$active_module && !in_array($setting['Setting']['id'], array(ConstModuleEnableFields::Affiliate, ConstModuleEnableFields::Charity, ConstModuleEnableFields::Friends, ConstModuleEnableFields::Referral) )){
							$options['disabled'] = 'disabled';
						}
					}
					if($is_submodule){
						if(in_array($setting['Setting']['setting_category_id'], array(ConstSettingsSubCategory::Commission) )){
							if(!in_array($setting['Setting']['id'], array(ConstModuleEnableFields::Commission) )){
								$options['class'] = 'js-disabled-inputs';
							}
							else{
								$options['class'] = 'js-disabled-inputs-active';						
							}
							if(!$active_submodule && !in_array($setting['Setting']['id'], array(ConstModuleEnableFields::Commission) )){
								$options['disabled'] = 'disabled';
							}
						}	
					}
					if(in_array($setting['Setting']['name'], array('facebook.like_box_title','facebook.feeds_code_title','twitter.tweets_around_city_title','home.add_box_title','home.app_box_title'))): 
					if($setting['Setting']['name'] == 'facebook.like_box_title')
					{
						$count = 1;
					} 
					elseif($setting['Setting']['name'] == 'facebook.feeds_code_title')
					{
						$count = 2;
					}
					elseif($setting['Setting']['name'] == 'twitter.tweets_around_city_title')
					{
						$count = 3;
					}
					elseif($setting['Setting']['name'] == 'home.add_box_title')
					{
						$count = 4;
					}
					elseif($setting['Setting']['name'] == 'home.app_box_title')
					{
						$count = 5;
					}
					?>
					<fieldset  class="form-block">
					<h3><?php echo __l('Widget #'). $count;?></h3>
                    <?php
					endif;
					echo $this->Form->input("Setting.{$setting['Setting']['id']}.name", $options);
					if(in_array($setting['Setting']['name'], array('facebook.like_box','facebook.feeds_code','twitter.tweets_around_city','home.add_box_link','home.app_box_link'))): ?>
                    </fieldset>
                    <?php
					endif;
					if(($setting['SettingCategory']['id'] == 46 || $setting['SettingCategory']['id'] == 43) && $setting['Setting']['id'] != 103 && $inputDisplay == 2):
						echo '</div>';
					endif;
		   
					$inputDisplay = ($inputDisplay == 2) ? 0 : $inputDisplay;
					unset($options);
					if(in_array($setting['Setting']['id'], array(133, 139, 146) ) ) {
					?>
                        </div>
                        </div>
					<?php
					}
		endforeach;
		?> 
        </fieldset>
		<?php
		if(!empty($beyondOriginals)){
            echo $this->Form->input('not_allow_beyond_original', array('label' => __l('Not Allow Beyond Original'),'type' => 'select', 'multiple' => 'multiple', 'options' => $beyondOriginals));
        }
        if(!empty($aspects)){
            echo $this->Form->input('allow_handle_aspect', array('label' => __l('Allow Handle Aspect'),'type' => 'select', 'multiple' => 'multiple', 'options' => $aspects));
        } ?>
    <div class="submit-block clearfix">
    <?php	echo $this->Form->end('Update'); ?>
    </div>
    <?php
	else:
?>
		<div class="notice"><?php echo __l('No settings available'); ?></div>
<?php
	endif;
?>
</div>
