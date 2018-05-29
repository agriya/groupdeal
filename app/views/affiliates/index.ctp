<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<?php if($user['User']['is_affiliate_user']): ?>
<div class="affiliates index">
 <div class="clearfix widget-affiliate-information-block">
    <h2><?php echo __l('Affiliate');?></h2>
  
   
    <div class="affiliate-information widget-affiliate-information round-5">
        <ul class="widget-link">
            <li>
             	<?php echo $this->Html->link(__l('Affiliate Cash Withdrawal Requests'), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index'),array( 'title' => __l('Affiliate Cash Withdrawal Requests'))); ?>
            </li>
             <li>
            <?php echo $this->Html->link(__l('Generate Affiliate Widget'), array('controller' => 'affiliates', 'action' => 'generate_widget'),array('title' => __l('Generate Affiliate Widget'))); ?>
            </li>
        </ul>
    </div>
    </div>
      <div class="affiliate-information">
<?php echo $this->element('user_affiliate_stat', array('cache' => array('config' => 'site_element_cache_1_min', 'key' => $this->Auth->user('id')))); ?>

        <div class="clearfix">	
		<div class="share-unique-block">
		<p>
            <?php echo __l('Share your below unique link for referral purposes'); ?>
            	</p>
                <input type="text" class="refer-box" readonly="readonly" value="<?php echo Router::url(array('controller' => 'users', 'action' => 'refer',  'r' =>$this->Auth->user('username')), true);?>" onclick="this.select()"/></div>

		<div class="share-unique-block">
        	<p><?php echo __l('Share your below unique link by appending to end of site URL for referral'); ?>
            </p>
                <input type="text" class="refer-box" readonly="readonly" value="<?php echo  '/r:'.$this->Auth->user('username');?>" onclick="this.select()"/>
  </div>
  </div>
  </div>
  
<h3><?php echo __l('Commission History');?></h3>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th><?php echo $this->Paginator->sort(__l('Created'), 'created');?></th>
        <th><?php echo __l('User').'/'. __l('Deal');?></th>
        <th><?php echo $this->Paginator->sort(__l('Type'), 'AffiliateType.name');?></th>
        <th><?php echo $this->Paginator->sort(__l('Status'), 'AffiliateStatus.name');?></th>
        <th><?php echo $this->Paginator->sort(__l('Commission'), 'commission_amount'). ' ('. Configure::read('site.currency').')';?></th>
    </tr>
<?php
if (!empty($affiliates)):

$i = 0;
foreach ($affiliates as $affiliate):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td> <?php echo $this->Html->cDateTimeHighlight($affiliate['Affiliate']['created']);?></td>
        <td> 
        	<?php 
				if($affiliate['Affiliate']['class'] == 'User'){
			?>	
					<?php echo $this->Html->link($this->Html->cText($affiliate['User']['username']), array('controller'=> 'users', 'action' => 'view', $affiliate['User']['username']), array('escape' => false));?>
			<?php
			   } else{
			?>	
					<?php echo $this->Html->cText($affiliate['DealUser']['Deal']['name']);?>
					(<?php echo $this->Html->link($this->Html->cText($affiliate['DealUser']['User']['username']), array('controller'=> 'users', 'action' => 'view', $affiliate['DealUser']['User']['username'], 'admin' => false), array('escape' => false));?>)
		<?php   } ?>
		</td>
		<td><?php echo $this->Html->cText($affiliate['AffiliateType']['name']);?></td>
		<td>
           <?php echo $this->Html->cText($affiliate['AffiliateStatus']['name']);   ?>
           <?php  if($affiliate['AffiliateStatus']['id'] == ConstAffiliateStatus::PipeLine): ?>
                   <?php echo $this->Html->cDateTimeHighlight($affiliate['Affiliate']['commission_holding_start_date']);?>
           <?php endif; ?>
        </td>

		<td><?php echo $this->Html->cFloat($affiliate['Affiliate']['commission_amount']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6" class="notice"><?php echo __l('No commission history available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($affiliates)) {
    echo $this->element('paging_links');
}
?>
</div>
<?php else: ?>
	<?php echo $this->element('pages-terms_and_policies', array('cache' => array('config' => 'site_element_cache_1_week', 'key' => 'affilates')));?>
	<?php 	
	if($this->Auth->sessionValid()):
		echo $this->element('affiliate_request-add');
    endif;
    ?>
<?php endif; ?>
