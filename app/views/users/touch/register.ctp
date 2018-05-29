<?php 
	if(!empty($type) && $type == 'company'){
		$action = "company_register";
	}
	else{
		$action = "register";
		$type = '';
	}
?>
<?php
  		$formClass = !empty($this->request->data['User']['is_requested']) ? 'js-ajax-login' : '';
?>
<?php echo $this->Form->create('User', array('action' => $action, 'class' => 'normal js-company-map js-register-form '.$formClass)); ?>

		<div data-role="fieldcontain">
		<?php echo $this->Form->input('username',array('info' => __l('Must start with an alphabet. <br/> Must be minimum of 3 characters and <br/> Maximum of 20 characters <br/> No special characters and spaces allowed'),'label' => __l('Username'), 'div'=>false));?>
		</div>
        <div data-role="fieldcontain">
		<?php echo $this->Form->input('email',array('label' => __l('Email'), 'div'=>false)); ?>
        </div>
        <?php echo $this->Form->input('referred_by_user_id',array('type' => 'hidden',)); ?>
        <div data-role="fieldcontain">
		<?php echo $this->Form->input('passwd', array('label' => __l('Password'), 'div'=>false)); ?>
        </div>
        <div data-role="fieldcontain">
        <?php echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => __l('Password Confirmation'), 'div'=>false)); ?>
        </div>
        <div data-role="fieldcontain">
    	           <?php echo $this->Html->image(Router::url(array('controller' => 'users', 'action' => 'show_captcha', md5(uniqid(time()))), true), array('alt' => __l('[Image: CAPTCHA image. You will need to recognize the text in it; audible CAPTCHA available too.]'), 'title' => __l('CAPTCHA image'), 'class' => 'captcha-img'));?>    	
        <?php echo $this->Form->input('captcha', array('label' => __l('Security Code'), 'class' => 'js-captcha-input')); ?>
		</div>
		<?php
		if(empty($this->request->data['User']['openid_url'])): ?>
			<?php echo $this->Html->link(__l('Terms & Conditions'), array('controller' => 'pages', 'action' => 'view', 'term-and-conditions'), array('target' => '_blank')); ?>
        	<div data-role="fieldcontain">
		         <?php echo $this->Form->input('is_agree_terms_conditions', array('label' => __l('I have read, understood &amp; agree to the Terms & Conditions') )); ?>
		     </div>
		<?php endif; ?>
		<?php echo $this->Form->input('type',array('type' => 'hidden', 'value' => $type)); ?>
		
		<fieldset class="ui-grid-a">
	<div class="ui-block-a"><?php echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'index'), array('data-role'=>'button','class' => 'cancel-button')); ?></div>
	<div class="ui-block-b"><?php echo $this->Form->submit(__l('Submit'), array('data-theme'=>'b', 'div'=>false)); ?></div>	   
</fieldset>
		<?php 	
		echo $this->Form->end();
		?>