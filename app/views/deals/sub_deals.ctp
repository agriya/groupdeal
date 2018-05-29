<?php /* SVN: $Id: $ */ ?>
<div class="subdeal index js-response js-responses">
<h2><?php echo __l('Sub Deals');?></h2>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Deal'),'Deal.name'); ?></div></th>
        <?php if($is_live_deal): ?>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Date Start/End'), 'Deal.start_date'); ?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Coupon Date Start/Exp'), 'Deal.coupon_start_date'); ?></div></th>
        <?php endif; ?>
		<th colspan="3"><?php echo __l('Price');?></th>
		<th colspan="2"><?php echo __l('User Limit');?></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Quantities Sold'),'Deal.deal_user_count'); ?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Total Purchased Amount'),'Deal.total_purchased_amount'); ?></div></th>
    </tr>
    <tr>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Original Price'), 'Deal.original_price'); ?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Discounted Price'),'Deal.discounted_price');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Discount Amount'), 'Deal.discount_amount').' ('.Configure::read('site.currency').')';?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Minimum'),'Deal.min_limit'); ?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Maximum'),'Deal.max_limit'); ?></div></th>
    </tr>
<?php
if (!empty($subDeals)):

$i = 0;
$sub_deal_count =  count($subDeals);
foreach ($subDeals as $subDeal):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class; ?>>
        <td><?php echo $subDeal['Deal']['name']; ?></td>
        <?php if($is_live_deal): ?>
        <td class="dc"><?php echo $this->Html->cDateTime($subDeal['Deal']['start_date']);?> <?php echo (!is_null($subDeal['Deal']['end_date']))? $this->Html->cDateTime($subDeal['Deal']['end_date']): ' - ';?></td>
        <td class="dc"><?php echo $this->Html->cDateTime($subDeal['Deal']['coupon_start_date']);?> <?php echo (!is_null($subDeal['Deal']['coupon_expiry_date']))? $this->Html->cDateTime($subDeal['Deal']['coupon_expiry_date']): ' - ';?></td>
        <?php endif; ?>
        <td class="dr"><?php echo $this->Html->cCurrency($subDeal['Deal']['original_price']); ?></td>
        <td class="dr"><?php echo $this->Html->cCurrency($subDeal['Deal']['discounted_price']); ?></td>
        <td class="dr"><?php echo $this->Html->cCurrency($subDeal['Deal']['discount_amount']); ?></td>
        <?php if($i == 1): ?>
        <td class="dr" rowspan="<?php echo $sub_deal_count; ?>"><?php echo $this->Html->cInt($subDeal['Deal']['min_limit']); ?></td>
        <?php endif; ?>
        <td class="dr"><?php echo (!empty($subDeal['Deal']['max_limit']) ? $this->Html->cInt($subDeal['Deal']['max_limit']) : __l('No Limit')); ?></td>
        <td class="dr"><?php echo $this->Html->cInt($subDeal['Deal']['deal_user_count']); ?></td>
        <td class="dr"><?php echo $this->Html->cCurrency($subDeal['Deal']['total_purchased_amount']); ?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6" class="notice"><?php echo __l('No Sub Deal available');?></td>
	</tr>
<?php
endif;
?>
</table>
	<?php if (!empty($subDeals)):?>
		<div class="js-pagination">
			<?php echo $this->element('paging_links'); ?>
		</div>    
	<?php endif;?>
</div>
