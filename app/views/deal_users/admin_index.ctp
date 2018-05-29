<?php /* SVN: $Id: admin_index.ctp 78195 2012-07-25 12:53:16Z arovindhan_144at11 $ */ ?>
	<div class="dealUsers index js-response js-responses">
    <div class="js-search-responses">
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
	<div class="clearfix">
        <div class="grid_15 omega alpha">
        
        <ul class="coupon-chart clearfix">
        <li class="common-link clearfix">
        <div class="coupon-top-block clearfix">
          <div class="coupon-bottom-block clearfix">
          <div class="coupon-inner-block clearfix">
          <span class="pending-approval">
                <?php echo $this->Html->link(sprintf(__l('Pending (%s)'),$open),array('controller' => 'deal_users', 'action' => 'index', 'filter_id' => 'open'), array('title' => __l('Pending'))); ?>
            </span>
           
         <ul class="coupon-cancel clearfix">
         <li>
            <div class="clearfix">
            <ul class="coupon-canceled">
                <li class="canceled clearfix">
                <span class="canceled">
                    <?php echo $this->Html->link(sprintf(__l('Canceled (%s)'),$canceled),array('controller' => 'deal_users', 'action' => 'index', 'filter_id' => 'canceled'), array('title' => __l('Canceled'))); ?>
                </span>
                </li>
                </ul>
                </div>
                </li>
        <li>
            <div class="clearfix">
            <ul class="coupon-available">
                <li class="open clearfix">
                <div class="coupon-top-block clearfix">
          <div class="coupon-bottom-block clearfix">
                    <span class="open">
                      <?php echo $this->Html->link(sprintf(__l('Available (%s)'), $available), array('controller' => 'deal_users', 'action' => 'index', 'filter_id' => 'available'), array('title' => __l('Available')));?>
                    </span>
                    <ul class="expired-block">
                        <li class="expired clearfix">
                            <span class="expired">
                            	<?php echo $this->Html->link(sprintf(__l('Expired (%s)'), $expired), array('controller' => 'deal_users', 'action' => 'index', 'filter_id' => 'expired'), array('title' => __l('Expired')));?>
                            </span>
                        </li>
                        <li class="closed clearfix">
                            <span class="used">
                               <?php echo $this->Html->link(sprintf(__l('Used (%s)'), $used), array('controller' => 'deal_users', 'action' => 'index', 'filter_id' => 'used'), array('title' => __l('Used'))); ?>
                            </span>
                        </li>
                    </ul>
                    </div>
                    </div>

                </li>
                </ul>
                </div>
                </li>
                   <li>
            <div class="clearfix">
            <ul class="coupon-refunded">
                <li class="refunded clearfix">
                
                 <span class="refunded">
                   <?php echo $this->Html->link(sprintf(__l('Refunded (%s)'),$refunded),array('controller' => 'deal_users', 'action' => 'index', 'filter_id' => 'refunded'), array('title' => __l('Refunded'))); ?>
                 </span>
                </li>
                </ul>
                </div>
                </li>

            </ul>
             </div>
            </div>
            </div>
              </li>
           </ul>

           

