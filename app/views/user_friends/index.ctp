<?php /* SVN: $Id: index.ctp 79491 2012-09-25 14:11:46Z rajeshkhanna_146ac10 $ */ ?>
<div class="userFriends index js-response">
<h3><?php echo $this->pageTitle;?></h3>
<?php echo $this->element('paging_counter');?>
<ol class="friends-list clearfix" start="<?php echo $this->Paginator->counter(array('format' => '%start%')); ?>">
<?php
if (!empty($userFriends)) {
foreach ($userFriends as $userFriend) {
?>
	<li id="friend-<?php echo $userFriend['UserFriend']['id']; ?>" class="list-row clearfix ">
<?php
		if ($type == 'received') {
?>
		<div class="friends-list-block">
		<?php echo $this->Html->getUserAvatarLink($userFriend['User'], 'medium_thumb');?>          
        	<p class="meta-row author">
		        <cite><span title="<?php echo $userFriend['User']['username'];?>"><?php echo $this->Html->getUserLink($userFriend['User']);?></span></cite>
		    </p>

<?php
	if ($status == ConstUserFriendStatus::Approved) {
		echo $this->Html->link(__l('Reject '), array('action'=>'reject', $userFriend['User']['username']), array('class' => 'reject js-friend js-friend-delete {container:"js-received-reject-friends"}', 'title' => __l('Reject')));
	}
	if ($status == ConstUserFriendStatus::Pending) {
		echo $this->Html->link(__l('Accept '), array('action'=>'accept', $userFriend['User']['username'], 'received'), array('class' => 'accept js-friend js-friend-delete {container:"js-received-approve-friends"}', 'title' => __l('Accept')));
		echo $this->Html->link(__l('Reject '), array('action'=>'reject', $userFriend['User']['username'], 'received'), array('class' => 'reject js-friend js-friend-delete {container:"js-received-reject-friends"}', 'title' => __l('Reject')));
	}
	if ($status == ConstUserFriendStatus::Rejected) {
		echo $this->Html->link(__l('Remove '), array('action'=>'remove', $userFriend['User']['username'], 'received'), array('class' => 'remove js-friend js-friend-delete {container:"js-remove-friends"}', 'title' => __l('Remove')));
	}
?>

		</div>
<?php
		}
		else {
?>
		<div class="friends-list-block">
			<?php echo $this->Html->getUserAvatarLink($userFriend['FriendUser'], 'medium_thumb');?>          
        	<p class="meta-row friends-list-author textb">
		        <cite><span title="<?php echo $userFriend['FriendUser']['username'];?>"><?php echo $this->Html->getUserLink($userFriend['FriendUser']);?></span></cite>
		    </p>
	<?php
	if ($status == ConstUserFriendStatus::Approved) {
		echo $this->Html->link(__l('Reject'), array('action'=>'reject', $userFriend['FriendUser']['username'], 'sent'), array('class' => 'reject js-friend js-friend-delete {container:"js-received-send-friends"}', 'title' => __l('Reject')));
	}
	if ($status == ConstUserFriendStatus::Pending) {
		echo $this->Html->link(__l('Remove'), array('action'=>'remove', $userFriend['FriendUser']['username'], 'sent'), array('class' => 'remove js-friend js-friend-delete {container:"js-remove-friends"}', 'title' => __l('Remove')));
	}
	if ($status == ConstUserFriendStatus::Rejected) {
		echo $this->Html->link(__l('Remove'), array('action'=>'remove', $userFriend['FriendUser']['username'], 'sent'), array('class' => 'remove js-friend js-friend-delete {container:"js-remove-friends"}', 'title' => __l('Remove')));
	}
?>

		</div>
<?php
		}
?>
	</li>
<?php
    }
  }
else {
?>
	<li class="friends-list-notice">
		<p class="notice">
			<?php
			if ($status == ConstUserFriendStatus::Approved) {
				echo __l('No approved friends available');
			}
			else if ($status == ConstUserFriendStatus::Rejected) {
				echo __l('No rejected friends available');
			}
			else if ($status == ConstUserFriendStatus::Pending) {
				echo __l('No pending friends available');
			}
			?>
		</p>
	</li>
<?php
 	}
?>
</ol>

<?php
if (!empty($userFriends)) {?>
<div class="js-pagination">
	<?php echo $this->element('paging_links');?>
</div>
<?php } ?>
</div>