<?php /* SVN: $Id: admin_index.ctp 5508 2010-05-25 11:48:42Z senthilkumar_017ac09 $ */ ?>
<?php 
	if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message');
	endif;
?>
<div class="businessSuggestions index">
<div class="js-response">   
	<?php echo $this->element('paging_counter');?>
        <table class="list">
            <tr>
                <th class="actions"><?php echo __l('Actions');?></th>
				<th><?php echo $this->Paginator->sort(__l('Email'), 'BusinessSuggestion.email');?></th>
                <th><?php echo __l('User');?></th>
				<th><?php echo __l('Contact Person');?></th>
				<th><?php echo __l('Contact Number');?></th>
				<th><?php echo __l('Company Name');?></th>
                <th><?php echo __l('Suggestion');?></th>
                <th><?php echo __l('Suggested On');?></th>
                
            </tr>
        <?php
        if (!empty($businessSuggestions)):
        
        $i = 0;
        foreach ($businessSuggestions as $businessSuggestion):
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
                                	<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $businessSuggestion['BusinessSuggestion']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
    						  </ul>
    						</div>
    						<div class="action-bottom-block"></div>
						  </div>
				 </div>
				</td>
				<td class="dl">
					<div class="businesssuggestion-email">
					<?php echo $this->Html->cText($businessSuggestion['BusinessSuggestion']['email']);?>
					</div>
				</td>
                <td class="dl"><?php echo !empty($businessSuggestion['User']['username']) ? $this->Html->showImage('UserAvatar', $businessSuggestion['User']['UserAvatar'], array('dimension' => 'micro_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($businessSuggestion['User']['username'], false)), 'title' => $this->Html->cText($businessSuggestion['User']['username'], false))).$this->Html->getUserLink($businessSuggestion['User']) : 'Guest';?></td>
				<td class="dc"><?php echo $businessSuggestion['BusinessSuggestion']['contact_person'];?></td>
				<td class="dc"><?php echo $businessSuggestion['BusinessSuggestion']['contact_number'];?></td>
				<td class="dc"><?php echo $businessSuggestion['BusinessSuggestion']['company_name'];?></td>
                <td class="dl"><?php echo $this->Html->truncate($businessSuggestion['BusinessSuggestion']['suggestion'],200, array('ending' => '...')); if(strlen($businessSuggestion['BusinessSuggestion']['suggestion']) > 199) { echo $this->Html->link(__l('View More'), array('controller' => 'business_suggestions', 'action' => 'view', $businessSuggestion['BusinessSuggestion']['id'], 'admin' => false), array('title' => __l('View More'))); }?></td>
                <td class="dc"><?php echo $this->Html->cDateTimeHighlight($businessSuggestion['BusinessSuggestion']['created']);?></td>
            </tr>
        <?php
            endforeach;
        else:
        ?>
            <tr>
                <td colspan="6" class="notice"><?php echo __l('No Business Suggestions available');?></td>
            </tr>
        <?php
        endif;
        ?>
        </table>
    <?php
    if (!empty($businessSuggestions)) {
		?>
            <?php echo $this->element('paging_links'); ?>
        <?php
    }
    ?>
</div>
</div>