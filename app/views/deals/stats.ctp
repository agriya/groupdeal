<div>
   <div class="side1-tl">
        <div class="side1-tr">
          <div class="side1-tm"> </div>
        </div>
     </div>
     <div class="side1-cl">
        <div class="side1-cr">
            <div class="block1-inner main-block-inner1 clearfix">
		<h3><?php echo __l('Deal Information'); ?></h3>
		<dl class="list clearfix">
			<dt><?php echo __l('Deal');?></dt>
				<dd>
					<?php echo $this->Html->cText($deal['Deal']['name']);?>
				</dd>
			<dt><?php echo __l('Current Deal Status');?></dt>
				<dd>
					<?php echo $this->Html->cText($deal['DealStatus']['name']);?>
				</dd>
			<?php if(!empty($deal['City'])):?>
			<dt><?php echo __l('Locations');?></dt>
				<dd>
					<?php 
						foreach($deal['City'] as $cities):
    						?>
    						<p>
    						<?php
    							echo $cities['name'];
                            ?>
                            </p>
                            <?php
						endforeach;
					?>
				</dd>	
			<?php endif;?>
			<dt><?php echo __l('Deal Lifetime');?></dt>
				<dd>
                    <p><?php echo __l('Created On').' '.$this->Html->cDateTime($deal['Deal']['created']);?></p>
    				<p><?php echo __l('Start(ed) On').' '.$this->Html->cDateTime($deal['Deal']['start_date']);?></p>
	    			<p><?php echo __l('End(ed) On').' '.$this->Html->cDateTime($deal['Deal']['end_date']);?></p>
                </dd>
             </dl>   


		<h3><?php echo __l('Deal Sales/Purchase Information'); ?></h3>
		<dl class="list clearfix">
        	<?php if(!$deal['Deal']['is_now_deal']) { ?>
			<dt><?php echo __l('Coupon Expires On');?></dt>
				<dd>
					<?php echo $this->Html->cDateTime($deal['Deal']['coupon_expiry_date']);?>
				</dd>
            <?php } ?>    
			<dt><?php echo __l('Total Purchases');?></dt>
				<dd>
					<?php echo $this->Html->cInt($deal['Deal']['deal_user_count']);?>
			    </dd>
		</dl>
	</div>
	</div>
	</div>
    <div class="side1-bl">
        <div class="side1-br">
          <div class="side1-bm"> </div>
        </div>
  </div>
</div> 