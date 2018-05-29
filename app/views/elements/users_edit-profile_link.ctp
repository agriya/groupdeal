
	<p class="user-profile-info"><?php echo $this->Auth->user('username').', '.__l(' your public profile page is here').':';?>  
	<?php 
		$profile_url= Router::url(array('controller' => 'users', 'action' => 'view', $this->Auth->user('username')), true);
		echo $this->Html->link($profile_url, array('controller' => 'users', 'action' => 'view', $this->Auth->user('username')),array('target' => '_blank', 'title' => $this->Auth->user('username')));?>
        <?php if($this->Auth->user('is_affiliate_user')): ?>
        	<p><?php echo __l('Share your unique referral link'); ?>
  			  <input type="text" class="refer-box" readonly="readonly" value="<?php echo Router::url(array('controller' => 'users', 'action' => 'refer',  'r' =>$this->Auth->user('username')), true);?>" onclick="this.select()"/>
			</p>
        	<p><?php echo __l('In site url to add your share unique referral'); ?>
  			  <input type="text" class="refer-box" readonly="readonly" value="<?php echo  '/r:'.$this->Auth->user('username');?>" onclick="this.select()"/>
			</p>
        <?php endif;?> 
		<?php
			if($this->Auth->user('is_openid_register')):
				$reg_type_class='open-id';
			 endif;
			if($this->Auth->user('fb_user_id')):
				$reg_type_class='facebook';
			 endif;
			 if($this->Auth->user('twitter_user_id')):
				$reg_type_class='twitter';
			 endif;
			?>
		<?php if(!empty($reg_type_class)): ?>
			<span class="<?php echo $reg_type_class; ?>">
				<?php echo __l('This account is associated with your '.$reg_type_class.' profile'); ?>
			</span>
		 <?php endif; ?>
	</p>
		<div class="clearfix">
		<?php if(!$this->Auth->user('fb_user_id') && !$this->Auth->user('twitter_user_id') && !$this->Auth->user('is_openid_register')):?>
				<?php $class = ($this->request->params['controller'] == 'user_profiles') ? 'active' : null; ?>
			<div class="cancel-block <?php echo $class ; ?>">	<?php echo $this->Html->link(__l('Settings'), array('controller' => 'user_profiles', 'action' => 'edit', 'admin' => false), array('title' => __l('Settings')));?></div>
				<?php $class = ($this->request->params['controller'] == 'users') ? 'active' : null; ?>
                  <?php
			  if(($this->Auth->user('user_type_id')!= ConstUserTypes::Admin) && (!$this->Auth->user('is_openid_register'))   && (!$this->Auth->user('fb_user_id'))  && (!$this->Auth->user('twitter_user_id')) ) : ?>
			<div class="cancel-block <?php echo $class ; ?>"><?php echo $this->Html->link(__l('Change Password'), array('controller' => 'users', 'action' => 'change_password'), array('title' => __l('Change password')));?></div>
            <?php endif;?>
		<?php endif;?> 
		<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Admin): ?>
				<?php $class = ($this->request->params['controller'] == 'user_notifications') ? 'active' : null; ?>
			<div class="cancel-block <?php echo $class ; ?>"><?php echo $this->Html->link(__l('Manage Email settings'), array('controller' => 'user_notifications', 'action' => 'edit', 'admin' => false), array('title' => __l('Manage email notifications')));?></div>
		<?php endif;?> 
		</div>

