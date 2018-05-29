<?php /* SVN: $Id: admin_index.ctp 61075 2011-07-26 04:20:30Z aravindan_111act10 $ */ ?>
<div class="js-response js-responses js-search-responses">
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
		<?php 
			if(!empty($this->request->params['named']['company'])):
				$url= array(
					'controller' => 'deals',
					'action' => 'live',
					'company' => $this->request->params['named']['company'],
				);
			elseif(!empty($this->request->params['named']['city_slug'])):
				$url= array(
					'controller' => 'deals',
					'action' => 'live',
					'city_slug' => $this->request->params['named']['city_slug'],
				);
			else:
				$url= array(
					'controller' => 'deals',
					'action' => 'live',
				);			
			endif;
			$url['key'] = time();

		$all = '';
		foreach($dealStatuses as $id => $dealStatus): 
        	$all += $dealStatusesCount[$id];
    	endforeach; 
		
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
    
<div class="clearfix flow-chart-outer">
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
                echo $this->Html->link(sprintf("%s", __l('Pending Approval').' ('.$dealStatusesCount[ConstDealStatus::PendingApproval].')'), $url, array('class' => 'all-deal','title' => __l('Pending Approval')));
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
                    echo $this->Html->link(sprintf("%s", __l('Rejected').' ('.$dealStatusesCount[ConstDealStatus::Rejected].')'), $url, array('class' => 'all-deal','title' => __l('Rejected')));
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
                        echo $this->Html->link(sprintf("%s", __l('Paused').' ('.$dealStatusesCount[ConstDealStatus::Pause].')'), $url, array('class' => 'pause ','title' => __l('Paused')));
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
                        echo $this->Html->link(sprintf("%s", __l('Open').' ('.$dealStatusesCount[ConstDealStatus::Open].')'), $url, array('class' => 'all-deal','title' => __l('Open')));
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
                                echo $this->Html->link(sprintf("%s", __l('Closed').' ('.$dealStatusesCount[ConstDealStatus::Closed].')'), $url, array('class' => 'all-deal','title' => __l('Closed')));
                                ?>
                               </span>
                          </li>
                          <li class="paid">
                              <span class="paid">
                           	          	<?php
                                        $url['filter_id'] = ConstDealStatus::PaidToCompany;
                                        echo $this->Html->link(sprintf("%s", __l('Paid to Merchant').' ('.$dealStatusesCount[ConstDealStatus::PaidToCompany].')'), $url, array('class' => 'all-deal','title' => __l('Paid to Merchant')));
                                        ?>
			
                                 
                                 </span>
                                 					<span class="small-info" title="<?php echo __l('Merchant share for their deals will be funded to their respective wallet account.
If the \'Paid to Merchant\' deals has Charity & Affiliate, their share will be also funded to their respective charity and affiliate user wallet account.');?>"><?php echo __l('Merchant share for their deals will be funded to their respective wallet account.
If the \'Paid to Merchant\' deals has Charity & Affiliate, their share will be also funded to their respective charity and affiliate user wallet account.');?>
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
                echo $this->Html->link(sprintf("%s", __l('Expired').' ('.$dealStatusesCount[ConstDealStatus::Expired].')'), $url, array('class' => 'all-deal','title' => __l('Expired')));
                ?>

                        </span>
                      </li>
                            <li>
                             <span class="refunded">
                	          		<?php
                                $url['filter_id'] = ConstDealStatus::Refunded;
                                echo $this->Html->link(sprintf("%s", __l('Refunded').' ('.$dealStatusesCount[ConstDealStatus::Refunded].')'), $url, array('class' => 'all-deal','title' => __l('Refunded')));
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
                    echo $this->Html->link(sprintf("%s", __l('Canceled').' ('.$dealStatusesCount[ConstDealStatus::Canceled].')'), $url, array('class' => 'all-deal','title' => __l('Canceled')));
                    ?>
                        </span>
                      </li>
                            <li class="hidden-link">
                            <span class="refunded">
                            		<?php
                                    $url['filter_id'] = ConstDealStatus::Refunded;
                                    echo $this->Html->link(sprintf("%s", __l('Refunded').' ('.$dealStatusesCount[ConstDealStatus::Refunded].')'), $url, array('class' => 'all-deal','title' => __l('Refunded')));
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
                echo $this->Html->link(sprintf("%s", __l('Upcoming').' ('.$dealStatusesCount[ConstDealStatus::Upcoming].')'), $url, array('class' => 'all-deal','title' => __l('Upcoming')));
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
          <li class="draft clearfix">
            <span class="draft-link">
                <?php
                $url['filter_id'] = ConstDealStatus::Draft;
                echo $this->Html->link(sprintf("%s", __l('Draft').' ('.$dealStatusesCount[ConstDealStatus::Draft].')'), $url, array('class' => 'all-deal','title' => __l('Draft')));
                ?>
            </span>


        </li>
        <li>
         <span class="upcoming">
                     <?php
                    $url['filter_id'] = ConstDealStatus::Upcoming;
                    echo $this->Html->link(sprintf("%s", __l('Upcoming').' ('.$dealStatusesCount[ConstDealStatus::Upcoming].')'), $url, array('class' => 'all-deal','title' => __l('Upcoming')));
                    ?>
               </span>
               </li>
        </ul>
        </div>
    </li>
