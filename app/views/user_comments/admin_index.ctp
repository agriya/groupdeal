<?php /* SVN: $Id: admin_index.ctp 71840 2011-11-18 07:17:47Z anandam_023ac09 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
<div class="userComments index js-response js-responses">
    <?php echo $this->Form->create('UserComment' , array('class' => 'normal js-ajax-form','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<?php echo $this->element('paging_counter');?>
<table class="list">
	<tr>
		<th class="select"></th>
		<th class="actions"><?php echo __l('Action'); ?></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Commented User'), 'PostedUser.username');?></div></th>
		<th><?php echo __l('Comments'); ?></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Commented On'), 'UserComment.created');?></div></th>
	</tr>
<?php
if (!empty($userComments)):

$i = 0;
foreach ($userComments as $userComment):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr <?php echo $class;?>>
		<td class="select">
			<?php echo $this->Form->input('UserComment.' . $userComment['UserComment']['id'] . '.id', array('type' => 'checkbox', 'id' => 'admin_checkbox_' . $userComment['UserComment']['id'], 'class' => 'js-checkbox-list', 'label' => false)); ?>
		</td>
		<td class="actions">
               <div class="action-block">
                        <span class="action-information-block">
                            <span class="action-left-block">&nbsp;
                            </span>
                                <span class="action-center-block">
                                    <span class="action-info">
                                        <?php echo __l('Action');?>
                                     </span>
                                </span>
                            </span>
                            <div class="action-inner-block">
                            <div class="action-inner-left-block">
                                <ul class="action-link clearfix">
                                	<li> <span> <?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $userComment['UserComment']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> </li>
                                    <li><span><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $userComment['UserComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span> </li>
                                    <?php
    		                    	if(!empty($userComment['UserComment']['ip'])): ?>
    			                     <li><?php echo $this->Html->link(__l('Ban User IP'), array('controller'=> 'banned_ips', 'action' => 'add', $userComment['UserComment']['ip']), array('class' => 'network-ip','title'=>__l('Ban User IP'), 'escape' => false));?></li>
    	                            <?php endif; ?>
           						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
		</td>
		<td class="dl">
		<?php echo $this->Html->getUserLink($userComment['User']);?></td>
		<td class="dl">
			<?php echo $this->Html->getUserLink($userComment['PostedUser']);?>
        </td>
		<td class="dl"><?php echo $this->Html->truncate($userComment['UserComment']['comment'], 300, array('ending' => '...'));?></td>
		<td class="dc"><?php echo $this->Html->cDateTimeHighlight($userComment['UserComment']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td class="notice" colspan="9"><?php echo __l('No User Comments available');?></td>
	</tr>
<?php endif; ?>
</table>
<?php
if (!empty($userComments)) { ?>
		<div class="clearfix">
          <div class="admin-select-block grid_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
            </div>
            <div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('options' => $moreActions, 'class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
        </div>
        <div class="hide">
            <?php echo $this->Form->submit('Submit'); ?>
        </div>
 		<div class="grid_right"><?php   echo $this->element('paging_links'); }?></div>
        </div>
<?php echo $this->Form->end(); ?>
</div>
