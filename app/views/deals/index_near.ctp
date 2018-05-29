<?php /* SVN: $Id: index_recent_deals.ctp 44785 2011-02-19 10:54:51Z aravindan_111act10 $ */?>
<div class="js-response js-dialog-over-block">
  <div class="recentread-side1">
    <div class="side1-tl">
                  <div class="side1-tr">
                    <div class="side1-tm"> </div>
                  </div>
                </div>
                <div class="side1-cl">
                  <div class="side1-cr">
                    <div class="block1-inner clearfix">
            <h2><?php echo $sub_title;?> </h2>
        <?php echo $this->element('paging_counter'); ?>
	 </div>
                  </div>
                </div>
                <div class="side1-bl recent-side1-bl">
                  <div class="side1-br">
                    <div class="side1-bm"> </div>
                  </div>
                </div>

   <div class="side1-tl">
                  <div class="side1-tr">
                    <div class="side1-tm"> </div>
                  </div>
                </div>
                <div class="side1-cl">
                  <div class="side1-cr">
                    <div class="block1-inner clearfix">
     	<ol class="main-list clearfix">
		<?php if(!empty($deals) && (!empty($has_near_by_deal) || $this->request->params['named']['type'] == 'main' || $this->request->params['named']['type'] == 'side')): ?>
		  
		  <?php 
		  $count = 1;
		  foreach($deals as $deal): 
		  if($count%3 == 0)
		  {
		  	$class ='last-deal';
		  } else {
		  	$class =''; 
		  }
		  	?>
            <li class="near-list clearfix  <?php echo $class; ?>"  >

            <div class="top-wrapper clearfix">
              
              <div class="livedeal-wrapper pr clearfix">
                <div class="livedeal-image-block pr grid_left"><?php  echo $this->Html->link($this->Html->showImage('Deal', $deal['Attachment'][0], array('dimension' => 'live_deal_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false))),array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('title'=>$deal['Deal']['name'],'escape' =>false));?>
                   <div class="price-block pa clearfix">
                    <div class="grid_right clearfix">
                      <p class="price grid_left"><?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price'])) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['discounted_price']));?></p>
                      <div class="clearfix near-deal-buy-block grid_3  omega">
                       	<?php
					if($this->Html->isAllowed($this->Auth->user('user_type_id')) && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval):
						if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped):
							if(empty($deal['Deal']['is_subdeal_available'])){
								 echo $this->Html->link(__l('Buy Now'), array('controller'=>'deals','action'=>'buy',$deal['Deal']['id']), array('title' => __l('Buy Now'),'class' =>'button dc'));
							}
							else{
								 echo $this->Html->link(__l('Buy Now'), '#', array('title' => __l('Buy Now'),'class' =>"button dc near-button-multi js-multiple-sub-deal {'opendialog': 'js-open-subdeal-".$deal['Deal']['id']."'}"));
							?>
                            	<div  id="js-open-subdeal-<?php echo $deal['Deal']['id']; ?>">
                                   <h2><?php echo ' '.__l('Choose your deal').':'; ?> </h2>
                                	<ol class="multi-deal-list">
                                    	<?php foreach($deal['SubDeal'] as $subdeal){ ?>
                                    	<li class="clearfix">
                                                <div class="multi-left-block grid_left">
                                                	 <h3 class="sub-deal-title"><?php echo $this->Html->cText($subdeal['name']);?></h3>
                                                     <div class="clearfix">
                                                        <dl class="price-count-list">
                                                            <dt class="value dc sfont"><?php echo __l('Value');?></dt>
                                                            <dd class="dc sfont"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['original_price']));?></dd>
                                                        </dl>
                                                        <dl class="price-count-list">
                                                            <dt class="dc sfont"><?php echo ' - '.__l('Discount');?></dt>
                                                            <dd class="dc sfont"><?php echo $this->Html->cInt($subdeal['discount_percentage']) . "%"; ?></dd>
                                                        </dl>
                                                        <dl class="price-count-list">
                                                            <dt class="dc sfont"><?php echo ' - '.__l('Save');?></dt>
                                                            <dd class="dc sfont"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['savings'])); ?></dd>
                                                         </dl>
                                                        </div>
                                                </div>
                                                <div class="multi-right-block textb grid_right">
                                                  <div class="clearfix near-price-block">
                                                        <div class="grid_3 omega alpha ">
                                                           <p class="near-bought main-bought">
                                                            <?php echo $this->Html->siteCurrencyFormat(($subdeal['discounted_price'])); ?>
                                                            </p>
                                                        </div>
                                                        <div class="clearfix near-deal-buy-block grid_3  omega">
                                                            <?php if( !empty($subdeal['max_limit']) && $subdeal['deal_user_count'] >= $subdeal['max_limit']):?>
                                                                    <?php echo __l('sold out'); ?>
                                                            <?php else: ?>
                                                                    <?php echo $this->Html->link(__l('Buy Now'), array('controller'=>'deals','action'=>'buy', $deal['Deal']['id'], $subdeal['id']),array('class'=>'button dc','title' => __l('Buy').' - '.$this->Html->siteCurrencyFormat($subdeal['discounted_price']),'escape' => false));?>
                                                            <?php endif;?>
                                                        </div>
                                                       </div>
                                                       <div class="bought-count dc main-bought-count">
    							                             <span title="three" class="c"><?php echo $this->Html->cInt($subdeal['deal_user_count']); ?></span>
                                                             <span class="sold-info">  <?php echo ' '.__l('Bought');?></span>
    						                          </div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                            </ol>
                                </div>
                            <?php

							}
						elseif($this->Html->isAllowed($this->Auth->user('user_type_id')) && $deal['Deal']['deal_status_id'] == ConstDealStatus::Upcoming):
						?>
							<span class="no-available dc" title="<?php echo __l('Upcoming');?>"><?php echo __l('Upcoming');?></span>
						<?php
							else:
						?>
							<span class="no-available dc" title="<?php echo __l('No Longer Available');?>"><?php echo __l('No Longer Available');?></span>
						<?php
						endif;
					endif;
                ?>
                     </div>
                      
                      
                      </div>
                  </div>
                  <div class="deal-list-block clearfix">
                    <dl class="deal-value dc grid_3 omega alpha clearfix">
                                <dt class="value sfont"><?php echo __l('Value'); ?></dt>
				            	<dd class="textb">
								<?php if(!empty($deal['Deal']['is_subdeal_available'])): ?>
									<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['original_price']));?>
								<?php else:?>
									<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['original_price']));?>
								<?php endif;?>
                                </dd>
                    </dl>
                    <dl class="deal-discount dc grid_2  omega alpha clearfix">
                             <dt class="sfont"><?php echo __l('Discount');?></dt>
    					        <dd class="textb">
								<?php if(!empty($deal['Deal']['is_subdeal_available'])): ?>
                                    <?php echo $this->Html->cInt($deal['SubDeal'][0]['discount_percentage']) . "%"; ?>
								<?php else:?>
									<?php echo $this->Html->cInt($deal['Deal']['discount_percentage']) . "%"; ?>
								<?php endif;?>
                                </dd>
                    </dl>
                    <dl class="deal-save dc grid_3 omega alpha  clearfix">
                         <dt class="sfont"><?php echo __l('Savings'); ?></dt>
                         <dd class="textb">
                        <?php if(!empty($deal['Deal']['is_subdeal_available'])): ?>
                            <?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['discount_amount'])); ?>
                        <?php else:?>
                            <?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discount_amount'])); ?>
                        <?php endif;?>

                         </dd>
                    </dl>
                  </div>


                </div>
                <div class="main-deal-desc grid_14 round-10">
                <h2 class="main-title no-mar no-border"><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($deal['Deal']['name']), 30, array('ending' => '...')), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('escape' => false, 'title' => $deal['Deal']['name']));?></h2>
                  <?php echo $this->Html->truncate($deal['Deal']['description'],98, array('ending' => '...'));?>
             
                  	<?php if($deal['Deal']['deal_status_id'] != ConstDealStatus::Upcoming && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval): ?>
                       
                        <div class="clearfix">
                    <?php if(($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped)):
							if(empty($deal['Deal']['is_anytime_deal'])){
					?>
                        <dl class="near-dl-list main-dl-list clearfix">
                            <dt class="time-left sfont textb"><?php echo __l('Time Left');?></dt>
                            <dd>
                                <div class="js-deal-end-countdown">&nbsp;</div>
                                <span class="js-time hide"><?php
                                    echo $end_time = intval(strtotime($deal['Deal']['end_date'].' GMT') - time());
                                ?></span>
                            </dd>
                         </dl>
                   <?php
				   			}
							else{
					?>
                    	 <dl class="near-dl-list main-dl-list clearfix">
                            <dt class="time-left sfont textb"><?php echo __l('Time Left ');?></dt>
                            <dd class="unlimited">
                                <span class="unlimited"><?php echo __l('Unlimited'); ?></span>
                            </dd>
                         </dl>
                    <?php
							}
                        $per = (strtotime($deal['Deal']['end_date']) - strtotime($deal['Deal']['start_date']))  / 10;
                        $next =  round((strtotime(date('Y-m-d H:i:s')) - strtotime($deal['Deal']['start_date'])) / $per);
                        if($next <= 0){
                            $next = 1;
                        }
                        if($next >= 10){
                            $next = 10;
                        }
                    ?>
                                       <?php elseif($deal['Deal']['deal_status_id'] == ConstDealStatus::Closed || $deal['Deal']['deal_status_id'] == ConstDealStatus::Canceled || $deal['Deal']['deal_status_id'] == ConstDealStatus::Expired || $deal['Deal']['deal_status_id'] == ConstDealStatus::PaidToCompany): ?>
                        <dl class="near-dl-list main-dl-list clearfix">
                            <dt class="time-left sfont textb"><?php echo __l('This deal ended at');?></dt>
                            <dd class="unlimited"><?php echo $this->Html->cDateTime($deal['Deal']['end_date'])?></dd>
                         </dl>
                    <?php endif; ?>
                  </div>
                 
			   <?php endif; ?>
                  	
                 
                  
              
                  <div class="main-sold">
                   <?php echo $this->Html->cInt($deal['Deal']['deal_user_count']);?>
             		<span class="sold-info"><?php echo __l('Sold');?></span>
                 </div>
                  
                </div>
              </div>
            </div>
 					
     
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
			if (!empty($deals) && (!empty($has_near_by_deal) || $this->request->params['named']['type'] == 'main' || $this->request->params['named']['type'] == 'side')):
				echo $this->element('paging_links');
			endif;
			?>
		</div>
          </div>
                  </div>
                </div>
                <div class="side1-bl">
                  <div class="side1-br">
                    <div class="side1-bm"> </div>
                  </div>
                </div>

      </div>
      </div>
	     <div id="fb-root"></div>
	<script type="text/javascript">
	  window.fbAsyncInit = function() {
		FB.init({appId: '<?php echo Configure::read('facebook.app_id');?>', status: true, cookie: true,
				 xfbml: true});
	  };
	  (function() {
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol +
		  '//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);
	  }());
	</script>