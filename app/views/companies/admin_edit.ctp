<?php /* SVN: $Id: admin_edit.ctp 79504 2012-09-26 14:21:18Z rajeshkhanna_146ac10 $ */ ?>
<div class="companies form clearfix js-responses js-response js-company-address-delete-over-block js-company-add-edit-over-block">
	<?php
		echo $this->Form->create('Company', array('class' => 'normal add-live-form js-company-map js-geo-submit', 'enctype' => 'multipart/form-data'));
		echo $this->Form->input('id');
	?>
	<?php
		if(!empty($this->request->data['Company']['is_company_profile_enabled']) and $this->request->data['Company']['is_company_profile_enabled']==1) {
			$show_company_profile = 1;
		} else {
			$show_company_profile = 0;
		}
	?>
	<fieldset class="form-block">
			<h3 class="genral"><?php echo __l('Account'); ?></h3>
	<?php
		echo $this->Form->input('name',array('label' => __l('Name')));
		echo $this->Form->input('phone',array('label' => __l('Phone')));
		echo $this->Form->input('url',array('label' => __l('URL'), 'info' => __l('eg. http://www.example.com')));
		echo $this->Form->input('User.email',array('label' => __l('Email')));
		echo $this->Form->input('is_online_account',array('label' =>__l('Online Account'), 'info' => __l('On enabling this, Merchant account will be created with an \'online Merchant\' access. (Disable this if you want to manage the Merchant\'s account manually)'.' '."<a href='http://dev1products.dev.agriya.com/doku.php?id=groupdeal-pro#frequently_asked_questions' target='_blank'>Click here</a> for more info")));
	?>

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
                    <div class="grid_14 omega alpha map-address-left-block">
                        <?php
                            echo $this->Form->input('latitude', array('id' => 'latitude', 'type' => 'hidden'));
                            echo $this->Form->input('longitude', array('id' => 'longitude', 'type' => 'hidden'));
    						echo $this->Form->input('address1',array('id'=>'js-street_id','type' => 'text', 'label' => 'Address'));
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
        	<?php
		          echo $this->Form->input('zip',array('label' => __l('Zip'), 'id' => 'PropertyPostalCode'));
  	         ?>
  	 </div>

			<h3><?php echo __l('Merchant Profile'); ?></h3>
	<?php
			echo $this->Form->input('is_company_profile_enabled', array('label' => __l('Enable merchant profile'), 'class' => 'js_company_profile js_company_profile_enable {"show_company_profile":"'. $show_company_profile .'"}', 'info' => __l('On enabling this other users will be able to view the Merchant\'s Profile. Disable this if you don\'t want others to look into the Merchant\'s Profile.'))); ?>
           <div class = "js-company_profile_show">		 		
            <?php echo $this->Form->input('Company.company_profile', array('label' => __l('Merchant Profile'),'type' => 'textarea', 'class' => 'js-editor'));?>
		</div>
			<h3 class="genral"><?php echo __l('Profile Image'); ?></h3>
    		<?php echo $this->Form->input('UserAvatar.filename', array('type' => 'file','size' => '20', 'label' => false,'class' =>'browse-field')); ?>
    		<?php
    			if(!empty($this->request->data['User']['UserAvatar']) && !empty($this->request->data['User']['UserAvatar']['id'])){
            		echo $this->Html->showImage('UserAvatar', $this->request->data['User']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['User']['username'], false)),'class' => 'upload-avatar', 'title' => $this->Html->cText($this->request->data['User']['username'], false)), null, array('inline' => false));
            	}
            ?>
        <?php /*?>
		<h3><?php echo __l('Paypal Account'); ?></h3> */?>
            <?php echo $this->Form->input('User.UserProfile.paypal_account',array('type'=>'hidden'));  ?>


		<h3><?php echo __l('Branch Address'); ?></h3>
		<div class="add-block">
			<?php echo $this->Html->link(__l('Add address'),array('controller' => 'company_addresses', 'action' => 'add', 'company_id' => $this->request->data['Company']['id']),array('title'=>__l('Add address'),'class' => "add")); ?>
		</div>
        <table class="list">
            <tr>
                <th class="actions"><?php echo __l('Actions');?></th>
                <th class="dl"><?php echo __l('Address');?></th>
            </tr>
				<?php
				$companyAddresses = $this->request->data['CompanyAddress'];
				if (!empty($companyAddresses)):

				$i = 0;
				foreach ($companyAddresses as $companyAddress):
					$class = null;
					if ($i++ % 2 == 0) {
						$class = "altrow";
					}
					?>
                  <tr<?php echo $class;?>>
                    <td class="actions">
                      <div class="action-block">
                        <span class="action-information-block">
                            <span class="action-left-block">&nbsp;
                            </span>
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
                                        <?php echo $this->Html->link(__l('Edit'), array('controller' => 'company_addresses', 'action' => 'edit', $companyAddress['id']), array('class' => 'edit ', 'title' => __l('Edit')));?>
                                        <li>
                                         <?php echo $this->Html->link(__l('Delete'), array('controller' => 'company_addresses', 'action' => 'delete', $companyAddress['id'], $id), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                                        </li>
                                    </ul>
                               </div>
                                <div class="action-bottom-block"></div>
                              </div>
                         </div>
                 
                    </td>
                    <td class="dl"><?php echo $this->Html->cText($companyAddress['address2']);?></td>
                 </tr>   
				<?php
					endforeach;
				else:
				?>
                    <tr>
                        <td colspan="2" class="notice"><?php echo __l('No Merchant Addresses available');?></td>
                    </tr>
				<?php
				endif;
				?>
				</table>

	<div class="">
			<?php
				$map_zoom_level = !empty($this->request->data['Company']['map_zoom_level']) ? $this->request->data['Company']['map_zoom_level'] : Configure::read('GoogleMap.static_map_zoom_level');
				echo $this->Form->input('Company.map_zoom_level',array('type' => 'hidden','value' => $map_zoom_level,'id'=>'zoomlevel'));
			?>
	</div>
	</fieldset>
		<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Update')); ?>
		</div>
		<?php echo $this->Form->end(); ?>
</div>