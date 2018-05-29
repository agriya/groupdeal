<div class="dashboard-block">
<?php if(empty($this->request->params['isAjax'])):?>
<h2><?php echo __l('Linked Accounts'); ?></h2>
<?php endif; ?>
<h3 class="connected-info"><?php echo __l('You can connect with').' '.Configure::read('site.name').' '.__l('using multiple connect.'); ?></h3>
<?php echo $this->Form->create('User', array('action' => 'profile_image', 'class' => 'normal',  'enctype' => 'multipart/form-data'));
      
	  echo $this->Form->input('User.id', array('type' => 'hidden'));
	  unset($profileimages[ConstProfileImage::Upload]);
?>
<div class="photo-upload-block">
<div class="photo-options">
</div>
<div class="clearfix avatar-options">
<div class="dashboard-inner-block ver-mspace round-10">

		<div class="connect-link-block clearfix">
		<div class="grid_18 omega alpha">
        	
            <?php 
			if(!empty($this->request->data['User']['twitter_avatar_url'])): ?>
                <h3><?php echo __l('Connect with Twitter'); ?></h3>
                <?php
                    echo $this->Html->image($this->request->data['User']['twitter_avatar_url'], array('title' => __l('Twitter Profile Image')));		
			else:
			?>
            	<h3><?php echo __l('Connect to Twitter'); ?></h3>
            	<p>
				<?php echo __l('Connect your twitter automatically get the profile information and avatar.'); ?>
                </p>
            <?php endif;?>
            </div>
            <div class="grid_3 grid_right connection-right-block omega near-deal-buy-block alpha">
			<?php
			if(!empty($this->request->data['User']['twitter_avatar_url'])):
				if(!$this->request->data['User']['is_twitter_register']) :
					echo $this->Html->link(__l('Disconnect'), array('controller' => 'users', 'action' => 'connect', $this->request->data['User']['id'], 'type' => 'twitter', 'c_action' => 'disconnect'), array('class'=>'button dc grid_right','escape' => false));
				endif;
			else:	
        	   echo $this->Html->link(__l('Connect'), array('controller' => 'users', 'action' => 'connect', $this->request->data['User']['id'], 'type' => 'twitter'), array('class'=>'button dc grid_right','escape' => false));
        	endif;
			?>
			</div>
		</div>
</div>
<div class="dashboard-inner-block ver-mspace round-10">
		<div class="connect-link-block clearfix">
		<div class="grid_18 omega alpha">
        	
            <?php if(!empty($this->request->data['User']['fb_user_id'])): ?>
            	<h3><?php echo __l('Connect with Facebook'); ?></h3>
            	<?php
				echo $this->Html->image('http://graph.facebook.com/'.$this->request->data['User']['fb_user_id'].'/picture?type=small', array('title' => __l('Facebook Profile Image')));
			?>
            <?php else: ?>
                <h3><?php echo __l('Connect to Facebook'); ?></h3>
                <p><?php echo __l('Connect your facebook automatically get the profile information and avatar.'); ?></p>
            <?php endif;?>
              </div>
            <div class="grid_3 grid_right connection-right-block omega near-deal-buy-block alpha">
			<?php 
			if(!empty($this->request->data['User']['fb_user_id'])):
				if(!$this->request->data['User']['is_facebook_register']) :
					echo $this->Html->link(__l('Disconnect'), array('controller' => 'users', 'action' => 'connect', $this->request->data['User']['id'], 'type' => 'facebook', 'c_action' => 'disconnect'), array('class'=>'button dc grid_right','escape' => false));
				endif;
			else:	
			   echo $this->Html->link(__l('Connect'), $fb_login_url ,array('class'=>'button dc grid_right'));
			endif;?>
			</div>
		</div>
						  
</div>
</div>
</div>
<?php echo $this->Form->end(); ?>
</div>