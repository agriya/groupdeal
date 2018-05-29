<?php /* SVN: $Id: $ */ ?>
<?php 
	if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message');
	endif;
?>
<div class="genders index js-response">
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr> 
       <th class="actions"><?php echo __l('Action');?></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Name'),'name');?></div></th>
     </tr>
<?php
if (!empty($genders)):

$i = 0;
foreach ($genders as $gender):
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
                	  <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $gender['Gender']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
				</ul>
			   </div>
				<div class="action-bottom-block"></div>
			  </div>
		 </div>
        </td>
        <td>  <?php echo $this->Html->cText($gender['Gender']['name']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No genders available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($genders)) { ?>
     <div class="js-pagination">
                        <?php echo $this->element('paging_links'); ?>
                    </div>
<?php } ?>
</div>
