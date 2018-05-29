<?php /* SVN: $Id: add.ctp 79650 2012-10-16 11:16:48Z balamurugan_177at12 $ */ ?>
<div class="deals form js-responses js-deal-subdeal-available-over-block">
<?php 
if(empty($this->request->data['CloneAttachment'][0])) 
	echo $this->Form->create('Deal', array('action' => 'add', 'class' => 'normal add-live-form js-upload-form {is_required:"true"}', 'enctype' => 'multipart/form-data'));
else
	echo $this->Form->create('Deal', array('action' => 'add', 'class' => 'normal add-live-form js-upload-form {is_required:"false"}', 'enctype' => 'multipart/form-data'));
?>
	<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company):?>
	<h2><?php //echo __l('Add Deal');?></h2>
	<?php endif; ?>
			<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company):?>
			<div class="adddeal-img-block hor-space"><?php echo $this->Html->image('company-deal-flow.jpg', array('height'=>'166','width'=>'604','alt'=> __l('[Image: Merchant Deal Flow]'), 'title' => __l('Merchant Deal Flow'))); ?></div>
		<?php else: ?>
			<div class="adddeal-img-block hor-space"> <?php echo $this->Html->image('admin-deal-flow.jpg', array('height'=>'148','width'=>'587','alt'=> __l('[Image: Administrator Deal Flow]'), 'title' => __l('Administrator Deal Flow'))); ?></div>
		<?php endif; ?>
	<fieldset class="form-block">
	<div class="js-validation-part">
 		 	<h3 class="genral"><?php echo __l('Type'); ?></h3>
			<div class="clearfix">
				<?php if(Configure::read('deal.is_enable_sub_deal')): ?>
					<?php echo $this->Form->input('is_subdeal_available', array('label' => __l('Add Sub Deals'), 'info' => __l('If checked, you will be able to add multiple sub deals for the same (main) deal, After filling up the below form you will be redirected to a sub deal section where you can add all your sub deal(s) details.')));?>
				<?php endif; ?>					
				<?php if(Configure::read('deal.is_side_deal_enabled')): ?>
					<?php echo $this->Form->input('is_side_deal', array('label'=>__l('Side Deal'), 'info'=>__l('Side deals will be displayed in the side bar of the home page.')));?>
				<?php endif; ?>
				<?php 
					echo $this->Form->input('is_anytime_deal', array('label' => __l('Any Time Deal'), 'info' => __l('This type of deal will not have any closing or expiry date. It can only be closed manually by the Site Administrator or by specifying the "Maximum Buy Quantity".')));
				?>	
			</div>
	
			<h3><?php echo __l('General'); ?></h3>
			<?php
				echo $this->Form->input('user_id', array('type' => 'hidden'));
				echo $this->Form->input('clone_deal_id', array('type' => 'hidden'));
				echo $this->Form->input('name',array('label' => __l('Name')));
				echo $this->Form->input('deal_category_id', array('label' => __l('Category'),'empty' =>__l('Please Select'))); 
				if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Form->input('company_id', array('label' => __l('Merchant'),'empty' =>__l('Please Select')));
					echo $this->Form->input('company_slug', array('type' => 'hidden'));
				else:
					echo $this->Form->input('company_id', array('type' => 'hidden'));
					echo $this->Form->input('company_slug', array('type' => 'hidden'));
				endif;
			?>
			<div class="clearfix date-time-block deal-add-date-time-block js-clone">
				<div class="input date-time grid_12 omega alpha clearfix required">
					<div class="js-datetime">
						<?php echo $this->Form->input('start_date', array('label' => __l('Start Date'),'minYear' => date('Y'), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
				<div class="input date-time grid_11 omega alpha end-date-time-block clearfix required js-anytime-deal">
					<div class="js-datetime">
						<?php echo $this->Form->input('end_date', array('label' => __l('End Date'),'minYear' => date('Y'), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
			</div>
			<div class="clearfix date-time-block deal-add-date-time-block js-clone">
				<div class="input date-time grid_12 omega alpha clearfix required">
					<div class="js-datetime">
						<?php echo $this->Form->input('coupon_start_date', array('label' => __l('Coupon Start Date'),'minYear' => date('Y'), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
				<div class="input date-time grid_11 omega alpha end-date-time-block clearfix required js-anytime-deal">
					<div class="js-datetime">
						<?php echo $this->Form->input('coupon_expiry_date', array('label' => __l('Coupon End Date'),'minYear' => date('Y'), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
			</div>
	
		<div class="js-subdeal-not-need">
				<h3><?php echo __l('Price'); ?></h3>
                <div class="clearfix">
                    <div class="deal-discount-form-block-left">
                    	<div class="page-info">
        					<?php
        						echo __l('If you want this deal to be added as a "Free deal", Mention/enter as 100% discount in the "Discount (%)" box below.');
        					 ?>
        				</div>
					<div class="price-form-block deal-discount-form-block">
						<?php
							if(Configure::read('site.currency_symbol_place') == 'left'):
								$currecncy_place = 'between';
							else:
								$currecncy_place = 'after';
							endif;	
						?>
						<div class=" deal-discount-form-block1 discount-form-block clearfix">
						<?php
							echo $this->Form->input('savings', array('type'=>'hidden',  'label' => __l('Savings for User'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
							echo $this->Form->input('original_price',array('div'=>'input text grid_3 omega alpha','label' => __l('Original Price'),'class' => 'js-price', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
						?>
						<?php echo $this->Form->input('discount_percentage', array('div'=>'input text grid_3 omega alpha','label' => __l('Discount (%)')));  ?>
        				<?php echo $this->Form->input('discount_amount', array('div'=>'input text grid_4 omega alpha','label' => __l('Discount Amount'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>')); ?>
  						<?php
						echo $this->Form->input('discounted_price', array('div'=>'input disable-field text required grid_4 omega alpha','label' => __l('Discounted Price for User'),'type'=>'text', 'readonly' => 'readonly', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
						?>
						</div>
						<!-- ADVANCE/PARTIALLY PAYMENT -->
						<?php $is_adv_enabled = Configure::read('deal.is_enable_payment_advance'); ?>
						<?php if(Configure::read('deal.is_enable_payment_advance')): ?>
							<?php echo $this->Form->input('is_enable_payment_advance', array('type' => 'checkbox', 'class' => 'js-enable-advance-payment {selected_container:"none"}', 'label' => __l('Allow users to make partially payments?'), 'info' => __l('If checked, user can make a partial payment now and pay the remaining at the redeem location.')));?>
							<div class="js-advance-payment-box hide">
								<?php
									echo $this->Form->input('pay_in_advance',array('label' => __l('Advance amount'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
									echo $this->Form->input('payment_remaining',array('label' => __l('Pending amount'), 'type' => 'hidden', 'class' => '', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));									
								?>
								<dl class="result-list clearfix">
									<dt><?php echo __l('Pay in Advance').'('.Configure::read('site.currency').'):  '; ?></dt>
										<dd>
											<span id="js-pay_in_advance">0</span>
										</dd>
									<dt><?php echo __l('Pay remaining').'('.Configure::read('site.currency').'):  '; ?></dt>
										<dd>
											<span id="js-payment_remaining">0</span>
										</dd>
								</dl>
							</div>						
						<?php endif; ?>
					</div>
					</div>
    				<div class="deal-discount-form-block-right">
					   <?php echo $this->element('../deals/budget_calculator', array('cache' => array('config' => 'site_element_cache', 'key' => $this->Auth->user('id')))); ?>
	       			</div>
				</div>
			
		
		</div>
	
			<h3><?php echo __l('Coupons & Quantities'); ?></h3>
			<div class="clearfix">
				<div class="clearfix input-blocks">
					<div class="grid_12 omega alpha">
						<?php
							echo $this->Form->input('min_limit', array('label'=>__l('No of Min Coupons'), 'info' => __l('Minimum limit (in numbers) of coupons that has to be purchased by the users to get the deal "Tipped".'), 'class' => 'js-min-limt'));
						?>
					</div>
					<div class="grid_12 omega alpha">
					   <div class="js-subdeal-not-need">
					
							<?php	echo $this->Form->input('max_limit', array('label'=>__l('No of Max Coupons'), 'info' => __l('Maximum limit (in numbers) of coupons that can be purchased by the users per deal.Leave the box space blank, for unlimited coupon buys.'))); ?>
						</div>
					</div>
				</div>
				<div class="clearfix input-blocks">
					<div class="grid_12 omega alpha">
						<?php
							echo $this->Form->input('buy_min_quantity_per_user', array('label'=>__l('Minimum Buy Quantity'),'info' => __l('Minimum purchase per user including gifts.')));
						?>
					</div>
					<div class="grid_12 omega alpha ">
						<?php
							echo $this->Form->input('buy_max_quantity_per_user', array('label'=>__l('Maximum Buy Quantity'),'info' => __l('Maximum purchase per user including gifts. Leave blank for no limit.')));
						?>
				   </div>
			   </div>
			</div>
	
		<div class="js-subdeal-not-need">
		
				<h3><?php echo __l('Commission'); ?></h3>
			
				<div class="clearfix">
				<div class="deal-discount-form-block-left">
                	<div class="page-info">
    					<?php
    						echo __l('Total Commission Amount = Bonus Amount + ((Discounted Price * Number of Buyers) * Commission Percentage/100))');
    					 ?>
    				</div>
					<div class="commision-form-block">
						<?php
							echo $this->Form->input('bonus_amount', array('label' => __l('Bonus Amount'),'value' => '0.00',$currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
						?>
						<span class="info sfont"> <?php echo __l('This is the flat fee that the merchant will be required to pay for the whole deal.');?></span>
						<?php if(($this->Auth->user('user_type_id') != ConstUserTypes::Admin) && (Configure::read('deal.is_admin_enable_commission')) && Configure::read('deal.commission_amount_type') == 'fixed'):
	                             echo $this->Form->input('commission_percentage', array('Readonly' =>'Readonly', 'info' => __l('This is the commission that merchant will pay for the whole deal in percentage.'), 'label' => __l('Commission (%)')));
							 else:
								if($this->Auth->user('user_type_id') != ConstUserTypes::Admin && Configure::read('deal.is_admin_enable_commission') && Configure::read('deal.commission_amount_type') == 'minimum'):
									$comm_info = __l('This is the commission that merchant will pay for the whole deal in percentage. The Commission set must be greater than'.' '.$this->Html->cCurrency(Configure::read('deal.commission_amount')).'(%)');
								else:
									$comm_info = __l('This is the commission that merchant will pay for the whole deal in percentage.');
								endif;
								echo $this->Form->input('commission_percentage', array('info' => $comm_info, 'label' => __l('Commission (%)')));
							 endif; 
						?>
					</div>
					</div>
				<div class="deal-discount-form-block-right">
						<?php echo $this->element('../deals/commission_calculator', array('cache' => array('config' => 'site_element_cache', 'key' => $this->Auth->user('id')))); ?>
                </div>
				</div>
		
		</div>
	
			<h3><?php echo __l('Deal Cities'); ?></h3>
			<div class="input cities-block required">
				<label><?php echo __l('Cities');?></label>
			</div>
			<div class="clearfix">
			<?php 
				if(empty($this->request->data['Deal']['City']) && empty($city_id)): ?>
					<div class="cities-checkbox-block clearfix">
						<?php
						echo $this->Form->input('City',array('label' =>false,'multiple'=>'checkbox')); ?>
					</div>
					<?php
				else:
				 ?>
				 <div class="cities-checkbox-block clearfix">
				 <?php
					echo $this->Form->input('City',array('label' => false,'multiple'=>'checkbox','value'=>$city_id));
				?>
					</div>
				<?php
				endif;
			?>
			</div>
	

			<h3><?php echo __l('Description'); ?></h3>
			<?php
				if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Form->input('private_note', array('type' =>'textarea', 'label' => __l('Private Note'), 'info' => __l('This is for admin reference. It will not be displayed for other users.')));
				endif; ?>
				 <div class="review-block">
				<?php
				echo $this->Form->input('description', array('label' => __l('Description'),'type' =>'textarea', 'class' => 'js-editor'));
			     ?>
                </div>
      
			<h3><?php echo __l('Deal Listing Locations'); ?></h3>
			<div class="deal-listing-location">
            <?php echo $this->Form->input('is_redeem_in_main_address', array('label' => __l('Can Redeem at Your Merchant Main Address?'), 'info' => __l('Uncheck this option, if you don\'t want to make the redeem location in your main merchant location. <br /><strong>Note:</strong> If all branch address are unchecked, this option will be automatically set as "true".')));?>
			<div class="js-show-deal-company-main-address">
			</div>
			<?php echo $this->Form->input('is_redeem_at_all_branch_address', array('label' => __l('Can Redeem at All Sub-locations?'), 'id' =>'js-redeem-all-branch', 'info' => __l('Uncheck this option, if you don\'t want the redeem to be done at all the branch location.'), 'disabled' => (empty($branch_addresses))? 'disabled' : ''));?>
			<div class="js-show-branch-addresses <?php echo (!isset($this->request->data['Deal']['is_redeem_at_all_branch_address']) ? 'hide' : (!empty($this->request->data['Deal']['is_redeem_at_all_branch_address']) ? 'hide' : ''));?>">
				<?php if(!empty($branch_addresses)):?>
					<span class="info sfont"><?php echo __l('Redeem only at');?></span>
					<div class="clearfix">
						<?php
							echo $this->Form->input('CompanyAddressesDeal.company_address_id', array('label' =>false,'multiple'=>'checkbox', 'options' => $branch_addresses ));
						?>
					</div>
				<?php else:?>
					<span class="info sfont"><?php echo __l('You don\'t have any branch address.');?></span>
				<?php endif;?>
			</div>
			</div>
			
	
	</div>
  
		<h3><?php echo __l('Deal Image'); ?></h3>
			<div class="required">
			<div class="input required gig-img-label">
					<label><?php echo __l('Deal Images');?></label>
					<?php
						$redirect_check = (!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') ? "true" : "false";
						if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
							$redirect_array = array('controller' => 'deals', 'action' => 'index', 'type' => 'success','admin' => true);
						else:
							$redirect_array = array('controller' => 'deals', 'action' => 'company', $this->request->data['Deal']['company_slug'], 'success','admin' => false);
						endif;
						echo $this->Form->uploader('Attachment.filename', array('type'=>'file', 'uController' => 'deals', 'uRedirectURL' => $redirect_array, 'uId' => 'dealID', 'uFiletype' => Configure::read('photo.file.allowedExt')));
					?>
					<span class="info sfont">
						<?php echo __l('Add up to 5 images in this uploader. Images will be shown in slider.');?>
					</span>
				</div>
                <div class="clearfix attachment-delete-outer-block">
				<?php
				 if(!empty($this->request->data['CloneAttachment'][0])) {?>
                 	<ul>
					<?php
						
                	$i =0;
                	foreach($this->request->data['CloneAttachment'] as $CloneAttachment){ ?>
                    	<li>	
							<div class="attachment-delete-block">
							  <span class="delete-photo"> <?php echo __l('Delete Photo'); ?></span>
                    <?php 
                    echo $this->Form->input('OldAttachment.'.$CloneAttachment['id'].'.id', array('type' => 'checkbox', 'class'=>'','id' => "gig_checkbox_".$CloneAttachment['id'], 'label' => false));
                    echo $this->Form->input('CloneAttachment.'.$i.'.id', array('type' => 'hidden', 'value' => $CloneAttachment['id']));
					echo $this->Html->showImage('Deal', $CloneAttachment, array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['Deal']['name'], false)), 'title' => $this->Html->cText($this->request->data['Deal']['name'], false), 'escape' => false));
					$i++;?>
					</div>
                    </li>
                    
					<?php 
					}?>
                    </ul>
                <?php }	?>
                </div>
			</div>
	
		 <?php if(Configure::read('charity.is_enabled') == 1):?>
		
			<h3><?php echo __l('Charity'); ?></h3>
			<span class="info sfont">
				<?php echo __l('You can decide whether you want to/Don\'t want to donate the amount to charity, if you desire to make the donation, then ');?>
				<?php if(Configure::read('charity.who_will_choose') == ConstCharityWhoWillChoose::CompanyUser): ?>
					<span><?php echo __l('the amount to the charity will be given from the commission amount you have earned.');?></span>
				<?php else:?>
					<span><?php echo __l('the amount to the charity will be given from admin commission amount. Your profit wont be affected.');?></span>
				<?php endif;?>
			</span>
			<?php if(Configure::read('charity.who_will_choose') == ConstCharityWhoWillChoose::CompanyUser): ?>
				<?php echo $this->Form->input('charity_id', array('empty' =>__l('Please Select'))); ?>
			<?php endif; ?>
			<?php echo $this->Form->input('charity_percentage', array('label' => __l('Charity Percentage (%)'),'info' =>__l('Percentage of the amount that you would like to donate to the charity.'))); ?>
			<?php if(Configure::read('charity.who_will_pay') == ConstCharityWhoWillPay::Admin || Configure::read('charity.who_will_pay') == ConstCharityWhoWillPay::AdminCompanyUser): ?>
			<div class="page-info">
			<?php
				echo __l('Admin also pay same percentage of amount from his commission');
			 ?>
			 </div>
			 <?php endif; ?>
		
		  <?php endif; ?>        
         <h3><?php echo __l('Deal Orders/Coupons'); ?></h3>
			<div class="page-info"><?php echo __l('Irrespective of the "Type of user". User can use this coupon code at the time of purchase. If you leave this field empty (free) or enter less number of coupons than the users can possibly to purchase then the system will automatically generate the coupons to compensate the total no of coupons users has purchased.');?></div>
		<?php echo $this->Form->input('coupon_code', array('type' => 'textarea', 'info' => __l('Comma seperated for multiple coupons. <br />e.g., 000781b0-1, 0004e1b0-6, 00a481b0-8')));?>

     
         <div class="review-block">
	       	<?php echo $this->Form->input('review', array('label' => __l('Review'),'type' => 'textarea', 'class' => 'js-editor')); ?>
		</div>
        
         <div class="review-block">
    		<?php
    			echo $this->Form->input('coupon_condition', array('label' => __l('Coupon Condition'),'type' =>'textarea', 'class' => 'js-editor'));
    			echo $this->Form->input('coupon_highlights', array('label' => __l('Coupon Highlights'),'type' =>'textarea', 'class' => 'js-editor'));
    			echo $this->Form->input('comment', array('label' => __l('Comment'),'type' =>'textarea', 'class' => 'js-editor'));
    		?>
    	</div>
        <h3><?php echo __l('SEO'); ?></h3>
        <?php
			echo $this->Form->input('meta_keywords',array('label' => __l('Meta Keywords')));
			echo $this->Form->input('meta_description',array('label' => __l('Meta Description')));
	?>
 </fieldset>

	<div class="submit-block clearfix">
		<?php echo $this->Form->input('is_save_draft', array('type' => 'hidden', 'id' => 'js-save-draft'));?>
		<?php echo $this->Form->input('is_preview', array('type' => 'hidden', 'id' => 'js-save-preview'));?>
		<div class="js-subdeal-not-need">
                <div class="clearfix">
    			  <?php
    			     	echo $this->Form->submit(__l('Add'), array('class' => 'js-update-order-field', 'id' => 'js-update-order-field-add-id'));
    				?>
    				<div class="save-draft-block">
        				<?php
        				echo $this->Form->submit(__l('Save as Draft'), array('div' =>false,'name' => 'data[Deal][save_as_draft]', 'class' => 'js-update-order-field' , 'id' => 'js-update-order-field-draft-id'));
        				echo $this->Form->submit(__l('Preview'), array('div' =>false,'name' => 'data[Deal][preview]', 'class' => 'js-update-preview-field', 'id' => 'js-update-order-field-preview-id'));  ?>
				    </div>
               	</div>
				    <div class="grid_15 alpha omega">
				     <span class="info info1 sfont"><?php echo __l('Save this deal as a draft and you can make changes untill you send it to ').$status.__l(' status. Use the update button in edit page to send it to ').$status.__l(' status.'); ?></span>
				    </div>
			<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company): $status = __l('pending approval'); else: $status = __l('upcoming');  endif; ?>
		</div>
		<div class="js-subdeal-need hide">
			<?php 			
				echo $this->Form->submit(__l('Continue'), array('name' => 'data[Deal][continue]', 'class' => 'js-update-order-field'));
			?>		
		</div>
   </div>
<?php echo $this->Form->end();
?>
</div>