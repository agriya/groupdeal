<?php /* SVN: $Id: add.ctp 51248 2011-04-22 11:37:47Z lakshmi_150act10 $ */ ?>
<div class="js-subdeal-delete-over-block js-deal-subdeal-available-over-block">
		<h2><?php echo __l('Deal Information'); ?></h2>
		<dl class="list clearfix">
			<dt><?php echo __l('Deal');?></dt>
				<dd>
					<?php echo $this->Html->cText($deal['Deal']['name']);?>
				</dd>
			<dt><?php echo __l('Current Deal Status');?></dt>
				<dd>
					<?php echo $this->Html->cText($deal['DealStatus']['name']);?>
				</dd>
			<dt><?php echo __l('No. of min. coupons');?></dt>
				<dd>
					<?php echo $this->Html->cText($deal['Deal']['min_limit']);?>
				</dd>
			<dt><?php echo __l('No. of max. coupons');?></dt>
				<dd>
					<?php echo $this->Html->cText($deal['Deal']['max_limit']);?>
				</dd>
			<dt><?php echo __l('Deal Lifetime');?></dt>
				<dd>
                    <p><?php echo __l('Created On').' '.$this->Html->cDateTime($deal['Deal']['created']);?></p>
    				<p><?php echo __l('Start(ed) On').' '.$this->Html->cDateTime($deal['Deal']['start_date']);?></p>
	    			<p><?php echo __l('End(ed) On').' '.$this->Html->cDateTime($deal['Deal']['end_date']);?></p>
                </dd>
          </dl>


