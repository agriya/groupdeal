<?php
//Configure::write('debug', 1); // to override

$css_files = array(	'reset.css',
                     'subscribe.css');
if(Configure::read('site.enable_three_step_subscription') == 0){
	$css_files = array_merge($css_files, array(
						'style.css',
						'960_24_col.css'
					));
}
?>