</ul>





    </div>
    
    <div class="grid_3 all-deal-block alpha omega">
     <?php echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$deal_percentage.'&amp;chs=120x120&amp;chco=fa9116|be7125|c576d3|74b732|444444|00b0c6|deb700|e21e1e|fd66b5|929292|ff8040&amp;chf=bg,s,FF000000'); ?>
    <div class="all-deal-links">
    	<?php
            $url['type'] ='all';
			unset($url['filter_id']);
            echo $this->Html->link(sprintf("%s", __l('All Deals').' ('.$all.')'), $url, array('class' => 'all-deal','title' => __l('All deal')));
            unset($url['type']);
            ?>
    </div>
     </div>
    </div>
	 <?php //if(empty($this->request->data)): ?>
		 <?php if(!empty($this->request->params['named']['filter_id']) && (!empty($dealStatusesCount[$this->request->params['named']['filter_id']]))){
            $id = $this->request->params['named']['filter_id'];
         }else if(!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'all')){
            $id = $this->request->params['named']['type'];
         }
         ?>    
        <div class="">
            <h2>
			<?php 
			if(!empty($this->request->params['named']['company'])) {
				echo  ' - ' . ucfirst($this->request->params['named']['company']);
			} elseif(!empty($this->request->params['named']['city_slug'])) {
				echo  ' - ' . ucfirst($this->request->params['named']['city_slug']);
			} else {
				echo '';
			}
			?>
            </h2>
            <div class="page-count-block clearfix">
           	<div class="grid_left">
               <?php echo $this->element('paging_counter');?>
            </div>
            	<div class="grid_left">
              <?php echo $this->Form->create('Deal' , array('type' => 'post', 'class' => 'normal search-form clearfix','action' => 'live','url' => $this->request->params['named'])); ?>
                   <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
					<?php echo $this->Form->input('filter_id', array('type' => 'hidden', 'value' => (!empty($this->request->params['named']['filter_id']) ? $this->request->params['named']['filter_id'] : ''))); ?>
                    <?php
                    echo $this->Form->submit(__l('Search'));
                    echo $this->Form->end();
            ?>
            </div>
            <div class="clearfix grid_right add-block1">
                <?php echo $this->Html->link(__l('Add'), array('controller' => 'deals', 'action' => 'live_add'), array('class' => 'add','title' => __l('Add'))); ?>                
            </div>
            </div>
    <?php //endif; ?>   
    		<div class="">   
				  <?php echo $this->Form->create('Deal' , array('class' => 'normal','action' => 'update')); ?>
                  <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
                
                   <div class="overflow-block">
                  <table class="list" id="js-expand-table">
                    <tr class="js-even">
                      <th rowspan="2" class="select"></th>
					  <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Deal'),'Deal.name'); ?></div></th>
                      <th rowspan="2" class="quantity-sold"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Date Start/End'), 'Deal.start_date'); ?></div></th>
                      <th rowspan="2" class="quantity-sold"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Total Sold'),'Deal.deal_user_count'); ?></div></th>
                      <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Sales'),'Deal.total_purchased_amount').' ('.Configure::read('site.currency').')'; ?></div></th>
                      <?php 
							if (Configure::read('charity.is_enabled') && Configure::read('affiliate.is_enabled')) {
								$colspan = 4;
							} elseif ((Configure::read('charity.is_enabled') && !Configure::read('affiliate.is_enabled')) || (!Configure::read('charity.is_enabled') && Configure::read('affiliate.is_enabled'))) {
								$colspan = 3;
							} elseif(!Configure::read('charity.is_enabled') && !Configure::read('affiliate.is_enabled')) {
								$colspan = 2;
							}
						?>
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
                      <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Site / Revenue'),'Deal.total_commission_amount').' ('.Configure::read('site.currency').')'; ?></div></th>
                    </tr>
                    <?php
                    
                        if (!empty($deals)):
                            $i = 0;
							$total_deals = $this->request->params['paging']['Deal']['current'];
                            foreach ($deals as $deal):
                            $status_class = '';
                                 $class = null;
                                if ($i++ % 2 == 0):
                                    $class = 'altrow';
                                endif;
                                if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open):
                                    $status_class = ' js-checkbox-active';
                                endif;
                                if($deal['Deal']['deal_status_id'] == ConstDealStatus::PendingApproval):
                                    $status_class = ' js-checkbox-inactive';
                                endif;
								$rowspan = '';	
								$add_row='';
								$have_sub_deal = '';
								if($i == $total_deals){
									$class.=" last-row";
								}
                                ?>
                    <tr class="<?php echo $class;?> expand-row js-odd">
					  
                       <td class="<?php echo $class;?> select">
                          		<div class="arrow"></div>
                       <?php if(!empty($moreActions)): ?>
                              <?php echo $this->Form->input('Deal.'.$deal['Deal']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$deal['Deal']['id'], 'label' => false, 'class' => 'js-checkbox-list '. $status_class. '' )); ?>
                       <?php endif; ?> 
                      </td>
                      <td class="dl deal-name">
                      <div class="clearfix paypal-status-info">
                         <?php
                            if($deal['Deal']['is_hold']){
                        ?>
                        <span class="resume-strip"><?php echo __l('Paused'); ?></span>
                        <?php
                            }
                        ?>
                        </div>
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
                     
                        </div>
                        </td>
                      <td class="dc quantity-sold">
					  <div class="clearfix">
                       <div class="grid_2 omega alpha deal-bought-count"></div>
                       <div class="deal-bought-info deal-date-info grid_4 omega alpha">
                       <?php 
							$deal_progress_precentage = 0;
							if(strtotime($deal['Deal']['start_date']) < strtotime(date('Y-m-d H:i:s'))) {
								
								if($deal['Deal']['end_date'] !== null) {
									$start = strftime('%Y-%m-%d %H:%M', strtotime($deal['Deal']['start_date'] . ' GMT'));
									$end = strftime('%Y-%m-%d %H:%M', strtotime($deal['Deal']['end_date'] . ' GMT'));
									$days_till_now = abs((strtotime(date("Y-m-d H:i:s")) - strtotime(date($start))) / (60 * 60 * 24));
									$total_days = abs((strtotime(date($end)) - strtotime(date($start))) / (60 * 60 * 24));
									$deal_progress_precentage = round((($days_till_now/$total_days) * 100));
									if($deal_progress_precentage > 100)
									{
										$deal_progress_precentage = 100;
									}
								} else {
									$deal_progress_precentage = 100;
									$any_time_deal_message = __l('No Limit');
								}
							}
						?>		
									
                        <p class="progress-bar round-5">
                           <span class="round-5 <?php echo ($deal['Deal']['end_date'] === null)? 'any-time-deal-progress ': 'progress-status '; ?>" style="width:<?php echo $deal_progress_precentage; ?>%" title="<?php echo ($deal['Deal']['end_date'] === null) ? $any_time_deal_message : $deal_progress_precentage . '%'; ?>">&nbsp;</span>
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
                      <td class="dr site-amount"><?php echo $this->Html->cCurrency($deal['Deal']['total_commission_amount']); ?></td>
                    </tr>
                    <tr class="hide">
                    <td colspan="9" class="action-block">
                    <div class="action-info-block sfont clearfix">
                    <div class="action-left-block">
                    <h3><?php echo __l('Actions');?></h3>
					
                                <ul>
								<?php if(!empty($this->request->params['named']['filter_id']) && ($this->request->params['named']['filter_id'] == ConstDealStatus::Draft)):?>
										<li><?php echo $this->Html->link(__l('Preview'), array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug'], 'city' => (!empty($city_new_slug) ? $city_new_slug : ''), 'admin' => false), array('title'=>__l('Preview'), 'escape' => false));?></li>
								   <?php endif; ?>
                              <?php if(!empty($this->request->params['named']['filter_id']) && (($deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped) || ($this->request->params['named']['filter_id'] == ConstDealStatus::Closed) || ($this->request->params['named']['filter_id'] == ConstDealStatus::PaidToCompany))):?>
                                      <li><?php echo $this->Html->link(__l('Coupons CSV'), array('controller' => 'deals', 'action' => 'coupons_export',  'admin' => false,'deal_id:'.$deal['Deal']['id'],'ext' => 'csv'), array('class' => 'export', 'title' => __l('Coupons CSV')));?></li>
                                      <li><?php echo $this->Html->link(__l('Print'),array('controller' => 'deals', 'action' => 'deals_print', 'filter_id' => $deal['Deal']['deal_status_id'],'page_type' => 'print', 'deal_id' => $deal['Deal']['id']),array('title' => __l('Print'), 'class'=>'print-icon'));?></li>
                                   <?php endif; ?>
                                  <?php if(!empty($deal['Deal']['deal_status_id']) && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval && $deal['Deal']['deal_status_id'] != ConstDealStatus::Rejected && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft && $deal['Deal']['deal_status_id'] != ConstDealStatus::Upcoming) {?>
                                     <?php if($deal['Deal']['deal_user_count'] > 0): ?>
                                     	<li><?php echo $this->Html->link(sprintf(__l('Quantities Sold  (%s)'),$this->Html->cInt($deal['Deal']['deal_user_count'], false)),array('controller'=>'deal_users', 'action'=>'index', 'deal_id'=>$deal['Deal']['id'], 'view' => 'deal_view', 'deal_user_view' => 'list', 'admin' => false), array('class' => 'js-thickbox edit js-edit coupon-sold', 'title' => __l('Quantities Sold')));?></li>
									 <?php endif; ?>
								  <?php } ?>
									<li>
									<?php
										echo $this->Html->link(__l('Edit'), array('controller' => 'deals', 'action'=>'live_edit', $deal['Deal']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));
                                    ?>
                                    </li>
                                    <li>
                                      <?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $deal['Deal']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                                    </li>
                                       <?php if($deal['Deal']['is_subdeal_available'] && $deal['Deal']['sub_deal_count'] > 0): ?>
										<li><?php echo $this->Html->link(__l('Sub Deals'). ' (' .$deal['Deal']['sub_deal_count'].')', array('controller' => 'deals', 'action' => 'sub_deals', $deal['Deal']['id'], 1), array('title' => __l('Sub Deals'), 'class' => 'js-thickbox subdeals-list')); ?></li>
		                               	<?php endif; ?>
                                  <?php 
									if(($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped)){
										if($deal['Deal']['is_hold']){ ?>
										<li>
										<?php
											echo $this->Html->link(__l('Resume'), array('action'=>'update_process', 'status' => 'resume', $deal['Deal']['id']), array('class' => 'resume js-delete', 'title' => __l('Resume')));
										?>
										</li>
										<?php }
										else{ ?>
										<li>
										<?php
											echo $this->Html->link(__l('Pause'), array('action'=>'update_process', 'status' => 'pause', $deal['Deal']['id']), array('class' => 'pause js-delete', 'title' => __l('Pause')));
										?>
										</li>
									<?php	}
									}
									?>
								  <?php if(empty($deal['Deal']['is_subdeal_available'])):?>
									<li> <?php echo $this->Html->link(__l('Clone Deal'),array('controller'=>'deals', 'action'=>'add', 'clone_deal_id'=>$deal['Deal']['id']), array('class' => 'add', 'title' => __l('Clone Deal')));?></li>
								  <?php endif;?>
								  <?php if(!empty($deal['Deal']['deal_status_id']) && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval && $deal['Deal']['deal_status_id'] != ConstDealStatus::Rejected && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft):
                                    ?>
                                  <li>
                                    <?php echo $this->Html->link(__l('Stats'), array('controller'=>'charts', 'action'=>'chart_deal_stats', $deal['Deal']['id'], 'admin' => true),array('class' => 'deal-stats', 'target' => '_blank'));?>
                                    </li>
                                  <?php
								   endif; ?>
        						</ul>
        				
                          </div>
                          <div class="action-right-block deal-action-right-block clearfix">
                               	<div class="clearfix">
                                   	<div class="action-right action-right1">
                                       <h3><?php echo __l('Price'); ?></h3>
                                       <dl class="clearfix">
        								   <dt><?php echo __l('Original Price').' ('.Configure::read('site.currency').')'; ?></dt><dd><?php echo $this->Html->cCurrency($deal['Deal']['original_price']); ?></dd>
        								   <dt><?php echo __l('Discounted Price').' ('.Configure::read('site.currency').')'; ?></dt><dd><?php echo $this->Html->cCurrency($deal['Deal']['discounted_price']); ?></dd>
        								   <dt><?php echo __l('Discount').' (%)'; ?></dt><dd><?php echo $this->Html->cFloat($deal['Deal']['discount_percentage']); ?></dd>
        								   <dt><?php echo __l('Discount').' ('.Configure::read('site.currency').')'; ?></dt><dd><?php echo $this->Html->cCurrency($deal['Deal']['discount_amount']); ?></dd>
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
                                     <dt><?php echo __l('Added 0n').': ';?></dt>
                                     <dd><?php echo $this->Html->cDateTimeHighlight($deal['Deal']['created']); ?></dd>
                               
                                   <?php
    								if( $deal['Deal']['deal_status_id'] != ConstDealStatus::Tipped ){
    									$deal_status =  $deal['DealStatus']['name'];
    								}
    								else{
    									$deal_status =  __l("Open");
    								}
    								?>
                                   <dt><?php echo __l('Status: '); ?></dt>
                                   <dd>
                                   <span title="<?php echo $deal_status; ?>" class="<?php echo 'deal-atatus-info deal-status-'.strtolower(str_replace(" ","",$deal_status));?>">&nbsp;</span>
                                   <?php echo $this->Html->cText($deal_status); ?>
                                   </dd>
                                   <dt><?php echo __l('User').': ';?></dt>
                                   <dd><?php echo  $this->Html->getUserAvatarLink($deal['User'], 'micro_thumb',false).' '.$this->Html->getUserLink($deal['User']); ?></dd>
                                    <dt><?php echo __l('Merchant').': '?></dt>
                                    <dd> <?php echo $this->Html->link($this->Html->cText($deal['Company']['name'], false), array('controller' => 'companies', 'action' => 'view', $deal['Company']['slug'], 'admin' => false),array('title' =>$this->Html->cText($deal['Company']['name'],false)), null, false); ?> </dd>
                               </dl>
                               </div>
                    </div>
                    </td>
                    </tr>
                    <?php
                            endforeach;
                        else:
                            ?>
                    <tr class="js-odd">
                      <td colspan="12" class="notice"><?php echo __l('No Deals available');?></td>
                    </tr>
                    <?php
                        endif;
                        ?>
                  </table>
                  </div>
                  <?php if (!empty($deals)):?>
                  	<div class="clearfix">
                      <div class="admin-select-block grid_left">
                      <?php
                      if(!empty($this->request->params['named']['filter_id'])) { ?>
                        <div>
                        	<?php if(!empty($moreActions)): ?>
								<?php echo __l('Select:'); ?>
                                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
                                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
                            <?php endif; ?>
                        </div>
                       <?php } ?>
                        <div class="admin-checkbox-button"><?php 
                            if(!empty($moreActions)):
                                echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --')));
                            endif;
                             ?></div>
                        <div class="hide"> <?php echo $this->Form->submit(__l('Submit'));  ?> </div>
                      </div>
                      <div class="grid_right"> <?php echo $this->element('paging_links'); ?> </div>
                      </div>
                  <?php endif; ?>
                  <?php echo $this->Form->end(); ?>
             </div>
    </div>
</div>