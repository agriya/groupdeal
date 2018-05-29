<?php
$is_from_pages = (!empty($is_from_pages) ? "pages" : "");
echo $this->requestAction(array('controller' => 'subscriptions','action' => 'add', 'is_from_pages' => $is_from_pages), array('return'));
?>
