<?php /* SVN: $Id: index.ctp 78472 2012-08-14 08:10:03Z balamurugan_177at12 $ */ ?>

<h2><?php echo __l('Select Your City');?></h2>
	<ol class="cities-list bot-mspace clearfix" start="<?php echo $this->Paginator->counter(array(
		'format' => '%start%'
		));?>">
	<?php
		if (!empty($cities)):
			$i = 0;
			foreach ($cities as $city):
				$class = null;
				if ($i++ % 2 == 0):
					$class = ' class="altrow"';
				endif;
				if($city['City']['slug'] == $city_slug) :
					$select_class = 'active';
				else:
					$select_class = '';
				endif;
			?>
				<li class="<?php echo $select_class;?>">
					<?php
						if (Cache::read('site.city_url', 'long') == 'prefix'):
							echo $this->Html->link($this->Html->cText($city['City']['name']), array('controller' => 'deals', 'action' => 'live', 'city' => $city['City']['slug'], 'admin' => false), array('escape' => false, 'class' => "$select_class",  'title' =>sprintf(__l('%s'),$city['City']['name'])));
						elseif (Cache::read('site.city_url', 'long') == 'subdomain'):
							$subdomain = substr(env('HTTP_HOST'), 0, strpos(env('HTTP_HOST'), '.'));			
							$sitedomain = substr(env('HTTP_HOST'), strpos(env('HTTP_HOST'), '.'));
							$url = env('HTTP_HOST');
							switch($subdomain):
								case 'www':	
									$url = "http://".$city['City']['slug']. $sitedomain;
									break;
								case 'm':
									$url = "http://m.".$city['City']['slug']. $sitedomain;
									break;
								case Configure::read('site.domain');
										$url = "http://".$city['City']['slug'].'.'. env('HTTP_HOST');
									break;
								default:
									$url = "http://".$city['City']['slug']. $sitedomain;
							endswitch;		
						?>
						<a href="<?php echo $url;?>" title="<?php echo $city['City']['name']; ?>" class="<?php echo $select_class;?>"><?php echo $city['City']['name']; ?></a>
					<?php endif;?>
					<?php if($city['City']['active_deal_count']):?>
							<span class="callout dc"><?php echo $city['City']['active_deal_count']; ?></span>
					<?php  endif;?>
				</li>
		<?php
		endforeach;
	endif;?>
</ol>
<p class="suggestion-link"><?php //echo $this->Html->link(__l('Don\'t see your city?'), array('controller' => 'city_suggestions', 'action' => 'add', 'admin' => false), array('title' => __l('Suggest a City'), 'escape' => false)); ?></p>

<?php //echo $this->element('citysuggestions');?>
