<?php /* SVN: $Id: $ */ ?>
<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
<div class="dealCategories index js-response js-responses">
<div class="page-count-block clearfix">
    <div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
   <div class="grid_left">
    <?php echo $this->Form->create('DealCategory' , array('type' => 'get', 'class' => 'normal search-form clearfix','action' => 'index')); ?>
		<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
		<?php echo $this->Form->submit(__l('Search'));?>
	<?php echo $this->Form->end(); ?>
	</div>
	<div class="add-block1 grid_right">
            <?php echo $this->Html->link(__l('Add'),array('controller'=>'deal_categories','action'=>'add'),array('class' => 'add', 'title' => __l('Add Category')));?>
    </div>
     </div>
    <?php echo $this->Form->create('DealCategory' , array('class' => 'normal js-ajax-form','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
   
<table class="list">
    <tr>
        <th class="select"></th>
        <th class="actions"><?php echo __l('Actions'); ?></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Added On'),'created');?></div></th>
    </tr>
<?php
if (!empty($dealCategories)):

$i = 0;
foreach ($dealCategories as $dealCategory):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td class="select">
        <?php echo $this->Form->input('DealCategory.'.$dealCategory['DealCategory']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$dealCategory['DealCategory']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                    	<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $dealCategory['DealCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $dealCategory['DealCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>

     	</td>
		<td class="dl"><?php echo $this->Html->cText($dealCategory['DealCategory']['name']);?></td>
		<td class="dc"><?php echo $this->Html->cDateTimeHighlight($dealCategory['DealCategory']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="5" class="notice"><?php echo __l('No Live Deal Categories available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($dealCategories)):
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
        </div>
        <div class = "hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        <?php
    echo $this->Form->end();
endif;
?>
</div>
