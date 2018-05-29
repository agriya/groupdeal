<?php /* SVN: $Id: admin_currency_update.ctp 73588 2011-12-05 05:49:29Z beautlin_108ac10 $ */ ?>
<div class="page-info">
	<?php if(Configure::read('site.is_auto_currency_updation') == 1):?>
		<?php echo __l('Automatic Currency Conversion Updation is currently enabled. You can disable it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 4), array('target' => '_blank')).' '.__l('page if you prefer to manually update the values here.');?>
	<?php else:?>
		<?php echo __l('Automatic Currency Conversion Updation is currently disabled. You can enable it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 4), array('target' => '_blank')).' '.__l('page. When you enabled automatic update, you don\'t have to manually update the values here.');?>
	<?php endif;?>
</div>
<div class="currencies form js-response">
<?php echo $this->Form->create('Currency', array('action' => 'admin_currency_update', 'class' => 'normal'));?>
	<?php
		echo $this->Form->input('currency_id', array('label' =>__l('Base Currency'), 'class' => 'js-onchange-currency'));
	?>
	<div class="overflow-block js-currency-input">
         <table class="list">
			<tr>     	
				<th><?php echo __l('Conversion');?></th>
				<th><?php echo __l('Rate');?></th>
			</tr>
			
			<?php  for($i=0; $i< count($this->request->data['CurrencyConversion']); $i++) {?>
				<tr>
					<td> <?php echo $this->request->data['CurrencyConversion'][$i]['code']; ?></td>
					<td><?php
						$read_only = '';
								if($currencies[$this->request->data['Currency']['currency_id']] == $this->request->data['CurrencyConversion'][$i]['code']){
							$read_only = 'readonly';
						}
						echo $this->Form->input('CurrencyConversion.'.$i.'.id', array('label' => false, 'type' => 'hidden'));
						echo $this->Form->input('CurrencyConversion.'.$i.'.code', array('label' => false,  'type' => 'hidden'));
						echo $this->Form->input('CurrencyConversion.'.$i.'.rate', array('label' => false)); 
						?>
					</td>
				</tr>
			<?php } ?>						
		</table>
		</div>
    <div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Update'));?>
	</div>
</div>
