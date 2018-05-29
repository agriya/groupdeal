<?php if (!(!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') && empty($this->request->data['User']['is_requested'])): ?>
    <div class="clearfix">
	<div class="openid-block grid_right multiple-logins clearfix">
				 <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
					<?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook'), array('title' => __l('Sign in with Facebook'), "class" =>"facebook-login", "data-ajax"=>"false", 'escape' => false)); ?>
				 <?php endif; ?>
    </div>
    </div>
<?php endif; ?>
<?php
    $formClass = !empty($this->request->data['User']['is_requested']) ? 'js-ajax-login' : '';
    echo $this->Form->create('User', array('action' => 'login', 'class' => 'normal '.$formClass));
?>	
	<div data-role="fieldcontain">
    <?php echo $this->Form->input(Configure::read('user.using_to_login'), array('div'=>false)); ?>
    </div>
    <div data-role="fieldcontain">
	<?php echo $this->Form->input('passwd', array('label' => __l('Password'), 'div'=>false)); ?>
    </div>
<?php    
    if(!empty($this->request->data['User']['is_requested'])) {
        echo $this->Form->input('is_requested', array('type' => 'hidden'));
    }
?>
<?php echo $this->Html->link(__l('Forgot your password?') , array('controller' => 'users', 'action' => 'forgot_password', 'admin' => false),array('title' => __l('Forgot your password?'))); ?>

		<fieldset class="ui-grid-a">
	<div class="ui-block-a">
	<?php echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'index'), array('data-role'=>'button','class' => 'cancel-button')); ?>
    </div>
	<div class="ui-block-b">
    <?php echo $this->Form->submit(__l('Login'), array('data-theme'=>'b', 'div'=>false)); ?>
	</div>	   
</fieldset>
<?php echo $this->Form->end(); ?>

