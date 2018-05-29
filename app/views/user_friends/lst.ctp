<?php /* SVN: $Id: lst.ctp 78438 2012-08-13 07:36:37Z balamurugan_177at12 $ */ ?>
<div class="userFriends lst">
    <div class="main-content-block js-corner round-5">
		<h2><?php echo __l('My Friends');?></h2>
        <p class="add-block people-information"><?php echo $this->Html->link(sprintf(__l('Find people you know on %s'), Configure::read('site.name')), array('controller' => 'user_friends', 'action' => 'import'), array('class' => 'people-find js-people-find', 'title' => sprintf(__l('Find people you know on %s'), Configure::read('site.name'))));?></p>
     		<div class="js-tabs">
			<ul class="clearfix">
				<li class="received"><em></em><?php echo $this->Html->link(__l('Received Friends Requests'), '#received-request'); ?></li>
				<li class="sent"><em></em><?php echo $this->Html->link(__l('Sent Friends Requests'), '#sent-request'); ?></li>
			</ul>
			<div id="received-request">
	            <div class="friend-lst-block">
					<div class="js-tabs">
						<ul class="clearfix">
							<li class="accepted"><em></em><?php echo $this->Html->link(__l('Accepted'), '#received-accepted'); ?></li>
							<li class="pending"><em></em><?php echo $this->Html->link(__l('Pending'), '#received-pending'); ?></li>
							<li class="rejected"><em></em><?php echo $this->Html->link(__l('Rejected'), '#received-rejected'); ?></li>
						</ul>
						<div id="received-accepted" class="js-responses">
							<?php
								echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Approved, 'type' => 'received', array('cache' => array('config' => 'site_element_cache_2_min', 'key' => $this->Auth->user('id')))));
							?>
						</div>
                        <div id="received-pending" class="js-responses">
                            <?php
                                echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Pending, 'type' => 'received', array('cache' => array('config' => 'site_element_cache_2_min', 'key' => $this->Auth->user('id')))));
                            ?>
                        </div>
						<div id="received-rejected" class="js-responses">
							<?php
								echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Rejected, 'type' => 'received', array('cache' => array('config' => 'site_element_cache_2_min', 'key' => $this->Auth->user('id')))));
							?>
						</div>
					</div>
				</div>
			</div>
			<div id="sent-request">
				<div class="friend-lst-block">
					<div class="js-tabs">
						<ul class="clearfix">
							<li class="accepted"><?php echo $this->Html->link(__l('Accepted'), '#sent-accepted'); ?></li>
                            <li class="pending"><?php echo $this->Html->link(__l('Pending'), '#sent-pending'); ?></li>
							<li class="rejected"><?php echo $this->Html->link(__l('Rejected'), '#sent-rejected'); ?></li>
						</ul>
						<div id="sent-accepted" class="js-responses">
							<?php
								echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Approved, 'type' => 'sent', array('cache' => array('config' => 'site_element_cache_2_min', 'key' => $this->Auth->user('id')))));
							?>
						</div>
                        <div id="sent-pending" class="js-responses">
                            <?php
                                echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Pending, 'type' => 'sent', array('cache' => array('config' => 'site_element_cache_2_min', 'key' => $this->Auth->user('id')))));
                            ?>
                        </div>
						<div id="sent-rejected" class="js-responses">
							<?php
								echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Rejected, 'type' => 'sent', array('cache' => array('config' => 'site_element_cache_2_min', 'key' => $this->Auth->user('id')))));
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
     </div>
</div>

