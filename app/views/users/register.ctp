<?php 
	if(!empty($type) && $type == 'company'){
		$action = "company_register";
	}
	else{
		$action = "register";
		$type = '';
	}
?>
<div class="grid_13 register-left-block <?php echo !empty($this->request->data['User']['is_requested']) ? 'js-login-response ajax-login-block' : ''; ?> clearfix js-captcha-overblock js-company-add-edit-over-block">

<h2 class="no-brd bot-mspace"><?php echo __l('Sign Up'); ?>
</h2>

<?php
  		$formClass = !empty($this->request->data['User']['is_requested']) ? 'js-ajax-login' : '';
?>
<?php echo $this->Form->create('User', array('action' => $action, 'class' => 'normal register-add-live-form add-live-form js-register-form '.$formClass)); ?>
	<fieldset>
    	<?php if(!empty($type)): ?>
    		   <fieldset class="form-block">
               <h3><?php echo __l('Account'); ?></h3>
        <?php endif; ?>
	<?php
		echo $this->Form->input('username',array('div'=>'input user-name-block text required','info' => __l('Must start with an alphabet. <br/> Must be minimum of 3 characters and <br/> Maximum of 20 characters <br/> No special characters and spaces allowed'),'label' => __l('Username')));
		echo $this->Form->input('email',array('label' => __l('Email')));
		echo $this->Form->input('referred_by_user_id',array('type' => 'hidden',));
		if(empty($this->request->data['User']['openid_url']) && empty($this->request->data['User']['fb_user_id']) && empty($this->request->data['User']['twitter_user_id'])):
			echo $this->Form->input('passwd', array('label' => __l('Password')));
			echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => __l('Password Confirmation')));
			  echo $this->Form->input('type',array('type' => 'hidden', 'value' => $type));
		endif;
		if(!empty($this->request->data['User']['openid_url'])):
			  echo $this->Form->input('openid_url',array('type' => 'hidden'));
		endif;
        if(!empty($type)):
    		echo $this->Form->input('Company.name',array('label' => __l('Merchant Name')));    		
			echo $this->Form->input('Company.phone',array('label' => __l('Phone')));
    		echo $this->Form->input('Company.url',array('label' => __l('URL'), 'help' => __l('eg. http://www.example.com')));
		endif;
		if(!empty($this->request->data['User']['is_requested'])):
			echo $this->Form->input('is_requested', array('type' => 'hidden'));
		endif;
		if (!empty($this->request->data['User']['f'])):
			echo $this->Form->input('f', array('type' => 'hidden'));
		endif;
		?>
    	<?php if(!empty($type)): ?>
    		   </fieldset>
        <?php endif; ?>
    	<?php if(!empty($type)): ?>
    		   <fieldset class="form-block">
               <h3><?php echo __l('Address'); ?></h3>
        <?php endif; ?>
        <?php
        if(!empty($type))
        {
		?>
        	<div class="padd-center clearfix">
           
                 <div class="mapblock-info1 no-mar">
                    <div class="clearfix">
                    <?php
                        echo $this->Form->input('Company.address2', array('label' => __l('Address'), 'id' => 'PropertyAddressSearch','info'=>'Address suggestion will be listed when you enter location.<br/>
(Note: If address entered is not exact/incomplete, you will be prompted to fill the missing address fields.)'));
                    ?>
                    </div>
                    <?php 
						$class = '';
						if(empty($this->request->data['Company']['address2']) || ( !empty($this->request->data['Company']['address1']) && !empty($this->request->data['City']['name']) &&  !empty($this->request->data['Company']['country_id']))){
							$class = 'hide';
						}
					?>
                    <div id="js-geo-fail-address-fill-block" class="<?php echo $class;?>">
                    <div class="clearfix">
                    <div class="grid_14 omega alpha map-address-left-block">
						<?php	
                            echo $this->Form->input('Company.latitude', array('id' => 'latitude', 'type' => 'hidden'));
                            echo $this->Form->input('Company.longitude', array('id' => 'longitude', 'type' => 'hidden'));
                            echo $this->Form->input('Company.address1', array('id'=>'js-street_id','type' => 'text', 'label' => 'Address'));
                            echo $this->Form->input('City.name', array('type' => 'text', 'label' => 'City'));
                            echo $this->Form->input('State.name', array('type' => 'text', 'label' => 'State'));
                            echo $this->Form->input('Company.country_id',array('id'=>'js-country_id', 'empty' => __l('Please Select')));
                        ?>
                        </div>
                          <div class="grid_8 omega alpha grid_right">
                          	<h3><?php echo __l('Point Your Location');?></h3>
                            <div class="js-side-map">
        						<div id="js-map-container"></div>
        						<span ><?php echo __l('Point the exact location in map by dragging marker');?></span>
    				        </div>
				        </div>
                    </div>
					</div>
                    <div id="mapblock">
                 
                        <div id="mapframe">
                            <div id="mapwindow"></div>
                        </div>
                    
                    </div>
                    </div>
                    	<?php 
							echo $this->Form->input('Company.zip',array('label' => __l('Zip'), 'id' => 'PropertyPostalCode'));
						?>
            	
				
            </div>
        <?php    
			
		}else{		
			?>
            <div class="js-register-load-geo-data"> 
            <?php	
			echo $this->Form->input('country_iso_code', array('type' => 'hidden','id' => 'js-country_iso_code'));
			echo $this->Form->input('State.name', array('type' => 'hidden'));
			echo $this->Form->input('City.name', array('type' => 'hidden'));
			?>
            </div>
            <?php
		}
		if(!empty($refer)){
    		if(isset($_GET['refer']) && ($_GET['refer']!='')) {
    			$refer = $_GET['refer'];
    		}
    		echo $this->Form->input('referer_name', array('value' => $refer, 'label'=>__l('Reference Code')));
    	}else{
    		echo $this->Form->input('referer_name', array('type' => 'hidden'));
    	}
		?>
    	<?php if(!empty($type)): ?>
    		   </fieldset>
        <?php endif; ?>
	
	<?php  	if(!empty($type)):  ?>
		<?php
				$map_zoom_level = !empty($this->request->data['Company']['map_zoom_level']) ? $this->request->data['Company']['map_zoom_level'] : Configure::read('GoogleMap.static_map_zoom_level');
				echo $this->Form->input('Company.map_zoom_level',array('type' => 'hidden','value' => $map_zoom_level,'id'=>'zoomlevel'));
			?>
         
   <?php endif; ?>


		<?php
		if(empty($this->request->data['User']['openid_url'])): ?>
		<?php if(Configure::read('system.captcha_type') == "Solve media"){?>
								<div class="input captcha-block clearfix">
									<?php 
									include_once VENDORS . DS . 'solvemedialib.php';		//include the Solve Media library 
									echo solvemedia_get_html(Configure::read('captcha.challenge_key'));	//outputs the widget?>
								</div>
							<?php } 
							else{?>
    		<div class="input captcha-block clearfix js-captcha-container">
    			<div class="captcha-left">
    	           <?php echo $this->Html->image(Router::url(array('controller' => 'users', 'action' => 'show_captcha', md5(uniqid(time()))), true), array('alt' => __l('[Image: CAPTCHA image. You will need to recognize the text in it; audible CAPTCHA available too.]'), 'title' => __l('CAPTCHA image'), 'class' => 'captcha-img'));?>
    	        </div>
                <?php if($this->layoutPath != 'mobile'){ ?>
    	        <div class="captcha-right">
                
        	        <?php echo $this->Html->link(__l('Reload CAPTCHA'), '#', array('class' => 'js-captcha-reload captcha-reload', 'title' => __l('Reload CAPTCHA')));?>
        			<div>
		              <?php echo $this->Html->link(__l('Click to play'), Router::url('/', true)."flash/securimage/play.swf?audio=". $this->Html->url(array('controller' => 'users', 'action'=>'captcha_play'), true) ."&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5&height=19&width=19&wmode=transparent", array('class' => 'js-captcha-play')); 
					  
					  ?>                      
			      </div>
                  				<?php 	  } ?>

    	        </div>
            </div>
        	<?php 
				echo $this->Form->input('captcha', array('label' => __l('Security Code'), 'class' => 'js-captcha-input'));
            }
				$terms = $this->Html->link(__l('Terms & Conditions'), array('controller' => 'pages', 'action' => 'view', 'term-and-conditions'), array('target' => '_blank', 'class'=>'terms-link'));
			?>
    		<?php echo $this->Form->input('is_agree_terms_conditions', array('label' => __l('I have read, understood &amp; agree to the ') .' ' . $terms)); ?>
    		<?php 
				if(empty($type) || Configure::read('user.is_company_actas_normal_user')):
					echo $this->Form->input('is_subscribe', array('type' => 'checkbox', 'label' => __l('Subscribe my email for deals in') .' ' . $subscribe_city));				
				endif;
			?>
		<?php endif; ?>
		<?php
			if(!empty($this->request->data['User']['foursquare_user_id'])):
				echo $this->Form->input('foursquare_user_id', array('type' => 'hidden', 'value' => $this->request->data['User']['foursquare_user_id']));
			endif;
			if(!empty($this->request->data['User']['foursquare_access_token'])):
				echo $this->Form->input('foursquare_access_token', array('type' => 'hidden', 'value' => $this->request->data['User']['foursquare_access_token']));
			endif;
			if(!empty($this->request->data['User']['fb_user_id'])):
				echo $this->Form->input('fb_user_id', array('type' => 'hidden', 'value' => $this->request->data['User']['fb_user_id']));
			endif;
			if(!empty($this->request->data['User']['fb_access_token'])):
				echo $this->Form->input('fb_access_token', array('type' => 'hidden', 'value' => $this->request->data['User']['fb_access_token']));
			endif;
			if(!empty($this->request->data['User']['twitter_user_id'])) :
				echo $this->Form->input('twitter_user_id', array('type' => 'hidden', 'value' => $this->request->data['User']['twitter_user_id']));
			endif;		 
			if(!empty($this->request->data['User']['twitter_avatar_url'])) :
				echo $this->Form->input('twitter_avatar_url', array('type' => 'hidden', 'value' => $this->request->data['User']['twitter_avatar_url']));
			endif;		 
			if(!empty($this->request->data['User']['twitter_access_token'])) :
				echo $this->Form->input('twitter_access_token', array('type' => 'hidden', 'value' => $this->request->data['User']['twitter_access_token']));
			endif;		 
			if(!empty($this->request->data['User']['twitter_access_key'])) :
				echo $this->Form->input('twitter_access_key', array('type' => 'hidden', 'value' => $this->request->data['User']['twitter_access_key']));
			endif;
			if(!empty($this->request->data['User']['is_yahoo_register'])) :
				echo $this->Form->input('is_yahoo_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_yahoo_register']));
			endif;
			if(!empty($this->request->data['User']['is_gmail_register'])) :
				echo $this->Form->input('is_gmail_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_gmail_register']));
			endif;
			if(!empty($this->request->data['User']['is_facebook_register'])) :
				echo $this->Form->input('is_facebook_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_facebook_register']));
			endif;
			if(!empty($this->request->data['User']['is_twitter_register'])) :
				echo $this->Form->input('is_twitter_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_twitter_register']));
			endif;
			if(!empty($this->request->data['User']['is_foursquare_register'])) :
				echo $this->Form->input('is_foursquare_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_foursquare_register']));
			endif;
		?>
   	<div class="submit-block clearfix">
		<?php
		echo $this->Form->submit(__l('Sign Up'));?>
		<div class="cancel-block">
			<?php
			echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'index'), array('class' => 'cancel-button'));
			?>
		</div>
    </div>
