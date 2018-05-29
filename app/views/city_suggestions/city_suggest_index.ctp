<?php /* SVN: $Id: admin_index.ctp 3 2010-04-07 06:03:46Z siva_063at09 $ */ ?>
<div class="citySuggestions index js-response">
<?php if(empty($this->request->params['named']['name'])):?>
	<h2><?php echo __l('Recent Suggestions');?></h2>
<?php else:?>
	<h2><?php echo __l('Suggesters');?></h2>
<?php endif;?>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
		<?php if(empty($this->request->params['named']['name'])):?>
			 <th><?php echo __l('City Name');?></th>
		<?php endif;?>
        <th><?php echo __l('User Email');?></th>
        <th><?php echo __l('Suggested On');?></th>
    </tr>
<?php
if (!empty($citySuggestions)):

$i = 0;
foreach ($citySuggestions as $citySuggestion):
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
                                <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $citySuggestion['CitySuggestion']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
        					</ul>
        					</div>
        						<div class="action-bottom-block"></div>
							  </div>

							 </div>
    			</td>
		<?php if(empty($this->request->params['named']['name'])):?>
			<td class = "dl"><?php echo $this->Html->cText($citySuggestion['CitySuggestion']['name']);?></td>
		<?php endif;?>
		<td class = "dl"><?php echo $this->Html->cText($citySuggestion['CitySuggestion']['email']);?></td>
		<td class = "dc"><?php echo $this->Html->cDateTime($citySuggestion['CitySuggestion']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6" class="notice"><?php echo __l('No City Suggestions available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php if (!empty($citySuggestions)) { ?>
	<div class="js-pagination">
    <?php echo $this->element('paging_links'); ?>
    </div>
<?php } ?>
</div>