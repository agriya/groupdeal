<div class="userProfiles form js-responses js-company-add-edit-over-block">
	<div class="main-content-block js-corner round-5">
		  <?php if($this->request->params['action'] == 'my_account') { ?>
	    <div class="js-tabs">
			<ul class="clearfix">
				<li><?php echo $this->Html->link(__l('My Profile'), '#my-profile'); ?></li>
				<?php if(!$this->Auth->user('fb_user_id') && !$this->Auth->user('is_openid_register')){?>
				    <li><?php  echo $this->Html->link(__l('Change Password'),array('controller'=> 'users', 'action'=>'change_password'),array('title' => __l('Change Password'))); ?></li>
				<?php } ?>
					  <li><?php echo $this->Html->link(__l('Privacy Settings'), array('controller' => 'user_permission_preferences', 'action' => 'edit', $this->request->data['UserProfile']['user_id'], 'admin' => false), array('title' => __l('Privacy Settings')));?></li>
			</ul>
		</div>
		<?php } ?>
		<div id='my-profile'>
        	<?php if(empty($this->request->params['isAjax'])):?>
        	<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Admin): ?>
			<h2><?php echo sprintf(__l('Edit Profile - %s'), $this->request->data['User']['username']); ?></h2>
            <?php endif; ?>
            <?php endif; ?>
			<div class="form-blocks  js-corner round-5">
				<?php echo $this->Form->create('UserProfile', array('action' => 'edit', 'class' => 'normal add-live-form js-ajax-form js-geo-submit', 'enctype' => 'multipart/form-data'));?>
					<fieldset  class="form-block">
						<h3 class=""><?php echo __l('Personal'); ?></h3>
						<div class="profile-image pa">
							<?php 
								$user_details = array(
									'username' => $this->request->data['User']['username'],
									'user_type_id' =>  $this->request->data['User']['user_type_id'],
									'id' =>  $this->request->data['User']['id'],
									'fb_user_id' =>  $this->request->data['User']['fb_user_id'],
									'UserAvatar' => $this->request->data['UserAvatar']
								);
								echo $this->Html->getUserAvatarLink($user_details, 'normal_thumb').' ';
							?>
							<p>
								<?php  echo $this->Html->link(__l('Change Image'),array('controller'=> 'users', 'action'=>'profile_image', $this->request->data['User']['id'], 'admin' => false),array('title' => __l('Change Image'))); ?>	
							</p>
						</div>
						<?php
							if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
								echo $this->Form->input('User.id',array('label' => __l('User')));
							endif;
							if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
								echo $this->Form->input('User.username');
							endif;
							echo $this->Form->input('first_name',array('label' => __l('First Name')));
							echo $this->Form->input('last_name',array('label' => __l('Last Name')));
							echo $this->Form->input('middle_name',array('label' => __l('Middle Name')));
							echo $this->Form->input('gender_id', array('empty' => __l('Please Select'),'label' => __l('Gender')));
							if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
								echo $this->Form->input('User.email',array('label' => __l('Email')));
							endif;
						?>
                        <div class="clearfix">
						<div class="date-time-block clearfix">
						<div class="input date-time clearfix required">
							<div class="js-datetime">
								<?php echo $this->Form->input('dob', array('label' => __l('DOB'),'empty' => __l('Please Select'), 'div' => false, 'minYear' => date('Y') - 100, 'maxYear' => date('Y'), 'orderYear' => 'asc')); ?>
							</div>
						</div>
                        </div>
                        </div>
							<?php
								if(Configure::read('site.currency_symbol_place') == 'left'):
									$currecncy_place = 'between';
								else:
									$currecncy_place = 'after';
								endif;	
							?>		
						<?php echo $this->Form->input('about_me', array('label' => __l('About Me'))); ?>
						<fieldset  class="form-block">
						<h3 class=""><?php echo __l('Demographics'); ?></h3>
						<?php echo $this->Form->input('user_education_id', array('empty' => __l('Please Select'),'label' => __l('Education'))); ?>
						<?php echo $this->Form->input('user_employment_id', array('empty' =>__l('Please Select'),'label' => __l('Employment Status'))); ?>
						<?php $currecncy_place = '<span class="currency">'.Configure::read('site.currency'). '</span>' ; ?>
						<?php echo $this->Form->input('user_income_range_id', array('empty' => __l('Please Select'),'label' => __l('Income range'),'after' => $currecncy_place,'options' => $userIncomeranges)); ?>
						<?php
                         $options = array('1' => 'Yes', '0' => 'No');
                         echo $this->Form->input('own_home', array('options' => $options, 'type' => 'radio', 'legend' => false, 'before' => '<span class="label-content label-content-radio">'.__l('Own home?').'</span>'));
                        ?>
						<?php echo $this->Form->input('user_relationship_id', array('empty' => __l('Please Select'),'label' => __l('Relationship status'))); ?>
						<?php
                           $options=array('1'=>'Yes','0'=>'No');
                           echo $this->Form->input('have_children', array('options' => $options, 'type' => 'radio', 'legend' => false, 'before' => '<span class="label-content label-content-radio">'.__l('Have Children?').'</span>'));
                        ?>
                        </fieldset>
                        <fieldset  class="form-block">
						<h3 class=""><?php echo __l('Regional'); ?></h3>
						<?php echo $this->Form->input('language_id', array('empty' => __l('Please Select'),'label' => __l('Language'), 'info'=>__l('This will be the default site languge after logged in')));?>				
                        </fieldset>
						<h3><?php echo __l('Address'); ?></h3>
                        <div class="padd-center">
                             <div class="mapblock-info1 no-mar">
                                <div class="clearfix">
                                <?php
                                    echo $this->Form->input('address2', array('label' => __l('Address'), 'id' => 'PropertyAddressSearch'));
                                ?>
                                </div>
								<?php 
                                    $class = '';
                                    if(empty($this->request->data['UserProfile']['address2']) || ( !empty($this->request->data['UserProfile']['address']) && !empty($this->request->data['City']['name']) &&  !empty($this->request->data['UserProfile']['country_id']))){
                                        $class = 'hide';
                                    }
                                ?>
                                <div id="js-geo-fail-address-fill-block" class="<?php echo $class;?>">
                                <div class="clearfix">
                                    <div class="grid_10 omega alpha map-address-left-block">
                                        <?php
                                            echo $this->Form->input('latitude', array('id' => 'latitude', 'type' => 'hidden'));
                                            echo $this->Form->input('longitude', array('id' => 'longitude', 'type' => 'hidden'));
                                            echo $this->Form->input('address',array('id'=>'js-street_id'));
                                            echo $this->Form->input('City.name', array('type' => 'text', 'label' => 'City'));
                                            echo $this->Form->input('State.name', array('type' => 'text', 'label' => 'State'));
                                            echo $this->Form->input('country_id',array('id'=>'js-country_id', 'empty' => __l('Please Select')));
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
                        </div>             
                        
						<?php
                            echo $this->Form->input('zip_code',array('label' => __l('Zip Code'), 'id' => 'PropertyPostalCode'));
						?>									
						<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):?>
						<h3><?php echo __l('Administrator Actions'); ?></h3>
						<div class="info-details">
							<p><?php echo __l("Manage and control user access by modifying below settings");?></p>
						</div>
						<?php
							if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
								if($this->request->data['User']['id'] != ConstUserIds::Admin):
						?>
<div class="page-info">
							<p><?php echo __("You can user suspend/disable the user by unchecking 'Active'");?></p>
						</div>                         
                        <?php 
									echo $this->Form->input('User.is_active', array('label' => __l('Active'), 'info' => __('')));
								endif;
						?>
<div class="page-info">
							<p><?php echo __("Incase for some reason user registered but didn't received email confirmation or unable to confirm, check 'Email confirmed' to confirm the user.");?></p>
						</div>                        
                        <?php
								echo $this->Form->input('User.is_email_confirmed', array('label' => __l('Email confirmed')));
							endif;
						?>
						<?php endif;?>
                	</fieldset>
				  <div class="submit-block clearfix">
                    <?php
                    	echo $this->Form->submit(__l('Update'));
                    ?>
                    </div>
                <?php
                	echo $this->Form->end();
                ?>
			
			</div>
		</div>
	</div>
</div>

