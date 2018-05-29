<?php /* SVN: $Id: admin_add.ctp 73588 2011-12-05 05:49:29Z beautlin_108ac10 $ */ ?>
<div class="users form">
<?php echo $this->Form->create('User', array('class' => 'normal'));?>
	<fieldset>
 	
	<?php
        echo $this->Form->input('user_type_id',array('label' => __l('User Type')));
		echo $this->Form->input('email',array('label' => __l('Email')));
		echo $this->Form->input('username',array('label' => __l('Username')));
		echo $this->Form->input('passwd', array('label' => __l('Password')));
		?>
	</fieldset>
<div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>