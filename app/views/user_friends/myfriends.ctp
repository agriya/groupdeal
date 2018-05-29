<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userFriends index js-response">
<ol class="friends-list clearfix " start="<?php echo $this->Paginator->counter(array('format' => '%start%')); ?>">
<?php
if (!empty($userFriends)) {
foreach ($userFriends as $userFriend) {
?>
	<li id="friend-<?php echo $userFriend['UserFriend']['id']; ?>" class="friend">
	<div>
    	<?php echo $this->Html->getUserAvatarLink($userFriend['FriendUser'], 'medium_thumb');?>
        </div>
		<?php echo $this->Html->link($userFriend['FriendUser']['username'], array('controller' => 'users', 'action' => 'view', $userFriend['FriendUser']['username']), array('title' => $userFriend['FriendUser']['username']));?>
	</li>
<?php
    } ?>
</ol>
 <?php  }

else {
?>
<div>
		<p class="notice"><?php echo __l('No friends available'); ?></p>
</div>

<?php
 	}
?>

<?php if (!empty($userFriends) and $total_friends >2) {?>
 <div class="js-pagination">
    <?php echo $this->element('paging_links');?>
</div>
<?php } ?>
</div>