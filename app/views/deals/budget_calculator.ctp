<?php /* SVN: $Id: commission_calculator.ctp 47780 2011-03-23 07:04:34Z lakshmi_150act10 $ */ ?>
<h3 class="budget-calculator"><?php echo __l('Budget Calculator'); ?></h3>
<?php
		if(Configure::read('site.currency_symbol_place') == 'left'):
			$currecncy_place = 'between';
		else:
			$currecncy_place = 'after';
		endif;
	?>
		<div class=" deal-discount-form-block1 discount-form-block clearfix">
	<?php
	    echo $this->Form->input('budget_amt',array('label'=>__l('Discount Budget Amout'),'div'=>'input text clearfix required ', $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
	    echo $this->Form->input('original_amt',array('class' => 'js-deal-original-price','label'=>__l('Original Price'),'div'=>'input clearfix text',$currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>' ));
		echo $this->Form->input('discount_amt',array('class' => 'js-deal-discount','label'=>__l('Discount Price'),'div'=>'input clearfix text',$currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
	?>

<dl class="budget-list list clearfix">
	<dt><?php echo __l('No of Max Coupons').':  '; ?></dt>
	<dd><span class="js-budget-calculator"><?php echo (!empty($this->request->data['Deal']['calculator_qty'])) ? $this->Html->cInt($this->request->data['Deal']['calculator_qty']) : 0; ?></span></dd>
</dl>
</div>
