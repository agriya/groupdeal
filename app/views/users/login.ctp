<div class="grid_9 login-left-block1 <?php echo !empty($this->request->data['User']['is_requested']) ? 'js-login-response ajax-login-block' : ''; ?>">
          <h2 class="no-brd"><?php echo __l('Sign In'); ?></h2>
          <?php
		$formClass = !empty($this->request->data['User']['is_requested']) ? 'js-ajax-form {container:\'js-login-response\'}' : '';
		echo $this->Form->create('User', array('action' => 'login', 'class' => 'normal login-form no-pad'.$formClass));
		echo $this->Form->input(Configure::read('user.using_to_login'));
		echo $this->Form->input('passwd', array('label' => __l('Password')));
		echo $this->Form->input('is_buy', array('type' => 'hidden', 'value' => $this->request->params['named']['is_buy']));		
		if(!empty($this->request->data['User']['is_requested'])) {
			echo $this->Form->input('is_requested', array('type' => 'hidden'));
		}
	?>
	<?php echo $this->Form->input('User.is_remember', array('type' => 'checkbox', 'label' => __l('Remember me on this computer.'))); ?>
	<div class="fromleft">
		<?php echo $this->Html->link(__l('Forgot your password?') , array('controller' => 'users', 'action' => 'forgot_password', 'admin' => false),array('title' => __l('Forgot your password?'))); ?>
		<?php if(!(!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') && empty($this->request->data['User']['is_requested'])): ?> |
			<?php  echo $this->Html->link(__l('Signup') , array('controller' => 'users',	'action' => 'register'),array('title' => __l('Signup'))); ?>
		<?php endif; ?>
	</div>
	<?php
		@$_GET['f'] = (!empty($this->request->data['User']['is_requested']) ? (!empty($this->request->data['User']['is_buy']) ? $_GET['f'] : $_GET['url']) : $_GET['f']);  			
		$f = (!empty($_GET['f'])) ? $_GET['f'] : ((!empty($this->request->data['User']['f'])) ? $this->request->data['User']['f'] : (($this->request->params['controller'] != 'users' && ($this->request->params['action'] != 'login' && $this->request->params['action'] != 'admin_login')) ? $this->request->url : ''));		
			if (!empty($f)):
				echo $this->Form->input('f', array('type' => 'hidden', 'value' => $f));
			endif;
	?>
	<div class="submit-block buy-submit-block clearfix">
		<?php echo $this->Form->submit(__l('Sign In')); ?>
		<?php if(!empty($this->request->data['User']['is_requested']) && $this->request->data['User']['is_requested']):  ?>
			<div class="cancel-block js-cancel-block">
				<?php echo $this->Html->link(__l('Cancel'), '#', array('title' => __l('Never Mind'),'class' => "js-toggle-show {'container':'js-login-message', 'hide_container':'js-login-form'}"));?>
			</div>
		<?php endif; ?>
	</div>
    <?php echo $this->Form->end(); ?>
        </div>
		<?php if (!(!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') && empty($this->request->data['User']['is_requested'])): ?>
        <div class="grid_left or-block">&nbsp;</div>
        <div class="grid_left">
          <h2 class="no-brd"><?php echo __l('Sign In Using'); ?></h2>
          <ul class="open-id-list open-id-list1 grid_7 clearfix">
            <li class="grid_left face-book">
				 <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
					<?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook'), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?>
				 <?php endif; ?>
			</li>
			<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
				<li class="grid_left twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
			<?php endif;?>
				<?php if(Configure::read('foursquare.is_enabled_foursquare_connect')):?>
					<li class="grid_left foursquare"><?php echo $this->Html->link(__l('Sign in with Foursquare'), array('controller' => 'users', 'action' => 'login',  'type'=> 'foursquare', 'admin'=>false), array('class' => 'Foursquare', 'title' => __l('Sign in with Foursquare')));?></li>
				<?php endif;?>
			<?php if(Configure::read('user.is_enable_yahoo_openid')):?>
				<li class="grid_left yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type'=>'yahoo'), array('title' => __l('Sign in with Yahoo')));?></li>
			<?php endif;?>
			<?php if(Configure::read('user.is_enable_gmail_openid')):?>
				<li class="grid_left gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type'=>'gmail'), array('title' => __l('Sign in with Gmail')));?></li>
			<?php endif;?>
			<?php if(Configure::read('user.is_enable_openid')):?>
				<li class="grid_left open-id"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid'), array('class'=>'','title' => __l('Sign in with Open ID')));?></li>
			<?php endif;?>
        </div>
	  <?php endif; ?>

