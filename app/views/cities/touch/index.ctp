<?php /* SVN: $Id: index.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
	<ul data-role="listview" start="<?php echo $this->Paginator->counter(array(
		'format' => '%start%'
		));?>">
	<?php
		if (!empty($cities)):
			$i = 0;
			foreach ($cities as $city):
			?>
				<li>
					<?php 
					 $deal_count = '';
					 if($city['City']['active_deal_count']){
						$deal_count = ' <span class="ui-li-count">'.$city['City']['active_deal_count'].'</span>';
					 }?>

					<?php
						if (Cache::read('site.city_url', 'long') == 'prefix'):
							echo $this->Html->link($city['City']['name'].$deal_count, array('controller' => 'deals', 'action' => 'index', 'city' => $city['City']['slug']), array('class' => "", 'title' => $city['City']['name'], 'escape' => false));
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
						<a href="<?php echo $url;?>" title="<?php echo $city['City']['name']; ?>" class="<?php echo __l('back fade');?>"><?php echo $city['City']['name'].$deal_count; ?></a>
					<?php endif;?>				
				</li>
		<?php
		endforeach;
	endif;?>
</ul>