<?php /* SVN: $Id: view.ctp 7480 2010-06-09 06:19:22Z senthilkumar_017ac09 $ */ ?>
<div class="block1">
    <h2 class="heading-middle"><span class="price-block">
        	<?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price'],false)) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['discounted_price'],false));?>
        </span><?php echo $this->Html->cText($deal['Deal']['name']); ?></h2>
	<div class="clearfix">
    <div class=" js-time-content">
        <div class="clearfix time-content">
            <dl class="time-block list">
            <?php 
                if(empty($deal['Deal']['is_anytime_deal'])){
			?>
                    <dt><?php echo __l('Time Left To Buy');?></dt>
					<dd>					
                        <?php echo $this->Html->time_left(intval(strtotime($deal['Deal']['end_date'].' GMT') - time()));?>                        
                    </dd>                    
              <?php
				  	} else{
			  ?>
                            <dt><?php echo __l('Time Left To Buy');?></dt>
                            <dd>                               
                                <span class="unlimited"><?php echo __l('Unlimited'); ?></span>
                            </dd>
            
            <?php 
					}
            ?>
            </dl>
            
        </div>
    </div>
</div>
    <div class="clearfix deals-block">
        <div class="image-block">
            <?php echo $this->Html->showImage('Deal', $deal['Attachment'][0], array('dimension' => 'iphone_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false)));?>
        </div>
		<div class="clearfix block2">

    <div class="bought-count heading-middle">
        <span><?php echo $deal['Deal']['deal_user_count'].' '.__l('Sold'); ?></span>
    </div>

    <dl class="time-block list">
        <dt>value<dt>
        <dd><?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['original_price'],false)) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['original_price'],false));?></dd>
        <dt>Discount</dt>
        <dd><?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->cFloat($deal['Deal']['discount_percentage']) . "%" : $this->Html->cFloat($deal['SubDeal'][0]['discount_percentage']) . "%"; ?></dd>
        <dt>saving</dt>
        <dd><?php echo (empty($deal['Deal']['is_subdeal_available'])) ?  $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['savings'])) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['savings'])); ?></dd>
    </dl>
	
</div>
		<div class="buy-button">
            <?php
				if(empty($deal['Deal']['is_subdeal_available'])){					
					echo $this->Html->link(__l('Buy'), array('controller' => 'deals', 'action' => 'buy', $deal['Deal']['id']), array('title' => __l('Buy'),'data-theme'=>'b', 'data-role' => 'button', 'class' =>'buy-but'));
				} 
			?>		
            </div>
        
    </div>

</div>


<?php
if(!empty($deal['Deal']['is_subdeal_available'])){
	?>
		<ul data-role="listview">
			<li data-role="list-divider"><?php echo ' '.__l('Choose your deal'); ?><span class="ui-li-count"><?php echo count($deal['SubDeal']);?></span></li>
				<?php foreach($deal['SubDeal'] as $subdeal){ 
					$site_buy_url = Router::url(array('controller' => 'deals', 'action' => 'buy', $deal['Deal']['id'], $subdeal['id']));
					?>
					<li>
					<a href="<?php echo $site_buy_url; ?>" class="fade">
						<h3><?php echo $this->Html->cText($subdeal['name']);?></h3>
						<p>
							<span>
								<strong><?php echo __l('Value');?></strong><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['original_price']));?>
							</span>
							<span>
								<strong><?php echo __l('Discount');?></strong><?php echo $this->Html->cInt($subdeal['discount_percentage']) . "%"; ?>
							</span>	
						</p>
						<p>
							<span>
								<strong><?php echo __l('Save');?></strong><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['savings'])); ?>
							</span>
							<span>
								<strong><?php echo __l('Bought');?></strong><?php echo $this->Html->cInt($subdeal['deal_user_count']); ?>
							</span>
						</p>
						<p class="ui-li-aside">
							<strong><?php echo $this->Html->siteCurrencyFormat($subdeal['discounted_price']); ?></strong>
						</p>
					</a>
					</li>
				<?php } ?>
		</ul>			
	<?php 
	}	
	?>
	<ul data-role="listview">
    	<li data-role="list-divider"><?php echo __l('Description');?></li>
            <li>
            	<?php echo $this->Html->cHtml($deal['Deal']['description']);?>
            </li>
        <li data-role="list-divider"><?php echo __l('Highlights');?></li>
        	<li><?php echo $this->Html->cHtml($deal['Deal']['coupon_highlights']);?></li>
        <?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped): ?>
        <li data-role="list-divider"><?php echo __l('Details');?></li>
        	<li>
            	<p>
				<?php 
                	echo __l('Expire on: ');
					if(empty($deal['Deal']['is_anytime_deal'])){
						echo $this->Html->cDate($end_time = $deal['Deal']['end_date']);
					}
					else{
						echo __l('Any time'); 
					}
                ?>
                </p>
        	</li>
        <?php endif; ?>    
        <li data-role="list-divider"><?php echo __l('Merchant');?></li>
        	<li>
                <p class="address-content">
                    <span><?php echo $this->Html->cHtml($deal['Company']['address1']).', '.$this->Html->cHtml($deal['Company']['address1']);?></span>
                    <span><?php echo $this->Html->cHtml($deal['Company']['City']['name']);?></span>
                    <span><?php echo $this->Html->cHtml($deal['Company']['State']['name']);?></span>
                    <span><?php echo $this->Html->cHtml($deal['Company']['zip']);?></span>
                    <span><?php echo $this->Html->cHtml($deal['Company']['url']);?></span>
                </p>            
        	</li>
	</ul>