<ul class="setting-links   clearfix">
<?php
	foreach ($setting_categories as $setting_category):		
?>	<li class="grid_12 omega alpha">
        <div class="setting-details-info setting-category-<?php echo str_replace(',','',$setting_category['SettingCategory']['name']); ?>">
    	<h3><?php echo $this->Html->link($this->Html->cText($setting_category['SettingCategory']['name'], false), array('controller' => 'settings', 'action' => 'edit', $setting_category['SettingCategory']['id']), array('title' => $setting_category['SettingCategory']['name'], 'escape' => false)); ?></h3>
    
        <div class="js-truncate">
        <?php 
			if(stristr($setting_category['SettingCategory']['description'], '##PAYMENT_SETTINGS_URL##') === FALSE) {
				echo $setting_category['SettingCategory']['description'];
			} else {
				echo $category_description = str_replace('##PAYMENT_SETTINGS_URL##',Router::url('/', true).$this->request->params['named']['city'].'/admin/payment_gateways',$setting_category['SettingCategory']['description']);
			}	
		?>
        </div>
      
        </div>
	</li>
<?php
	endforeach;
?> 
</ul>