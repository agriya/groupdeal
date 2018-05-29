<?php /* SVN: $Id: remove.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<?php
	echo $this->Html->link(__l('Add as Friend'), array('controller' => 'user_friends', 'action' => 'add', $username), array('class' => 'add add-friend js-add-friend','title' => __l('Remove Friend')));
?>
