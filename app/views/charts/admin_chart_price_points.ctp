<div class="clearfix">
	<table class="list">
		<tr>        
			<th class="dc" rowspan="2"><?php echo __l('Price Point');?></th>   
			<th class="dc" colspan="3"><?php echo __l('Total');?></th> 
			<th class="dc" colspan="4"><?php echo __l('Average');?></th>			
		</tr>
		<tr>
			<th class="dc"><?php echo __l('Revenue').' ('.Configure::read('site.currency').')';?></th>
			<th class="dc"><?php echo __l('# Deals');?></th>
			<th class="dc"><?php echo __l('# Coupons');?></th>
			<th class="dc"><?php echo __l('Coupons').'/'.__l('Deal');?></th>
			<th class="dc"><?php echo __l('Revenue').'/'.__l('Deal').' ('.Configure::read('site.currency').')';?></th>
			<th class="dc"><?php echo __l('Price'). '('.Configure::read('site.currency').')';?></th>
			<th class="dc"><?php echo __l('Discount'). '(%)';?></th>			
		</tr>
		<?php
		if (!empty($pricePoints)):

		$i = 0;
		
		foreach ($pricePoints as $pricePoint):
			
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
			<tr<?php echo $class;?>>       
				<td><?php echo $this->Html->cText($pricePoint['price_points']);?></td>
				<td class="dr"><?php echo $this->Html->cCurrency($pricePoint['revenue']);?></td>
				<td class="dr"><?php echo $this->Html->cInt($pricePoint['deals_count']);?></td>
				<td class="dr"><?php echo $this->Html->cInt($pricePoint['coupons_count']);?></td>
				<td class="dr"><?php echo $this->Html->cFloat($pricePoint['average_coupons_deal_count']);?></td>
				<td class="dr"><?php echo $this->Html->cFloat($pricePoint['average_revenue_deal_amoumt']);?></td>
				<td class="dr"><?php echo $this->Html->cCurrency($pricePoint['avg_discounted_price']);?></td>
				<td class="dr"><?php echo $this->Html->cFloat($pricePoint['avg_discount_percentage']);?></td>
			</tr>
		<?php
			endforeach;
		else:
		?>
			<tr>
				<td colspan="11" class="notice"><?php echo __l('No stats available');?></td>
			</tr>
		<?php
		endif;
		?>
	</table>
	<div class="clearfix">
		<div class="js-load-column-chart chart-half-section {'data_container':'total_revenue_column_data', 'chart_container':'total_revenue_column_chart', 'chart_title':'<?php echo __l('Total Revenue by Price Point') ;?>', 'chart_y_title': '<?php echo __l('Total Revenue');?>'}">
        <div class="dashboard-tl">
             <div class="dashboard-tr">
                 <div class="dashboard-tc">
                 </div>
             </div>
        </div>
     <div class="dashboard-cl">
         <div class="dashboard-cr">
         <div class="dashboard-cc clearfix">
         	<div id="total_revenue_column_chart" class="deal-price-point-chart"></div>
			<div class="hide">
				<table id="total_revenue_column_data" class="list">
				<tbody>
					<?php foreach($pricePoints as $pricePoint): ?>
					<tr>
					   <th><?php echo $pricePoint['price_points']; ?></th>
					   <td><?php echo $pricePoint['revenue']; ?></td>
					</tr>	
					<?php endforeach; ?>    
				</tbody>				
				</table>
			</div>
	   </div>
		</div>
		</div>
        <div class="dashboard-bl">
             <div class="dashboard-br">
                 <div class="dashboard-bc">
                 </div>
             </div>
         </div>
		</div>
		<div class="js-load-column-chart chart-half-section {'data_container':'total_coupon_column_data', 'chart_container':'total_coupon_column_chart', 'chart_title':'<?php echo __l('Total Sold Coupons by Price Point') ;?>', 'chart_y_title': '<?php echo __l('Total Sold Coupons');?>'}">
          <div class="dashboard-tl">
                     <div class="dashboard-tr">
                         <div class="dashboard-tc">
                         </div>
                 </div>
            </div>
         <div class="dashboard-cl">
             <div class="dashboard-cr">
             <div class="dashboard-cc clearfix">
        	   <div id="total_coupon_column_chart" class="deal-price-point-chart"></div>
    			<div class="hide">
    				<table id="total_coupon_column_data" class="list">
    				<tbody>
    					<?php foreach($pricePoints as $pricePoint): ?>
    					<tr>
    					   <th><?php echo $pricePoint['price_points']; ?></th>
    					   <td><?php echo $pricePoint['coupons_count']; ?></td>
    					</tr>
    					<?php endforeach; ?>
    				</tbody>
    				</table>
    			</div>
	   </div>
		</div>
		</div>
        <div class="dashboard-bl">
             <div class="dashboard-br">
                 <div class="dashboard-bc">
                 </div>
             </div>
         </div>

			
		</div>
		<div class="js-load-column-chart chart-half-section {'data_container':'total_avg_revenue_column_data', 'chart_container':'total_avg_revenue_column_chart', 'chart_title':'<?php echo __l('Avg Revenue per Deal by Price Point') ;?>', 'chart_y_title': '<?php echo __l('Avg Revenue per Deal');?>'}">
          <div class="dashboard-tl">
                     <div class="dashboard-tr">
                         <div class="dashboard-tc">
                         </div>
                 </div>
            </div>
         <div class="dashboard-cl">
             <div class="dashboard-cr">
             <div class="dashboard-cc clearfix">
        	<div id="total_avg_revenue_column_chart" class="deal-price-point-chart"></div>
			<div class="hide">
				<table id="total_avg_revenue_column_data" class="list">
				<tbody>
					<?php foreach($pricePoints as $pricePoint): ?>
					<tr>
					   <th><?php echo $pricePoint['price_points']; ?></th>
					   <td><?php echo $pricePoint['average_revenue_deal_amoumt']; ?></td>
					</tr>	
					<?php endforeach; ?>    
				</tbody>				
				</table>
			</div>
        </div>
		</div>
		</div>
        <div class="dashboard-bl">
             <div class="dashboard-br">
                 <div class="dashboard-bc">
                 </div>
             </div>
         </div>

			
		</div>
		<div class="js-load-column-chart chart-half-section {'data_container':'total_avg_coupons_column_data', 'chart_container':'total_avg_coupons_column_chart', 'chart_title':'<?php echo __l('Avg Sold Coupons per Deal by Price Point') ;?>', 'chart_y_title': '<?php echo __l('Avg Sold Coupons per Deal');?>'}">
            <div class="dashboard-tl">
                     <div class="dashboard-tr">
                         <div class="dashboard-tc">
                         </div>
                 </div>
            </div>
         <div class="dashboard-cl">
             <div class="dashboard-cr">
             <div class="dashboard-cc clearfix">

        	<div id="total_avg_coupons_column_chart" class="deal-price-point-chart"></div>
			<div class="hide">
				<table id="total_avg_coupons_column_data" class="list">
				<tbody>
					<?php foreach($pricePoints as $pricePoint): ?>
					<tr>
					   <th><?php echo $pricePoint['price_points']; ?></th>
					   <td><?php echo $pricePoint['average_coupons_deal_count']; ?></td>
					</tr>	
					<?php endforeach; ?>    
				</tbody>				
				</table>
			</div>
        	</div>
		</div>
		</div>
        <div class="dashboard-bl">
             <div class="dashboard-br">
                 <div class="dashboard-bc">
                 </div>
             </div>
         </div>

         
		</div>
	</div>
</div>