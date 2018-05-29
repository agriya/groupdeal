<?php /* SVN: $Id: admin_deduct_amount.ctp 3 2010-04-07 06:03:46Z siva_063at09 $ */ ?>
<div class="transactions form js-response js-responses">
<?php echo $this->Form->create('Company', array('action' => 'deductamount', 'admin' => true, 	'class' => 'normal js-ajax-form'));?>
	<fieldset>
 		<h2><?php echo __l('Deduct Amount');?></h2>
	<?php
		foreach($companies as  $company)
		{
			?>
			<h3><?php echo $this->Html->cText($company['Company']['name']); ?></h3>
			<?php echo __l('Available Balance: ').$this->Html->siteCurrencyFormat($this->Html->cCurrency($company['User']['available_balance_amount']));?>
            <?php if($company['User']['available_balance_amount'] > 0): ?>
				<?php
					if(Configure::read('site.currency_symbol_place') == 'left'):
						$currecncy_place = 'between';
					else:
						$currecncy_place = 'after';
					endif;	
				?>
				<div class="required "><?php echo $this->Form->input('Company.'.$company['Company']['id'].'.amount', array($currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>', 'label' => __l('Amount')));?>
				</div>
                <?php echo $this->Form->input('Company.'.$company['Company']['id'].'.user_id', array('type' => 'hidden', 'value' => $company['Company']['user_id']));?>
                <?php echo $this->Form->input('Company.'.$company['Company']['id'].'.description', array('type' => 'textarea', 'label' => __l('Description')));?>
             <?php endif; ?>
            <?php
		}
	?>
	</fieldset> 
		<div class="submit-block clearfix">
<?php 
	if(empty($company['User']['available_balance_amount']) || $company['User']['available_balance_amount'] == '0.00'):
		echo $this->Form->submit(__l('Update'),array('disabled' => true));
	else:
		echo $this->Form->submit(__l('Update'));
	endif;
?>
<div class="cancel-block">
	<?php echo $this->Html->link(__l('Cancel'), array('controller' => 'companies', 'action' => 'index'), array('class' => 'js-deduct-disable', 'title' => __l('Cancel'), 'escape' => false));?>
</div>
</div>
<?php echo $this->Form->end();?>
</div>
