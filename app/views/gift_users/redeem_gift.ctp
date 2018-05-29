<?php /* SVN: $Id: redeem_gift.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<div class="giftUsers form js-responses">
        <?php echo $this->Form->create('GiftUser', array('action' => 'redeem_gift','class' => "normal js-ajax-form"));?>
		<div class="clearfix">
        	<div class="gift-form">
        	<?php
                echo $this->Form->input('coupon_code',array('label' => __l('Redemption Code')));
                echo $this->Form->input('submit',array('type' => 'hidden'));
			?>
				</div>
				</div>
			<div class="submit-block clearfix">
                 <?php echo $this->Form->submit(__l('Redeem'),array('name' => 'data[GiftUser][submit]'));?>
                
				 <div class="cancel-block">
					<?php echo $this->Html->link(__l('Cancel'), '#', array('class' => "cancel-button js-toggle-show {'container':'js-redeem-form'}"));?>
                 </div>
			</div>
		
			<?php echo $this->Form->end();?>
        
			
</div>
