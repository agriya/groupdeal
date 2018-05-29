<?php
	echo $this->requestAction(array('controller' => 'deal_categories','action' => 'index', 'admin' => false, 'type'=> 'home', 'category_name'=> $category), array('return'));
?>
