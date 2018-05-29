<?php /* SVN: $Id: index_company_deals.ctp 71107 2011-11-12 07:26:42Z josephine_065at09 $ */?>
<div class="js-response js-responses js-search-responses">
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
<h2><?php echo $pageTitle; ?></h2>
	<?php endif; ?>
    <?php
		$all = '';
		foreach($dealStatuses as $id => $dealStatus):
        	$all += $dealStatusesCount[$id];
    	endforeach;
	?>
	<div class="clearfix">

<div class="grid_19 omega alpha">

<ul class="flow-chart clearfix">
<li>

<div class="newdeal-top-block clearfix">
<div class="newdeal-bottom-block clearfix">
<div class="newdeal-block clearfix">
  <span class="new-deal">
	 <span class="new-deal-r">
    	<?php
        echo __l('New Deal');
        ?>
     </span>
    </span>
    </div>
    </div>
    </div>

    <div class="clearfix">
        <ul class="pending">
        <li class="pending">
           <div class="pending-bottom-block clearfix">
         <div class="pending-top-block clearfix">
            <div class="pending-block clearfix">
                <span class="pending-approval">
                   <?php
                                $url['filter_id'] = ConstDealStatus::PendingApproval;
                                echo $this->Html->link(sprintf("%s", __l('Pending Approval').' ('.$dealStatusesCount[ConstDealStatus::PendingApproval].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::PendingApproval, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Pending Approval')));
                                ?>
                </span>
            </div>
            </div>
            </div>
            <ul class="rejected-link clearfix">
                <li class="rejected">
                <span class="rejected">
                          <?php
                $url['filter_id'] = ConstDealStatus::Rejected;
                echo $this->Html->link(sprintf("%s", __l('Rejected').' ('.$dealStatusesCount[ConstDealStatus::Rejected].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Rejected, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Rejected')));
                ?>
                </span>
                </li>
                <li class="open">
            <div class="clearfix">
              <ul class="pause clearfix">
                  <li>
                  <div class="pause-top-block">
                    <div class="pause-bottom-block clearfix">
                        <span class="pause open">
                              <?php
                        $url['filter_id'] = ConstDealStatus::Pause;
                        echo $this->Html->link(sprintf("%s", __l('Paused').' ('.$dealStatusesCount[ConstDealStatus::Pause].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Pause, 'company' => $company_slug, 'view' => 'live'), array('class' => 'pause ','title' => __l('Paused')));
                        ?>
                        </span>
                     </div>
                     </div>

                </li>
                </ul>
                </div>
   <div class="clearfix">
                  <ul class="open open1 clearfix">
                  <li class="open1">
<ul><li class="upcoming">
                     <div class="open-top-block">
                          <div class="open-bottom-block">
                        <div class="open-block">
                        <span class="open">
                           	<?php
                $url['filter_id'] = ConstDealStatus::Open;
                echo $this->Html->link(sprintf("%s", __l('Open').' ('.$dealStatusesCount[ConstDealStatus::Open].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Open, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Open')));
                ?>
                        </span>
                        </div>
                         </div>
                 </div>
                 </li>
                 </ul>

                </li>
                </ul>
                </div>
                   <div class="clearfix">

                <ul class="tipped tipped-block clearfix">

                         <li class="closed">
                              <span class="closed">
                    	             <?php
                            $url['filter_id'] = ConstDealStatus::Closed;
                            echo $this->Html->link(sprintf("%s", __l('Closed').' ('.$dealStatusesCount[ConstDealStatus::Closed].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Closed, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Closed')));
                            ?>
                               </span>
                          </li>
                          <li class="paid">
                              <span class="paid">
                           	            <?php
                                    $url['filter_id'] = ConstDealStatus::PaidToCompany;
                                    echo $this->Html->link(sprintf("%s", __l('Paid to Merchant').' ('.$dealStatusesCount[ConstDealStatus::PaidToCompany].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::PaidToCompany, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Paid to Merchant')));
                                    ?>
                                 </span>
                       </li>
                              </ul>
                    </div>
                       <div class="clearfix">
                  <ul class="refunded refunded-block">
                    <li class="expired">
                        <span class="expired">
                       <?php
                            $url['filter_id'] = ConstDealStatus::Expired;
                            echo $this->Html->link(sprintf("%s", __l('Expired').' ('.$dealStatusesCount[ConstDealStatus::Expired].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Expired, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Expired')));
                            ?>

                        </span>
                      </li>
                            <li>
                             <span class="refunded">
                	          	 	<?php
                                $url['filter_id'] = ConstDealStatus::Refunded;
                                echo $this->Html->link(sprintf("%s", __l('Refunded').' ('.$dealStatusesCount[ConstDealStatus::Refunded].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Refunded, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Refunded')));
                                ?>
                             </span>
                            </li>
                        </ul>
                        </div>
                           <div class="clearfix">
                   <ul  class="canceled canceled-block clearfix">
                    <li class="canceled">
                        <span class="canceled">
                	       	<?php
                    $url['filter_id'] = ConstDealStatus::Canceled;
                    echo $this->Html->link(sprintf("%s", __l('Canceled').' ('.$dealStatusesCount[ConstDealStatus::Canceled].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Canceled, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Canceled')));
                    ?>
                        </span>
                      </li>
                            <li class="hidden-link">
                            <span class="refunded">
                            		<?php
                                $url['filter_id'] = ConstDealStatus::Refunded;
                                echo $this->Html->link(sprintf("%s", __l('Refunded').' ('.$dealStatusesCount[ConstDealStatus::Refunded].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Refunded, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Refunded')));
                                ?>
                             </span>
                            </li>
                        </ul>
                        </div>



                </li>
                <li class="upcoming1">
                <span class="upcoming">
                      <?php
                $url['filter_id'] = ConstDealStatus::Upcoming;
                echo $this->Html->link(sprintf("%s", __l('Upcoming').' ('.$dealStatusesCount[ConstDealStatus::Upcoming].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Upcoming, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Upcoming')));
                ?>
                 </span>
                 <ul  class="hidden-link">
                 <li>
                    <span class="open">
                       <?php
                $url['filter_id'] = ConstDealStatus::Open;
                echo $this->Html->link(sprintf("%s", __l('Open').' ('.$dealStatusesCount[ConstDealStatus::Open].')'), $url, array('class' => 'all-deal','title' => __l('Open')));
                ?>
                </span>
                 </li>
                 </ul>

                </li>
            </ul>
        </li>
        </ul>
        </div>
        <div class="clearfix">
        <ul class="draft draft-block clearfix">
          <li class="clearfix">
            <span class="draft-link">
                 <?php
                $url['filter_id'] = ConstDealStatus::Draft;
                echo $this->Html->link(sprintf("%s", __l('Draft').' ('.$dealStatusesCount[ConstDealStatus::Draft].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Draft, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Draft')));
                ?>
            </span>


        </li>
        <li>
         <span class="upcoming">
                           <?php
                $url['filter_id'] = ConstDealStatus::Upcoming;
                echo $this->Html->link(sprintf("%s", __l('Upcoming').' ('.$dealStatusesCount[ConstDealStatus::Upcoming].')'), array('controller' => 'deals', 'action' => 'index', 'filter_id' => ConstDealStatus::Upcoming, 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('Upcoming')));
                ?>
               </span>
               </li>
        </ul>
        </div>
    </li>
</ul>









</div>

	<div class="grid_4 all-deal-block">
           <?php
				$deal_percentage = '';
				$deal_stat = '';
				$all = 0;
				foreach($dealStatuses as $id => $dealStatus){
					$all += $dealStatusesCount[$id] ;
				}

				foreach($dealStatuses as $id => $dealStatus){
					$deal_percentage .= ($deal_percentage != '') ? ',' : '';
					$deal_stat .= (!empty($deal_stat)) ? '|'.$dealStatus : $dealStatus;
					$deal_percentage .= round((empty($dealStatusesCount[$id])) ? 0 : ( ($dealStatusesCount[$id] / $all) * 100 ));
				}
				?>
             <?php echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$deal_percentage.'&amp;chs=120x120&amp;chco=fa9116|be7125|c576d3|74b732|444444|00b0c6|deb700|e21e1e|fd66b5|929292|ff8040'); ?>
        <div class="all-deal-links">
        	<?php
                $url['type'] ='all';
                echo $this->Html->link(sprintf("%s", __l('All Deals').' ('.$all.')'), array('controller'=> 'deals', 'action'=>'index', 'type' => 'all', 'company' => $company_slug, 'view' => 'live'), array('class' => 'all-deal','title' => __l('All deal')));
                unset($url['type']); ?>
        </div>
    </div>
  </div>
     <?php if(!empty($this->request->params['named']['filter_id']) && (!empty($dealStatusesCount[$this->request->params['named']['filter_id']]))){
        $id = $this->request->params['named']['filter_id'];
     }else if(!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'all')){
        $id = $this->request->params['named']['type'];
     }
     ?>

	<div class="">
    
    <div class="page-count-block clearfix">
        <div class="grid_left">
         <?php echo $this->element('paging_counter'); ?>
      </div>
      <div class="grid_left">
       <?php echo $this->Form->create('Deal', array('url' => array('controller' => 'deals', 'action' => 'index','filter_id' => (!empty($this->request->params['named']['filter_id'])) ? $this->request->params['named']['filter_id'] : '', 'company' => $company_slug) ,'class' => 'search-form normal js-ajax-form {"container" : "js-search-responses"} clearfix'));?>
	   <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
	   <?php echo $this->Form->hidden('filter_id', array('value' => (!empty($this->request->params['named']['filter_id'])) ? $this->request->params['named']['filter_id'] : '')); ?>
	   <?php echo $this->Form->hidden('type', array('value' => (!empty($this->request->params['named']['type'])) ? $this->request->params['named']['type'] :'')); ?>
	   <?php echo $this->Form->hidden('company_slug', array('value' => $company_slug)); ?>
		<?php
		echo $this->Form->end(__l('Search')); ?>
		</div>
    </div>
<?php 
	if (Configure::read('charity.is_enabled') && Configure::read('affiliate.is_enabled')) {
		$colspan = 4;
	} elseif ((Configure::read('charity.is_enabled') && !Configure::read('affiliate.is_enabled')) || (!Configure::read('charity.is_enabled') && Configure::read('affiliate.is_enabled'))) {
		$colspan = 3;
	} elseif(!Configure::read('charity.is_enabled') && !Configure::read('affiliate.is_enabled')) {
		$colspan = 2;
	}
?>
  <table class="list" id="js-expand-table">
    <tr class="js-even">
      <th rowspan="2" class="select"><?php echo __l('Action'); ?></th>
      <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Deal'),'Deal.name'); ?></div></th>
      <th rowspan="2" class="quantity-sold"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Date Start/End'), 'Deal.start_date'); ?></div></th>
      <th rowspan="2" class="quantity-sold"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Total Sold'),'Deal.deal_user_count'); ?></div></th>
      <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Sales'),'Deal.total_purchased_amount').' ('.Configure::read('site.currency').')'; 
?></div></th>      
      <th colspan="<?php echo $colspan; ?>"><?php echo __l('Share'); ?></th>
    </tr>
	<tr class="js-even">
			<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Company'),'Deal.total_company_earned_amount').' ('.Configure::read('site.currency').')'; ?></div></th>
			<?php if (Configure::read('charity.is_enabled')) { ?>
			<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Charity'),'Deal.total_charity_amount').' ('.Configure::read('site.currency').')'; ?></div></th>
			<?php } ?>
			<?php if (Configure::read('affiliate.is_enabled')) { ?>
			<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Affiliate'),'Deal.total_affiliate_amount').' ('.Configure::read('site.currency').')'; ?></div></th>
			<?php } ?>		  
	</tr>
    <?php if(!empty($deals)): ?>
      <?php foreach($deals as $deal):
		if($deal['Deal']['is_subdeal_available'] && $deal['Deal']['sub_deal_count'] > 0){
			$original_price = $deal['SubDeal'][0]['original_price'];
			$discounted_price = $deal['SubDeal'][0]['discounted_price'];
			$discount_percentage = $deal['SubDeal'][0]['discount_percentage'];
			$discount_amount = $deal['SubDeal'][0]['discount_amount'];
			$bonus_amount = $deal['SubDeal'][0]['bonus_amount'];
			$commission_percentage = $deal['SubDeal'][0]['commission_percentage'];
			$total_commission_amount = $deal['SubDeal'][0]['total_commission_amount'];
			$max_limit = $deal['SubDeal'][0]['max_limit'];
		} else {
			$original_price = $deal['Deal']['original_price'];
			$discounted_price = $deal['Deal']['discounted_price'];
			$discount_percentage = $deal['Deal']['discount_percentage'];
			$discount_amount = $deal['Deal']['discount_amount'];
			$bonus_amount = $deal['Deal']['bonus_amount'];
			$commission_percentage = $deal['Deal']['commission_percentage'];
			$total_commission_amount = $deal['Deal']['total_commission_amount'];
			$max_limit = $deal['Deal']['max_limit'];
		}
	  ?>
                    <tr class="expand-row js-odd">

                       <td class="select">
                          		<div class="arrow"></div>
                      </td>
                      <td class="dl deal-name">
                      <div class="clearfix">
						<?php 
                        if( $deal['Deal']['deal_status_id'] != ConstDealStatus::Tipped ){ 
                            $deal_status =  $deal['DealStatus']['name'];
                        }
                        else{
                            $deal_status =  __l("Open");
                        }	
                        ?>                                    
                      <span title="<?php echo $deal_status; ?>" class="<?php echo 'deal-atatus-info  deal-status-'.strtolower(str_replace(" ","",$deal_status));?>"><?php echo $this->Html->cText($deal_status); ?></span>
					  <?php echo $this->Html->truncate($deal['Deal']['name'],90, array('ending' => '...')); ?>
						<?php
                        if($deal['Deal']['is_hold'])
							{
                        ?>
                        		<span class="resume-strip"><?php echo __l('Paused'); ?></span>
                        <?php
                        	}
                        ?>
                      </div>
                      </td>
                      <td class="quantity-sold">
					  <div class="clearfix">

                       <div class="deal-bought-info deal-date-info omega alpha">
                       <?php
							$deal_progress_precentage = 0;
							if(strtotime($deal['Deal']['start_date']) < strtotime(date('Y-m-d H:i:s'))) {
								if($deal['Deal']['end_date'] !== null) {
									$days_till_now = (strtotime(date("Y-m-d")) - strtotime(date($deal['Deal']['start_date']))) / (60 * 60 * 24);
									$total_days = (strtotime(date($deal['Deal']['end_date'])) - strtotime(date($deal['Deal']['start_date']))) / (60 * 60 * 24);
									$deal_progress_precentage = round((($days_till_now/$total_days) * 100));
									if($deal_progress_precentage > 100)
									{
										$deal_progress_precentage = 100;
									}
								} else {
									$deal_progress_precentage = 100;
								}
							}
						?>

                        <p class="progress-bar round-5">
                           <span class="<?php echo ($deal['Deal']['end_date'] === null)? 'any-time-deal-progress ': 'progress-status '; ?> round-5" style="width:<?php echo $deal_progress_precentage; ?>%" title="<?php echo $deal_progress_precentage; ?>%">&nbsp;</span>
                        </p>
                        <p class="progress-value clearfix"><span class="progress-from"><?php echo $this->Html->cDateTimeHighlight($deal['Deal']['start_date']);?></span><span class="progress-to"><?php echo (!is_null($deal['Deal']['end_date']))? $this->Html->cDateTimeHighlight($deal['Deal']['end_date']): ' - ';?></span></p>
                       </div>
                     </div>
                      </td>
					  <td class="dr">
                         <?php echo $this->Html->cInt($deal['Deal']['deal_user_count']);?>
					  </td>
                      <td class="dr site-amount"><?php echo $this->Html->cCurrency($deal['Deal']['total_purchased_amount']); ?></td>
                      <td class="dr"><?php echo $this->Html->cCurrency($deal['Deal']['total_company_earned_amount']); ?></td>
                      <?php if (Configure::read('charity.is_enabled')) { ?>
					  <td class="dr"><?php echo $this->Html->cCurrency($deal['Deal']['total_charity_amount']); ?></td>
					  <?php } ?>
					   <?php if (Configure::read('affiliate.is_enabled')) { ?>
                      <td class="dr"><?php echo $this->Html->cCurrency($deal['Deal']['total_affiliate_amount']); ?></td>
					  <?php } ?>
                    </tr>
                    <tr>
                    <td colspan="9" class="action-block">
                    <div class="action-info-block sfont clearfix">
                    <div class="action-left-block">
                     <h3><?php echo __l('Actions'); ?></h3>
                    <?php if(!empty($this->request->params['named']['filter_id']) && ( $this->request->params['named']['filter_id'] == ConstDealStatus::Upcoming || $this->request->params['named']['filter_id'] == ConstDealStatus::PendingApproval || $this->request->params['named']['filter_id'] == ConstDealStatus::Rejected || $this->request->params['named']['filter_id'] == ConstDealStatus::Canceled || $this->request->params['named']['filter_id'] == ConstDealStatus::Draft)){?>
						<?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstDealStatus::Draft):?>
                                <ul>
                                     <li><?php echo $this->Html->link(__l('Edit'), array('controller' => 'deals', 'action'=>'live_edit', $deal['Deal']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                     <li><?php echo $this->Html->link(__l('Delete'), array('controller' => 'deals', 'action'=>'delete', $deal['Deal']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                                     <li><?php echo $this->Html->link(__l('Save and send to admin approval'), array('controller' => 'deals', 'action'=>'update_status', $deal['Deal']['id']), array('class' => 'add js-delete', 'title' => __l('Save and send to admin approval')));?></li>
                                     <li><?php echo $this->Html->link(__l('Preview'), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug'], 'city' => (!empty($city_new_slug) ? $city_new_slug : ''), 'admin' => false), array('title'=>__l('Preview'), 'escape' => false));?></li>
                                </ul>
                    	<?php elseif(!empty($this->request->params['named']['filter_id']) && ( $this->request->params['named']['filter_id'] == ConstDealStatus::Upcoming || $this->request->params['named']['filter_id'] == ConstDealStatus::PendingApproval)):?>
                           <?php if(empty($deal['Deal']['is_subdeal_available'])):?>
                                <ul>
                                     <li><?php echo $this->Html->link(__l('Clone Deal'),array('controller'=>'deals', 'action'=>'add', 'clone_deal_id'=>$deal['Deal']['id']), array('class' => 'add', 'title' => __l('Clone Deal')));?></li>
                                </ul>
                        <?php endif; ?>
                        <?php endif; ?>
                    <?php } else { ?>
                                <ul>
                                    <?php if(in_array($deal['Deal']['deal_status_id'], array(ConstDealStatus::Tipped,ConstDealStatus::Closed,ConstDealStatus::PaidToCompany))):?>
                                        <li><?php echo $this->Html->link(__l('Coupons CSV'), array('controller' => 'deals', 'action' => 'coupons_export', 'deal_id' =>  $deal['Deal']['id'], 'city' => $city_slug, 'filter_id' => $id, 'ext' => 'csv'), array('class' => 'export', 'title' => __l('Coupons CSV')));?></li>
                                        <li> <?php echo $this->Html->link(__l('Print of Coupons'),array('controller' => 'deals', 'action' => 'deals_print', 'filter_id' => $this->request->params['named']['filter_id'],'page_type' => 'print', 'deal_id' => $deal['Deal']['id'], 'company' => $company_slug),array('title' => __l('Print of Coupons'), 'target' => '_blank', 'class'=>'print-icon'));?></li>
                                    <?php endif; ?>
                                        <li><?php echo $this->Html->link(__l('List Coupons'), array('controller' => 'deal_coupons', 'action' => 'index', 'deal_id' =>  $deal['Deal']['id']), array('class'=>'list-coupon','target' => '_blank', 'title' => __l('List Coupons')));?></li>
                                    <?php if(in_array($deal['Deal']['deal_status_id'], array(ConstDealStatus::Open, ConstDealStatus::Tipped,ConstDealStatus::Closed,ConstDealStatus::PaidToCompany))):?>
                                        <li>
                                        <?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open):?>
												<?php echo $this->Html->link(__l('Quantities Sold').' ('.$this->Html->cInt($deal['Deal']['deal_user_count'], false).')',array('controller'=>'deal_users', 'action'=>'index', 'deal_id'=>$deal['Deal']['id'], 'view' => 'company_view', 'deal_user_view' => 'list'),array('class' => 'quantity-sold js-thickbox'));?>											
											<?php else:?>
												<?php echo $this->Html->link(__l('Quantities Sold').' ('.$this->Html->cInt($deal['Deal']['deal_user_count'], false).')',array('controller'=>'deal_users', 'action'=>'index', 'deal_id'=>$deal['Deal']['id'], 'view' => 'company_view', 'deal_user_view' => 'list'),array('class' => 'quantity-sold js-thickbox'));?>											
												<?php echo $this->Html->link(__l('Used'), array('controller'=>'deal_users', 'action'=>'index', 'deal_id'=>$deal['Deal']['id'], 'view' => 'company_view', 'type' => 'used', 'deal_user_view' => 'coupon'),array('class' => 'quantity-sold js-thickbox'));?>											
												<?php echo $this->Html->link(__l('Available'), array('controller'=>'deal_users', 'action'=>'index', 'deal_id'=>$deal['Deal']['id'], 'view' => 'company_view', 'type' => 'available', 'deal_user_view' => 'coupon'),array('class' => 'quantity-sold js-thickbox'));?>											
											<?php endif?>
                                        </li>
                                    <?php endif; ?>
                                   <?php if(empty($deal['Deal']['is_subdeal_available'])):?>
                                        <li><?php echo $this->Html->link(__l('Clone Deal'),array('controller'=>'deals', 'action'=>'add', 'clone_deal_id'=>$deal['Deal']['id']), array('class' => 'add', 'title' => __l('Clone Deal')));?></li>
                                    <?php endif; ?>
                                </ul>
					<?php  } ?>
                    	<ul>
                            <li>
                            <?php echo $this->Html->link(__l('Stats'), array('controller'=>'charts', 'action'=>'chart_deal_stats', $deal['Deal']['id'], 'admin' => false),array('class' => 'deal-stats', 'target' => '_blank'));?>
                            </li>
                            <?php if($deal['Deal']['is_subdeal_available'] && $deal['Deal']['sub_deal_count'] > 0): ?>
                            <li><?php echo $this->Html->link(__l('Sub Deals'). ' (' .$deal['Deal']['sub_deal_count'].')', array('controller' => 'deals', 'action' => 'sub_deals', $deal['Deal']['id'], 1), array('title' => __l('Sub Deals'), 'class' => 'js-thickbox subdeals-list')); ?></li>
                            <?php endif; ?>
                        </ul>
                        </div>
                        <div class="action-right-block deal-action-right-block clearfix">
                            <div class="clearfix">
								<div class="action-right action-right1">
								   <h3><?php echo __l('Price'); ?></h3>
									<dl class="clearfix">
									   <dt><?php echo __l('Original Price').' ('.Configure::read('site.currency').')';?></dt>
										<dd><?php echo $this->Html->cCurrency($original_price); ?></dd>
									   <dt><?php echo __l('Discounted Price').' ('.Configure::read('site.currency').')';?></dt>
										<dd><?php echo $this->Html->cCurrency($discounted_price); ?></dd>
									   <dt><?php echo __l('Discount').' (%)';?></dt>
										<dd><?php echo $this->Html->cFloat($discount_percentage); ?></dd>
									   <dt><?php echo __l('Discount').' ('.Configure::read('site.currency').')';?></dt>
										<dd> <?php echo $this->Html->cCurrency($discount_amount); ?></dd>
								   </dl>
								</div>
								<div class="action-right">
								   <h3><?php echo __l('Commission'); ?></h3>
								   <dl class="clearfix">
									   <dt><?php echo __l('Bonus').' ('.Configure::read('site.currency').')'; ?></dt><dd><?php echo $this->Html->cCurrency($deal['Deal']['bonus_amount']); ?></dd>
									   <dt><?php echo __l('Commission').' (%)'; ?></dt><dd><?php echo $this->Html->cFloat($deal['Deal']['commission_percentage']); ?></dd>
									   <dt><?php echo __l('Total Commission').' ('.Configure::read('site.currency').')'; ?></dt><dd><?php echo $this->Html->cCurrency($deal['Deal']['total_commission_amount']); ?></dd>
								   </dl>
								</div>
								<div class="action-right action-right3">
								   <h3><?php echo __l('User Limit'); ?></h3>
								   <dl class="clearfix">
									   <dt><?php echo __l('Maximum (per user)'); ?></dt><dd><?php echo (!empty($deal['Deal']['user_each_purchase_max_limit']) ? $this->Html->cInt($deal['Deal']['user_each_purchase_max_limit']) : __('Unlimit')); ?></dd>
								   </dl>
								   <h3><?php echo __l('Deal Limit'); ?></h3>
								   <dl class="clearfix">
									   <dt><?php echo __l('Maximum (per day)'); ?></dt><dd><?php echo (!empty($deal['Deal']['maxmium_purchase_per_day']) ? $this->Html->cInt($deal['Deal']['maxmium_purchase_per_day']) : __('Unlimit')); ?></dd>
								   </dl>
								</div>
                            </div>
                            <div class="action-right city-action">
								<?php
								$cities_list =array();
								if(isset($deal['City']) && !empty($deal['City'])):
								foreach($deal['City'] as $city_sub):
									$cities_list[] =  $city_sub['name'];
								endforeach;
								endif;
								?>
								<?php if(!empty($cities_list)) :?>
								<dl class="clearfix">
									 <dt><?php echo __l('City');?></dt>
									 <dd><?php echo implode(', ', $cities_list); ?></dd>
								</dl>
								<?php endif; ?>
                            </div>
						</div>
						<div class="action-right action-right-block action-right4">
							<div class="deal-img-block">
								<?php echo $this->Html->link($this->Html->showImage('Deal', (!empty($deal['Attachment'][0]) ? $deal['Attachment'][0] : ''), array('dimension' => 'live_deal_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false))), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug'], 'city' => (!empty($city_new_slug) ? $city_new_slug : ''), 'admin' => false), array('title'=>$this->Html->cText($deal['Deal']['name'],false),'escape' => false));?>
                            </div>
                            <dl class="clearfix">
								<dt><?php echo __l('Added 0n');?></dt>
								<dd><?php echo $this->Html->cDateTimeHighlight($deal['Deal']['created']); ?></dd>
								<dt><?php echo __l('Status'); ?></dt>
								<dd>
                                   <div class="clearfix">
									<?php 
                                    if( $deal['Deal']['deal_status_id'] != ConstDealStatus::Tipped ){ 
                                        $deal_status =  $deal['DealStatus']['name'];
                                    }
                                    else{
                                        $deal_status =  __l("Open");
                                    }	
                                    ?>                                    
                                    <span title="<?php echo $deal_status; ?>" class="<?php echo 'deal-atatus-info deal-status-'.strtolower(str_replace(" ","",$deal_status));?>">&nbsp;</span>
                                    <?php echo $this->Html->cText($deal_status); ?>
                                    </div>
                                </dd>
								<dt><?php echo __l('User');?></dt>
								<dd><?php echo $this->Html->getUserAvatarLink($deal['User'], 'micro_thumb',false).' '.$this->Html->getUserLink($deal['User']); ?></dd>
                            </dl>
                        </div>
                    </div>
                    </td>
                    </tr>
      <?php endforeach; ?>
    <?php else: ?>
        <tr class="js-odd"><td class="notice" colspan="11"><?php echo __l('No deals available');?></td></tr>
    <?php endif; ?>
    </table>
	<?php
    if (!empty($deals)) {
        ?>
            <div class="js-pagination">
                <?php echo $this->element('paging_links'); ?>
            </div>
        <?php
    }
    ?>
    </div>
 </div>