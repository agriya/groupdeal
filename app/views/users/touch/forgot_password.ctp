<p>
	<?php echo __l('Enter your Email, and we will send you instructions for resetting your password.'); ?>
</p>
<?php
	echo $this->Form->create('User', array('action' => 'forgot_password', 'class' => 'normal'));
	?>
	<div data-role="fieldcontain">
    <?php 
	echo $this->Form->input('email', array('type' => 'text','label' => __l('Email'))); 
	?>
	</div>
<?php
	echo $this->Form->submit(__l('Send'), array('data-theme'=>'b', 'div'=>false));
?>
<?php
	echo $this->Form->end();
?>