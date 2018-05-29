<?php /* SVN: $Id: $ */ ?>
<div class="userFriends form">
<?php echo $this->Form->create('UserFriend', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('User Friends'), array('action' => 'index'), array('title' => __l('User Friends')));?> &raquo; <?php echo __l('Edit User Friend');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('friend_user_id');
		echo $this->Form->input('friend_status_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Update'));?>
</div>
