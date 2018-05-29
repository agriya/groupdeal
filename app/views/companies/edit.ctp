<?php /* SVN: $Id: edit.ctp 79504 2012-09-26 14:21:18Z rajeshkhanna_146ac10 $ */ ?>
<div class="companies form js-responses js-company-add-edit-over-block  js-company-responses">
<div class="js-tabs">
	<ul class="clearfix">
		<li><em></em><?php echo $this->Html->link(__l('My Profile'), '#my-profile'); ?></li>
		<li><em></em><?php echo $this->Html->link(__l('Change Password'),array('controller'=> 'users', 'action'=>'change_password'),array('title' => __l('Change Password'))); ?></li>
        <li><em></em><?php echo $this->Html->link(__l('Privacy Settings'), array('controller' => 'user_permission_preferences', 'action' => 'edit', $this->Auth->user('id'), 'admin' => false), array('title' => __l('Privacy Settings')));?></li>
		<li><em></em><?php echo $this->Html->link(__l('My Connections'), array('controller' => 'users', 'action' => 'profile_image', 'connect' => 'linked_accounts', $this->Auth->user('id'), 'admin' => false), array('title' => 'My Connections', 'rel'=> '#Connect')); ?></li>
      
	</ul>
	<div id='my-profile' class="clearfix">
		<?php echo $this->Form->create('Company', array('class' => "normal add-live-form js-company-map js-ajax-form js-geo-submit {container:'js-company-responses'}", 'enctype' => 'multipart/form-data'));?>
	  <fieldset class="form-block">
                <div class="profile-image pa">
                        <?php 
                            $user_details = array(
                                'username' => $this->request->data['User']['username'],
                                'user_type_id' =>  $this->request->data['User']['user_type_id'],
                                'id' =>  $this->request->data['User']['id'],
                                'fb_user_id' =>  $this->request->data['User']['fb_user_id'],
                                'UserAvatar' => $this->request->data['User']['UserAvatar']
                            );
                            echo $this->Html->getUserAvatarLink($user_details, 'normal_thumb').' ';
                        ?>
                        <p>
                            <?php  echo $this->Html->link(__l('Change Image'),array('controller'=> 'users', 'action'=>'profile_image', $this->request->data['User']['id'], 'admin' => false),array('title' => __l('Change Image'))); ?>	
                        </p>
                </div>
               <h3 class="genral"><?php echo __l('Account'); ?></h3>
                <?php
                    echo $this->Form->input('id');
                    echo $this->Form->input('name',array('label' => __l('Merchant Name')));
                    echo $this->Form->input('phone',array('label' => __l('Phone')));
                    echo $this->Form->input('url',array('label' => __l('URL'), 'info' => __l('eg. http://www.example.com')));
                	echo $this->Form->input('UserProfile.language_id', array('empty' => __l('Please Select'),'label' => __l('Profile Language'), 'value' => $this->request->data['User']['UserProfile']['language_id'], 'info'=>__l('This will be the default site languge after logged in')));
				?>
                <?php /*?>
		<h3><?php echo __l('Paypal Account'); ?></h3> */?>
            <?php echo $this->Form->input('User.UserProfile.paypal_account',array('type'=>'hidden'));  ?>
               <h3><?php echo __l('Address'); ?></h3>
                    <div class="padd-center clearfix">
                    
                         <div class="mapblock-info1 no-mar">
                            <div class="clearfix">
                            <?php
                                echo $this->Form->input('address2', array('label' => __l('Address'), 'id' => 'PropertyAddressSearch','info'=>__l('Address suggestions will be listed when you enter the location.<br/>
(Note: If address entered is not exact/incomplete, you will be prompted to fill the missing address fields.)')));
                            ?>
                            </div>
						<?php 
                            $class = '';
                            if(empty($this->request->data['Company']['address2']) || (empty($this->request->data['Company']['address1']) && empty($this->request->data['City']['name']) &&  empty($this->request->data['Company']['country_id']))){
                                $class = 'hide';
                            }
                        ?>
                        <div id="js-geo-fail-address-fill-block" class="<?php echo $class;?>">
                         <div class="clearfix">
                         <div class="grid_14 omega alpha">
                                <?php
                                    echo $this->Form->input('latitude', array('id' => 'latitude', 'type' => 'hidden'));
                                    echo $this->Form->input('longitude', array('id' => 'longitude', 'type' => 'hidden'));
                                    echo $this->Form->input('address1',array('id'=>'js-street_id','type' => 'text', 'label' => 'Address'));
                                    echo $this->Form->input('City.name', array('type' => 'text', 'label' => 'City'));
                                    echo $this->Form->input('State.name', array('type' => 'text', 'label' => 'State'));
                                    echo $this->Form->input('country_id',array('id'=>'js-country_id', 'empty' => __l('Please Select')));
                                ?>
                            </div>
                            <div class="grid_right">
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
                        echo $this->Form->input('zip',array('label' => __l('Zip'), 'id' => 'PropertyPostalCode'));
					?>
				
				
					
			
                </div>
		
               <h3><?php echo __l('Merchant Profile'); ?></h3>
			   <?php
						echo $this->Form->input('is_company_profile_enabled', array('label' => __l('Enable Merchant Profile'), 'class' => 'js_company_profile_enable', 'info' => __l('On enabling this other users will be able to view the Merchant\'s Profile. Disable this if you don\'t want others to look into the Merchant\'s Profile.')));
						?>
                        <div class = "js-company_profile_show">
							<?php echo $this->Form->input('Company.company_profile', array('label' => __l('Merchant Profile'),'type' => 'textarea', 'class' => 'js-editor'));   ?>
                        </div>
		
        
                             
				<?php 
                    echo $this->Form->input('User.id',array('type' => 'hidden'));
                ?>
        <div class="">
		<?php
            $map_zoom_level = !empty($this->request->data['Company']['map_zoom_level']) ? $this->request->data['Company']['map_zoom_level'] : Configure::read('GoogleMap.static_map_zoom_level');
            echo $this->Form->input('Company.map_zoom_level',array('type' => 'hidden','value' => $map_zoom_level,'id'=>'zoomlevel'));
        ?>
		</div>
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
<?php
if(!empty($this->request->data['Company']['is_company_profile_enabled']) and $this->request->data['Company']['is_company_profile_enabled']==1)
{
   $show_company_profile = 1;
}
else{
	$show_company_profile = 0;
}
?>
<script type="text/javascript">
        $(document).ready(function() {
        $('.js_company_profile').companyprofile(<?php echo $show_company_profile; ?>);
        });
</script>