</div>
    <div class="grid_right filter-list-info omega alpha">
		<ul class="clearfix filter-list">
            <li class="gifted"><?php echo $this->Html->link(sprintf(__l('Gifted (%s)'),$gifted_deals),array('controller' => 'deal_users', 'action' => 'index', 'filter_id' => 'gifted_deals'), array('title' => __l('Gifted'))); ?></li>
            <li class="all"><?php  echo $this->Html->link(sprintf(__l('All (%s)'), $all),array('controller' => 'deal_users', 'action' => 'index', 'filter_id' => 'all'), array('title' => __l('All'))); ?></li>
        </ul>
        </div>
     </div>
        <div class="page-count-block clearfix">
        	<div class="grid_left">
              <?php echo $this->element('paging_counter');?>
          </div>
         	<div class="grid_left">
              <?php echo $this->Form->create('DealUser', array('type' => 'post', 'class' => 'normal search-form clearfix', 'action'=>'index')); ?>
                    <div class="mapblock-info">
                        <?php echo $this->Form->autocomplete('deal_name', array('label' => __l('Deal'), 'acFieldKey' => 'Deal.id', 'acFields' => array('Deal.name'), 'acSearchFieldNames' => array('Deal.name'), 'maxlength' => '255')); ?>
                        <div class="autocompleteblock">            
                        </div>
                    </div>               
					<?php if (!empty($this->request->params['named']['filter_id']) && ($this->request->params['named']['filter_id'] == 'available' || $this->request->params['named']['filter_id'] == 'used')): ?>
						<?php echo $this->Form->input('coupon_code', array('label' => __l('Coupon code')));?>
					<?php endif;?>
                    <?php if(!empty($this->request->data['DealUser']['filter_id'])): ?>
						<?php echo $this->Form->input('filter_id', array('type' => 'hidden'));?>
                    <?php elseif(!empty($this->request->data['DealUser']['deal_id'])): ?>
						<?php echo $this->Form->input('deal_id', array('type' => 'hidden'));?>
                    <?php endif; ?>
                    <?php echo $this->Form->submit(__l('Search'),array('name' => 'data[DealUser][search]'));?>
                <?php echo $this->Form->end(); ?>
                </div>
        </div>
		<?php echo $this->Form->create('DealUser' , array('class' => 'normal js-ajax-form','action' => 'update'));?>
        <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url.$param_string)); ?>
      
        <div class="overflow-block">
        <table class="list">
            <tr>
				<?php if(!empty($this->request->params['named']['filter_id'])  && $this->request->params['named']['filter_id'] != 'expired' && $this->request->params['named']['filter_id'] != 'canceled' && $this->request->params['named']['filter_id'] != 'refunded'): ?>
					<th rowspan="2" class="actions"><?php echo __l('Actions');?></th>
				<?php endif;?>
                <th rowspan="2"><div class=""><?php echo $this->Paginator->sort(__l('Purchased Date'),'DealUser.created');?></div></th>
				<?php if(!empty($this->request->params['named']['filter_id'])  && ($this->request->params['named']['filter_id'] == 'canceled')): ?>
	                <th rowspan="2"><div class=""><?php echo $this->Paginator->sort(__l('Canceled Date'),'DealUser.modified');?></div></th>
				<?php endif;?>
                <th rowspan="2"><div class=""><?php echo $this->Paginator->sort(__l('User'),'User.username');?></div></th>
                <th rowspan="2" class="deal-name"><div class=""><?php echo $this->Paginator->sort(__l('Deal'), 'Deal.name');?></div></th>
				<?php if ((!empty($this->request->params['named']['filter_id']) && ($this->request->params['named']['filter_id'] == 'available' || $this->request->params['named']['filter_id'] == 'used')) || (empty($this->request->params['named']['filter_id']) && !empty($is_show_coupon_code))): ?>
					<th rowspan="2"><div class=""><?php echo __l('Coupon Code');?><div><?php echo __l('Top/Bottom');?></div></div></th>
				<?php endif;?>
				<?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 'gifted_deals'): ?>
                    <th rowspan="2"><div class=""><?php echo $this->Paginator->sort(__l('Gift Email'), 'DealUser.gift_email');?></div></th>
                    <th rowspan="2"><div class=""><?php echo $this->Paginator->sort(__l('Message'), 'DealUser.message');?></div></th>
                <?php endif; ?>	
                <th rowspan="2"><div class=""><?php echo $this->Paginator->sort(__l('Quantities'), 'DealUser.quantity');?><?php echo ' x '.$this->Paginator->sort(__l('Price'), 'DealUser.discount_amount').' ('.Configure::read('site.currency').')';?></div></th>
                <th rowspan="2"><div class=""><?php echo __l('Total').' ('.Configure::read('site.currency').')';?></div></th>       
				<?php if(Configure::read('charity.is_enabled') == 1):?>
				<th colspan="4"><?php echo __l('Charity Contributions');?></th>
				<?php endif; ?>
            </tr>
			<tr>
				<?php if(Configure::read('charity.is_enabled') == 1):?>
				<th><div class=""><?php echo __l('From');?><div><?php echo __l('Site').' ('.Configure::read('site.currency').')';?></div></div></th>
                <th><div class=""><?php echo __l('From');?><div><?php echo __l('Merchant').' ('.Configure::read('site.currency').')';?></div></div></th>
                <th><div class=""><?php echo __l('Total').' ('.Configure::read('site.currency').')';?></div></th>
                <th><div class=""><?php echo __l('Charity');?><div><?php echo __l('Name');?></div></div></th>
				<?php endif; ?>
			</tr>
	<?php
        if (!empty($dealUsers)):
        
        $i = 0;
        foreach ($dealUsers as $dealUser):
		     $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
			if($dealUser['DealUser']['deal_user_coupon_count'] == $dealUser['DealUser']['quantity']):
                $status_class = 'js-checkbox-active';
            else:
                $status_class = 'js-checkbox-inactive';
            endif;
        ?>
            <tr<?php echo $class;?>>
				<?php if(!empty($this->request->params['named']['filter_id'])  && $this->request->params['named']['filter_id'] != 'expired' && $this->request->params['named']['filter_id'] != 'canceled' && $this->request->params['named']['filter_id'] != 'refunded'): ?>
				<td class="actions">
		           <div class="action-block">
                        <span class="action-information-block">
                            <span class="action-left-block">&nbsp;
                            </span>
                                <span class="action-center-block">
                                    <span class="action-info">
                                        <?php echo __l('Action');?>
                                     </span>
                                </span>
                            </span>
                            <div class="action-inner-block">
                            <div class="action-inner-left-block">
                            <ul class="action-link clearfix">
                                <?php if(!$dealUser['DealUser']['is_repaid'] && !$dealUser['DealUser']['is_canceled']): ?>
                                <li><?php echo $this->Html->link(__l('Print'),array('controller' => 'deal_users', 'action' => 'view',$dealUser['DealUser']['id'],'type' => 'print', 'filter_id' => $this->request->params['named']['filter_id'], 'admin' => false),array('title' => __l('Print'), 'class'=>'print-icon','target' => '_blank'));?></li>
                                 <li><?php echo $this->Html->link(__l('View Coupon'),array('controller' => 'deal_users', 'action' => 'view',$dealUser['DealUser']['id'], 'filter_id' => $this->request->params['named']['filter_id'],'admin' => false),array('title' => __l('View Coupon'), 'class'=>'view-icon js-thickbox','target' => '_blank'));?></li>
                                <?php endif; ?>
        					</ul>
        					</div>
        						<div class="action-bottom-block"></div>
							  </div>

							 </div>
    			</td>
				<?php endif;?>
                <td class="dc"><?php echo $this->Html->cDateTime($dealUser['DealUser']['created']);?></td>
				<?php if(!empty($this->request->params['named']['filter_id'])  && ($this->request->params['named']['filter_id'] == 'canceled')): ?>
					<td class="dc"><?php echo $this->Html->cDateTime($dealUser['DealUser']['modified']);?></td>
				<?php endif; ?>
                <td class="dl">
                <?php echo $this->Html->getUserAvatarLink($dealUser['User'], 'micro_thumb',false);?>
                <?php echo $this->Html->getUserLink($dealUser['User']);?></td>
                <td class="dl deal-name">
					<?php echo $this->Html->showImage('Deal', $dealUser['Deal']['Attachment'][0], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($dealUser['Deal']['name'], false)), 'title' => $this->Html->cText($dealUser['Deal']['name'], false)));?>
					<span>
						<?php echo $this->Html->link($this->Html->cText($dealUser['Deal']['name'].(!empty($dealUser['SubDeal']['name']) ? ' - '.$dealUser['SubDeal']['name'] : '')), array('controller' => 'deals', 'action' => 'view', $dealUser['Deal']['slug'], 'admin' => false), array('title'=>$this->Html->cText($dealUser['Deal']['name'].(!empty($dealUser['SubDeal']['name']) ? ' - '.$dealUser['SubDeal']['name'] : ''),false),'escape' => false));?>
					</span>
				</td>
				<?php if ((!empty($this->request->params['named']['filter_id']) && ($this->request->params['named']['filter_id'] == 'available' || $this->request->params['named']['filter_id'] == 'used')) || (empty($this->request->params['named']['filter_id']) && !empty($is_show_coupon_code))): ?>
                <td class="dl">
					<ul>
					<?php foreach($dealUser['DealUserCoupon'] as $dealUserCoupon){?>
						<?php if((!empty($coupon_find_id) && in_array($dealUserCoupon['id'],$coupon_find_id)) || (empty($coupon_find_id) && $this->request->params['named']['filter_id'] == 'available' && $dealUserCoupon['is_used'] == '0') || (empty($coupon_find_id) && $this->request->params['named']['filter_id'] == 'used' && $dealUserCoupon['is_used'] == '1') || (empty($coupon_find_id) && $this->request->params['named']['filter_id'] != 'used' && $this->request->params['named']['filter_id'] != 'available' )){?>
							<?php 
								if(!empty($dealUserCoupon['is_used'])):
									$image = 'icon-used.png';
								else:
									$image = 'icon-not-used.png';
								endif;
							?>
							<li>
								<?php echo $this->Html->cText($dealUserCoupon['coupon_code']).'/'.$this->Html->cText($dealUserCoupon['unique_coupon_code']);?>
								<?php if(!empty($this->request->params['named']['filter_id'])  && ($this->request->params['named']['filter_id'] != 'used') && ($this->request->params['named']['filter_id'] != 'available')): ?>
									<?php echo $this->Html->image($image);?>
								<?php endif;?>
							</li>
						<?php }?>
					<?php } ?>
					</ul>
				</td>
				<?php endif;?>
				<?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 'gifted_deals'): ?>
                    <td class="dl"><?php echo $this->Html->cText($dealUser['DealUser']['gift_email']);?></td>
                    <td class="dl"><?php echo $this->Html->cText($dealUser['DealUser']['message']);?></td>
                <?php endif; ?>			
				<td class="dr"><?php echo $this->Html->cInt($dealUser['DealUser']['quantity']).' x '.$this->Html->cFloat((empty($dealUser['DealUser']['sub_deal_id']))? $dealUser['Deal']['discounted_price'] : $dealUser['SubDeal']['discounted_price']);?></td>
				<td class="dr"><?php echo $this->Html->cFloat($dealUser['DealUser']['discount_amount']);?></td>  
				<?php if(Configure::read('charity.is_enabled') == 1):?>
				<td class="dr"><?php echo (!empty($dealUser['CharitiesDealUser']['Charity']['name']) ? $this->Html->cCurrency($dealUser['CharitiesDealUser']['site_commission_amount']) : '-');?></td>		
				<td class="dr"><?php echo (!empty($dealUser['CharitiesDealUser']['Charity']['name']) ? $this->Html->cCurrency($dealUser['CharitiesDealUser']['seller_commission_amount']) : '-');?></td>	
				<td class="dr"><?php echo (!empty($dealUser['CharitiesDealUser']['Charity']['name']) ? $this->Html->cCurrency($dealUser['CharitiesDealUser']['amount']) : '-');?></td>		
				<td class="dl"><?php echo $this->Html->cText((!empty($dealUser['CharitiesDealUser']['Charity']['name']) ? $dealUser['CharitiesDealUser']['Charity']['name'] : '-'));?></td>		
				<?php endif ?>
            </tr>
        <?php
            endforeach;
        else:
        ?>
            <tr>
                <td colspan="14" class="notice"><?php echo __l('No coupons available');?></td>
            </tr>
        <?php
        endif;
        ?>
        </table>
        </div>
		<?php if (!empty($dealUsers)):?>
        	<div class="clearfix">
            <div class=" grid_right">
            <?php echo $this->element('paging_links'); ?>
            </div>    
            </div>
        <?php  endif;  ?>
        <div class="hide">
            <?php echo $this->Form->end('Submit'); ?>
        </div>
        </div>
        </div>
