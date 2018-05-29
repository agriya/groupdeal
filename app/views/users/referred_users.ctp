<div class="js-response">
	<?php if (!empty($referredFriends)): 
	?>
			<ol class="deal-user-list">
			
			<?php foreach ($referredFriends as $referredFriend): ?>
				<li class="clearfix">
					<div class="company-list-image">
                    
					<?php 
					$user_details = array(
						'username' => $referredFriend['User']['username'],
						'user_type_id' =>  $referredFriend['User']['user_type_id'],
						'id' =>  $referredFriend['User']['id'],
						'UserAvatar' => $referredFriend['UserAvatar']
					);
					echo $this->Html->getUserAvatarLink($user_details,'medium_thumb', false); ?>
                    </div>
                      <div class="company-list-content">
                    	<p><?php echo $this->Html->getUserLink($referredFriend['User']);?></p>
                        <dl class="list no-mar statistics-list">
                            <dt><?php echo __l('Member Since:');?></dt>
                                <dd><?php echo $this->Html->cDate($referredFriend['User']['created']);?></dd>
                             <?php if($referredFriend['User']['deal_count']) { ?>
                                <dt><?php echo __l('Total Deal Purchase:');?></dt>
                                    <dd><?php echo $this->Html->cInt($referredFriend['User']['deal_count']);?></dd>
                             <?php } ?>
                        </dl>
                    </div>
				</li>
			<?php endforeach; ?>
		</ol>
		<div class="js-pagination">
			<?php echo $this->element('paging_links'); ?>
		</div>  
	<?php else: ?>
        <p class="notice"><?php echo __l('No Referred Users Available');?></p>
    <?php endif; ?>
</div>