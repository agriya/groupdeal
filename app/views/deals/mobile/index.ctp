<?php /* SVN: $Id: index.ctp 4098 2010-05-06 08:04:25Z senthilkumar_017ac09 $ */ ?>
<div>
	<?php if(!empty($deals)): ?>
        <h2><?php echo ucfirst($city_name).' '.__l('Deals of the Day'); ?></h2>
        <div>
            <?php
                $count = 1;
				foreach($deals as $deal):
					echo $this->element('../deals/mobile/view', array('deal' => $deal, 'count' => $count, 'get_current_city' => $get_current_city, 'cache' => array('config' => 'site_element_cache', 'key' => $deal['Deal']['id'])));
					$count++;
            	endforeach; 
			?>
        </div>
    <?php else: ?>
			<?php echo $this->element('subscriptions-add', array('cache' => array('config' => 'site_element_cache')));?>

    <?php endif; ?>
</div>