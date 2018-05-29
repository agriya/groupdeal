<div class="index js-response">
<?php echo $this->element('paging_counter'); ?>
<?php if(!empty($user_deals)){ ?>
	<ol class="deal-user-list" >
		<?php foreach($user_deals as $user_deal){ ?>
			<?php
				if(!empty($user_deal['DealUser']['is_used']) && ($user_deal['DealUser']['is_used'] == 1)) {
					$class = 'used';
				} else {
					$class = 'not-used';
				}
			?>
			<li class = "clearfix <?php echo $class;?>">
		
                <div class="company-list-image">
					<?php echo $this->Html->showImage('Deal', $user_deal['Deal']['Attachment'][0], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($user_deal['Deal']['name'], false)), 'title' => $this->Html->cText($user_deal['Deal']['name'], false)));?>
				</div>
				<div class="company-list-content">
					<h3 class="no-pad"><?php echo $this->Html->link($user_deal['Deal']['name'], array('controller' => 'deals', 'action' => 'view', $user_deal['Deal']['slug']),array('title' => sprintf(__l('%s'),$user_deal['Deal']['name'])));?></h3>
					<div class="clearfix purchased-block">
						<span class="purchased sfont textb purchased-info" title="<?php echo __l('Bought Date');?>"><?php echo __l('Purchased On: ');?></span>
						<span class="purchased-info sfont textb"><?php echo $this->Html->cDate($user_deal['DealUser']['created']);?></span>
						<span class="quantity purchased-info sfont textb" title="<?php echo __l('Quantity');?>"><?php echo __l('Quantity: ');?></span>
						<span class="purchased-info sfont textb"><?php echo $this->Html->cInt($user_deal['DealUser']['quantity']);?></span>
					</div>
				</div>
  			</li>
		<?php } ?>
	</ol>
<?php } else { ?>
<div>
	<p class="notice"><?php echo __l('No deals purchased');?></p>
</div>
<?php } ?>


<?php if (!empty($user_deals)) {?>
 <div class="js-pagination">
    <?php echo $this->element('paging_links');?>
</div>
<?php } ?>
</div>