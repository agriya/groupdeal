<?php /* SVN: $Id: edit.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<div class="userFriends form">
<?php echo $this->Form->create('UserFriend', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('User Friends'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit User Friend');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id',array('label' => __l('User')));
		echo $this->Form->input('friend_user_id',array('label' => __l('Friend User')));
		echo $this->Form->input('friend_status_id',array('label' => __l('Friend Status')));
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Update'));?>
</div>
