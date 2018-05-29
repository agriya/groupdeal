<?php /* SVN: $Id: view.ctp 66606 2011-09-21 05:22:01Z arovindhan_144at11 $ */ ?>
<div class="clearfix js-lazyload">
	<div class="grid_18 grid_right omega alpha clearfix">
		<div class="deal-list-right">
		<h2><?php echo __l($this->pageTitle);?></h2>
			<ol class="near-list deal-list clearfix">
				<?php foreach($deals as $deal):	?>
					<li class="clearfix">
						<h3 class="textn">
							<?php
							if($deal['Deal']['is_subdeal_available']==1)
							{
								$count_subdeal=count($deal['SubDeal']);
								if($deal['Deal']['is_now_deal']){
									$deal_name=$this->Html->cInt($deal['SubDeal'][$count_subdeal-1]['discount_percentage'])."% Off on ".$deal['Deal']['name'];
								} else {
									$deal_percentage=array();
									for($i=0;$i<$count_subdeal;$i++)
									{
										$deal_percentage[]=round($deal['SubDeal'][$i]['discount_percentage']);
									}
									sort($deal_percentage);
									$deal_per=implode("% or ",$deal_percentage);
									
									$deal_name=$deal_per."% Off on ".$deal['Deal']['name'];
								}
							}
							else 
							{
							$deal_name=$this->Html->cInt($deal['Deal']['discount_percentage'])."% Off on ".$deal['Deal']['name'];
							}
							?>
							<?php echo $this->Html->link($this->Html->truncate($this->Html->cText($deal_name),50, array('ending' => '...')), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('escape' => false, 'title' =>sprintf(__l('%s'),$deal['Deal']['name']))); ?>
						</h3>
						<p class="recent-deal-description"><?php echo $this->Html->truncate($deal['Deal']['description'],98, array('ending' => '...'));?></p>
						<div class="deal-img home-list">
							 <div class="gallery-block no-border" style="overflow:hidden; height:272px;">
								<div id="js-gallery-<?php echo $deal['Deal']['id']; ?>">
									<?php foreach($deal['Attachment'] as $attachment){?>
										<?php echo $this->Html->link($this->Html->showImage('Deal', $attachment, array('dimension' => 'medium_big_thumb', 'class' => 'show-case-image', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false))), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug'], 'admin' => false), array('title'=>$this->Html->cText($deal['Deal']['name'],false),'escape' => false)); ?>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="price-block pa clearfix">
							<ul class="location-list grid_left">
							<li>
                            <?php echo $this->Html->link($deal['DealCategory']['name'], array('controller' => 'deals', 'action' => 'index', 'category' => $deal['DealCategory']['slug']),array('title' => $deal['DealCategory']['name']));?>
                             </li>
							</ul>
						</div>
					</li>    
				<?php endforeach; ?>
			</ol>
		</div>
		<?php if (!empty($deals)) { ?>
			<div class="grid_right">
				<?php echo $this->element('paging_links'); ?>
			</div>
		<?php } ?>
	</div>
	<div class="grid_6 omega alpha">
		<div class="deallist-side2">
			<div class="category-block">
				<div class="side1-tl">
					<div class="side1-tr">
						<div class="side1-tm"> </div>
					</div>
				</div>
				<div class="side1-cl">
					<div class="side1-cr">
						<?php echo $this->element('home_categories', array('cache' => array('key'=>$city_slug)),$category); ?>
					</div>
				</div>
				<div class="side1-bl">
					<div class="side1-br">
						<div class="side1-bm"> </div>
					</div>
				</div>
            </div>
           <!-- <div class="map-block2">
				<a href="#" title="map"><?php //echo $this->Html->image("map2.png", array('alt'=> __l('[Image: Map]'), 'title' => __l('Map'))); ?></a>
			</div>-->
            <?php
				$facebook_like_box = Configure::read('facebook.like_box');
				if(!empty($facebook_like_box) && Configure::read('facebook.like_box_title')):?>
					<div class="blue-bg1 bot-mspace facebook-inner-block clearfix">
						<div class="deal-tl">
							<div class="deal-tr">
								<div class="deal-tm">
									<h3 class="clearfix"><span ><?php echo Configure::read('facebook.like_box_title'); ?></span> <a class="js-sidebar-toggle-show-minus {'container':'fb-like-inner'}" href="#" title="close">close</a></h3>
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
            <?php endif; ?>
            <?php if(Configure::read('twitter.tweets_around_city_title') && Configure::read('twitter.tweets_around_city')): ?>
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
								<?php echo strtr(Configure::read('twitter.tweets_around_city'),array('##CITY_NAME##' => ucwords($city_name), )); ?>
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
			<div class="app-block">
				<a href="http://itunes.apple.com/us/app/igroupdeal/id445358823?ls=1&amp;mt=8" target='_blank' title="<?php echo Configure::read('home.app_box_title');?>"><?php echo Configure::read('home.app_box_link'); ?></a>
			</div>
		</div>
	</div>
</div>
<div class="advertise dc">
	<a  href="#" title="<?php echo Configure::read('home.add_box_title');?>"><?php echo Configure::read('home.add_box_link');?></a>
</div>
<div id="fb-root"></div>
<script type="text/javascript">
	window.fbAsyncInit = function() {
		FB.init({appId: '<?php echo Configure::read('facebook.app_id');?>', status: true, cookie: true, xfbml: true});
	};
	(function() {
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);
	}());
</script>