<?php /* SVN: $Id: admin_index.ctp 71289 2011-11-14 12:28:02Z anandam_023ac09 $ */ ?>
<div class="pages index">
<div class="page-count-block clearfix">
    <div class="grid_left">
     <?php echo $this->element('paging_counter');?>
    </div>
    <div class="clearfix grid_right add-block1">
    	<?php echo $this->Html->link(__l('Add'), array('controller' => 'pages', 'action' => 'add'), array('class' => 'add','title' => __l('Add'))); ?>
    </div>
</div>

<div class="staticpage index">

<div class="overflow-block">
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort(__l('Title'),'title');?></th>
        <th><?php echo $this->Paginator->sort(__l('Content'),'content');?></th>
    </tr>
<?php
if (!empty($pages)):

$i = 0;
foreach ($pages as $page):
	$class = null;
	if ($i++ % 2 == 0) :
		$class = ' class="altrow"';
    endif;
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
                                	 <?php if($page['Page']['slug'] != 'pre-launch'): ?>
                    				<li><?php echo $this->Html->link(__l('View'), array('controller' => 'pages', 'action' => 'view', $page['Page']['slug']), array('class' => 'view', 'title' => __l('View')));?></li>
                    				<?php endif; ?>
                                    <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $page['Page']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                    <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $page['Page']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
			 </div>
        </td>
        <td class="dl">
		<?php echo $this->Html->cText($page['Page']['title']);?>
		</td>
		<td class="dl"><?php echo $this->Html->cText($this->Html->truncate($page['Page']['content'], 100, array('ending' => '...')));?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="17" class="notice"><?php echo __l('No Pages available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($pages)) :
    echo $this->element('paging_links');
endif;
?>

</div>
</div>
