<?php
echo $this->requestAction(array('controller' => 'users', 'action' => 'login', 'f' => $f, 'is_buy' => $is_buy), array('return'));
?>