<?php
	if(!empty($city_slug)):
		echo $this->requestAction(array('controller' => 'cities', 'action' => 'index'), array('named' => array('admin' => false, 'city' => $city_slug), 'return'));
	else:
		echo $this->requestAction(array('controller' => 'cities', 'action' => 'index'), array('named' => array('admin' => false), 'return'));
	endif;
?>