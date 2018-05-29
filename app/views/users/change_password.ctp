<div class="js-password-responses">
<?php if(empty($this->request->params['isAjax'])):?>
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Admin){ ?>
    <h2><?php echo __l('Change Password'); ?></h2>
<?php } ?>
<?php endif; ?>

<div class="js-response js-responses">
<?php echo $this->Form->create('User', array('action' => 'change_password' ,'class' => 'normal js-ajax-form {"container" : "js-password-responses"}')); ?>
<?//php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
	<?php
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) :
			echo $this->Form->input('user_id', array('empty' => __l('Please Select')));
		endif;
		if($this->Auth->user('user_type_id') != ConstUserTypes::Admin) :
			echo $this->Form->input('user_id', array('type' => 'hidden', 'readonly' => 'readonly'));
			echo $this->Form->input('old_password', array('type' => 'password','label' => __l('Old Password') ,'id' => 'old-password'));
		endif;
		echo $this->Form->input('passwd', array('type' => 'password','label' => __l('Enter a new Password') , 'id' => 'new-password'));
		echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => __l('Confirm Password')));
    ?>
    <div class="submit-block clearfix">
        <?php
        	echo $this->Form->submit(__l('Change Password'));
        ?>
    </div>
        <?php
        	echo $this->Form->end();
        ?>
    </div>
</div>