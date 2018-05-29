<?php /* SVN: $Id: $ */ ?>
<div class="dealCoupons index js-response js-responses">
<h2><?php echo __l('Deal Orders/Coupons').' - '.$deal['Deal']['name'];?></h2>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort('coupon_code');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Allocated?'), 'is_used');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('System generated?'), 'is_system_generated');?></div></th>
    </tr>
<?php
if (!empty($dealCoupons)):

$i = 0;
foreach ($dealCoupons as $dealCoupon):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php if(empty($dealCoupon['DealCoupon']['is_used'])):?>
			<div class="actions-block">
				<div class="actions round-5-left">
					<?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $dealCoupon['DealCoupon']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
				</div>
			</div>
			<?php endif;?>
			<?php echo '#' . $this->Html->cText($dealCoupon['DealCoupon']['coupon_code']);?>
		</td>
		<td><?php $is_used = ($dealCoupon['DealCoupon']['Deal']['DealUser'][0]['is_canceled'] == 1) ? 0: $dealCoupon['DealCoupon']['is_used'];
		 echo $this->Html->cBool($is_used);?></td>
		<td><?php echo $this->Html->cBool($dealCoupon['DealCoupon']['is_system_generated']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6" class="notice"><?php echo __l('No Deal Orders/Coupons available');?></td>
	</tr>
<?php
endif;
?>
</table>

	<?php if (!empty($dealCoupons)):?>
		<div class="js-pagination">
			<?php echo $this->element('paging_links'); ?>
		</div>    
	<?php endif;?>
</div>
