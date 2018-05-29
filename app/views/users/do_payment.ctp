<?php /* SVN: $Id: do_payment.ctp 79503 2012-09-26 14:06:48Z rajeshkhanna_146ac10 $ */ ?>
<div class="payment-block clearfix">
<h2><?php echo __l('Add amount to wallet');?></h2>
    <?php /*?><div class="wallet-amount-block textb">
		<?php 
			$currency ='';
			$currency_code ='';
			if($action == 'pagseguro'):
				$currency ='R$';			
				$currency_code ='BRL';
			endif;
		?>
        <?php echo __l('Amount: '); ?><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($amount,'span','',$currency_code), $currency); ?>
    </div><?php */?>
<?php 
	if($action == 'pagseguro'):
?>	
		<h2 class="paypal-title textb"><?php echo __l('Redirecting you to Pagseguro');?></h2>
		<?php echo __l('If your browser doesn\'t redirect you please '); ?>
<?php	
		$this->pagSeguro->form($gateway_options);
		$this->pagSeguro->data(); 
?>
        <?php $this->pagSeguro->submit($gateway_options); ?>
        <?php echo __l('to continue '); ?>
<?php
	else:
?>
		<h2 class="paypal-title textb"><?php echo __l('Redirecting you to PayPal');?></h2>
		<?php echo __l('If your browser doesn\'t redirect you please '); ?>
<?php        
		$this->Gateway->$action($gateway_options);
?>
		<?php echo __l('to continue '); ?>		
<?php		
	endif;
?>
</div>