<?php /* SVN: $Id: admin_index.ctp 69348 2011-10-19 13:27:16Z arovindhan_144at11 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
<div class="userViews index js-response js-responses">
    <h2><?php echo __l('User Views');?></h2>
    <?php echo $this->Form->create('UserView' , array('type' => 'get', 'class' => 'normal search-form clearfix','action' => 'index')); ?>
			<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
			<?php echo $this->Form->submit(__l('Search'));?>
	<?php echo $this->Form->end(); ?>
    <?php echo $this->Form->create('UserView' , array('class' => 'normal js-ajax-form','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <?php echo $this->element('paging_counter');?>
    <table class="list">
        <tr>
            <th><?php echo __l('Select'); ?></th>            
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Viewed Time'),'UserView.created');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Viewed User'), 'ViewingUser.username');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'),'ip');?></div></th>
        </tr>
        <?php
        if (!empty($userViews)):
            $i = 0;
            foreach ($userViews as $userView):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td>
					<div class="actions-block">
						<div class="actions round-5-left">
							 <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userView['UserView']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span>  
							 <span>
								<?php echo $this->Html->link(__l('Ban User IP'), array('controller'=> 'banned_ips', 'action' => 'add', $userView['UserView']['ip']), array('class' => 'network-ip','title'=>__l('Ban User IP'), 'escape' => false));?>
							</span>
						</div>
					</div>
						 <?php echo $this->Form->input('UserView.'.$userView['UserView']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userView['UserView']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
					</td>                   
                    <td><?php echo $this->Html->cDateTimeHighlight($userView['UserView']['created']);?></td>
                    <td>
					<?php echo $this->Html->getUserAvatarLink($userView['User'], 'micro_thumb',false);	?>
                    <?php echo $this->Html->getUserLink($userView['User']);?></td>
                    <td>
					<?php echo $this->Html->getUserAvatarLink($userView['ViewingUser'], 'micro_thumb',false);	?>
					<?php echo !empty($userView['ViewingUser']['username']) ? $this->Html->getUserLink($userView['ViewingUser']) : __l('Guest');?></td>
					<td>
					<?php echo $this->Html->cText($userView['UserView']['ip']);?>
					<?php echo ' ['.$userView['UserView']['dns'].']' . '('. $this->Html->link(__l('whois'), array('controller' => 'users', 'action' => 'whois', $userView['UserView']['ip'], 'admin' => false), array('target' => '_blank', 'title' => __l('whois'), 'escape' => false)) .')';?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="7" class="notice"><?php echo __l('No User Views available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>

    <?php
    if (!empty($userViews)) :
        ?>
        <div class="clearfix">
        <div class="admin-select-block grid_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
            </div>
           <div class="admin-checkbox-button">
                <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
            </div>
         </div>
          <div class="js-pagination grid_right">
            <?php echo $this->element('paging_links'); ?>
        </div>
        <div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>