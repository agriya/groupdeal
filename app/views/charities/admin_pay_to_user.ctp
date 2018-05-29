<?php /* SVN: $Id: $ */ ?>
<?php 
	if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message');
	endif;
?>
<div class="charities index js-response">
<?php echo $this->Form->create('Charity' , array('class' => 'normal','action' => 'pay_to_user')); ?>
<div class="overflow-block">
<table class="list">
    <tr>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Name'),'name');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Available Amount'),'available_amount');?></div></th>
		<th><div class="js-pagination"><?php echo __l('Pay Amount?');?></div></th>
		<th class="dl"><?php echo __l('Transfer Account');?></th>
		<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Paid Amount'),'paid_amount');?></div></th>
    </tr>
<?php
if (!empty($charities)):

$i = 0;
foreach ($charities as $charity):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	if($charity['Charity']['is_active']):
		$status_class= 'js-checkbox-active';
	else:
		$status_class= 'js-checkbox-inactive';
	endif;
?>
	<tr<?php echo $class;?>>
		<td class="dl">
       <?php echo $this->Html->cText($charity['Charity']['name']);?>
		</td>
		<td class="dr"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($charity['Charity']['available_amount'])); ?></td>
		<td class="dl"><?php
			 echo $this->Form->input('CharityCashWithdrawal.'.($i-1).'.charity_id', array('type' => 'hidden', 'label' => false)); 
			 echo $this->Form->input('CharityCashWithdrawal.'.($i-1).'.amount', array('type' => 'text', 'label' => false)); 
		 ?></td>
		<td class="dl"><?php 
				 echo $this->Form->input('CharityCashWithdrawal.'.($i-1).'.gateway', array('type' => 'select', 'options' => $charity['paymentways'], 'label' => false, 'class' => "js-payment-gateway_select {container:'js-info-".($i-1)."-container'}")); ?>
            	<div class="<?php echo "js-info-".($i-1)."-container"; ?>">
            	<?php echo $this->Form->input('CharityCashWithdrawal.'.($i-1).'.info',array('type' => 'textarea', 'label' => false, 'info' => 'Info for Paid')); ?>
                </div>
			 
		</td>
		<td class="dr"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($charity['Charity']['paid_amount'])); ?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="14" class="notice"><?php echo __l('No Charities available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
	<?php if (!empty($charities)) {?>
    	<div class="pay-submit-block clearfix">
			<?php echo $this->Form->submit(__l('Proceed'));  ?>
			</div>
		<?php echo $this->Form->end(); ?>
	<?php }?>
</div>
