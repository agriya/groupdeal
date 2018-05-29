<?php
echo $this->requestAction(array('controller' => 'deals', 'action' => 'index'), array('type' => 'near', 'view' => 'simple','deal_id' => $deal_id, 'return'));
?>