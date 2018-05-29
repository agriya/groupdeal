<?php /* SVN: $Id: view.ctp 78977 2012-09-10 10:42:37Z rajeshkhanna_146ac10 $ */ ?>
<div class="users view">
    <h2 class="user-view"><?php echo ucfirst($this->Html->cText($user['User']['username'], false)); ?></h2>
	<div class="add-block js-login-form">
    <?php
			 if($this->Auth->user('username')!=$user['User']['username'] && Configure::read('friend.is_enabled') && $user['User']['user_type_id'] != ConstUserTypes::Admin):
				if (!empty($friend)):
					if ($friend['UserFriend']['friend_status_id'] == ConstUserFriendStatus::Pending):
						$is_requested = ($friend['UserFriend']['is_requested']) ? 'sent' : 'received';
						echo $this->Html->link(__l('Friend Request is Pending'), array('controller' => 'user_friends', 'action' => 'remove', $user['User']['username'], $is_requested), array('class' => 'user-pending js-friend', 'title' => __l('Click to remove from friend\'s list')));
					else:
						$is_requested = ($friend['UserFriend']['is_requested']) ? 'sent' : 'received';
						echo $this->Html->link(__l('Remove Friend'), array('controller' => 'user_friends', 'action' => 'remove', $user['User']['username'], $is_requested), array('class' => 'js-delete remove-user delete js-add-friend', 'title' => __l('Click to remove from friend\'s list')));
					endif;
				else:
					if($this->Html->isAllowed($this->Auth->user('user_type_id'))):  // If company user logged in.
						if($user['User']['user_type_id'] == ConstUserTypes::Company && Configure::read('user.is_company_actas_normal_user')):  // On viewing company user profile
							echo $this->Html->link(__l('Add as Friend'), array('controller' => 'user_friends', 'action' => 'add', $user['User']['username']), array('class' => ' add-friend js-add-friend', 'title' => __l('Add as Friend')));
						elseif($user['User']['user_type_id'] != ConstUserTypes::Company):
							echo $this->Html->link(__l('Add as Friend'), array('controller' => 'user_friends', 'action' => 'add', $user['User']['username']), array('class' => ' add-friend', 'title' => __l('Add as Friend')));
						endif;
					endif;
				endif;
			endif;
		
        ?>
	 </div>

	<div class="clearfix">
     <div class="clearfix user-view-left-block grid_6 omega alpha">
	 <div class="user-avatar  user-view-image">
                        <?php
							$current_user_details = array(
								'username' => $user['User']['username'],
								'user_type_id' =>  $user['User']['user_type_id'] ,
								'id' =>  $user['User']['id'],
								'fb_user_id' => $user['User']['fb_user_id']
							);
    						$current_user_details['UserAvatar'] = $this->Html->getUserAvatar($user['User']['id']);
    						echo $this->Html->getUserAvatarLink($current_user_details, 'big_thumb'); 
						?>

	 </div>
	 <?php if(!empty($user['UserProfile'])){ ?>
    	<dl class="list company-list clearfix">
        <?php if($user['UserProfile']['created'] != '0000-00-00 00:00:00'){ ?>
			<dt><?php echo __l('Member Since');?></dt>
			<dd><?php echo $this->Html->cDate($user['User']['created']);?></dd>
		<?php } ?>
        <?php
			if (Configure::read('Profile-is_show_name') && ($this->Html->checkForPrivacy('Profile-is_show_name', $this->Auth->user('id'), $user['User']['id']) || $this->Auth->user('user_type_id') == ConstUserTypes::Admin)):
				$name = array();
				if(!empty($user['UserProfile']['first_name'])):
					$name[]= $this->Html->cText($user['UserProfile']['first_name']);
				endif;
				if(!empty($user['UserProfile']['middle_name'])):
					$name[]= $this->Html->cText($user['UserProfile']['middle_name']);
				endif;
				if(!empty($user['UserProfile']['last_name'])):
					$name[]= $this->Html->cText($user['UserProfile']['last_name']);
				endif;
				if($name):
				?>
				<dt><?php echo __l('Name');?></dt>
					<dd>
					<?php echo implode(' ',$name);?>

					</dd>

				<?php
				endif;
			endif;
		?>
		<?php
			if (Configure::read('Profile-is_show_gender') && ($this->Html->checkForPrivacy('Profile-is_show_gender', $this->Auth->user('id'), $user['User']['id']) || $this->Auth->user('user_type_id') == ConstUserTypes::Admin)):
				if (!empty($user['UserProfile']['Gender']['name'])):
		?>
					<dt><?php echo __l('Gender');?></dt>
						<dd><?php echo $this->Html->cText($user['UserProfile']['Gender']['name']);?></dd>
		<?php
				endif;
			endif;
		?>
		<?php
			if (Configure::read('Profile-is_show_address') && ($this->Html->checkForPrivacy('Profile-is_show_address', $this->Auth->user('id'), $user['User']['id']) || $this->Auth->user('user_type_id') == ConstUserTypes::Admin)):
				if(!empty($user['UserProfile']['address'])):
		?>
					<dt><?php echo __l('Address');?></dt>
						<dd>
                            <?php if(!empty($user['UserProfile']['address'])) { ?>
                                <p><?php echo $this->Html->cText($user['UserProfile']['address']);?></p>
                            <?php } ?>
                            <?php if(!empty($user['UserProfile']['City']['name'])) { ?>
                                <p><?php echo $this->Html->cText($user['UserProfile']['City']['name']);?></p>
                            <?php } ?>
                            <?php if(!empty($user['UserProfile']['State']['name'])) { ?>
                                <p><?php echo $this->Html->cText($user['UserProfile']['State']['name']);?></p>
                            <?php } ?>
                              <?php if(!empty($user['UserProfile']['Country']['name'])) { ?>
                                <p><?php echo $this->Html->cText($user['UserProfile']['Country']['name']);?></p>
                            <?php } ?>
                             <?php if(!empty($user['UserProfile']['zip_code'])) { ?>
                                <p><?php echo $this->Html->cText($user['UserProfile']['zip_code']);?></p>
                            <?php } ?>
                        </dd>
		<?php
				endif;
			endif;
		?>
		<?php
			if ($this->Auth->user('username')== $user['User']['username'] || $this->Auth->user('user_type_id') == ConstUserTypes::Admin):
				if (!empty($user['UserProfile']['paypal_account'])):
		?>
					<dt><?php echo __l('Paypal Account');?></dt>
						<dd><?php echo $this->Html->cText($user['UserProfile']['paypal_account']);?></dd>
		<?php
				endif;
			endif;
		?>
		<?php
			if (!empty($user['UserProfile']['language_id'])):
		?>
				<dt><?php echo __l('Language');?></dt>
					<dd><?php echo $this->Html->cText($user['UserProfile']['Language']['name']);?></dd>
		<?php
			endif;
		?>
	</dl>

 <?php if(!empty($user['UserProfile']['about_me'])){ ?>
     <div class="about-content ">
	    <?php if(!empty($user['UserProfile']['about_me'])): ?>
			<h3><?php echo __l('About Me');?></h3>
			<div class="about-me"><?php echo nl2br($this->Html->cText($user['UserProfile']['about_me']));?></div>
		<?php endif; ?>
    </div>
    <?php } ?>

 <?php }?>
	 <?php if (Configure::read('user.is_show_user_statistics')): ?>
		<div class="js-responses">
				<div class="statistics-count clearfix">
					<?php if (Configure::read('user.is_show_referred_friends') && Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)) {?>
					<span class="referred-users statistics-title-info" title="<?php echo __l('Referred Users');?>"><?php echo __l('Referred Users');?></span>
						<span><?php echo $this->Html->cInt($statistics['referred_users']);?></span>
					<?php } ?>
					<?php if (Configure::read('user.is_show_friend') && Configure::read('friend.is_enabled')) {?>
					<span class="friends statistics-title-info" title="<?php echo __l('Friends');?>"><?php echo __l('Friends');?></span>
						<span><?php echo $this->Html->cInt($statistics['user_friends']);?></span>
					<?php } ?>
					<?php if (Configure::read('user.is_show_deal_purchased')) {?>
					<span class="deal-purchased statistics-title-info" title="<?php echo __l('Deals Purchased');?>"><?php echo __l('Deals Purchased');?></span>
						<span><?php echo $this->Html->cInt($statistics['deal_purchased']);?></span>
					<?php } ?>
					<span class="gift-sent statistics-title-info"  title="<?php echo __l('Gift Sent');?>"><?php echo __l('Gift Sent');?></span>
						<span><?php echo $this->Html->cInt($statistics['gift_sent']);?></span>
					<span class="gift-received statistics-title-info" title="<?php echo __l('Gift Received');?>"><?php echo __l('Gift Received');?></span>
						<span><?php echo $this->Html->cInt($statistics['gift_received']);?></span>
				</div>
			</div>
	<?php endif; ?>
	 </div>


	<div class="js-tabs user-view-tabs grid_17 omega alpha">
        <ul class="clearfix">
			<?php if (Configure::read('Profile-is_allow_comment_add') && $this->Html->isAllowed($this->Auth->user('user_type_id')) && ($this->Html->checkForPrivacy('Profile-is_allow_comment_add', $this->Auth->user('id'), $user['User']['id']) || $this->Auth->user('user_type_id') == ConstUserTypes::Admin)): ?>
				<li><em></em><?php echo $this->Html->link(__l('Comments'), '#tabs-1');?></li>
			<?php endif; ?>
            <?php if($this->Auth->user('id')): ?>
				<?php if (Configure::read('user.is_show_deal_purchased')): ?>
                    <li><em></em><?php echo $this->Html->link(__l('Deals Purchased'), array('controller' => 'deal_users', 'action' => 'user_deals', 'user_id' =>  $user['User']['id']),array('title' => __l('Deals Purchased'))); ?></li>
                <?php endif; ?>
                <?php if (Configure::read('user.is_show_friend') && Configure::read('friend.is_enabled')): ?>
                    <li><em></em><?php echo $this->Html->link(__l('Friends'), array('controller' => 'user_friends', 'action' => 'myfriends', 'user_id' =>  $user['User']['id'], 'status' => ConstFriendRequestStatus::Approved),array('title' => __l('Friends'))); ?></li>
                <?php endif; ?>
                <?php if (Configure::read('user.is_show_referred_friends') && Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)): ?>
                    <li><em></em><?php echo $this->Html->link(__l('Referred Users'), array('controller' => 'users', 'action' => 'referred_users', 'user_id' =>  $user['User']['id']),array('title' => __l('Referred Users'))); ?></li>
                <?php endif; ?>
              <?php endif; ?>
        </ul>
		<?php if (Configure::read('Profile-is_allow_comment_add') && $this->Html->isAllowed($this->Auth->user('user_type_id')) && $this->Html->checkForPrivacy('Profile-is_allow_comment_add', $this->Auth->user('id'), $user['User']['id']) || $this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
			<div id='tabs-1'>
				<div class="main-content-block js-corner round-5">
					<div class="js-responses">
						<?php
						echo $this->element('user_comments-index', array('username' => $user['User']['username'], 'cache' => array('config' => 'sec 1', 'key' => $user['User']['username'])));?>
					</div>
				</div>
                <?php if($this->Auth->user('id') and $this->Auth->user('id')!= $user['User']['id']): ?>
                    <div class="main-content-block js-corner round-5">
                        <h2><?php echo __l('Add Your comments'); ?></h2>
                        <?php echo $this->element('../user_comments/add');?>
                    </div>
                <?php endif; ?>
			</div>
		<?php endif; ?>
    </div>
    </div>
</div>