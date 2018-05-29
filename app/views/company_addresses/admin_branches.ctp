<?php /* SVN: $Id: admin_index.ctp 62425 2011-08-04 14:23:26Z anandam_023ac09 $ */ ?>
<div class="overflow-block js-responses js-response">
<table class="list">
	<tr>
    	<th class="actions"> <?php echo __l('Actions'); ?> </th>
        <th><?php echo __l('Name');?></th>
		<th><?php echo __l('Branches');?></th>
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
<?php foreach($companies as $company) { ?>
<?php     $class = ($company['Company']['tipped_count'] == 0 && $company['Company']['open_count'] == 0) ? 'nodeal' : ''; ?>
	<tr class="<?php echo $class; ?> main-branch ">
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
                            	<?php echo $this->Html->link(__l('Edit'), array('controller' => 'companies', 'action' => 'edit', $company['Company']['id']), array('class' => 'edit', 'title' => __l('Edit')));?>
                            </li>
                            <?php if(Configure::read('deal.is_live_deal_enabled')) { ?>
                            <li>
                 				<?php echo $this->Html->link(__l('Add Live Deal'), array('controller' => 'deals', 'action' => 'live_add', 'company' => $company['Company']['id']), array('class'=>'add-deal add', 'title' => __l('Add Live Deal')));?>
                            </li>
                            <?php } ?>
						</ul>
						</div>
						<div class="action-bottom-block"></div>
					  </div>
				</div>
         </td> 
         <?php $rowspan = count($company['CompanyAddress'])+1; ?>  
        <td rowspan="<?php echo $rowspan; ?>" class="company-name"><?php echo $this->Html->cText($company['Company']['name']);?></td>
		<td><?php echo $this->Html->cText($company['Company']['address2']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['draft_count']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['pending_approval_count']);?></td>
		<td><?php echo $this->Html->cInt($company['Company']['upcoming_count']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['open_count'] + $company['Company']['tipped_count']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['closed_count']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['paid_to_company_count']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['refunded_count']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['rejected_count']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['canceled_count']);?></td>
        <td><?php echo $this->Html->cInt($company['Company']['expired_count']);?></td>
         <td class="user-count"><?php echo$this->Html->cInt($company['Company']['near_user_count']);?></td>
          <td class="user-count"><?php echo$this->Html->cInt($company['Company']['iphone_near_user_count']);?></td>
	</tr>
    <?php foreach($company['CompanyAddress'] as $company) { ?>
    <?php     $class = ($company['tipped_count'] == 0 && $company['open_count'] == 0) ? 'nodeal' : ''; ?>
	<tr class="<?php echo $class; ?> branches">
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
         <td class="user-count"><?php echo$this->Html->cInt($company['near_user_count']);?></td>
         <td class="user-count"><?php echo$this->Html->cInt($company['iphone_near_user_count']);?></td>
	</tr>
	<?php } }?>
    </table>
</div>

