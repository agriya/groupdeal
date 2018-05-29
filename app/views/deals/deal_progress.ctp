
<?php 
    if(!empty($deal) && $deal['Deal']['min_limit'] > $deal['Deal']['deal_user_count']):
		$pixels = round(($deal['Deal']['deal_user_count']/$deal['Deal']['min_limit']) * 100);
	?>
		<div class="">
			<?php if($deal['Deal']['deal_user_count'] != 0): ?>
				<span class=""><?php echo $deal['Deal']['deal_user_count'];?></span><?php echo __l('bought'); ?>
			<?php else: ?>
				<span class=""><?php echo __l('Be the first to buy!') ?></span>
			<?php endif; ?>
		</div>
		<div class="">
			<div style="left: <?php echo $pixels; ?>px;" class=""></div>
			<div class=""><div class=""></div>
			<div style="width: <?php echo $pixels; ?>px;" class=""></div>
			</div>
				<span class="">0</span>
				<span class=""><?php echo $deal['Deal']['min_limit'];?></span>
			</div>
			<p class=""><span class=""><?php echo  $deal['Deal']['min_limit']-$deal['Deal']['deal_user_count'];?></span> <?php echo __l('more needed to get the deal'); ?></p>
	<?php else: ?>
			<div class="deal-bought-block">
				<h5 class="deal-bought"><span><?php echo $deal['Deal']['deal_user_count'];?></span><?php echo __l('bought'); ?></h5>
				<p class="deal-start"><?php echo __l('The deal is on!'); ?></p>
				<p class="tipped-info"><?php echo __l('Tipped at ') . $this->Html->cDateTimeHighlight($deal['Deal']['deal_tipped_time']) . __l(' with '). $deal['Deal']['min_limit'] .__l('  bought'); ?></p>
			</div>
<?php  endif; ?>
