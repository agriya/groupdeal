<?php /* SVN: $Id: admin_index.ctp 77092 2012-04-04 10:36:02Z mohanraj_109at09 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
<div class="userLogins index js-response js-responses">
<div class="page-count-block clearfix">
	<div class="grid_left">
    <?php echo $this->element('paging_counter');?>
   </div>
   	<div class="grid_left">
        <?php echo $this->Form->create('UserLogin' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
    		<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
    		<?php echo $this->Form->submit(__l('Search'));?>
    	<?php echo $this->Form->end(); ?>
       </div>
 </div>
        <?php echo $this->Form->create('UserLogin' , array('class' => 'normal js-ajax-form','action' => 'update')); ?>
        <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
 
    <div class="overflow-block">
    <table class="list">
        <tr>
            <th class="select"></th>
            <th class="actions"><?php echo __l('Actions'); ?></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Login Time'), 'UserLogin.created');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Login IP'), 'Ip.ip');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User Agent'), 'UserLogin.user_agent');?></div></th>
        </tr>
        <?php
        if (!empty($userLogins)):
            $i = 0;
            foreach ($userLogins as $userLogin):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                	<td class="select">
				    	<?php echo $this->Form->input('UserLogin.'.$userLogin['UserLogin']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userLogin['UserLogin']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                                	<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userLogin['UserLogin']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
						          	<li>
						              	<?php echo $this->Html->link(__l('Ban Login IP'), array('controller'=> 'banned_ips', 'action' => 'add', $userLogin['Ip']['ip']), array('class' => 'network-ip','title'=>__l('Ban Login IP'), 'escape' => false));?>
                                    </li>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
  					</td>
				
                    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($userLogin['UserLogin']['created']);?></td>
                    <td class="dl">
					<?php echo $this->Html->getUserAvatarLink($userLogin['User'], 'micro_thumb',false);	?>
                    <?php echo $this->Html->getUserLink($userLogin['User']);?></td>
						<td class="dl">
                        <?php if(!empty($userLogin['Ip']['ip'])): ?>							  
                            <?php echo  $this->Html->link($userLogin['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $userLogin['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$userLogin['UserLogin']['dns'], 'escape' => false));								
							?>
							<p>
							<?php 					
                            if(!empty($userLogin['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($userLogin['Ip']['Country']['iso2']); ?>" title ="<?php echo $userLogin['Ip']['Country']['name']; ?>">
									<?php echo $userLogin['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif; 
							 if(!empty($userLogin['Ip']['City'])):
                            ?>             
                            <span> 	<?php echo $userLogin['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>    
						</td>

                    <td class="dl"><?php echo $this->Html->cText($userLogin['UserLogin']['user_agent']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="6" class="notice"><?php echo __l('No User Logins available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    </div>

    <?php
    if (!empty($userLogins)) :
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
        <div class = "hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>