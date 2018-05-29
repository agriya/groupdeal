<?php /* SVN: $Id: do_payment.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<h2><?php echo sprintf(__l('Buy %s Deal'),$deal['Deal']['name']);?></h2>
    <div class="wallet-amount-block textb">
		<?php echo __l('Amount: '); ?><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($amount)); ?>
    </div>

<?php
	if($action == 'pagseguro'):
		$this->PagSeguro->form($gateway_options);
		$this->PagSeguro->data();
?>
	<div class="submit-block clearfix">
		<?php $this->PagSeguro->submit($gateway_options); ?>
	</div>
<?php
	else:
		$this->Gateway->$action($gateway_options);
	endif;
?>