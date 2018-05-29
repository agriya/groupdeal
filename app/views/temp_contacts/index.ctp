<?php /* SVN: $Id: index.ctp 72385 2011-11-22 13:00:41Z josephine_065at09 $ */ ?>
<div id="temp-contacts-<?php echo $member; ?>" class="js-response js-responses js-import-over-block">
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
	<div class="tempContacts index">
		<?php if($member == 1) { ?>
			<div class="info-msg"><h3><?php echo __l('Contacts found in your friends list'); ?></h3></div>
		<?php } else if($member == 2) { ?>
			<div class="info-msg"><h3><?php echo sprintf(__l('Contacts found in %s'), Configure::read('site.name')); ?></h3></div>
		<?php } else if($member == 3) { ?>
			<div class="info-msg"><h3><?php echo sprintf(__l('Invite your contacts to %s'), Configure::read('site.name')); ?></h3></div>
		<?php } ?>
		<?php
			if (!empty($tempContacts) && $member != 1 && $member != 2):
				echo $this->Form->create('UserFriend', array('id' => 'temp-contacts-form-' . $member, 'action' => 'importfriends', 'class' => 'normal js-ajax-form'));// js-ajax-form
				echo $this->Form->input('contacts_source', array('id' => 'contact_source_field' . $member, 'type' => 'hidden', 'value' => $contacts_source));
				echo $this->Form->input('member', array('id' => 'mem_' . $member, 'type' => 'hidden', 'value' => $member));
			endif;
			if(!empty($deal_slug)):
				echo $this->Form->input('deal_slug',array('type' => 'hidden', 'label' => false, 'value' => $deal_slug));
			endif;
		?>
		<table class="list">
			<?php
				if (!empty($tempContacts)):
					$i = 0;
			?>
			<tr>
				<?php if($member == 2){?>
					<th><?php echo __l('Actions') ;?></th>
				<?php } ?>
				<th><?php echo __l('Contact Name') ;?></th>
				<th><?php echo __l('Contact E-mail') ;?></th>
				<?php if($member == 3) { ?>
					<th colspan="4" class="invite-contacts-details"><?php echo $this->Form->input('invite_all', array('type' => 'select', 'class' => 'js-invite-all', 'label' => false, 'options' => $invite_friend_options)); ?></th>
				<?php } ?>
			</tr>
			<?php
				foreach ($tempContacts as $tempContact):
					$class = null;
					if ($i++ % 2 == 0) {
						$class = ' class="altrow"';
					}
			?>
			<tr<?php echo $class;?>>
					<?php if($member == 2){?>
						<td>
							<span><?php echo $this->Html->link(__l('Add as Friend'), array('controller' => 'user_friends', 'action' => 'add', 'username'=>urlencode_rfc3986($tempContact['ContactUser']['username'])), array('class' => ' add-friend js-add-friend', 'title' => __l('Add as Friend')));?></span>
						</td>				
					<?}?>
					<td>
						<?php echo $this->Html->truncate($tempContact['TempContact']['contact_name'], 25, array('ending' => '...')); ?>
					</td>				
				<td><?php echo $this->Html->cText($tempContact['TempContact']['contact_email']); ?></td>
				<?php if($tempContact['TempContact']['is_member'] == 3) { ?>
					<td><?php echo $this->Form->input($tempContact['TempContact']['id'], array('type' => 'select', 'class' => 'invite-select', 'label' => '', 'options' => $invite_friend_options,'name'=>'data[UserFriend][FriendList]['.$tempContact['TempContact']['id'].']')); ?></td>
				<?php } ?>
			</tr>
			<?php
				endforeach;
				else:
			?>
			<tr>
				<?php if($member == 1) { ?>
					<td colspan="8" class="notice"><?php echo sprintf(__l('No %s friends available in your mail'), Configure::read('site.name')); ?></td>
				<?php } else if($member == 2) { ?>
					<td colspan="8" class="notice"><?php echo sprintf(__l('No %s contacts available in your mail'), Configure::read('site.name'));?></td>
				<?php } else if($member == 3) { ?>
					<td colspan="8" class="notice"><?php echo __l('No contacts available in your mail');?></td>
				<?php } ?>
			</tr>
			<?php
				endif;
			?>
		</table>
		<?php
			if (!empty($tempContacts) && $member != 1 && $member != 2): ?>
            	    <div class="submit-block clearfix">
						<?php echo $this->Form->submit(__l('Submit'));?>
                        <div class="cancel-block">
                            <?php echo $this->Html->link(__l('Cancel'), array('controller' => 'users', 'action' => 'my_stuff#Import_friends','admin' => false), array('class' => 'cancel-button')); ?>
                        </div>
                   </div>
		<?php
				echo $this->Form->end(null);
                        else:
			?>
			<div class="submit-block clearfix">				
                        <div class="cancel-block">
                            <?php echo $this->Html->link(__l('Cancel'), array('controller' => 'users', 'action' => 'my_stuff#Import_friends','admin' => false), array('class' => 'cancel-button')); ?>
                        </div>
                   </div>
			<?php

			endif;
		?>

		  <div class="clearfix js-pagination">
		<?php
			if (!empty($tempContacts)) {
				echo $this->element('paging_links');
			}
		?>
		</div>
	</div>
</div>