<?php /* SVN: $Id: add.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<?php
	echo $this->Html->link(__l('Remove Friend'), array('controller' => 'user_friends', 'action' => 'remove', $username,'sent'), array('class' => 'delete js-add-friend','title' => __l('Remove Friend')));
?>
