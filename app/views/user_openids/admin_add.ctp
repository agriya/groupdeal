<?php /* SVN: $Id: admin_add.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<div class="userOpenids form">
<?php echo $this->Form->create('UserOpenid', array('class' => 'normal'));?>
	<fieldset>
 		<h2><?php echo __l('Add User Openid');?></h2>
	<?php
		echo $this->Form->input('user_id',array('label' => __l('User')));
		echo $this->Form->input('openid',array('label' => __l('OpenID')));
		echo $this->Form->input('verify',array('type' => 'checkbox','label' => __l('Verify')));
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Add'));?>
</div>
