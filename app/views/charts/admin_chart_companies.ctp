<div class="clearfix">
    <?php 
	    $total_revenue = 0;
		$total_coupons = 0;
		if (!empty($companies)){
			foreach ($companies as $company){
				$total_revenue += $company['Company']['total_site_revenue_amount'];
				$total_coupons += $company['Company']['coupons_count'];
			}
		}
		?>
	<div class="clearfix">	    
		<?php if(!empty($companies)): ?>
			<div class="js-load-pie-chart chart-half-section {'data_container':'company_pie_revenue_data', 'chart_container':'company_pie_revenue_chart', 'chart_title':'<?php echo __l('Merchant by Total Revenue');?>', 'chart_y_title': '<?php echo __l('Merchant by Total Revenue');?>'}">
              <div class="dashboard-tl">
                 <div class="dashboard-tr">
                     <div class="dashboard-tc">
                     </div>
                 </div>
             </div>
             <div class="dashboard-cl">
                 <div class="dashboard-cr">
                 <div class="dashboard-cc clearfix">
               	<div id="company_pie_revenue_chart" class="merchant-chart"></div>
				<div class="hide">
					<table id="company_pie_revenue_data" class="list">								
						<tbody>
							<?php foreach($companies as $company): ?>
							<tr>
							   <th><?php echo $company['Company']['name']; ?></th>
							   <td><?php echo ($company['Company']['total_site_revenue_amount']/$total_revenue)*100; ?></td>
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
		<?php endif; ?>
		<?php if(!empty($companies)): ?>
			<div class="js-load-pie-chart chart-half-section {'data_container':'company_pie_coupon_data', 'chart_container':'company_pie_coupon_chart', 'chart_title':'<?php echo __l('Merchant by # Coupons Sold');?>', 'chart_y_title': '<?php echo __l('Merchant by # Coupons Sold');?>'}">
               <div class="dashboard-tl">
                 <div class="dashboard-tr">
                     <div class="dashboard-tc">
                     </div>
                 </div>
             </div>
             <div class="dashboard-cl">
                 <div class="dashboard-cr">
                 <div class="dashboard-cc clearfix">
                <div id="company_pie_coupon_chart" class="merchant-chart"></div>
				<div class="hide">
					<table id="company_pie_coupon_data" class="list">								
						<tbody>
							<?php foreach($companies as $company): ?>
							<tr>
							   <th><?php echo $company['Company']['name']; ?></th>
							   <td><?php echo ($company['Company']['coupons_count']/$total_coupons)*100; ?></td>
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
		<?php endif; ?>
	</div>
	<div class="clearfix">
	<h3><?php echo __l('Top 10 Merchants'); ?></h3>
	<table class="list">
		<tr>        
			<th rowspan="2"><?php echo __l('Merchant');?></th>
			<th colspan="3"><?php echo __l('Total');?></th> 
			<th colspan="3"><?php echo __l('Average');?></th>
			<th colspan="2"><?php echo __l('Max').'/'.__l('Deal');?></th>
		</tr>
		<tr>
			<th><?php echo __l('Revenue').' ('.Configure::read('site.currency').')';?></th>
			<th><?php echo __l('# Deals');?></th>
			<th><?php echo __l('# Coupons');?></th>
			<th><?php echo __l('Coupons').'/'.__l('Deal');?></th>
			<th><?php echo __l('Revenue').'/'.__l('Deal').' ('.Configure::read('site.currency').')';?></th>
			<th><?php echo __l('Offered Price'). '('.Configure::read('site.currency').')';?></th>
			<th><?php echo __l('# Coupons');?></th>
			<th><?php echo __l('Revenue').' ('.Configure::read('site.currency').')';?></th>
		</tr>
		<?php
		if (!empty($companies)):

		$i = 0;		
		foreach ($companies as $company):			
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}			
		?>
			<tr<?php echo $class;?>>       
				<td class="dl"><?php echo $this->Html->cText($company['Company']['name']);?></td>
				<td class="dr"><?php echo $this->Html->cCurrency($company['Company']['total_site_revenue_amount']);?></td>
				<td class="dr"><?php echo $this->Html->cInt($company['Company']['deals_count']);?></td>
				<td class="dr"><?php echo $this->Html->cInt($company['Company']['coupons_count']);?></td>
				<td class="dr"><?php echo $this->Html->cInt($company['Company']['average_coupons_deal_count']);?></td>
				<td class="dr"><?php echo $this->Html->cCurrency($company['Company']['average_revenue_deal_amoumt']);?></td>
				<td class="dr"><?php echo $this->Html->cCurrency($company['Company']['average_offered_price']);?></td>
				<td class="dr"><?php echo $this->Html->cInt($company['Company']['max_coupon_per_deal']);?></td>
				<td class="dr"><?php echo $this->Html->cCurrency($company['Company']['max_revenue_per_deal']);?></td>
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
	</div>
</div>