<?php /* SVN: $Id: admin_index.ctp 71289 2011-11-14 12:28:02Z anandam_023ac09 $ */ ?>
<div class="bannedIps index js-response">
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
  <div class="clearfix page-count-block">
        <div class="grid_left">
          <?php echo $this->element('paging_counter');?>
        </div>
        <div class="clearfix grid_right add-block1">
            <?php echo $this->Html->link(__l('Add'), array('controller' => 'banned_ips', 'action' => 'add'), array('class' => 'add','title' => __l('Add'))); ?>
        </div>
    </div>
    <?php echo $this->Form->create('BannedIp' , array('class' => 'normal', 'action' => 'update')); ?>
		<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
		<table class="list">
			<tr>
            	<th class="select"></th>
                <th class="actions"><?php echo __l('Actions'); ?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Victims'), 'BannedIp.address');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Reason'), 'BannedIp.reason');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Redirect to'), 'BannedIp.redirect');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Date Set'), 'BannedIp.thetime');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Expiry Date'), 'BannedIp.timespan');?></div></th>
			</tr>
			<?php
			if (!empty($bannedIps)):
				$i = 0;
				foreach ($bannedIps as $bannedIp):
					$class = null;
					if ($i++ % 2 == 0) :
						$class = ' class="altrow"';
					endif;
					?>
                    
					<tr<?php echo $class;?>>
					<td class="select">
						 
						<?php echo $this->Form->input('BannedIp.'.$bannedIp['BannedIp']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$bannedIp['BannedIp']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                                	<li>
                                        	<?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $bannedIp['BannedIp']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                                    </li>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
                        
                        </td>
						<td class="dl">
							<?php
								if ($bannedIp['BannedIp']['referer_url']) :
									echo $bannedIp['BannedIp']['referer_url'];
								else:
									echo long2ip($bannedIp['BannedIp']['address']);
									if ($bannedIp['BannedIp']['range']) :
										echo ' - '.long2ip($bannedIp['BannedIp']['range']);
									endif;
								endif;
							?>
						</td>
						<td class="dl"><?php echo $this->Html->cText($bannedIp['BannedIp']['reason']);?></td>
						<td class="dl"><?php echo $this->Html->cText($bannedIp['BannedIp']['redirect']);?></td>
						<td class="dc"><?php echo date('M d, Y h:i A', $bannedIp['BannedIp']['thetime']); ?></td>
						<td class="dc"><?php echo ($bannedIp['BannedIp']['timespan'] > 0) ? date('M d, Y h:i A', $bannedIp['BannedIp']['thetime']) : __l('Never');?></td>
					</tr>
			<?php
				endforeach;
			else:
			?>
				<tr>
					<td colspan="7" class="notice"><?php echo __l('No Banned IPs available');?></td>
				</tr>
			<?php
			endif;
			?>
		</table>
		<?php if (!empty($bannedIps)): ?>
        <div class="clearfix">
         <div class="admin-select-block grid_left">
			<div>
				<?php echo __l('Select:'); ?>
				<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
				<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
			</div>
			<div class="admin-checkbox-button clearfix">
				<?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
			</div>
            <div class="js-pagination grid_right">
				<?php echo $this->element('paging_links'); ?>
			</div>
            </div>
            </div>

			<div class="hide">
				<?php echo $this->Form->submit('Submit');  ?>
			</div>
		<?php endif; ?>
    <?php echo $this->Form->end(); ?>
</div>