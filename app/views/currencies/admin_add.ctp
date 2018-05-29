<?php /* SVN: $Id: $ */ ?>
<div class="currencies form">
<?php echo $this->Form->create('Currency', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('name', array('label' => __l('Name')));
		echo $this->Form->input('code', array('label' => __l('Code')));
		echo $this->Form->input('symbol', array('label' => __l('Symbol')));		
		echo $this->Form->input('decimals', array('label' => __l('Decimals')));
		echo $this->Form->input('dec_point', array('label' => __l('Decimal Point')));
		echo $this->Form->input('thousands_sep', array('label' => __l('Thousand Separate')));		
		echo $this->Form->input('is_enabled',array('label' =>__l('Enabled?')));
		echo $this->Form->input('is_paypal_supported');
	?>
	</fieldset>
    <div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Add'));?>
	</div>
</div>
