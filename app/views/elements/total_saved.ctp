<?php $total_array = $this->Html->total_saved(); ?>
<dl class="total-list clearfix">
    <dt class="grid_left"><?php echo __l('Total Saved: '); ?></dt>
    <dd class="grid_left"><span><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($total_array['total_saved'])); ?></span></dd>
</dl>
<dl class="total-list clearfix">
    <dt class="grid_left"><?php echo __l('Total Bought: '); ?></dt>
    <dd class="grid_left"><?php echo $this->Html->cInt($total_array['total_bought']); ?></dd>
</dl> 
 