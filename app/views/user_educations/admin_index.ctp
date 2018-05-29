<?php /* SVN: $Id: $ */ ?>
<div class="userEducations index">
<div class="clearfix page-count-block">
    <div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
    <div class="clearfix grid_right add-block1"><?php echo $this->Html->link(__l('Add'), array('controller' => 'user_educations', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?></div>
</div>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('education');?></th>
        <th><?php echo $this->Paginator->sort(__l('Active?'),'is_active');?></th>
    </tr>
<?php
if (!empty($userEducations)):

$i = 0;
foreach ($userEducations as $userEducation):
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
                        <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $userEducation['UserEducation']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userEducation['UserEducation']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                      
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>
        </td>
        <td><?php echo $this->Html->cText($userEducation['UserEducation']['education']);?></td>
		<td><?php echo $this->Html->cBool($userEducation['UserEducation']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6" class="notice"><?php echo __l('No Educations available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($userEducations)) {
    echo $this->element('paging_links');
}
?>
</div>
