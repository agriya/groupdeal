<div class="clearfix js-responses js-load-admin-chart-deal-ctp">
 <?php 
 		$arrow = "down-arrow";
 		if(isset($this->request->params['named']['is_ajax_load'])){ 
 		$arrow = "up-arrow";
	   }
 ?>
<?php
	$meta_data = '';
	$class = 'admin-dashboard-chart';
	$class_coupon = 'admin-dashboard-coupon-usage-chart';
	if($this->Auth->user('user_type_id') == ConstUserTypes::Company){
		$class = 'company-dashboard-chart';
		$class_coupon = 'company-dashboard-coupon-usage-chart';
		$meta_data = ", 'dataloading':'div.js-load-admin-chart-deal-ctp',  'dataurl':'charts/chart_deals/is_ajax_load:1'";
	}
	else{
		$meta_data = ", 'dataloading':'div.js-load-admin-chart-deal-ctp',  'dataurl':'admin/charts/chart_deals/is_ajax_load:1'";
	}
	
?>
	<div class="admin-side1-tl ">
		<div class="admin-side1-tr">
		  <div class="admin-side1-tc page-title-info">
			<h2 class="chart-dashboard-title pr no-border"><?php echo __l('Deals/Live Deals/Coupons'); ?>
			<span class="js-chart-showhide <?php echo $arrow; ?> {'chart_block':'admin-dashboard-deals'<?php echo $meta_data; ?>}"></span>
			</h2>
		  </div>
		</div>
	</div>
