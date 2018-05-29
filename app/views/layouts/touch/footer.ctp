<div id="footer">
<p>&copy;<?php echo date('Y');?> <?php echo Configure::read('site.name');?>. <?php echo __l('All rights reserved');?>. </p>
<p>
<?php
	$parsed_url = parse_url(Router::url('/', true));
	$touch_site_url = str_ireplace('touch.', '', Router::url('/', true).'?mobile=false');
	echo $this->Html->link(__l('Switch to Standard View'), $touch_site_url);
 ?>
</p>
</div>