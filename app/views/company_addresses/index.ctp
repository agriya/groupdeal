<?php /* SVN: $Id: admin_index.ctp 62425 2011-08-04 14:23:26Z anandam_023ac09 $ */ ?>
<div class="overflow-block js-responses js-response">
<div class="clearfix">
<div class="add-block grid_right">
<?php echo $this->Html->link(__l('Add Branch'),array('controller' => 'company_addresses', 'action' => 'add', 'company_id' => $companies['Company']['id']),array('title'=>__l('Add Branch'), 'class' => 'add')); ?>
<?php if(Configure::read('deal.is_live_deal_enabled')) { ?>
 <?php echo $this->Html->link(__l('Add Live Deal'), array('controller' => 'deals', 'action' => 'live_add'), array('class'=>'add-deal add', 'title' => __l('Add Live Deal')));?>
<?php } ?>
</div>
</div>

<table class="list">
	<tr>
    	<th class="actions"> <?php echo __l('Action'); ?> </th>
		<th class="actions"><?php echo __l('Branches');?></th>
        <?php 
			foreach($dealStatuses as $key => $status){
				if($key != ConstDealStatus::Tipped){
		?>
		<th class="actions"><?php echo __l($status);?></th>
        <?php
				}
			} 
		?>	
        <th class="actions"><?php echo __l('Online Users');?></th>
         <th class="actions"><?php echo __l('iPhone Online Users');?></th>
	</tr>
<?php     
$companies['Company']['open_count'] = $companies['Company']['open_count'] + $companies['Company']['tipped_count'];
$class = ($companies['Company']['open_count'] == 0) ? 'nodeal' : 'main-branch'; ?>
	<tr class="<?php echo $class; ?>">
    	<td class="branch-action actions">
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
                               	<li>
                                  <?php echo $this->Html->link(__l('Edit'), array('controller' => 'companies', 'action' => 'edit', $companies['Company']['id']), array('class' => 'edit', 'title' => __l('Edit')));?>
                                </li>
                                <?php if(Configure::read('deal.is_live_deal_enabled')) { ?>
                                 <li>
                     				<?php echo $this->Html->link(__l('Add Live Deal'), array('controller' => 'deals', 'action' => 'live_add', 'company' => $companies['Company']['id']), array('class'=>'add-deal add', 'title' => __l('Add Live Deal')));?>
                                 </li>
                                    <?php } ?>
        					</ul>
        					</div>
        						<div class="action-bottom-block"></div>
							  </div>

	   </div>
    	
    
         </td>        
		<td><?php echo $this->Html->cText($companies['Company']['address2']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['draft_count']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['pending_approval_count']);?></td>
		<td><?php echo$this->Html->cInt($companies['Company']['upcoming_count']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['open_count']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['closed_count']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['paid_to_company_count']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['refunded_count']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['rejected_count']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['canceled_count']);?></td>
        <td><?php echo$this->Html->cInt($companies['Company']['expired_count']);?></td>
         <td class="user-count"><?php echo $this->Html->cInt($companies['Company']['near_user_count']);?></td>
          <td class="user-count"><?php echo $this->Html->cInt($companies['Company']['iphone_near_user_count']);?></td>
	</tr>
    <?php foreach($companies['CompanyAddress'] as $company) { 
			$company['open_count'] = $company['tipped_count'] + $company['open_count'];
	?>
    <?php     $class = ($company['open_count'] == 0) ? 'nodeal' : 'branches'; ?>
	<tr class="<?php echo $class; ?>">
   	<td class="branch-action actions">
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
                              	<li>
                                	<?php echo $this->Html->link(__l('Edit'), array('controller' => 'company_addresses', 'action' => 'edit', $company['id']), array('class' => 'edit', 'title' => __l('Edit')));?>
                                </li>
                                <li>
                        			<?php echo $this->Html->link(__l('Delete'), array('controller' => 'company_addresses','action' => 'delete', $company['id']), array('class' => 'delete', 'title' => __l('Delete')));?>
                                </li>
                                <?php if(Configure::read('deal.is_live_deal_enabled')) { ?>
                                 <li>
 				                       	<?php echo $this->Html->link(__l('Add Live Deal'), array('controller' => 'deals', 'action' => 'live_add', 'companyaddress' => $company['id']), array('class'=>'add-deal add', 'title' => __l('Add Live Deal')));?>
            	                </li>
                                <?php } ?>
        					</ul>
        					</div>
        						<div class="action-bottom-block"></div>
							  </div>

	   </div>
       </td>
		<td><?php echo $this->Html->cText($company['address2']);?></td>
        <td><?php echo$this->Html->cInt($company['draft_count']);?></td>
        <td><?php echo$this->Html->cInt($company['pending_approval_count']);?></td>
		<td><?php echo$this->Html->cInt($company['upcoming_count']);?></td>
        <td><?php echo$this->Html->cInt($company['open_count'] + $company['tipped_count']);?></td>
        <td><?php echo$this->Html->cInt($company['closed_count']);?></td>
        <td><?php echo$this->Html->cInt($company['paid_to_company_count']);?></td>
        <td><?php echo$this->Html->cInt($company['refunded_count']);?></td>
        <td><?php echo$this->Html->cInt($company['rejected_count']);?></td>
        <td><?php echo$this->Html->cInt($company['canceled_count']);?></td>
        <td><?php echo$this->Html->cInt($company['expired_count']);?></td>
         <td class="user-count"><?php echo $this->Html->cInt($company['near_user_count']);?></td>
         <td class="user-count"><?php echo $this->Html->cInt($company['iphone_near_user_count']);?></td>
	</tr>
	<?php } ?>
    </table>
</div>