<?php if(isset($this->request->params['named']['is_ajax_load'])){ ?>    
	<div class="admin-center-block clearfix dashboard-center-block" id="admin-dashboard-deals">
    <div class="clearfix">
    	<?php echo $this->Form->create('Chart' , array('class' => 'language-form '.$meta_data, 'action' => 'chart_deals')); ?>
			<?php
			echo $this->Form->input('is_ajax_load', array('type' => 'hidden', 'value' => 1));
			echo $this->Form->input('select_range_id', array('class' => 'js-chart-autosubmit', 'label' => __l('Select Range'))); ?>
		<div class="hide"> <?php echo $this->Form->submit('Submit');  ?> </div>
		<?php echo $this->Form->end(); ?>
		</div>
		<div class="js-load-line-graph chart-half-section {'data_container':'deals_line_data', 'chart_container':'deals_line_chart', 'chart_title':'<?php echo __l('Deals') ;?>', 'chart_y_title': '<?php echo __l('Deals');?>'}">
         <div class="dashboard-tl">
             <div class="dashboard-tr">
                 <div class="dashboard-tc">
                 </div>
             </div>
         </div>
         <div class="dashboard-cl">
             <div class="dashboard-cr">
            <div class="dashboard-cc clearfix">

        	<div id="deals_line_chart" class="<?php echo $class; ?>"></div>
			<div class="hide">
				<table id="deals_line_data" class="list">
				<thead>
					<tr>
					   <th>Peried</th>
						   <?php foreach($chart_deals_periods as $_period): ?>
							 <th><?php echo $_period['display']; ?></th>
						   <?php endforeach; ?>
					</tr>
					</thead>
					<tbody>
					   <?php foreach($chart_deals_data as $display_name => $chart_data): ?>
							<tr>
								<th><?php echo $display_name; ?></th>
								<?php foreach($chart_data as $val): ?>
									<td><?php echo $val; ?></td>
								<?php endforeach; ?>
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
		<div class="js-load-line-graph chart-half-section {'data_container':'deal_coupons_line_data', 'chart_container':'deal_coupons_line_chart', 'chart_title':'<?php echo __l('Deal Orders/Coupons') ;?>', 'chart_y_title': '<?php echo __l('Orders/Coupons');?>'}">
     <div class="dashboard-tl">
                 <div class="dashboard-tr">
                     <div class="dashboard-tc">
                     </div>
             </div>
         </div>
         <div class="dashboard-cl">
             <div class="dashboard-cr">
             <div class="dashboard-cc clearfix">
            <div id="deal_coupons_line_chart" class="<?php echo $class; ?>"></div>
			<div class="hide">
				<table id="deal_coupons_line_data" class="list">
				<thead>
					<tr>
					   <th>Peried</th>
						   <?php foreach($chart_deal_coupons_periods as $_period): ?>
							 <th><?php echo $_period['display']; ?></th>
						   <?php endforeach; ?>
					</tr>
					</thead>
					<tbody>
					   <?php foreach($chart_deal_coupons_data as $display_name => $chart_data): ?>
							<tr>
								<th><?php echo $display_name; ?></th>
								<?php foreach($chart_data as $val): ?>
									<td><?php echo $val; ?></td>
								<?php endforeach; ?>
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
		<div class="js-load-line-graph chart-half-section {'data_container':'live_deals_line_data', 'chart_container':'live_deals_line_chart', 'chart_title':'<?php echo __l('Live Deals') ;?>', 'chart_y_title': '<?php echo __l('Live Deals');?>'}">
         <div class="dashboard-tl">
             <div class="dashboard-tr">
                 <div class="dashboard-tc">
                     </div>
             </div>
         </div>
         <div class="dashboard-cl">
             <div class="dashboard-cr">
             <div class="dashboard-cc clearfix">

        	<div id="live_deals_line_chart" class="<?php echo $class; ?>"></div>
			<div class="hide">
				<table id="live_deals_line_data" class="list">
				<thead>
					<tr>
					   <th>Peried</th>
						   <?php foreach($chart_live_deals_periods as $_period): ?>
							 <th><?php echo $_period['display']; ?></th>
						   <?php endforeach; ?>
					</tr>
					</thead>
					<tbody>
					   <?php foreach($chart_live_deals_data as $display_name => $chart_data): ?>
							<tr>
								<th><?php echo $display_name; ?></th>
								<?php foreach($chart_data as $val): ?>
									<td><?php echo $val; ?></td>
								<?php endforeach; ?>
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
		<div class="js-load-line-graph chart-half-section {'data_container':'live_deal_coupons_line_data', 'chart_container':'live_deal_coupons_line_chart', 'chart_title':'<?php echo __l('Live Deal Orders/Coupons') ;?>', 'chart_y_title': '<?php echo __l('Orders/Coupons');?>'}">
       <div class="dashboard-tl">
                 <div class="dashboard-tr">
                     <div class="dashboard-tc">
                         </div>
                 </div>
             </div>
             <div class="dashboard-cl">
                 <div class="dashboard-cr">
                 <div class="dashboard-cc clearfix">
            	<div id="live_deal_coupons_line_chart" class="<?php echo $class; ?>"></div>
    			<div class="hide">
    				<table id="live_deal_coupons_line_data" class="list">
    				<thead>
    					<tr>
    					   <th>Peried</th>
    						   <?php foreach($chart_live_deal_coupons_periods as $_period): ?>
							 <th><?php echo $_period['display']; ?></th>
						   <?php endforeach; ?>
					</tr>
					</thead>
					<tbody>
					   <?php foreach($chart_live_deal_coupons_data as $display_name => $chart_data): ?>
							<tr>
								<th><?php echo $display_name; ?></th>
								<?php foreach($chart_data as $val): ?>
									<td><?php echo $val; ?></td>
								<?php endforeach; ?>
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
		<div class="js-load-line-graph chart-full-section {'data_container':'coupon_usages_line_data', 'chart_container':'coupon_usages_line_chart', 'chart_title':'<?php echo __l('Coupon Usages') ;?>', 'chart_y_title': '<?php echo __l('Coupons');?>'}">
         <div class="dashboard-tl">
         <div class="dashboard-tr">
             <div class="dashboard-tc">
             </div>
         </div>
     </div>
     <div class="dashboard-cl">
         <div class="dashboard-cr">
         <div class="dashboard-cc clearfix">
        	<div id="coupon_usages_line_chart" class="<?php echo $class_coupon; ?>"></div>
			<div class="hide">
				<table id="coupon_usages_line_data" class="list">
				<thead>
					<tr>
					   <th>Peried</th>
						   <?php foreach($chart_coupon_usage_periods as $_period): ?>
							 <th><?php echo $_period['display']; ?></th>
						   <?php endforeach; ?>
					</tr>
					</thead>
					<tbody>
					   <?php foreach($chart_coupon_usage_data as $display_name => $chart_data): ?>
							<tr>
								<th><?php echo $display_name; ?></th>
								<?php foreach($chart_data as $val): ?>
									<td><?php echo $val; ?></td>
								<?php endforeach; ?>
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
<?php } ?>    
</div>