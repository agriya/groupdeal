<?php
	echo $this->requestAction(array('controller' => 'apns_devices', 'action' => 'broadcast', 'admin' => true), array('return'));
?>