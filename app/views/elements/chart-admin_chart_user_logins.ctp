<?php
	echo $this->requestAction(array('controller' => 'charts','action' => 'chart_user_logins', 'admin' => true, 'user_type_id'=> $user_type_id), array('return'));
?>
