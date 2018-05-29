<?php /* SVN: $Id: $ */ ?>
<div class="currencyConversionHistories index">
	<div class="page-info">
		<?php echo __l('Currency Conversion History Updation is currently enabled. You can disable or configure it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 4), array('target' => '_blank')). __l(' page');?>
	</div>
<?php echo $this->element('paging_counter');?>
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('currency_id');?></th>
			<th><?php echo $this->Paginator->sort('converted_currency');?></th>
			<th><?php echo $this->Paginator->sort('rate_before_change');?></th>
			<th><?php echo $this->Paginator->sort('rate');?></th>
		</tr>
	<?php
if (!empty($currencyConversionHistories)):
	$i = 0;
	foreach ($currencyConversionHistories as $currencyConversionHistory):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
	   <td  class="actions">
		<div class="action-block">
			<span class="action-information-block">
				<span class="action-left-block">&nbsp;
				</span>
					<span class="action-center-block">
						<span class="action-info">
							<?php echo __l('Action');?>
						 </span>
					</span>
				</span>
				<div class="action-inner-block">
				<div class="action-inner-left-block">
					<ul class="action-link clearfix">
						 <li> <?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $currencyConversionHistory['CurrencyConversionHistory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>
		</td>		
		<td class="dc">
			<?php echo $this->Html->cDateTime($currencyConversionHistory['CurrencyConversionHistory']['created']); ?>
		</td>
		<td class="">
			<?php echo $this->Html->cText($currencyConversionHistory['CurrencyConversion']['Currency']['code']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cText($currencyConversionHistory['CurrencyConversion']['ConvertedCurrency']['code']); ?>			
		</td>
		<td class="dc">
			<?php echo $this->Html->cFloat($currencyConversionHistory['CurrencyConversionHistory']['rate_before_change']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cFloat($currencyConversionHistory['CurrencyConversionHistory']['rate']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="9" class="notice"><?php echo __l('No Currency Conversion Histories available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($currencyConversionHistories)) {
    echo $this->element('paging_links');
}
?>
</div>
