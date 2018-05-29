<?php /* SVN: $Id: $ */ ?>
<div class="currencies form">
<?php echo $this->Form->create('Currency', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name', array('label' => __l('Name')));
		echo $this->Form->input('code', array('label' => __l('Code')));
		echo $this->Form->input('symbol', array('label' => __l('Symbol')));		
		echo $this->Form->input('decimals', array('label' => __l('Decimals')));
		echo $this->Form->input('dec_point', array('label' => __l('Decimal Point')));
		echo $this->Form->input('thousands_sep', array('label' => __l('Thousand Separate')));		
		$_currencies = Cache::read('site_currencies');
		$selected_currency = $_currencies[Configure::read('site.currency_id')];
		$c_selected_currency = $_currencies[Configure::read('site.paypal_currency_converted_id')];
		if(($selected_currency['Currency']['id'] != $this->request->data['Currency']['id']) && ($c_selected_currency['Currency']['id'] != $this->request->data['Currency']['id'])):
			echo $this->Form->input('is_enabled',array('label' =>__l('Enabled?')));
			echo $this->Form->input('is_paypal_supported');		
		endif;
	?>
	</fieldset>
    <div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Update'));?>
	</div>
</div>
