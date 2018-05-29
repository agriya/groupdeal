<?php /* SVN: $Id: $ */ ?>
<div class="ips index">
  <div class="page-count-block clearfix">
  	<div class="grid_left">
         <?php echo $this->element('paging_counter');?>
    </div>
     <div class="grid_left">
        <?php echo $this->Form->create('Ip', array('class' => 'normal search-form', 'action'=>'index')); ?>
    	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
    
    		<?php echo $this->Form->submit(__l('Search'));?>
    
    	<?php echo $this->Form->end();
    	?>
	</div>
	</div>
	<?php
	echo $this->Form->create('Ip', array('class' => 'normal clearfix', 'action'=>'update'));
	 ?>
	 <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>

<div class="overflow-block">
<table class="list">
    <tr>
        <th rowspan="2" class="select"></th>
        <th rowspan="2" class="actions"><?php echo __l('Action');?></th>
        <th rowspan="2" class="dc"><?php echo $this->Paginator->sort(__l('Created'), 'created');?></th>
        <th rowspan="2" class="dc"><?php echo $this->Paginator->sort(__l('IP'), 'ip');?></th>
        <th colspan="5"><?php echo __l('Auto detected'); ?></div></th>
    </tr>
    <tr>
        <th class="dc"><?php echo $this->Paginator->sort(__l('City'), 'City.name');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('State'), 'State.name');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Country'), 'Country.name');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Latitude'), 'latitude');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Longitude'), 'longitude');?></th>
    </tr>    

<?php
if (!empty($ips)):

$i = 0;
foreach ($ips as $ip):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	
		$status_class = 'js-checkbox-deactiveusers';

?>
	<tr<?php echo $class;?>>
       <td><?php echo $this->Form->input('Ip.'.$ip['Ip']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$ip['Ip']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                    <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $ip['Ip']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
				 </ul>
			 </div>
			<div class="action-bottom-block"></div>
			 </div>
		</div>
        </td>
		<td class="dc"><?php echo $this->Html->cDateTimeHighLight($ip['Ip']['created']);?></td>
		<td class="dc"><?php echo $this->Html->cText($ip['Ip']['ip']);?></td>
		<td class="dc"><?php echo $this->Html->cText($ip['City']['name']);?></td>
		<td class="dc"><?php echo $this->Html->cText($ip['State']['name']);?></td>
		<td class="dc"><?php echo $this->Html->cText($ip['Country']['name']);?></td>
		<td class="dc"><?php echo $this->Html->cText($ip['Ip']['latitude']);?></td>
		<td class="dc"><?php echo $this->Html->cText($ip['Ip']['longitude']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="11" class="notice"><?php echo __l('No Ips available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>

<?php
if (!empty($ips)) {
    echo $this->element('paging_links');?>
    <div class="admin-select-block">
<div>
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>    		
        </div>
        <div class="admin-checkbox-button">
            <?php echo $this->Form->input('more_action_id', array('options' => $moreActions,'class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div></div>
<?php
}
 echo $this->Form->end();
?>
</div>