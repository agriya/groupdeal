<?php /* SVN: $Id: admin_live_deal_add.ctp 60970 2011-07-22 13:42:48Z mohanraj_109at09 $ */ ?>
<div class="deals form js-responses js-deal-subdeal-available-over-block">
<?php echo $this->Form->create('Deal', array('action' => 'live_edit', 'class' => 'normal add-live-form js-upload-form {is_required:"false"}', 'enctype' => 'multipart/form-data'));?>
    <fieldset class="form-block round-5">
	<div class="js-validation-part">
		<h3 class="genral"><?php echo __l('General'); ?></h3>
			<div class = "hide">
				<?php echo $this->Form->input('is_now_deal', array('value' => 1, 'type' => 'hidden'));?>
			</div>
			<?php
				echo $this->Form->input('user_id', array('type' => 'hidden'));
				echo $this->Form->input('clone_deal_id', array('type' => 'hidden'));
				echo $this->Form->input('name',array('label' => __l('Name')));			

				if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Form->input('company_id', array('label' => __l('Merchant'),'empty' =>__l('Please Select')));
					echo $this->Form->input('company_slug', array('type' => 'hidden'));
				else:
					echo $this->Form->input('company_id', array('type' => 'hidden'));
					echo $this->Form->input('company_slug', array('type' => 'hidden'));
				endif;
			?>
			<?php echo $this->Form->input('deal_category_id', array('label' => __l('Category'),'empty' =>__l('Please Select'))); ?>
			<div class="clearfix date-time-block js-clone">
				<div class="input date-time live-date-time clearfix required">
					<div class="js-datetime">
						<?php echo $this->Form->input('start_date', array('label' => __l('Deal Start From'),'minYear' => date('Y'), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
			</div>
			<div class="clearfix add-time-picker-formate time-picker-formate js-clone">
            	<div class="input  clearfix required">
                    <div class="js-time" >
                        <?php
                            echo $this->Form->input('coupon_start_date', array('type' => 'time', 'timeFormat' => 12, 'label' => __l('Coupon Start Time'), 'orderYear' => 'asc'));
                        ?>
                    </div>
                </div>
                <div class="input clearfix required js-anytime-deal">
                    <div class="js-time" >
                        <?php
                            echo $this->Form->input('coupon_expiry_date', array('type' => 'time', 'timeFormat' => 12, 'label' => __l('Coupon Expiry Time'), 'orderYear' => 'asc'));
                        ?>
                    </div>
                </div>
			</div>
			<div class="clearfix deal-repeat-block">
				<?php echo $this->Form->input('deal_repeat_type_id', array('class' => 'js-repeat-type-select', 'label' => __l('Repeat Deal'))); ?>
				<div class="hide js-repeat-date">
				
				</div>
                 <div class="hide js-repeat-date">
                <span class="repeat-every"><?php echo __l('Repeat Every');?> </span>
                <div class="clearfix deal-repeat-select-block">
					<?php echo $this->Form->input('RepeatDate', array('multiple' => 'checkbox', 'label' =>false)); ?>
                </div>
                </div>
				
				<div class="hide js-repeat_until_block">
					<span class="repeat-every"><?php echo __l('	Repeat Until');?> </span>
					<?php echo $this->Form->input('repeat_until', array('type' => 'radio','legend' =>false, 'class' => 'js-repeat-until-select')); ?>
					<div class="hide js-repeat-until">
						<div class="input date-time date-time-block clearfix required js-anytime-deal js-clone">
							<div class="js-datetime">
								<?php echo $this->Form->input('end_date', array('label' => __l('End Date'),'minYear' => date('Y'), 'maxYear' => date('Y') + 10, 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
	
       
			<h3 class="round-5"><?php echo __l('Deal Listing Locations'); ?></h3>
			<div class="deal-listing-location">
			<?php echo $this->Form->input('is_redeem_in_main_address', array('label' => __l('Can Redeem at Your Merchant Main Address?'), 'info' => __l('Uncheck this option, if you don\'t want to make the redeem location in your main merchant location. <br /><strong>Note:</strong>  If all branch address are unchecked, this option will be automatically set as "true".')));?>
   			<div class="js-show-deal-company-main-address">
			</div>
			<?php echo $this->Form->input('is_redeem_at_all_branch_address', array('label' => __l('Can Redeem at All Sub-locations?'), 'id' =>'js-redeem-all-branch', 'info' => __l('Uncheck this option, if you don\'t want the redeem to be done at all the branch location.')));?>
			<div class="js-show-branch-addresses input <?php echo (!isset($this->request->data['Deal']['is_redeem_at_all_branch_address']) ? 'hide' : (!empty($this->request->data['Deal']['is_redeem_at_all_branch_address']) ? 'hide' : ''));?>">
				<?php if(!empty($branch_addresses)):?>
					<span class="info redeem-info sfont"><?php echo __l('Redeem only at');?></span>
					<div class="clearfix redeem-input-block">
						<?php
							echo $this->Form->input('CompanyAddressesDeal.company_address_id',array('label' =>false,'multiple'=>'checkbox', 'checked' => true, 'options' => $branch_addresses));
						?>
					</div>
				<?php else:?>
					<span class="info sfont"><?php echo __l('You don\'t have any branch address.');?></span>
				<?php endif;?>
			</div>
        </div>
		<div class="js-subdeal-not-need">
		
				<h3><?php echo __l('Price'); ?></h3>
				<div class="clearfix">
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
							echo $this->Form->input('id');
							echo $this->Form->input('original_price',array('div'=>'input text grid_3 omega alpha','label' => __l('Original Price'),'class' => 'js-price', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
						?>
					
							<?php echo $this->Form->input('discount_percentage', array('div'=>'input text grid_3 omega alpha','label' => __l('Discount (%)')));  ?>
						
							<?php echo $this->Form->input('discount_amount', array('div'=>'input text grid_4 omega alpha','label' => __l('Discount Amount'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>')); ?>
					
						<?php 
							echo $this->Form->input('savings', array('type'=>'hidden','div'=>'input text grid_4 omega alpha', 'label' => __l('Savings for User'),  'readonly' => 'readonly', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
							echo $this->Form->input('discounted_price', array('div'=>'input disable-field text required grid_4 omega alpha','label' => __l('Discounted Price for User'),'type'=>'text', 'readonly' => 'readonly', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
						?>
							</div>
					</div>
				</div>   
		
		</div>
		<div class="js-subdeal-not-need">
			<h3><?php echo __l('Commission'); ?></h3>
				<div class="page-info">
					<?php
						echo __l('Total Commission Amount = Bonus Amount + ((Discounted Price * Number of Buyers) * Commission Percentage/100))');
					 ?>
				</div>
				<div class="clearfix">
				 	<?php
        				echo $this->Form->input('bonus_amount', array('info'=>__l('This is the flat fee that the merchant will be required to pay for the whole deal.'),'label' => __l('Bonus Amount'),$currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
        			?>
                     <?php if(($this->Auth->user('user_type_id') != ConstUserTypes::Admin) && (Configure::read('deal.is_admin_enable_commission')) && Configure::read('deal.commission_amount_type') == 'fixed'):
                         echo $this->Form->input('commission_percentage', array('Readonly' =>'Readonly', 'info' => __l('This is the commission that merchant will pay for the whole deal in percentage.'), 'label' => __l('Commission (%)')));
					 else:
						if($this->Auth->user('user_type_id') != ConstUserTypes::Admin && Configure::read('deal.is_admin_enable_commission') && Configure::read('deal.commission_amount_type') == 'minimum'):
							$comm_info = __l('This is the commission that merchant will pay for the whole deal in percentage. The Commission set must be greater than'.' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('deal.commission_amount'))));
						else:
							$comm_info = __l('This is the commission that merchant will pay for the whole deal in percentage.');
						endif;
						echo $this->Form->input('commission_percentage', array('info' => $comm_info, 'label' => __l('Commission (%)')));
					 endif;
				?>
  				</div>
	
		</div>
		<h3><?php echo __l('Coupons & Quantities'); ?></h3>
			<?php
					echo $this->Form->input('maxmium_purchase_per_day', array('label'=>__l('Maximum quantity'), 'info' => __l('Maximum Quantity of coupons to be bought per day.'), 'class' => 'js-min-limt'));
				?>
	
				<?php
					echo $this->Form->input('user_each_purchase_max_limit', array('label'=>__l('Maximum quantity per purchase'),'info' => __l('Maximum quantity per purchase including gifts. Leave blank for no limit.')));
				?>
 			<h3><?php echo __l('Description'); ?></h3>
			<?php
				if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Form->input('private_note', array('type' =>'textarea', 'label' => __l('Private Note'), 'info' => __l('This is for admin reference. It will not be displayed for other users.')));
				endif;?>
			<div class="review-block">
				<?php
				echo $this->Form->input('description', array('label' => __l('Description'),'type' =>'textarea', 'class' => 'js-editor'));
			?>
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
		<div class="page-info">
			<?php echo __l('Add up to 5 images. Images will be shown in slider.');?>
		</div>
		<?php
			if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
				$redirect_array = array('controller' => 'deals', 'action' => 'live', 'type' => 'success','admin' => true);
			else:
				$redirect_array = array('controller' => 'deals', 'action' => 'company', $this->request->data['Deal']['company_slug'], 'success','admin' => false);
			endif;

			echo $this->Form->uploader('Attachment.filename', array('type'=>'file', 'uController' => 'deals', 'uRedirectURL' => $redirect_array, 'uId' => 'dealID', 'uFiletype' => Configure::read('photo.file.allowedExt')));
		?>
		<?php if(Configure::read('charity.is_enabled') == 1):?>
	
				<h3><?php echo __l('Charity'); ?></h3>
				<span class="info sfont">
					<?php echo __l('You can decide whether you want to/Don\'t want to donate the amount to charity, if you desire to make the donation, then ');?>
					<?php if(Configure::read('charity.who_will_choose') == ConstCharityWhoWillChoose::CompanyUser): ?>
						<p><?php echo __l('the amount to the charity will be given from the commission amount you have earned.');?></p>
					<?php else:?>
						<p><?php echo __l('the amount to the charity will be given from admin commission amount. Your profit wont be affected.');?></p>
					<?php endif;?>
				</span>
				<?php if(Configure::read('charity.who_will_choose') == ConstCharityWhoWillChoose::CompanyUser): ?>
					<?php echo $this->Form->input('charity_id', array('empty' =>__l('Please Select'))); ?>
				<?php endif; ?>
				<?php echo $this->Form->input('charity_percentage', array('label' => __l('Charity Percentage (%)'),'info' =>__l('Percentage of amount you would to like to give for charity.'))); ?>
				<?php if(Configure::read('charity.who_will_pay') == ConstCharityWhoWillPay::Admin || Configure::read('charity.who_will_pay') == ConstCharityWhoWillPay::AdminCompanyUser): ?>
				<div class="page-info">
				<?php
					echo __l('Admin also pay same percentage of amount from his commission');
				 ?>
				 </div>
				 <?php endif; ?>
	
		  <?php endif; ?>      
         <h3><?php echo __l('Coupon'); ?></h3>
        <div class="review-block">
    		<?php
    			echo $this->Form->input('coupon_highlights', array('label' => __l('Coupon Highlights'),'type' =>'textarea', 'class' => 'js-editor'));
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
		  <?php echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'index', 'admin' => true), array('class' => 'cancel-button'));?>
       </div>
    </div>
<?php echo $this->Form->end();
?>
</div>