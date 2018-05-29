<div class="js-cache-load js-cache-load-admin-charts {'data_url':'admin/charts/chart_transactions', 'data_load':'js-cache-load-admin-charts-transactions'}">
	<?php echo $this->element('chart-admin_chart_transactions', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
</div>
<?php echo $this->element('chart-admin_chart_users', array('user_type_id'=> ConstUserTypes::User, 'cache' => array('key' => 'user'.ConstUserTypes::User, 'config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_user_logins', array('user_type_id'=> ConstUserTypes::User, 'cache' => array('key' => 'user'.ConstUserTypes::User, 'config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_users', array('user_type_id'=> ConstUserTypes::Company, 'cache' => array('key' => 'user'.ConstUserTypes::Company, 'config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_user_logins', array('user_type_id'=> ConstUserTypes::Company, 'cache' => array('key' => 'user'.ConstUserTypes::Company, 'config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_deals', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