</fieldset>
 <?php  echo $this->Form->end();?>
</div>
<?php if(empty($type) && empty($this->request->data['User']['is_requested'])): ?>
        <div class="grid_left or-block">&nbsp;</div>

       <div class="grid_6">
            <h2 class="no-brd"><?php echo __l('Sign Up Using: '); ?></h2>
		<ul class="open-id-list open-id-list1 grid_7 clearfix">
				<li class="grid_left face-book">
					 <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
						<?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook'), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?>
					 <?php endif; ?>
				</li>
				<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
					<li class="grid_left twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
				<?php endif;?>
				<?php if(Configure::read('foursquare.is_enabled_foursquare_connect')):?>
					<li class="grid_left foursquare"><?php echo $this->Html->link(__l('Sign in with Foursquare'), array('controller' => 'users', 'action' => 'login',  'type'=> 'foursquare', 'admin'=>false), array('class' => 'Foursquare', 'title' => __l('Sign in with Foursquare')));?></li>
				<?php endif;?>
                <?php if(Configure::read('user.is_enable_yahoo_openid')):?>
					<li class="grid_left yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type'=>'yahoo'), array('title' => __l('Sign in with Yahoo')));?></li>
				<?php endif;?>
                <?php if(Configure::read('user.is_enable_gmail_openid')):?>
                    <li class="grid_left gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type'=>'gmail'), array('title' => __l('Sign in with Gmail')));?></li>
                <?php endif;?>
                <?php if(Configure::read('user.is_enable_openid')):?>
					<li class="grid_left open-id"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid'), array('class'=>'','title' => __l('Sign in with Open ID')));?></li>
                <?php endif;?>
			</ul>
		</div>
<?php endif; ?>