<?php 	echo $this->Form->create('Deal', array('action' => 'subdeal_edit', 'class' => 'normal add-live-form'));
		echo $this->Form->input('Deal.main_deal_id',array('type' => 'hidden'));
		unset($this->request->data['Deal']['main_deal_id']);
		$count = 2;
		if(!empty($this->request->data['Deal'])){
			$count = count($this->request->data['Deal']);
		}
		for($i=0; $i< $count; $i++){	
?>
<?php if($i == 2){ ?>
	<div class="js-subdeal-addmore-deal">
<?php } ?>
   <div id="js-subdeal-<?php echo $i; ?>" class="js-subdeal-<?php echo $i; ?> {'available': '<?php echo (!empty($this->request->data['Deal'][$i]['id'])) ? $this->request->data['Deal'][$i]['id'] : 'Notpresent';?>', 'main_deal_id': '<?php echo (!empty($this->request->data['Deal'][$i]['id'])) ? $deal['Deal']['id'] : '';?>'}">    
       <fieldset class="form-block">
		<h3 class="genral"><?php echo __l('SubDeal').' #'.($i+1); ?></h3>
		<h3><?php echo __l('General'); ?></h3>
		<?php
			echo $this->Form->input('Deal.'.$i.'.id',array('type' => 'hidden'));
			echo $this->Form->input('Deal.'.$i.'.name',array('label' => __l('Name')));
		?>
				
			
						  <?php	echo $this->Form->input('Deal.'.$i.'.max_limit', array('label'=>__l('No. of max. coupons'), 'info' => __l('Maximum limit of coupons can be bought for this deal. Leave blank for no limit.'))); ?>
			
	
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
				<?php
					$class = "{ 'DealOriginalPrice': 'Deal".$i."OriginalPrice', 'DealDiscountPercentage': 'Deal".$i."DiscountPercentage', 'DealDiscountAmount': 'Deal".$i."DiscountAmount', 'DealDiscountedPrice': 'Deal".$i."DiscountedPrice', 'DealSavings': 'Deal".$i."Savings', 'DealCalculatorDiscountedPrice': 'Deal".$i."CalculatorDiscountedPrice', 'DealCalculatorBonusAmount': 'Deal".$i."CalculatorBonusAmount', 'DealCalculatorCommissionPercentage': 'Deal".$i."CalculatorCommissionPercentage' , 'DealCalculatorMinLimit': 'Deal".$i."CalculatorMinLimit', 'DealBonusAmount': 'Deal".$i."BonusAmount', 'DealCommissionPercentage': 'Deal".$i."CommissionPercentage', 'ivalue': '".$i."'}";
                    ?>
                     <div class="deal-discount-form-block1 discount-form-block clearfix">
                    <?php
                	echo $this->Form->input('Deal.'.$i.'.original_price',array('div'=>'input text grid_3 omega alpha','label' => __l('Original price'),'class' => "js-price js-sub-deal-price ".$class , $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>')); ?>
   					<?php echo $this->Form->input('Deal.'.$i.'.discount_percentage', array('div'=>'input text grid_3 omega alpha','class' => "js-sub-deal-price ".$class ,'label' => __l('Discount (%)')));  ?>
					<?php echo $this->Form->input('Deal.'.$i.'.discount_amount', array('div'=>'input text grid_4 omega alpha','class' => "js-sub-deal-amount ".$class ,'label' => __l('Discount Amount'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>')); ?>
					<?php echo $this->Form->input('Deal.'.$i.'.savings', array('div'=>'input text grid_4 omega alpha','type'=>'hidden',  'label' => __l('Savings for user'),  'readonly' => 'readonly', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
					echo $this->Form->input('Deal.'.$i.'.discounted_price', array('div'=>'input disable-field text required grid_4 omega alpha','label' => __l('Discounted price for user'),'type'=>'text', 'readonly' => 'readonly', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
				?>
				</div>
				 </div>
			     </div>
				<!-- ADVANCE/PARTIALLY PAYMENT -->
					<?php $is_adv_enabled = Configure::read('deal.is_enable_payment_advance'); ?>
					<?php if(Configure::read('deal.is_enable_payment_advance')): ?>
						<?php echo $this->Form->input('Deal.'.$i.'.is_enable_payment_advance', array('type' => 'checkbox', 'class' => 'js-enable-advance-payment {selected_container:"'.$i.'"}', 'label' => __l('Allow users to make partially payments?'), 'info' => __l('If checked, user can make a partial payment now and pay the remaining at the redeem location.')));?>
						<div class="js-advance-payment-box<?php echo '-'.$i;?>  <?php echo (!empty($this->request->data['Deal'][$i]['is_enable_payment_advance']) ? '' : 'hide');?>">
							<?php
								echo $this->Form->input('Deal.'.$i.'.pay_in_advance',array('label' => __l('Advance amount'), 'class' => 'js-pay-in-advance {selected_container:"'.$i.'"}', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
								echo $this->Form->input('Deal.'.$i.'.payment_remaining',array('label' => __l('Pending amount'), 'type' => 'hidden', 'class' => '', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));									
							?>
							<dl class="result-list clearfix">
								<dt><?php echo __l('Pay in Advance').'('.Configure::read('site.currency').'):  '; ?></dt>
									<dd>
										<span id="js-pay_in_advance<?php echo '-'.$i;?>"><?php echo (!empty($this->request->data['Deal'][$i]['pay_in_advance']) ? $this->request->data['Deal'][$i]['pay_in_advance'] : '0');?></span>
									</dd>
								<dt><?php echo __l('Pay remaining').'('.Configure::read('site.currency').'):  '; ?></dt>
									<dd>
										<span id="js-payment_remaining<?php echo '-'.$i;?>"><?php echo (!empty($this->request->data['Deal'][$i]['payment_remaining']) ? $this->request->data['Deal'][$i]['payment_remaining'] : '0');?></span>
									</dd>
							</dl>
						</div>						
					<?php endif; ?>
                <div class="page-info">
				<?php
					echo __l('When you want to add as a free deal, just give 100% discount for this deal');
				 ?>
			     </div>

  		<h3><?php echo __l('Commission'); ?></h3>
        <div class="clearfix">
  			<div class="deal-discount-form-block-left">
			 <div class="page-info">
				<?php
					echo __l('Total Commission Amount = Bonus Amount + ((Discounted Price * Number of Buyers) * Commission Percentage/100))');
				 ?>
			</div>
			<div class="clearfix">
		
			<?php
			echo $this->Form->input('Deal.'.$i.'.bonus_amount', array('class' => "js-sub-deal-bonus-amount ".$class ,'label' => __l('Bonus Amount'),$currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
            ?>
           <span class="info sfont"> <?php echo __l('This is the flat fee that the merchant will pay for the whole deal.');?></span>
           <?php if(($this->Auth->user('user_type_id') != ConstUserTypes::Admin) && (Configure::read('deal.is_admin_enable_commission')) && Configure::read('deal.commission_amount_type') == 'fixed'):
					echo $this->Form->input('Deal.'.$i.'.commission_percentage', array('class' => "js-sub-deal-bonus-amount ".$class, 'readonly' =>'readonly', 'info' => __l('This is the commission that merchant will pay for the whole deal in percentage.'), 'label' => __l('Commission (%)')));
				else:
					 echo $this->Form->input('Deal.'.$i.'.commission_percentage', array('class' => "js-sub-deal-bonus-amount ".$class, 'info' => __l('This is the commission that merchant will pay for the whole deal in percentage.'), 'label' => __l('Commission (%)')));
				 endif; 
				?>
            </div>
            </div>
			<div class="deal-discount-form-block-right">
				<?php echo $this->element('../deals/subdeal_commission_calculator', array('i'=> $i, 'class' => $class, 'cache' => array('config' => 'site_element_cache', 'key' => $this->Auth->user('id')))); ?>
			
			</div>
			</div>
      
</fieldset>	
	</div>
<?php 
		}
?>
<?php if($count >= 2){ ?>
	<?php if($i == 2){ ?>
		<div class="js-subdeal-addmore-deal">
	<?php } ?> 
	</div>   
<?php } ?> 
<div class="more-info-block no-mar sub-deal-more-info grid_right">
<span class="js-subdeal-add add-more-deal {'id': '<?php echo $count; ?>'}"> Add more </span>
<span class="js-subdeal-delete delete-deal {'id': '<?php echo $count; ?>'}"> Delete </span>
</div>
<div class="submit-block clearfix">
<?php		
	echo $this->Form->submit(__l('Update'), array('class' => '')); ?>
	</div>
<?php	echo $this->Form->end();
?>
</div>