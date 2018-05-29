<?php /* SVN: $Id: $ */ ?>
<?php 
	if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message');
	endif;
?>
<div class="charities index js-response">
	<div class="page-info">
		<?php echo __l('Charity module is currently enabled. You can disable or configure it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 8), array('target' => '_blank')). __l(' page');?>
	</div>
<div class="info-details">
	<p>
		<?php echo __l('Using charity feature, site can support charities on every purchase. Charities are to be added and managed by site admin. All contributions for charities will be accumulated in their wallet. Once charity amount is accumulated in wallet, site admin may directly pay/clear to charity if "Transfer Account" is configured. When site admin processes the payment manually through offline mode, he may also mark it as paid/cleared.');?>
	</p>
</div>
<div class="page-count-block clearfix">
	<div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
    <div class="grid_left">
    <?php
    	echo $this->Form->create('Charity' , array('action' => 'admin_index', 'class' => 'normal search-form clearfix ')); //js-ajax-form
    	echo $this->Form->input('Charity.q', array('label' => __l('Keyword')));
    	echo $this->Form->submit(__l('Search'));
    	echo $this->Form->end();
    ?>
    </div>
     <div class="add-block1 grid_right">
    	<?php echo $this->Html->link(__l('Add'),array('controller'=>'charities','action'=>'add'),array('title' => __l('Add'),	'class' =>'add'));?>
        <?php echo $this->Html->link(__l('Payment History'),array('controller'=>'charity_cash_withdrawals','action'=>'index'),array('class' => 'widthdraw', 'title' => __l('Payment History')));?>
    </div>
</div>
<?php echo $this->Form->create('Charity' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<div class="overflow-block">
    <table class="list">
        <tr>
            <th rowspan="2" class="select"></th>
            <th rowspan="2" class="actions"><?php echo __l('Action');?></th>
            <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Charity'),'name');?></div></th>
            <th rowspan="2"><?php echo __l('Transfer Account');?></th>
            <th colspan="3"><?php echo __l('Share');?></th>
            <th colspan="2"><?php echo __l('Amount');?></th>
        </tr>
      	<tr> 
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Merchant'),'total_seller_amount');?></div></th>		
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Site'),'total_site_amount');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Total'),'total_amount');?><div><?php echo '('.__l('Merchant').' + '.__l('Site').')'; ?></div></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Paid').'/'.__l('Cleared'),'paid_amount');?></div></th>		
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Wallet'),'available_amount');?></div></th>
    	</tr>
<?php
if (!empty($charities)):

$i = 0;
foreach ($charities as $charity):
	$class = null;
	$active_class = '';
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
	if($charity['Charity']['is_active']):
		$status_class= 'js-checkbox-active';
	else:
		$status_class= 'js-checkbox-inactive';
		$active_class = ' inactive-record';
	endif;
?>
	<tr class="<?php echo $class;?><?php echo $active_class; ?>">
		<td class="select">
			<?php echo $this->Form->input('Charity.'.$charity['Charity']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$charity['Charity']['id'], 'class' => $status_class.' js-checkbox-list', 'label' => false)); ?>
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
                        	<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $charity['Charity']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
							<li><?php echo $this->Html->link(__l('Edit Transfer Account'), array('controller' => 'charity_money_transfer_accounts', 'action' => 'index', $charity['Charity']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit Transfer Account')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $charity['Charity']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
    					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			</div>
		</td>
		<td class="dl">
       <?php if(!empty($charity['Charity']['url'])): 
	    	echo $this->Html->link($this->Html->cText($charity['Charity']['name'], false), $charity['Charity']['url'] ,array('title' => $this->Html->cText($charity['Charity']['url'], false), 'target' => '_blank'));
	    else:
			echo $this->Html->cText($charity['Charity']['name']);
		endif; ?>
       <div class="clearfix company-info-block">
       <?php if(!empty($charity['Charity']['url'])): ?><?php echo $this->Html->link($this->Html->cText($charity['Charity']['url'], false), $charity['Charity']['url'] ,array('title' => $this->Html->cText($charity['Charity']['url'], false), 'target' => '_blank', 'class' => 'url'));?> <?php endif; ?>
       <span><?php echo $this->Html->cText($charity['CharityCategory']['name']);?></span>
       </div>
		</td>
		<td><?php
			if(!empty($charity['CharityMoneyTransferAccount'])):
				foreach($charity['CharityMoneyTransferAccount'] as $charityMoneyTransferAccount):
				  if(!empty($charityMoneyTransferAccount['PaymentGateway'])):?>
                  <div class="paypal-status-info">
					<span class="paypal"><?php echo $this->Html->cText($charityMoneyTransferAccount['PaymentGateway']['display_name']); ?></span>
					<span><?php echo $this->Html->cText($charityMoneyTransferAccount['account']); ?></span>
                    </div>
            	<?php 
				endif;		
				endforeach;
			endif;		
		?></td>
		<td class="dr"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($charity['Charity']['total_seller_amount'])); ?></td>
		<td class="dr"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($charity['Charity']['total_site_amount'])); ?></td>
		<td class="dr"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($charity['Charity']['total_amount'])); ?></td>
		<td class="paid-amount dr"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($charity['Charity']['paid_amount'])); ?></td>
		<td class="site-amount dr"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($charity['Charity']['available_amount'])); ?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="9" class="notice"><?php echo __l('No Charities available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
	<?php if (!empty($charities)) {?>
      <div class="clearfix">
		<div class="admin-select-block grid_left">
			<div>
				<?php echo __l('Select:'); ?>
				<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
				<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
				<?php echo $this->Html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved','title' => __l('Active'))); ?>
				<?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending','title' => __l('Inactive'))); ?>
			</div>
			<div class="admin-checkbox-button">
				<?php echo $this->Form->input('more_action_id', array('options' => $moreActions, 'class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
			</div>
		</div>
		<div class="js-pagination grid_right">
			<?php echo $this->element('paging_links');?>
		</div>
        </div>
		<div class="hide">
			<?php echo $this->Form->submit(__l('Submit'));  ?>
		</div>
		<?php echo $this->Form->end(); ?>
	<?php }?>
</div>
