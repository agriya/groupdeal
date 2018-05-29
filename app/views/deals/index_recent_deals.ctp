<?php /* SVN: $Id: index_recent_deals.ctp 80001 2013-04-24 04:58:54Z balamurugan_177at12 $ */?>
		<div class="live-top round-3 clearfix">
            <h2 class="grid_left no-mar textn live-deal  no-mar recent-title"><?php echo __l(strtoupper($sub_title));?></h2>
                <span class="live-category select-category">
				    <?php echo $this->Html->link(__l('Select Category'), (array('controller' => 'deal_categories', 'action' => 'index', 'type' => 'recent', 'admin' => false,'category_name'=>$category_name)), array('title' => 'Select Category', 'class' => 'js-thickbox-category'));
				?>
			 </span>
				 <span class="live-category">
				    <?php 
					  if(isset($this->params['named']['category']))
					  {
					      echo $dealCategory_name;
					  }
					?>
				 </span> 
				</div>
                <div class="recent-deal-block">
      <ol class="recent-list clearfix">
	   <?php if(!empty($deals)): ?>
	    <?php 
		  $count = 1;
		  foreach($deals as $deal): 
		  if($count%3 == 0)
		  {
		  	$class ='last-deal';
		  } else {
		  	$class =''; 
		  }
			$deal_end_date_detail = explode(" ",$deal['Deal']['end_date']);
			?>
			
			<?php
				if($deal['Deal']['is_subdeal_available']==1)
				{
						$count_subdeal=count($deal['SubDeal']);
						if($deal['Deal']['is_now_deal']){
									$deal_per=$this->Html->cInt($deal['SubDeal'][$count_subdeal-1]['discount_percentage']);
						} else {
							$deal_percentage=array();
							for($i=0;$i<$count_subdeal;$i++)
							{
									$deal_percentage[]=round($deal['SubDeal'][$i]['discount_percentage']);
							}
							sort($deal_percentage);
							$deal_per=implode("% or ",$deal_percentage);
						}
				 }
				 else 
				 {
						$deal_per=$this->Html->cInt($deal['Deal']['discount_percentage']);
				 }
			?>
			
            <li class="round-3 grid_8 pr w-bg clearfix">
              <div class="offer pa"><span><?php echo $deal_per; ?>%</span> OFF</div>
              <p class="deals-time pa"> <span class="month dc"><?php echo date("M" ,strtotime($deal_end_date_detail[0])); ?></span> <span class="date top-space dc"><?php echo date("j" ,strtotime($deal_end_date_detail[0]));?></span> <span class="year dc"><?php echo date("l" ,strtotime($deal_end_date_detail[0]));?></span> </p>
             <div class="clearfix recent-image js-lazyload">
			  <?php  echo $this->Html->link($this->Html->showImage('Deal', $deal['Attachment'][0], array('dimension' => 'recent_deal_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false))),array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('title'=>$deal['Deal']['name'],'escape' =>false));?>
			  </a> </div>
              <div class="price-block pa clearfix">
                <ul class="location-list grid_left pa">
                  <li>	  
				   <?php
							    echo $this->Html->link($deal['DealCategory']['name'] , array('controller' => 'deals', 'action' => 'index', 'type' => 'recent', 'category' => $deal['DealCategory']['slug']),array('title' => $deal['DealCategory']['name']));
				    ?>
				  </li>
                </ul>
                <div class="grid_right clearfix sold-block">
                  <p class="near-bought grid_left">
				  <span title="Eighty Dollars" class="c cr">
                     <?php if(!empty($deal['Deal']['is_subdeal_available'])): ?>
									<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['discounted_price']));?>
								<?php else:?>
									<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price']));?>
					 <?php endif;?>
				  </span></p>
                  <div class="bought-count grid_left"> <span class="c" title="two"><?php echo $this->Html->cInt($deal['Deal']['deal_user_count']);?></span> <span>SOLD</span> </div>
                </div>
              </div>
              <div class="recent-deal-description">
                <h3 class="dc no-pad">
					<?php $deal_title=$this->Html->truncate($deal['Deal']['name'],25,array('ending' => ''))." @ ".$deal['Company']['address1'];?>
                   <?php echo $this->Html->link($deal_title,array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('title' => $deal['Deal']['slug']));?>
                </h3>
              </div>
              <div class="grid_6 omega "> </div>
            </li>           
			<?php $count++; ?>
			  <?php endforeach; ?>
			<?php else: ?>
				<li>
                    <p class="notice"><?php echo __l('No Deals available');?></p>
                </li>
			<?php endif; ?>		   
          </ol>
        	<div class="clearfix">
			<?php
			if (!empty($deals)):
				echo $this->element('paging_links');
			endif;
			?>
		</div>
          </div>