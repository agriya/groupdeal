<div class="user_friends import">
<div class="main-content-block js-corner round-5">
<h2><?php echo __l('Friends Import'); ?></h2>
<div class="form-blocks js-corner round-5">
<?php
	if(isset($exist_friend_arr) or isset($add_friend_arr) or isset($invite_friend_arr)) {
?>
<?php $deal_invite_check = !empty($this->request->data['UserFriend']['deal_slug']) ? $this->request->data['UserFriend']['deal_slug'] : '';?>
<div id="friends-add-import-div">
	<div class="js-tabs">
		<ul class="clearfix">			
			<li><em></em><?php echo $this->Html->link(sprintf(__l('Invite your contacts to %s'), Configure::read('site.name')), '#js-invite-list'); ?></li>
			<?php if(empty($deal_invite_check)):?>
				<li><em></em><?php echo $this->Html->link(__l('Contacts found in your friends list'), '#js-friends-list'); ?></li>
				<li><em></em><?php echo $this->Html->link(sprintf(__l('Contacts found in %s'), Configure::read('site.name')), '#js-conatcts-list'); ?></li>
			<?php endif;?>
		</ul>
		<?php if(empty($deal_invite_check)):?>
		<div id="js-friends-list" class="exist-friend responses">
				<?php echo $this->requestAction(array(
					'controller' => 'temp_contacts',
					'action' => 'index',
					1,
					$contacts_source
				), array('return')); ?>
			</div>	
			<div id ="js-conatcts-list" class="exist-friend responses">
				<?php echo $this->requestAction(array(
					'controller' => 'temp_contacts',
					'action' => 'index',
					2,
					$contacts_source
				), array('return')); ?>
			</div>
		<?php endif;?>
			<div  id ="js-invite-list"  class="exist-friend responses">
				<?php echo $this->requestAction(array(
					'controller' => 'temp_contacts',
					'action' => 'index',
					3,
					$contacts_source,
					$deal_invite_check 
				), array('return')); ?>
			</div>
	</div>
</div>
<?php
	}
	else {
?>
<div>
	<div class="page-info">
		<h3><?php echo __l('Your privacy is our top concern');?></h3>
		<p>
			<?php echo sprintf(__l('Your contacts are your private information. Only you have access to your contacts, and %s will not send them any email. For more information please see the %'), Configure::read('site.name'), Configure::read('site.name'));?>
			<span><?php echo $this->Html->link(__l('Privacy Policy'), array('controller' => 'page', 'action' => 'privacy_policy'), array('target' => '_blank', 'class'=>'js-contact-thickbox'));?></span>
		</p>
	</div>
	<div class="js-tabs">
    	<ul class="clearfix">
			<li class="msn"><em></em><?php echo $this->Html->link(__l('CSV Import'), '#csv-import'); ?></li>
    		<li class="yahoo"><em></em><?php echo $this->Html->link(__l('YAHOO!'), '#yahoo-import'); ?></li>
    		<li class="gmail"><em></em><?php echo $this->Html->link(__l('Gmail'), '#gmail-import'); ?></li>
    		<li class="msn"><em></em><?php echo $this->Html->link(__l('MSN'), '#msn-import'); ?></li>
    	</ul>
	<div id="yahoo-import">
		<div class="space display-information">
			<p><?php echo sprintf(__l('You need to give %s permission to access your Yahoo! Mail address book.'), Configure::read('site.name'));?></p>
			<p><?php echo sprintf(__l('We will take you to Yahoo! where you will be asked to let').' '.('%s').' '.__l('take a peek at your address book. Once you get there, click "Grant access" and you will be returned here to find your friends.'), Configure::read('site.name'));?></p>
		</div>
		<div>
		<?php
			echo $this->Form->create('UserFriend', array('action' => 'import', 'class' => 'normal','id'=>'yahoo-form'));
			echo $this->Form->hidden('domain', array('value' => 'yahoo','id'=>'yahoodomain'));
                ?>
              <div class="submit-block clearfix">
                    <?php
                    	echo $this->Form->submit(__l('Go'));
                    ?>
                    </div>
                <?php
                	echo $this->Form->end();
                ?>
		</div>
	</div>
	<div id="gmail-import">
		<div class="space display-information">
            <p><?php echo sprintf(__l('You need to give %s permission to access your Gmail address book.'), Configure::read('site.name'));?></p>
			<p><?php echo sprintf(__l('We\'ll take you to Google where you\'ll be asked to let %s take a peek at your address book. Once you get there, click "Grant access" and you\'ll be returned here to find your friends.'), Configure::read('site.name'));?></p>
		</div>
		<div>
			<?php
				echo $this->Form->create('UserFriend', array('action' => 'import', 'class' => 'normal','id'=>'gmail-form'));
				echo $this->Form->hidden('domain', array('value' => 'gmail','id'=>'gmaildomain'));
			     ?>
              <div class="submit-block clearfix">
                    <?php
                    	echo $this->Form->submit(__l('Go'));
                    ?>
                    </div>
                <?php
                	echo $this->Form->end();
                ?>
		</div>
	</div>
	<div id="msn-import">
		<div class="space display-information">
            <p><?php echo sprintf(__l('You need to give').' '.' %s'.' '.__l('permission to access your Windows Live Hotmail address book.'), Configure::read('site.name'));?></p>
			<p><?php echo sprintf(__l('We will take you to Windows Live where you will be asked to let').' '.' %s '.' '.__l('take a peek at your address book. Once you get there, click "Grant access" and you will be returned here to find your friends.'), Configure::read('site.name'));?></p>
		</div>
		<div>
			<?php
				echo $this->Form->create('UserFriend', array('action' => 'import', 'class' => 'normal','id'=>'msn-form'));
				echo $this->Form->hidden('domain', array('value' => 'msn','id'=>'msndomain'));
			     ?>
              <div class="submit-block clearfix">
                    <?php
                    	echo $this->Form->submit(__l('Go'));
                    ?>
                    </div>
                <?php
                	echo $this->Form->end();
                ?>
		</div>
	</div>
	<div id="csv-import">
		<div class="space display-information">
            <?php echo __l('You can export contacts to a file (csv - comma separated values) from any address book software and upload that file.'); ?>			
			<a href="<?php echo Router::url('/').'files/sample.csv'	?>"  target = '_blank' title ="<?php echo __l("View Sample CSV File");?> "><?php echo __l("View Sample CSV File");?></a>
		</div>

			<?php echo $this->Form->create('UserFriend', array('action' => 'import', 'class' => 'normal', 'id'=>'csv-form', 'enctype' => 'multipart/form-data'));?>
			<div class="required">
				<?php echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' =>__l('Upload Friends'),'class' =>'browse-field','accept'=>"text/csv,text/plain")); ?>
			</div>
             <div class="submit-block clearfix">
                <?php
                	echo $this->Form->submit(__l('Go'));
                ?>
                </div>
            <?php
            	echo $this->Form->end();
            ?>
	
		<div class="import-note">
		</div>
	</div>
  </div>

</div>
<?php
	}
?>
</div>
</div>
</div>