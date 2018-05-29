<?php /* SVN: $Id: authorize_net.ctp 8007 2010-06-12 12:09:11Z shankari_062at09 $ */ ?>
<div class="UserPaymentProfile  form">
	<h2><?php echo __l('Edit Credit Card'); ?></h2>
	<?php echo $this->Form->create('UserPaymentProfile', array('action' => 'edit', 'class' => 'normal user-payment-form')); ?>
	<div class=" clearfix billing-info-block">
	<div class="billing-left grid_left">
	<h3><?php echo __l('Billing Information'); ?></h3>
	<?php		
		echo $this->Form->input('UserPaymentProfile.id', array('type' => 'hidden'));
		echo $this->Form->input('UserPaymentProfile.firstName', array('label' => __l('First name')));
		echo $this->Form->input('UserPaymentProfile.lastName', array('label' => __l('Last name')));
		echo $this->Form->input('UserPaymentProfile.creditCardType', array('label' => __l('Card Type'), 'type' => 'select', 'options' => $credit_card_types, 'info' =>__l('For security reason, we are not saved the credit card details. You have to specify again.') ));
		echo $this->Form->input('UserPaymentProfile.creditCardNumber', array('AUTOCOMPLETE' => 'OFF', 'type' => 'hidden', 'label' => __l('Card Number'), 'info' =>__l('For security reason, we are not saved the credit card details. You have to specify again.')));
	?>
	<div class="input date">
		<label><?php echo __l('Expiration Date'); ?> </label>
		<?php 
			echo $this->Form->month('UserPaymentProfile.expDateMonth', array('value' => date('m')));
			echo $this->Form->year('UserPaymentProfile.expDateYear', date('Y'), date('Y')+25, array('value' => date('Y')+2, 'orderYear' => 'asc', 'info' =>__l('For security reason, we are not saved the credit card details. You have to specify again.')));
		?>
		<span class="info sfont"><?php echo __l('For security reason, we are not saved the credit card details. You have to specify again.');?></span>
	</div>
	<?php echo $this->Form->input('UserPaymentProfile.cvv2Number', array('AUTOCOMPLETE' => 'OFF', 'type' => 'hidden', 'maxlength' =>'4', 'label' => __l('Card Verification Number'), 'info' =>__l('For security reason, we are not saved the credit card details. You have to specify again.'))); ?> 
	</div>
	<div class="billing-right grid_left">
    <h3><?php echo __l('Billing Address'); ?></h3>
	<?php
		echo $this->Form->input('UserPaymentProfile.address', array('label' => __l('Billing address')));
		echo $this->Form->input('UserPaymentProfile.city', array('label' => __l('City')));
		echo $this->Form->input('State.name', array('label' => __l('State')));	
		echo $this->Form->input('UserPaymentProfile.zip', array('label' => __l('ZIP')));
		echo $this->Form->input('UserPaymentProfile.country', array('label' => __l('Country'), 'type' => 'select', 'options' => $countries, 'empty' => __l('Select Country')));		
	?>
	</div>
	</div>
	<div class="submit-block buy-submit-block clearfix">
		<?php echo $this->Form->submit(__l('Update')); ?>
		<div class="cancel-block">
    	   <?php echo $this->Html->link(__l('Cancel'), array('action'=>'index'), array('title' => __l('Cancel'),'class' => 'cancel-button js-inline-edit'));?>
    	</div>
	</div>
	<?php echo $this->Form->end();?>
</div>