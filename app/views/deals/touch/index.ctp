<?php /* SVN: $Id: index.ctp 4098 2010-05-06 08:04:25Z senthilkumar_017ac09 $ */ ?>
<?php if(!empty($deals)): ?>
	<ul data-role="listview">
		<?php
			$count = 1;
			foreach($deals as $deal):
		?>	
				<li>
                	<a href="<?php echo Router::url(array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug'])); ?>" class="fade">
                        <?php 
						echo $this->Html->showImage('Deal', $deal['Attachment'][0], array('dimension' => 'iphone_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false)));
						?> <h3><?php echo $deal['Deal']['name']; ?></h3>                        
						<p>
						<?php 
						echo __l('Expire on ');
						if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped): 
                                if(empty($deal['Deal']['is_anytime_deal'])){
                                    echo $this->Html->cDate($end_time = $deal['Deal']['end_date']);
                                }
                                else{
                                    echo __l('Any time'); 
                                }
                       	endif; 
					   	?>
						</p>
                        <span class="ui-li-count">
						<?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price'])) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['discounted_price'])); ?>
						</span>					
					</a>	
				</li>	
		<?php                   	
			endforeach; 
		?>
	</ul> 
<?php else: ?>
		<?php echo $this->element('subscriptions-add', array('cache' => array('config' => 'site_element_cache')));?>

<?php endif; ?>
