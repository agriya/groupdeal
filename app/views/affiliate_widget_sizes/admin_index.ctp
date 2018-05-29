<?php /* SVN: $Id: $ */ ?>
<div class="affiliateWidgetSizes index js-response">
	<div class="page-info">
		<?php echo __l('Affiliate module is currently enabled. You can disable or configure it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 9), array('target' => '_blank')). __l(' page');?>
	</div>
<div class="clearfix page-count-block">
    <div class="grid_left">
         <?php echo $this->element('paging_counter');?>
    </div>
    <div class="clearfix grid_right add-block1">
        <?php echo $this->Html->link(__l('Add'), array('controller' => 'affiliate_widget_sizes', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
    </div>
</div>

<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Action');?> </th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Created On'),'created');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
        <th><?php echo __l('Logo');?></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort('width');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort('height');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Display Side Deal?'),'is_display_side_deal');?><span class="small-info" title="<?php echo __l('On enabled, side deals will be also be listed in widget'); ?>"><?php echo __l('On enabled, side deals will be also be listed in widget'); ?></span></div></th>
    </tr>
<?php
if (!empty($affiliateWidgetSizes)):

$i = 0;
foreach ($affiliateWidgetSizes as $affiliateWidgetSize):
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
                               <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $affiliateWidgetSize['AffiliateWidgetSize']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $affiliateWidgetSize['AffiliateWidgetSize']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
    						</ul>
    						</div>
					       	<div class="action-bottom-block"></div>
					  </div>
			</div>
        </td>
         <td>
        	<?php echo $this->Html->cDateTimeHighLight($affiliateWidgetSize['AffiliateWidgetSize']['created']);?>
        </td>
		<td><?php echo $this->Html->cText($affiliateWidgetSize['AffiliateWidgetSize']['name']);?></td>
        <td><?php echo $this->Html->showImage('AffiliateWidgetSize', $affiliateWidgetSize['Attachment'], array('dimension' => 'original', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($affiliateWidgetSize['AffiliateWidgetSize']['name'], false)), 'title' => $this->Html->cText($affiliateWidgetSize['AffiliateWidgetSize']['name'], false)));?>                      
        </td>
		<td><?php echo $this->Html->cInt($affiliateWidgetSize['AffiliateWidgetSize']['width']);?></td>
		<td><?php echo $this->Html->cInt($affiliateWidgetSize['AffiliateWidgetSize']['height']);?></td>
		<td><?php echo $this->Html->cBool($affiliateWidgetSize['AffiliateWidgetSize']['is_display_side_deal']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Affiliate Widget Sizes available');?></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($affiliateWidgetSizes)):?>
<div class="js-pagination">
            <?php echo $this->element('paging_links');?>
</div>
<?php endif; ?>
</div>






