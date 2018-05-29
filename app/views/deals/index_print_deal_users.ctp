<?php
  echo $this->Html->image('logo.png', array('alt' => 'Logo', 'title' => Configure::read('site.name')));
?>
	<h2><?php echo $this->Html->cText($deal_list['deal_name']);?> </h2>
    <p><?php echo __l('Total Quantities Sold').': '.$this->Html->cInt($deal_list['deal_user_count']);?> </p>
	<?php if(empty($deal_list['is_anytime_deal'])):?>
		<p><?php echo __l('Expires On').': '.$this->Html->cDateTime($deal_list['coupon_expiry_date']);?> </p>
	<?php endif;?>
    <p><?php echo __l('Deal Status').': '.$this->Html->cText($deal_list['deal_status']);?> </p>
    <table border="1">
        <tr>
            <th><?php echo __l('Username'); ?></th>
            <th><?php echo __l('Quantity'); ?></th>
            <th><?php echo __l('Amount'); ?></th>
            <th><?php echo __l('Coupon Code'); ?></th>
            <th><?php echo __l('Status'); ?></th>
        </tr>
    <?php if(!empty($deals)): ?>
    
    <?php foreach($deals as $deal): ?>
        <tr>
            <td><?php echo $this->Html->cText($deal['Deal']['username']);?></td>
            <td><?php echo $this->Html->cInt($deal['Deal']['quantity']); ?></td>
            <td><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discount_amount'])); ?></td>
            <td>
				<ul>
				<?php 
					foreach($deal['Deal']['coupon_code'] as $deal_user_coupon){
						echo '<li>#'.$this->Html->cText($deal_user_coupon['coupon_code']).'</li>';
					}
				?>
				</ul>
			</td>
			<td>
				<ul>
				<?php 
					foreach($deal['Deal']['coupon_code'] as $deal_user_coupon){
						echo '<li>'.(!empty($deal_user_coupon['is_used']) ? __l('Used') : __l('Not used')).'</li>';
					}
				?>
				</ul>
			</td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="11"><?php echo __l('No Deals available');?></td></tr>
    <?php endif; ?>
    </table>
<script>
	window.print();
</script>