<?php /* SVN: $Id: $ */ ?>
	<div class="page-info">
		<?php echo __l('Affiliate module is currently enabled. You can disable or configure it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 9), array('target' => '_blank')). __l(' page');?>
	</div>
<ul class="clearfix filter-list">
    <li class="filter-giftcard"><?php echo $this->Html->link(__l('Waiting for Approval').': '.$this->Html->cInt($waiting_for_approval), array('action' => 'index', 'main_filter_id' => ConstAffiliateRequests::Pending),array('title' => __l('Waiting for Approval'), 'escape' => false)); ?></li>
    <li class="filter-active"><?php echo $this->Html->link(__l('Approved').': '.$this->Html->cInt($approved), array('action'=>'index', 'main_filter_id' => ConstAffiliateRequests::Accepted), array('title' => __l('Accepted'), 'escape' => false)); ?></li>
    <li class="filter-foursquare"><?php echo $this->Html->link(__l('Rejected').': '.$this->Html->cInt($rejected), array('action' => 'index', 'main_filter_id' => ConstAffiliateRequests::Rejected), array('title' => __l('Rejected'), 'escape' => false));?></li>
	<li class="filter-all"><?php echo $this->Html->link(__l('All').': '.$this->Html->cInt($all), array('action' => 'index'), array('title' => __l('All'), 'escape' => false));?></li>
</ul>
<div class="affiliateRequests index">
<div class="clearfix page-count-block">
<div class="grid_left">
    <?php echo $this->element('paging_counter');?>
</div>
<div class="grid_left">
    <?php echo $this->Form->create('AffiliateRequest', array('type' => 'get', 'class' => 'normal search-form', 'action'=>'index')); ?>
      <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
    	<?php echo $this->Form->submit(__l('Search'));?>
	<?php echo $this->Form->end(); ?>
</div>
<div class="clearfix grid_right add-block1">
	<?php echo $this->Html->link(__l('Add'), array('controller' => 'affiliate_requests', 'action' => 'add'), array('class' => 'add', 'title'=>__l('Add'))); ?>
</div>
</div>
<?php echo $this->Form->create('AffiliateRequest' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
 <div class="overflow-block">
<table class="list">
    <tr>
        <th class="select"></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></th>
        <th><?php echo $this->Paginator->sort(__l('Site'), 'site_name');?></th>
        <th><?php echo $this->Paginator->sort(__l('Why Do You Want Affiliate'), 'why_do_you_want_affiliate');?></th>
        <th><?php echo $this->Paginator->sort(__l('Website Marketing?'), 'is_web_site_marketing');?></th>
        <th><?php echo $this->Paginator->sort(__l('Search Engine Marketing?'),'is_search_engine_marketing');?></th>
        <th><?php echo $this->Paginator->sort(__l('Email Marketing'),'is_email_marketing');?></th>
         <th><?php echo $this->Paginator->sort(__l('Promotional Method'),'special_promotional_method');?></th>
        <th><?php echo $this->Paginator->sort(__l('Approved?'),'is_approved');?></th>
    </tr>
<?php
if (!empty($affiliateRequests)):

$i = 0;
foreach ($affiliateRequests as $affiliateRequest):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	if($affiliateRequest['AffiliateRequest']['is_approved'] == ConstAffiliateRequests::Accepted):
		$status_class = 'js-checkbox-active';
	elseif($affiliateRequest['AffiliateRequest']['is_approved'] == ConstAffiliateRequests::Rejected):
		$status_class = 'js-checkbox-inactive';
	elseif($affiliateRequest['AffiliateRequest']['is_approved'] == ConstAffiliateRequests::Pending):
		$status_class = 'js-checkbox-waiting';
	endif;
?>
	<tr<?php echo $class;?>>
         <td class="select"><?php echo $this->Form->input('AffiliateRequest.'.$affiliateRequest['AffiliateRequest']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$affiliateRequest['AffiliateRequest']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                                    <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $affiliateRequest['AffiliateRequest']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                     <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $affiliateRequest['AffiliateRequest']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                                  </ul>
      							</div>
        						<div class="action-bottom-block"></div>
							  </div>
					 </div>
  
        </td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($affiliateRequest['User']['username']), array('controller'=> 'users', 'action'=>'view', $affiliateRequest['User']['username'], 'admin' => false), array('escape' => false));?></td>
		<td class="dl">
		       <?php if(!empty($affiliateRequest['AffiliateRequest']['site_url'])): 
	    	echo $this->Html->link($this->Html->cText($affiliateRequest['AffiliateRequest']['site_name'], false), $affiliateRequest['AffiliateRequest']['site_url'] ,array('title' => $this->Html->cText($affiliateRequest['AffiliateRequest']['site_url'], false), 'target' => '_blank'));
	    else:
			echo $this->Html->cText($affiliateRequest['AffiliateRequest']['site_name']);
		endif; ?>
		<div class="clearfix company-info-block">
       <?php if(!empty($affiliateRequest['AffiliateRequest']['site_url'])): ?><?php echo $this->Html->link($this->Html->cText($affiliateRequest['AffiliateRequest']['site_url'], false), $affiliateRequest['AffiliateRequest']['site_url'] ,array('title' => $this->Html->cText($affiliateRequest['AffiliateRequest']['site_url'], false), 'target' => '_blank', 'class' => 'url'));?> <?php endif; ?>
       <span><?php echo $this->Html->cText($affiliateRequest['SiteCategory']['name']);?></span>
       </div>
		</td>
		<td class="dl"><?php echo $this->Html->truncate($affiliateRequest['AffiliateRequest']['why_do_you_want_affiliate'],200, array('ending' => '...'));?></td>
		<td class="dc"><?php echo $this->Html->cBool($affiliateRequest['AffiliateRequest']['is_web_site_marketing']);?></td>
		<td class="dc"><?php echo $this->Html->cBool($affiliateRequest['AffiliateRequest']['is_search_engine_marketing']);?></td>
		<td class="dc"><?php echo $this->Html->cBool($affiliateRequest['AffiliateRequest']['is_email_marketing']);?></td>
        <td class="dl"><?php echo $this->Html->cText($affiliateRequest['AffiliateRequest']['special_promotional_method']);?></td>
		<td class="dc"><?php if($affiliateRequest['AffiliateRequest']['is_approved'] == 0){
					echo __l('Waiting for Approval');
				  } else if($affiliateRequest['AffiliateRequest']['is_approved'] == 1){
				  	echo __l('Approved');
				  } else if($affiliateRequest['AffiliateRequest']['is_approved'] == 2){
				  	echo __l('Rejected');
				  }
		?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="16" class="notice"><?php echo __l('No Affiliate Requests available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($affiliateRequests)) :
        ?>
        <div class="clearfix">
        <div class="admin-select-block grid_left">
        <div>
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
            <?php echo $this->Html->link(__l('Reject'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Reject'))); ?>
		    <?php echo $this->Html->link(__l('Approve'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Approve'))); ?>
        </div>
        <div class="admin-checkbox-button">
            <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
        </div>
        <div class="grid_right">
            <?php echo $this->element('paging_links'); ?>
        </div>
        </div>    
        <div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>















