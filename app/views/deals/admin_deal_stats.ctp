<div>
	<h3><?php echo __l('Summary Statistics'); ?></h3>
	<div class="admin-stats-block js-cache-load js-cache-load-chart-deals-stats {'data_url':'admin/charts/chart_deals_stats', 'data_load':'js-cache-load-chart-deals-stats'}">
		<?php echo $this->element('chart-admin_chart_deals_stats', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
	</div>
	<h3><?php echo __l('Price Point Statistics'); ?></h3>
	<div class="admin-stats-block js-cache-load js-cache-load-chart-deals-price-points {'data_url':'admin/charts/chart_price_points', 'data_load':'js-cache-load-chart-deals-price-points'}">
		<?php echo $this->element('chart-admin_chart_price_points', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
	</div>
</div>
