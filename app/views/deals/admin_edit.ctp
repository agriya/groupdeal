<?php /* SVN: $Id: admin_edit.ctp 79836 2012-12-17 10:43:20Z balamurugan_177at12 $ */?>
<div class="deals form js-responses js-deal-subdeal-available-over-block">
<?php echo $this->Form->create('Deal', array('class' => 'normal add-live-form js-upload-form {is_required:"false"}', 'enctype' => 'multipart/form-data'));?>
    <fieldset class="form-block">
 	<h3 class="genral"><?php echo __l('Type'); ?></h3>
	<div class="js-validation-part">
	
		
			<div class="clearfix">
				<div class="hide">
					<?php
						// Toggling Sub Deal Option Disabled in Edit Pages //
						echo $this->Form->input('is_subdeal_available', array('label' => __l('Add Sub Deals'), 'info' => __l('If checked, you will be able to add miltiple sub deals for the same (main) deal, After filling up the below form you will be redirected to a sub deal section where you can add all your sub deal(s) details.')));
					?>
				</div>
				<?php if(Configure::read('deal.is_side_deal_enabled')): ?>
					<?php echo $this->Form->input('is_side_deal', array('label'=>__l('Side Deal'), 'info'=>__l('Side deals will be displayed in the side bar of the home page.')));?>
				<?php endif; ?>
				<div>
					<?php 
						echo $this->Form->input('is_anytime_deal', array('label' => __l('Any Time Deal'), 'info' => __l('This type of deal will not have any closing or expiry date. It can only be closed manually by the Site Administrator or by specifying the "Maximum Buy Quantity".')));
					?>	
				</div>
			</div>
 			<h3><?php echo __l('General'); ?></h3>
			<?//php echo $this->Html->link($this->Html->showImage('Deal', $this->request->data['Attachment'][0], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['Deal']['name'], false)), 'title' => $this->Html->cText($this->request->data['Deal']['name'], false))), array('controller' => 'deals', 'action' => 'view',  $deal['Deal']['slug'], 'admin' => false), null, array('inline' => false)); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('name',array('label' => __l('Name')));
				echo $this->Form->input('deal_category_id', array('label' => __l('Category'),'empty' =>__l('Please Select')));
				echo $this->Form->input('company_id', array('label' => __l('Merchant'),'empty' => 'Please Select'));
			?>
        	<div class="clearfix date-time-block deal-add-date-time-block js-clone">
				<div class="input date-time grid_12 omega alpha clearfix required">
					<div class="js-datetime">
						<?php echo $this->Form->input('start_date', array('label' => __l('Start Date'),'minYear' => date('Y', strtotime($this->request->data['Deal']['start_date'])), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
				<div class="input date-time grid_11 omega alpha end-date-time-block clearfix required js-anytime-deal">
					<div class="js-datetime">
						<?php echo $this->Form->input('end_date', array('label' => __l('End Date'),'minYear' => date('Y', strtotime($this->request->data['Deal']['end_date'])), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
			</div>
			<div class="clearfix date-time-block deal-add-date-time-block js-clone">
				<div class="input date-time grid_12 omega alpha clearfix required">
					<div class="js-datetime">
						<?php echo $this->Form->input('coupon_start_date', array('label' => __l('Coupon Start Date'),'minYear' => date('Y', strtotime($this->request->data['Deal']['coupon_start_date'])), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
				<div class="input date-time grid_11 omega alpha end-date-time-block clearfix required js-anytime-deal">
					<div class="js-datetime">
							<?php echo $this->Form->input('coupon_expiry_date', array('label' => __l('Coupon End Date'),'minYear' => date('Y', strtotime($this->request->data['Deal']['coupon_expiry_date'])), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
			</div>




				
	<?php if(empty($this->request->data['Deal']['is_subdeal_available'])) { ?>
		<div class="js-subdeal-not-need <?php echo (!empty($this->request->data['Deal']['is_subdeal_available']) ? 'hide' : '');?>">        
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
							<div class="deal-discount-form-block1 discount-form-block clearfix">
						<?php
							echo $this->Form->input('original_price',array('div'=>'input text grid_3 omega alpha','label' => __l('Original Price'),'class' => 'js-price', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
						?>
					
							<?php
							echo $this->Form->input('savings', array('type'=>'hidden',  'label' => __l('Savings for User'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
							 echo $this->Form->input('discount_percentage', array('div'=>'input text grid_4 omega alpha','label' => __l('Discount (%)')));  ?>
					
							<?php echo $this->Form->input('discount_amount', array('div'=>'input text grid_4 omega alpha','label' => __l('Discount Amount'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>')); ?>
				
						<?php 
						
							echo $this->Form->input('discounted_price', array('div'=>'input disable-field text required grid_4 omega alpha','label' => __l('Discounted Price for User'),'type'=>'text', 'readonly' => 'readonly', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
						?>
								</div>
						<!-- ADVANCE/PARTIALLY PAYMENT -->
						<?php $is_adv_enabled = Configure::read('deal.is_enable_payment_advance'); ?>
						<?php if(Configure::read('deal.is_enable_payment_advance')): ?>
							<?php echo $this->Form->input('is_enable_payment_advance', array('type' => 'checkbox', 'class' => 'js-enable-advance-payment {selected_container:"none"}', 'label' => __l('Allow users to make partially payments?'), 'info' => __l('If checked, user can make a partial payment now and pay the remaining at the redeem location.')));?>
							<div class="js-advance-payment-box <?php echo (!empty($this->request->data['Deal']['is_enable_payment_advance']) ? '' : 'hide');?>">
								<?php
									echo $this->Form->input('pay_in_advance',array('label' => __l('Advance amount'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
									echo $this->Form->input('payment_remaining',array('label' => __l('Pending amount'), 'type' => 'hidden', 'class' => '', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));									
								?>
								<dl class="result-list clearfix">
									<dt><?php echo __l('Pay in Advance').'('.Configure::read('site.currency').'):  '; ?></dt>
										<dd>
											<span id="js-pay_in_advance"><?php echo $this->Html->cCurrency($this->request->data['Deal']['pay_in_advance']);?></span>
										</dd>
									<dt><?php echo __l('Pay remaining').'('.Configure::read('site.currency').'):  '; ?></dt>
										<dd>
											<span id="js-payment_remaining"><?php echo $this->Html->cCurrency($this->request->data['Deal']['payment_remaining']);?></span>
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
	<?php } ?>
			<h3><?php echo __l('Coupons & Quantities'); ?></h3>
			<div class="clearfix input-blocks">
				<div class="grid_12 omega alpha">
					<?php echo $this->Form->input('min_limit', array('label'=>__l('No of Min Coupons'), 'info' => __l('Minimum limit (in numbers) of coupons that has to be purchased by the users to get the deal "Tipped".'), 'class' => 'js-min-limt')); ?>
				</div>
				<div class="grid_12 omega alpha">
			 	<div class="js-subdeal-not-need <?php echo (!empty($this->request->data['Deal']['is_subdeal_available']) ? 'hide' : '');?>">
   					<?php	echo $this->Form->input('max_limit', array('label'=>__l('No of Max Coupons'), 'info' => __l('Maximum limit (in numbers) of coupons that can be purchased by the users per deal.Leave the box space blank, for unlimited coupon buys.'))); ?>
					</div>
				</div>
			</div>
			<div class="clearfix input-blocks">
				<div class="grid_12 omega alpha">
					<?php 	echo $this->Form->input('buy_min_quantity_per_user', array('label'=>__l('Minimum Buy Quantity'),'info' => __l('How much minimum coupons user should buy for himself. Default 1'))); ?>
				</div>
				<div class="grid_12 omega alpha ">
					<?php	echo $this->Form->input('buy_max_quantity_per_user', array('label'=>__l('Maximum Buy Quantity'),'info' => __l('How much coupons user can buy for himself. Leave blank for no limit.'))); ?>
				</div>
			</div>
	
		<div class="js-subdeal-not-need <?php echo (!empty($this->request->data['Deal']['is_subdeal_available']) ? 'hide' : '');?>">        
		
			   <h3><?php echo __l('Commission'); ?></h3>
				
					<div class="clearfix">
						   <div class="deal-discount-form-block-left">
						   	<div class="page-info"><?php echo __l('Total Commission Amount = Bonus Amount + ((Discounted Price * Number of Buyers) * Commission Percentage/100))'); ?></div>
							<?php
								echo $this->Form->input('bonus_amount', array('label' => __l('Bonus Amount'),$currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>', 'info' => __l('This is the flat fee that the merchant will be required to pay for the whole deal.')));
								echo $this->Form->input('commission_percentage', array('info' => __l('This is the commission that merchant will pay for the whole deal in percentage.')));
							?>
						</div>
						   <div class="deal-discount-form-block-right">
				    		<?php echo $this->element('../deals/commission_calculator', array('cache' => array('config' => 'site_element_cache', 'key' => $this->Auth->user('id'))));;?>
                            </div>
                	</div>

		</div>
	
			<h3><?php echo __l('Deal Cities'); ?></h3>
			<div class="input cities-block required">
				<label><?php echo __l('Cities');?></label>
			</div>
			<div class="clearfix">
			<?php if(empty($this->request->data['Deal']['City'])): ?>
				<div class="cities-checkbox-block clearfix">
					<?php
						echo $this->Form->input('City',array('label' =>false,'multiple'=>'checkbox', 'class' => 'js-check-invalid'));
					?>
				</div>
				<?php else:?>
				<div class="cities-checkbox-block">
					<?php echo $this->Form->input('City',array('label' =>false,'multiple'=>'checkbox','value'=>$city_id));?>
				</div>
			<?php endif;?>
			</div>
 			<h3><?php echo __l('Description'); ?></h3>
			<?php
				if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Form->input('private_note', array('type' =>'textarea', 'label' => __l('Private Note'), 'info' => __l('This is for admin reference. It will not be displayed for other users.')));
				endif;
			?>
			<div class="review-block">
		          	<?php echo $this->Form->input('description', array('label' => __l('Description'),'type' =>'textarea', 'class' => 'js-editor'));?>
            </div>
			<h3><?php echo __l('Deal Listing Locations'); ?></h3>
			<div class="deal-listing-location">
            <?php echo $this->Form->input('is_redeem_in_main_address', array('label' => __l('Can Redeem at Your Merchant Main Address?'), 'info' => __l('Uncheck this option, if you don\'t want to make the redeem location in your main merchant location. <br /><strong>Note: </strong> If all branch address are unchecked, this option will be automatically set as "true".')));?>
			<div class="js-show-deal-company-main-address">
			</div>            
			<?php echo $this->Form->input('is_redeem_at_all_branch_address', array('label' => __l('Can Redeem at All Sub-locations?'), 'id' =>'js-redeem-all-branch', 'info' => __l('Uncheck this option, if you don\'t want the redeem to be done at all the branch location.'), 'disabled' => (empty($branch_addresses))? 'disabled' : ''));?>
			<div class="js-show-branch-addresses <?php echo (!isset($this->request->data['Deal']['is_redeem_at_all_branch_address']) ? 'hide' : (!empty($this->request->data['Deal']['is_redeem_at_all_branch_address']) ? 'hide' : ''));?>">
				<?php if(!empty($branch_addresses)):?>
					<span class="info redeem-info sfont"><?php echo __l('Redeem only at');?></span>
					<div class="clearfix redeem-input-block">
						<?php
							echo $this->Form->input('CompanyAddressesDeal.company_address_id',array('label' =>false, 'multiple'=>'checkbox', 'value' => $branch_checked_addresses, 'options' => $branch_addresses));
						?>
					</div>
				<?php else:?>
					<span class="info sfont"><?php echo __l('You don\'t have any branch address.');?></span>
				<?php endif;?>
			</div>
			</div>
            
            
	
    </div> 
 
	
		<h3><?php echo __l('Deal Image'); ?></h3>
		<div class="clearfix attachment-delete-outer-block">
			<ul>
				<?php 
					foreach($this->request->data['Attachment'] as $attachment){ 
				?>
					<li>	
					<div class="attachment-delete-block">
					  <span class="delete-photo"> <?php echo __l('Delete Photo'); ?></span>

					<?php	
						echo $this->Form->input('OldAttachment.'.$attachment['id'].'.id', array('type' => 'checkbox', 'class'=>'js-gig-photo-checkbox','id' => "gig_checkbox_".$attachment['id'], 'label' => false));
						echo $this->Html->showImage('Deal', $attachment, array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['Deal']['name'], false)), 'title' => $this->Html->cText($this->request->data['Deal']['name'], false)));
					?>
					</div>
					</li>
				<?php } ?>
			</ul>
        </div>        
		
		<?php
			echo $this->Form->uploader('Attachment.filename', array('type'=>'file', 'uController' => 'deals', 'uRedirectURL' => array('controller' => 'deals', 'action' => 'index', 'admin' => true), 'uId' => 'dealID', 'uFiletype' => Configure::read('photo.file.allowedExt')));
		?>
		<span class="info sfont">
			<?php echo __l('Add up to 5 images in this uploader. Images will be shown in slider.');?>
		</span>
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
           <?php if ($deal['Deal']['is_subdeal_available'] != 0): ?>
    
		<h3><?php echo __l('Sub Deal'); ?></h3>
			<?php if (!empty($subdeal)):?>
				<?php echo $this->Html->link(__l('Edit'), array('controller' => 'deals', 'action' => 'subdeal_edit', $deal['Deal']['id']), array('class' => 'edit', 'title' => __l('Edit')));?> 
			<?php else: ?>
				<?php echo $this->Html->link(__l('Add'), array('controller' => 'deals', 'action' => 'subdeal_edit', $deal['Deal']['id']), array('class' => 'edit', 'title' => __l('Add')));?>
			<?php endif;?>
        
        <ol class="list sub-deal-list clearfix js-subdeal-on-fly-over-block">
			<?php
				if (!empty($subdeal)):
				$i = 0;
				foreach ($subdeal as $sub_deal):
				   $class = null;
					if ($i++ % 2 == 0) {
						$class = "altrow";
					}
					?>
                    <li class= "vcard clearfix <?php echo $class;?>" >
					    <h3><?php echo $this->Html->cText($sub_deal['Deal']['name']);?></h3>
						<dl class="list clearfix">
							<dt><?php echo __l('Discounted Price: ');?></dt>
								<dd><?php echo $this->Html->cText($sub_deal['Deal']['discounted_price']);?></dd>
							<dt><?php echo __l('Max limit: ');?></dt>
								<dd><?php echo $this->Html->cInt($sub_deal['Deal']['max_limit']);?></dd>
						</dl>
                        <div class="address-actions pa">
							<?php echo $this->Html->link(__l('Delete'), array('controller' => 'deals', 'action' => 'subdeal_delete', $sub_deal['Deal']['id'], $deal['Deal']['id'], 'admin' => false), array('class' => 'no-pad delete js-on-the-fly-sub-deal-delete', 'title' => __l('Delete')));?>
						</div>
					</li>
				<?php
					endforeach;
				else:
				?>
					<li class="notice"><?php echo __l('No sub deals available');?></li>
				<?php
				endif;
				?>
				</ol>  
      

        <?php endif; ?>

			<h3><?php echo __l('Deal Orders/Coupons'); ?></h3>
				<span><?php echo $this->Html->link(__l('List Existing Coupons'), array('controller' => 'deal_coupons', 'action' => 'index', 'deal_id' =>  $this->request->data['Deal']['id']), array('target' => '_blank', 'title' => __l('List Existing Coupons')));?></span>
				<div class="page-info"><?php echo __l('Irrespective of the "Type of user". User can use this coupon code at the time of purchase. If you leave this field empty (free) or enter less number of coupons than the users can possibly to purchase then the system will automatically generate the coupons to compensate the total no of coupons users has purchased.');?></div>
					<?php 
						if(!empty($manual_coupon_codes)):
							echo $this->Form->input('old_coupon_code', array('type' => 'textarea', 'disabled' => true, 'value' => $manual_coupon_codes));
						endif;
					?>
					<?php echo $this->Form->input('coupon_code', array('type' => 'textarea', 'info' => __l('Comma seperated for multiple coupons. <br />e.g., 000781b0-1, 0004e1b0-6, 00a481b0-8')));?>
      
        <div class="review-block">
		<?php
			echo $this->Form->input('review', array('label' => __l('Review'),'type' =>'textarea', 'class' => 'js-editor'));
		?>
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
        <?php echo $this->Form->submit(__l('Update'),array('name' => 'data[Deal][send_to_admin]', 'id' => 'js-update-order-field-add-id')); ?>
    	<?php
			if($deal['Deal']['deal_status_id'] == ConstDealStatus::Draft):
				echo $this->Form->submit(__l('Update Draft'));
			endif;
			?>
			<div class="cancel-block">
    			<?php
    			echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'index', 'admin' => true), array('class' => 'cancel-button'));
        		?>
             </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
