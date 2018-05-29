<?php /* SVN: $Id: $ */ ?>
<div class="apnsFeedbackLogs index js-response js-responses">
<?php echo $this->element('paging_counter');?>
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Date'), 'created');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'),'User.username');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Device Token'), 'devicetoken');?></div></th>
	   </tr>
	<?php
if (!empty($apnsFeedbackLogs)):
	$i = 0;
	foreach ($apnsFeedbackLogs as $apnsFeedbackLog):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
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
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $apnsFeedbackLog['ApnsFeedbackLog']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
    				    </ul>
    				</div>
    				<div class="action-bottom-block"></div>
    			  </div>
    		</div>
    	</td>
		<td class="dc">
			<?php echo $this->Html->cDateTimeHighlight($apnsFeedbackLog['ApnsFeedbackLog']['created']); ?>
		</td>	
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($apnsFeedbackLog['User']['username'], false), array('controller' => 'users', 'action' => 'view', $apnsFeedbackLog['User']['username'], 'admin' => false)); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($apnsFeedbackLog['ApnsFeedbackLog']['devicetoken']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No Unregistered Devices Feedback available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($apnsFeedbackLogs)) {
    echo $this->element('paging_links');
}
?>
</div>
