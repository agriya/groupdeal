<?php /* SVN: $Id: $ */ ?>
<div class="userIncomeRanges index">
<div class="clearfix page-count-block">
    <div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
    <div class="clearfix grid_right add-block1">
        <?php echo $this->Html->link(__l('Add'), array('controller' => 'user_income_ranges', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
    </div>
</div>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('income');?></th>
        <th><?php echo $this->Paginator->sort(__l('Active?'),'is_active');?></th>
    </tr>
<?php
if (!empty($userIncomeRanges)):

$i = 0;
foreach ($userIncomeRanges as $userIncomeRange):
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
                        <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $userIncomeRange['UserIncomeRange']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                         <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userIncomeRange['UserIncomeRange']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			</div>
         
        </td>
		<td><?php echo $this->Html->cText($userIncomeRange['UserIncomeRange']['income']);?></td>
		<td><?php echo $this->Html->cBool($userIncomeRange['UserIncomeRange']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6" class="notice"><?php echo __l('No Income Ranges available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($userIncomeRanges)) {
    echo $this->element('paging_links');
}
?>
</div>
