<?php /* SVN: $Id: $ */ ?>
<div class="userRelationships index">
<div class="page-count-block clearfix">
    <div class="grid_left">
    <?php echo $this->element('paging_counter');?>
    </div>
    <div class="add-block1 grid_right"><?php echo $this->Html->link(__l('Add'), array('controller' => 'user_relationships', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?></div>
</div>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
       <th><?php echo $this->Paginator->sort('relationship');?></th>
        <th><?php echo $this->Paginator->sort(__l('Active?'),'is_active');?></th>
    </tr>
<?php
if (!empty($userRelationships)):

$i = 0;
foreach ($userRelationships as $userRelationship):
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
                                	<li> <span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $userRelationship['UserRelationship']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span></li>
                                    <li><span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userRelationship['UserRelationship']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></li>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
        
        </td>
		<td><?php echo $this->Html->cText($userRelationship['UserRelationship']['relationship']);?></td>
		<td><?php echo $this->Html->cBool($userRelationship['UserRelationship']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6" class="notice"><?php echo __l('No Relationships available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($userRelationships)) {
    echo $this->element('paging_links');
}
?>
</div>
