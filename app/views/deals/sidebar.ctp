<div class="grid_6 grid_right omega alpha">
  <div class="side2-inner">
      <div class="bot-mspace blue-bg deal-blue-bg clearfix">
        <div class="business-card clarfix">
            <h3><?php echo sprintf(__l('Get Your Business on').' %s!', Configure::read('site.name')); ?></h3>
            <div class="blue-bg-inner clearfix">
              <?php /*<p class="new-img"><?php echo __l('Learn More for the basics.'); ?> <?php echo sprintf(__l('about how').' %s '.__l('can help bring tonnes of customers to your door'), Configure::read('site.name'));?></p> */?>
              <div class="learn-block top-mspace"><?php echo $this->Html->link(__l('Learn More'), array('controller' => 'pages', 'action' => 'view','merchant', 'admin' => false), array('title' => __l('Learn More'),'class'=>'learn'));?></div>
            </div>
        </div>
      </div>
<?php if($this->Html->isAllowed($this->Auth->user('user_type_id'))){ ?>
      <div class="bot-mspace blue-bg deal-blue-bg clearfix">
        <div class="buy-gift-card clarfix">
            <h3><?php echo __l('Give the Gift of').' '.Configure::read('site.name');?></h3>
            <div class="blue-inner clearfix">
            <?php echo $this->Html->link(__l('Buy a').' '.Configure::read('site.name').' '.'<span>'.('Gift Card').'</span>', array('controller' => 'gift_users', 'action' => 'add'), array('class' => 'buy','title' => __l('Buy a').' '.Configure::read('site.name').' '.__l('Gift Card'), 'escape' => false)); ?> </div>
        </div>
      </div>
  <?php } ?>
  <?php  if(!empty($main_deals)): ?>
  <div class="bot-mspace blue-bg top clearfix">
      <h3> <?php echo __l("Today's Main Deals");?> </h3>
        <ol class="side-deal-list">
          <?php	foreach($main_deals as $main_deal):	?>
          <li class="round-5 pr no-pad">
              <div class="deal-info pa">
                <p><?php echo round($main_deal['Deal']['discount_percentage']);?>% OFF</p>
              </div>
              <?php echo $this->Html->link($this->Html->showImage('Deal', (!empty($main_deal['Attachment'][0]) ? $main_deal['Attachment'][0] : ''), array('dimension' => 'sidebar_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($main_deal['Deal']['name'], false)), 'title' => $this->Html->cText($main_deal['Deal']['name'], false))), array('controller' => 'deals', 'action' => 'view', $main_deal['Deal']['slug']),array('title' =>sprintf(__l('%s'),$main_deal['Deal']['name']), 'escape' => false));?>
              <div class="deal-value-info textb pa clearfix">
                    <span>
    					<?php if(!empty($main_deal['Deal']['is_subdeal_available'])): ?>
    						<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($main_deal['SubDeal'][0]['discounted_price']));?>
    					<?php else:?>
    						<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($main_deal['Deal']['discounted_price']));?>
    					<?php endif;?>
                    </span>
                <?php echo $this->Html->link(__l('View it'), array('controller' => 'deals', 'action' => 'view', $main_deal['Deal']['slug']),array('title' => __l('View it')), null, false);?>
              </div>
            <h4 class="dc space sfont"><?php echo $this->Html->link($this->Html->cText($main_deal['Deal']['name']), array('controller' => 'deals', 'action' => 'view', $main_deal['Deal']['slug']),array('escape' => false, 'title' =>sprintf(__l('%s'),$main_deal['Deal']['name'])));?></h4>
            <?php /*<div class="clearfix">
              <div class="deal-button dc deal-price-r grid_3 grid_right alpha">
                        <div class="clearfix">
                      <div class="deal-currency textb left-space">
    						<?php if(!empty($main_deal['Deal']['is_subdeal_available'])): ?>
                                <?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($main_deal['SubDeal'][0]['discounted_price']));?>
                            <?php else:?>
                                <?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($main_deal['Deal']['discounted_price']));?>
                            <?php endif;?>
    				  </div>
    				   </div>
                </div>
            </div>
            */?>
          </li>
          <?php	endforeach;?>
          <li class="dr no-border view-all ver-space clearfix"><?php echo $this->Html->link(__l('View all'), array('controller' => 'deals', 'action' => 'index', 'type' => 'main'),array('title' => __l('View all')), null, false);?></li>
        </ol>
   </div>
  <?php endif; ?>
  <?php if(Configure::read('deal.is_side_deal_enabled') && !empty($side_deals)): ?>
  <div class="bot-mspace blue-bg top clearfix">
        <h3> <?php echo __l("Today's Side Deals");?> </h3>
        <ol class="side-deal-list">
              <?php foreach($side_deals as $side_deal):?>
          <li class="round-5 pr no-pad">
                  <div class="deal-info pa">
                    <p><?php echo round($side_deal['Deal']['discount_percentage']);?>% OFF</p>
                  </div>
                    <?php echo $this->Html->link($this->Html->showImage('Deal', $side_deal['Attachment'][0], array('dimension' => 'sidebar_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($side_deal['Deal']['name'], false)), 'title' => $this->Html->cText($side_deal['Deal']['name'], false))), array('controller' => 'deals', 'action' => 'view', $side_deal['Deal']['slug']),array('title' =>sprintf(__l('%s'),$side_deal['Deal']['name']), 'escape' => false));?>
                 <div class="deal-value-info pa textb clearfix">
                  	<span>
    					<?php if(!empty($side_deal['Deal']['is_subdeal_available'])): ?>
    						<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($side_deal['SubDeal'][0]['discounted_price']));?>
    					<?php else:?>
    						<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($side_deal['Deal']['discounted_price']));?>
    					<?php endif;?>
                    </span>
                    <?php echo $this->Html->link(__l('View it'), array('controller' => 'deals', 'action' => 'view', $side_deal['Deal']['slug']),array('title' => __l('View it')), null, false);?>
                </div>
                <h4 class="dc space sfont"><?php echo $this->Html->link($this->Html->cText($side_deal['Deal']['name']), array('controller' => 'deals', 'action' => 'view', $side_deal['Deal']['slug']),array('escape' => false, 'title' =>sprintf(__l('%s'),$side_deal['Deal']['name'])));?></h4>
                <?php /*<div class="clearfix">
                  <div class="deal-button deal-price-r grid_3 grid_right  alpha">
                    <div class="clearfix">
                          <div class="deal-currency textb left-space">
    							<?php if(!empty($side_deal['Deal']['is_subdeal_available'])): ?>
                                    <?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($side_deal['SubDeal'][0]['discounted_price']));?>
                                <?php else:?>
                                    <?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($side_deal['Deal']['discounted_price']));?>
                                <?php endif;?>
    					  </div>
    					</div>
                     </div>
                </div>
                */?>
              </li>
              <?php	endforeach;?>
            <li class="dr no-border view-all ver-space clearfix"><?php echo $this->Html->link(__l('View all'), array('controller' => 'deals', 'action' => 'index', 'type' => 'side'),array('title' => __l('View all')), null, false);?></li>
        </ol>
   </div>
 <?php endif; ?>
      <?php
      $deal_id = 0;
      if($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'index'):
    	 $deal_id = $deal[0]['Deal']['id'];
      else:
    	$deal_id = $deal['Deal']['id'];
      endif;
      echo $this->element('deals-nearby_simple', array('deal_id' => $deal_id, 'cache' => array('config' => 'site_element_cache_15_min', 'key' => $deal_id))); ?>
     <?php
	 
$facebook_like_box = Configure::read('facebook.like_box');
if(!empty($facebook_like_box) && Configure::read('facebook.like_box_title')):?>
  <div class="blue-bg1 bot-mspace facebook-inner-block clearfix">
    <div class="deal-tl">
      <div class="deal-tr">
        <div class="deal-tm">
          <h3 class="clearfix"><span><?php echo Configure::read('facebook.like_box_title'); ?></span> <a class="js-sidebar-toggle-show-minus {'container':'fb-like-inner'}" href="#" title="close">close</a></h3>
        </div>
      </div>
    </div>
    <div class="side1-cl">
      <div class="side1-cr">
        <div class="fb-like-inner block1-inner blue-bg-inner clearfix">
         <?php echo $facebook_like_box;?>
        </div>
      </div>
    </div>
    <div class="side1-bl">
      <div class="side1-br">
        <div class="side1-bm"> </div>
      </div>
    </div>
  </div>
<?php endif;  ?>
<?php
  $facebook_feeds_code = Configure::read('facebook.feeds_code');
  if(!empty($facebook_feeds_code) && Configure::read('facebook.feeds_code_title')):?>
  <div class="blue-bg1 bot-mspace facebook-inner-block clearfix">
    <div class="deal-tl">
      <div class="deal-tr">
        <div class="deal-tm">
          <h3 class="clearfix"><span><?php echo Configure::read('facebook.feeds_code_title'); ?></span><a class="js-sidebar-toggle-show-minus {'container':'fb-feed-inner'}" href="#" title="close">close</a> </h3>
        </div>
      </div>
    </div>
    <div class="side1-cl">
      <div class="side1-cr">
        <div class="fb-feed-inner block1-inner blue-bg-inner clearfix">
          <?php echo $facebook_feeds_code;?>
        </div>
      </div>
    </div>
    <div class="side1-bl">
      <div class="side1-br">
        <div class="side1-bm"> </div>
      </div>
    </div>
  </div>
<?php endif;  ?>  
<?php	if(Configure::read('twitter.tweets_around_city_title') && Configure::read('twitter.tweets_around_city')): ?>
<div class="blue-bg1 bot-mspace facebook-inner-block clearfix">
    <div class="tweet-tl">
      <div class="tweet-tr">
        <div class="tweet-tm">
          <h3 class="clearfix"><span><?php echo Configure::read('twitter.tweets_around_city_title'); ?></span><a class="js-sidebar-toggle-show-minus {'container':'twitter-feed-inner'}" href="#" title="close">close</a></h3>
        </div>
      </div>
    </div>
    <div class="side1-cl">
      <div class="side1-cr">
        <div class="twitter-feed-inner block1-inner blue-bg-inner twitter-block clearfix">
          <?php	echo strtr(Configure::read('twitter.tweets_around_city'),array(
    					'##CITY_NAME##' => ucwords($city_name),
    				));
                ?>
        </div>
      </div>
    </div>
    <div class="side1-bl">
      <div class="side1-br">
        <div class="side1-bm"> </div>
      </div>
    </div>
  </div>
<?php endif; ?>
</div>
</div>
