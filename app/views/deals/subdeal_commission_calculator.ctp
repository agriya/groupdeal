<?php /* SVN: $Id: commission_calculator.ctp 47780 2011-03-23 07:04:34Z lakshmi_150act10 $ */ ?>
 <h3 class="budget-calculator"><?php echo __l('Commission Calculator'); ?></h3>
	<?php
		if(Configure::read('site.currency_symbol_place') == 'left'):
			$currecncy_place = 'between';
		else:
			$currecncy_place = 'after';
		endif;
	?>
	<div class=" deal-discount-form-block1 discount-form-block clearfix">
        	<?php
        		echo $this->Form->input('Deal.'.$i.'.calculator_discounted_price',array('div'=>'input clearfix discount-price text','class' => "js-sub-deal-calculator ".$class, 'label'=>__l('Discounted price'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
        		echo $this->Form->input('Deal.'.$i.'.calculator_bonus_amount', array('div'=>'input  clearfix text','class' => "js-sub-deal-calculator ".$class, 'label'=> __l('Bonus Amount'), 'value' => '0.00'));
        	?>
           	<?php
        		echo $this->Form->input('Deal.'.$i.'.calculator_commission_percentage', array('div'=>'input clearfix text','class' => "js-sub-deal-calculator ".$class, 'label'=>__l('Commission (%)')));
        		echo $this->Form->input('Deal.'.$i.'.calculator_min_limit', array('div'=>'input clearfix text','class' => "js-sub-deal-calculator ".$class, 'label'=>__l('No. of buyers')));
        	?>
        </div>

    <dl class="budget-list list clearfix">
    	<dt><?php echo __l('Total Purchased Amount: '); ?></dt>
    	<dd><span class="js-calculator-purchased<?php echo $i?>"><?php echo $this->Html->siteCurrencyFormat((!empty($this->request->data['Deal']['calculator_total_purchased_amount'])) ? $this->request->data['Deal']['calculator_total_purchased_amount'] : 0); ?></span></dd>
    	<dt><?php echo __l('Total Commission Amount: '); ?></dt>
    	<dd><span class="js-calculator-commission<?php echo $i?>"><?php echo $this->Html->siteCurrencyFormat((!empty($this->request->data['Deal']['calculator_total_commission_amount'])) ? $this->request->data['Deal']['calculator_total_commission_amount'] : 0); ?></span></dd>
    	<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):?>
    		<dt><?php echo __l('Net Profit: '); ?></dt>
    		<dd><span class="js-calculator-net-profit<?php echo $i?>"><?php echo $this->Html->siteCurrencyFormat((!empty($this->request->data['Deal']['calculator_net_profit'])) ? $this->request->data['Deal']['calculator_net_profit'] : 0); ?></span></dd>
    	<?php endif; ?>
    </dl>
