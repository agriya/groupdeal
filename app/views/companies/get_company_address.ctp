<?php /* SVN: $Id: edit.ctp 47854 2011-03-23 11:24:58Z aravindan_111act10 $ */ ?>
<?php if(!empty($branch_addresses)){ ?>
<span class="info redeem-info sfont"> <?php echo __l('Redeem only at'); ?></span>
<div class="clearfix redeem-input-block">
<?php 
echo $this->Form->input('CompanyAddressesDeal.company_address_id',array('label' =>false,'multiple'=>'checkbox', 'checked' => true, 'options' => $branch_addresses));
?>
</div>
<?php } else{ ?> 
	<?php echo "No Branch"; ?>
 <?php } ?>
<div class="hide js-deal-company-main-address">
<?php echo $this->Html->cText($main_address['Company']['name']); ?>
<address>
	<?php echo $this->Html->cText($main_address['Company']['address2']);?>
	</address>
</div>