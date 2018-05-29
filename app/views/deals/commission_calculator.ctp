<?php /* SVN: $Id: commission_calculator.ctp 69751 2011-10-29 10:07:36Z sakthivel_135at10 $ */ ?>
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
            	if(empty($this->request->data['Deal']['user_id'])):
            		//echo $this->Form->create('Deal', array('action'=> 'commission_calculator', 'class' => 'normal'));
            	endif;
            ?>
        	<?php
        		if(Configure::read('site.currency_symbol_place') == 'left'):
        			$currecncy_place = 'between';
        		else:
        			$currecncy_place = 'after';
        		endif;
        	?>
        	<?php
        		echo $this->Form->input('calculator_discounted_price',array('div'=>'input discount-price clearfix text','label'=>__l('Discounted Price'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
        		echo $this->Form->input('calculator_bonus_amount', array('div'=>'input text clearfix','label'=> __l('Bonus Amount'), 'value' => '0.00'));
        	?>
        	<?php
        		echo $this->Form->input('calculator_commission_percentage', array('div'=>'input text clearfix', 'label'=>__l('Commission (%)')));
        		echo $this->Form->input('calculator_min_limit', array('div'=>'input text clearfix','label'=>__l('No of Buyers')));
        	?>
            <?php
            	if(empty($this->request->data['Deal']['user_id'])):
            		//echo $this->Form->end(__l('Calculate'));
            	endif;
            ?>
        </div>

<dl class="budget-list list clearfix">
	<dt><?php echo __l('Total Purchased Amount: '); ?></dt>
	<dd><?php echo Configure::read('site.currency'); ?><span class="js-calculator-purchased"><?php echo !empty($this->request->data['Deal']['calculator_total_purchased_amount']) ? $this->request->data['Deal']['calculator_total_purchased_amount'] : '0.00'; ?></span></dd>
	<dt><?php echo __l('Total Commission Amount: '); ?></dt>
	<dd><?php echo Configure::read('site.currency'); ?><span class="js-calculator-commission"><?php echo !empty($this->request->data['Deal']['calculator_total_commission_amount']) ? $this->request->data['Deal']['calculator_total_commission_amount'] : '0.00'; ?></span></dd>
	<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):?>
		<dt><?php echo __l('Net Profit: '); ?></dt>
		<dd><?php echo Configure::read('site.currency'); ?><span class="js-calculator-net-profit"><?php echo !empty($this->request->data['Deal']['calculator_net_profit']) ? $this->request->data['Deal']['calculator_net_profit'] : '0.00'; ?></span></dd>
	<?php endif; ?>
</dl>
