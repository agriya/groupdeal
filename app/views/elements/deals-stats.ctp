<?php
	echo $this->requestAction(array('controller' => 'deals', 'action' => 'stats'), array('named' => array('admin' => false, 'deal_id' => $deal_id), 'return'));
?>