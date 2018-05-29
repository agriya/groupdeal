<?php  if(!empty($deals) && !empty($has_near_by_deal)): ?>
<div class="blue-bg bot-mspace top clearfix">
    <h3><?php echo __l('Nearby Deals');?> </h3>
         <ol class="side-deal-list">
            <?php	foreach($deals as $deal):?>
          <li class="round-5 pr no-pad">
              <div class="deal-info pa">
                <p><?php echo round($deal['Deal']['discount_percentage']);?>% OFF</p>
              </div>
              <?php echo $this->Html->link($this->Html->showImage('Deal', $deal['Attachment'][0], array('dimension' => 'sidebar_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false))), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('title' =>sprintf(__l('%s'),$deal['Deal']['name']), 'escape' => false));?>
              <div class="deal-value-info pa textb clearfix">
				<span>
					<?php if(!empty($deal['Deal']['is_subdeal_available'])): ?>
						<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['discounted_price']));?>
					<?php else:?>
						<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price']));?>
					<?php endif;?>
				</span>
				    <?php echo $this->Html->link(__l('View it'), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('title' => __l('View it')), null, false);?>
			  </div>
                <h4 class="dc space sfont"><?php echo $this->Html->link($this->Html->cText($deal['Deal']['name']), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('escape' => false, 'title' =>sprintf(__l('%s'),$deal['Deal']['name'])));?></h4>
                <?php /*<div class="clearfix">
                   <div class="deal-button dc deal-price-r grid_3 grid_right alpha">
                             <div class="clearfix">
                               <div class="deal-currency textb left-space">
        							<?php if(!empty($deal['Deal']['is_subdeal_available'])): ?>
        								<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['discounted_price']));?>
        							<?php else:?>
        								<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price']));?>
        							<?php endif;?>
    							</div>
							  </div>
                        </div>
                    </div>
                */?>
              </li>
              <?php endforeach;?>
               <li class="dr no-border view-all ver-space clearfix"><?php echo $this->Html->link(__l('View all'), array('controller' => 'deals', 'action' => 'index', 'type' => 'near'),array('title' => __l('View all')), null, false);?></li>
        </ol>
</div>
  <?php  endif; ?>