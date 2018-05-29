<div class="clearfix">
	<table class="list">
		<tr>        
			<th class="dc" colspan="2"></th>   
			<th class="dc"><?php echo __l('Min');?></th> 
			<th class="dc"><?php echo __l('Max');?></th>
			<th class="dc"><?php echo __l('Sum');?></th>
		</tr>
		<tr>
			<td class="dr" rowspan="4"><?php echo __l('Offered');?></td>
			<td class="dr"><?php echo __l('Price').' ('.Configure::read('site.currency').')';?></td>
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['price']['min']);?></td>
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['price']['max']);?></td>	
			<td class="dr" rowspan="4"></td>
		</tr>
		<tr>			
			<td class="dr"><?php echo __l('Original Price').' ('.Configure::read('site.currency').')';?></td>
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['original_price']['min']);?></td>
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['original_price']['max']);?></td>			
		</tr>
		<tr>			
			<td class="dr"><?php echo __l('Savings').' ('.Configure::read('site.currency').')';?></td>
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['savings']['min']);?></td>
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['savings']['max']);?></td>			
		</tr>
		<tr>			
			<td class="dr"><?php echo __l('% Off');?></td>
			<td class="dr"><?php echo $this->Html->cFloat($deals_stats['off']['min']);?></td>
			<td class="dr"><?php echo $this->Html->cFloat($deals_stats['off']['max']);?></td>			
		</tr>
		<tr>			
			<td class="dr" colspan="2"><?php echo __l('Quantities Sold');?></td>
			<td class="dr"><?php echo $this->Html->cInt($deals_stats['sold_quantity']['min']);?></td>
			<td class="dr"><?php echo $this->Html->cInt($deals_stats['sold_quantity']['max']);?></td>	
			<td class="dr"><?php echo $this->Html->cInt($deals_stats['sold_quantity']['sum']);?></td>	
		</tr>
		<tr class="total-block">			
			<td class="dr" colspan="2"><?php echo __l('Total Revenue').' ('.Configure::read('site.currency').')';?></td>
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['total_revenue']['min']);?></td>
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['total_revenue']['max']);?></td>	
			<td class="dr"><?php echo $this->Html->cCurrency($deals_stats['total_revenue']['sum']);?></td>	
		</tr>
		<tr>			
			<td class="dr" colspan="2"><?php echo __l('Average Sold Price');?></td>
			<td class="dr" colspan="3"><?php echo $this->Html->cFloat(!empty($deals_stats['sold_quantity']['sum']) ? ($deals_stats['total_revenue']['sum']/$deals_stats['sold_quantity']['sum']): 0);?></td>			
		</tr>
	</table>				
</div>