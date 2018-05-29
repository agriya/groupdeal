<div class="clearfix">	 
  <h2>Stats on <?php echo $deal['Deal']['name'];?></h2>
	<div class="clearfix">
	<table class="list">
		<tr>        
			<th class="dc" colspan="2"><?php echo __l('Purchases');?></th>   			
		</tr>
		<tr>
			<td class="dr"><?php echo __l('Total Sold');?></td>			
			<td class="dr"><?php echo $this->Html->cInt($deal_stats['coupons']);?></td>
		</tr>
		<tr>
			<td class="dr"><?php echo __l('Bought as Gift');?></td>			
			<td class="dr"><?php echo $this->Html->cInt($deal_stats['coupon_as_gift']);?></td>
		</tr>
		<tr>
			<td class="dr"><?php echo __l('Total Redeemed');?></td>			
			<td class="dr"><?php echo $this->Html->cInt($deal_stats['redeemed']);?></td>
		</tr>
	</table>				
</div>
   <?php if(!empty($chart_quantity_sold)): ?>
		<div class="js-load-column-chart chart-full-section {'data_container':'deal_coupon_sold_column_data', 'chart_container':'deal_coupon_sold_column_chart', 'chart_title':'<?php echo __l('Quantities Sold') ;?>', 'chart_y_title': '<?php echo __l('Quantity');?>', 'series_type':'line'}">
			<div id="deal_coupon_sold_column_chart" class="deal-stats-user-chart"></div>
			<div class="hide">
				<table id="deal_coupon_sold_column_data" class="list">
				<tbody>
					<?php foreach($chart_quantity_sold as $data): ?>
					<tr>
					   <th><?php echo $data['display']; ?></th>
					   <td><?php echo $data['quantity']; ?></td>
					</tr>	
					<?php endforeach; ?>    
				</tbody>				
				</table>
			</div>
		</div>		
		<h3><?php echo __l('Purchase Demographics'); ?></h3>
	<?php endif; ?>		
    <div class="clearfix"> 
	<?php echo $this->element('chart-user_demographics', array('chart_y_title'=> __l('Purchased Users'), 'user_type_id' => 1)); ?>
    </div>
	<?php if(!empty($deal['DealUser'])): ?>
	<div class="js-deal-purchase-map clearfix">
		<h3><?php echo __l('Purchase Locations'); ?></h3>
		<div id="js-deal-purchase-location-map"  class="deal-purchased-user-map">
		
		</div>		
		<div class="hide">
			<table id="deal_sold_location_data" class="list">
			<tbody>
				<?php foreach($deal['DealUser'] as $dealUser): ?>
				<tr>
				   <th><?php echo $dealUser['latitude']; ?></th>
				   <td><?php echo $dealUser['longitude']; ?></td>
				</tr>	
				<?php endforeach; ?>    
			</tbody>				
			</table>
		</div>
	</div>
	<?php endif; ?>	
</div>