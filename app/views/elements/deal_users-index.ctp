<?php
	$view = 'deal_view';
	if(!empty($view_type)):
		$view = $view_type;
	endif;
	echo $this->requestAction(array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'view' => $view, 'deal_user_view' => 'list'), array('return'));
?>
