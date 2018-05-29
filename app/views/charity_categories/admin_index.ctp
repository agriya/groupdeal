<?php /* SVN: $Id: $ */ ?>
<?php 
	if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message');
	endif;
?>
<div class="charityCategories index js-response">
<div class="page-count-block clearfix">
 <div class="grid_left">
    <?php echo $this->element('paging_counter');?>
    </div>
     <div class="grid_left">
    <?php
    	echo $this->Form->create('CharityCategory' , array('action' => 'admin_index', 'type' => 'get', 'class' => 'normal search-form clearfix ')); //js-ajax-form
    	echo $this->Form->input('CharityCategory.q', array('label' => __l('Keyword')));
    	echo $this->Form->submit(__l('Search'));
    	echo $this->Form->end();
    ?>
    </div>
    <div class="add-block1 grid_right">
    	<?php echo $this->Html->link(__l('Add'), array('controller'=>'charity_categories','action'=>'add'),array('title' => __l('Add'), 'class' =>'add'));?>
    </div>
    </div>
<?php echo $this->Form->create('CharityCategory' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<div class="overflow-block">
<table class="list">
    <tr>        
		<th class="select"></th>
		<th class="actions"><?php echo __l('Actions');?></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Category'), 'name');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Charities'), 'charity_count');?></div></th>
    </tr>
<?php
if (!empty($charityCategories)):

$i = 0;
foreach ($charityCategories as $charityCategory):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td class="select">
        		<?php echo $this->Form->input('CharityCategory.'.$charityCategory['CharityCategory']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$charityCategory['CharityCategory']['id'], 'class' => 'js-checkbox-list', 'label' => false)); ?>
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
                    	<li><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $charityCategory['CharityCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span></li>
                        <li><span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $charityCategory['CharityCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></li>
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>
	
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($charityCategory['CharityCategory']['name']);?></td>
		<td class="dr"><?php echo $this->Html->link($this->Html->cInt($charityCategory['CharityCategory']['charity_count'], false), array('controller' => 'charities', 'action' => 'index', 'charity_category_id' => $charityCategory['CharityCategory']['id']));?></td>		
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="5" class="notice"><?php echo __l('No Charity Categories available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
	<?php if (!empty($charityCategories)) {?>
      <div class="clearfix">
      <div class="admin-select-block grid_left">
			<div>
				<?php echo __l('Select:'); ?>
				<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
				<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
			</div>
			<div class="admin-checkbox-button">
				<?php echo $this->Form->input('more_action_id', array('options' => $moreActions, 'class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
			</div>
		</div>
		<div class="js-pagination grid_right">
			<?php echo $this->element('paging_links');?>
		</div>
        </div>
		<div class="hide">
			<?php echo $this->Form->submit(__l('Submit'));  ?>
		</div>
		<?php echo $this->Form->end(); ?>
	<?php }?>
</div>
