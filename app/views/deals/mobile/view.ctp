<?php /* SVN: $Id: view.ctp 7480 2010-06-09 06:19:22Z senthilkumar_017ac09 $ */ ?>
     <?php					
					if(empty($deal['Deal']['is_redeem_at_all_branch_address'])){
						if(count($deal['CompanyAddressesDeal']) == 1 && empty($deal['Deal']['is_redeem_in_main_address'])){
							$id =0;
							foreach($deal['Company']['CompanyAddress'] as $key => $company_address){
								if($deal['CompanyAddressesDeal'][0]['company_address_id'] == $company_address['id'])
									$id = $key;
							}
							$multiple_loc_message = $deal['Company']['CompanyAddress'][$id]['address1'];
						}
						else if(count($deal['CompanyAddressesDeal']) <= 0 && !empty($deal['Deal']['is_redeem_in_main_address'])){
							$multiple_loc_message = $deal['Company']['address1'];
						}
						else{
							$multiple_loc_message = __l('Multiple Location');						
						}
					}else{
						if(!empty($deal['Deal']['is_redeem_in_main_address']) && empty($deal['Company']['CompanyAddress']) ){
							$multiple_loc_message = $deal['Company']['address1'];
						} 
						else{						
							$multiple_loc_message = __l('Multiple Location');						
						}	
					}
				?>
    <h2><?php echo $this->Html->link($deal['Deal']['name'], array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),array('title' =>sprintf(__l('%s'),$deal['Deal']['name'])));?></h2>
     <p class="company-msg-info textn">
				<span class="c-name"><?php echo $deal['Company']['name'] ;?></span>
				 <span class="c-message"><?php echo $multiple_loc_message;?></span>
    </p>
    <div class="clearfix js-dialog-over-block">
      <div class="side1">
        <?php /*?><p class="refer"><?php echo $this->Html->link(sprintf(__l('Refer Friends, Get').' %s',$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount')))), array('controller' => 'pages', 'action' => 'refer_a_friend'), array('escape' => false,'title' => sprintf(__l('Refer Friends and Get %s%s'),$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'))))));?></p>
        <?php */?>
        <?php if(Configure::read('referral.referral_enable') && Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer):?>
                          <p class="refer"><?php echo $this->Html->link(__l('Refer Friends, Get').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'), false)), array('controller' => 'pages', 'action' => 'refer_a_friend'), array('title' => __l('Refer Friends, Get').' '. $this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'), false))));?></p>
					<?php elseif(Configure::read('referral.referral_enable') && Configure::read('referral.referral_enabled_option') == ConstReferralOption::XRefer):?>
							<?php 
								if(Configure::read('referral.refund_type') == ConstReferralRefundType::RefundDealAmount):
									$refund_type = __l('Get a Free Deal!!!');
								else:
									$refund_type = __l('Get').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('referral.refund_amount'), false)).' '.__l('');
								endif;
								$msg = __l('Refer').' '.Configure::read('referral.no_of_refer_to_get_a_refund').' '.__l('Friends').', '.$refund_type;
							?>
							
                          <p class="refer"><?php echo $this->Html->link($msg, array('controller' => 'pages', 'action' => 'refer_friend'), array('title' => $msg));?></p>
					<?php endif; ?>
        <?php if($this->request->params['action'] !='index'):?>
					<div class="gallery-block">
						<div id='js-mobile-gallery'>
								<?php foreach($deal['Attachment'] as $attachment){?>
									<a><?php echo $this->Html->showImage('Deal', $attachment, array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false)));?></a>
								<?php } ?>
						</div>
					</div>
				<?php else:?>
					<div>
						<div>
							<ul>
								<li><?php echo $this->Html->showImage('Deal', $deal['Attachment'][0], array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false)));?></li>
							</ul>
						</div>
					</div>
				<?php endif;?>
        <div class="clearfix">
          <div class="side1-l">
            <h3><?php echo __l('The Fine Print');?></h3>
             <?php 
							  if(!empty($deal['Deal']['coupon_expiry_date']) && empty($deal['Deal']['is_subdeal_available'])){  ?>
		                 		<p><?php echo __l('Expires '); ?></p>
                                 <p><?php echo $this->Html->cDateTime($deal['Deal']['coupon_expiry_date']).$this->Html->cHtml($deal['Deal']['coupon_condition']);?></p>
							  <?php }
							  else if(!empty($deal['Deal']['is_subdeal_available']) && !empty($deal['SubDeal'][0]['coupon_expiry_date']) ){ ?>
		                 		 <p><?php echo __l('Expires '); ?></p>
		                        <p><?php echo  $this->Html->cDateTime($deal['SubDeal'][0]['coupon_expiry_date']).$this->Html->cHtml($deal['Deal']['coupon_condition']); ?></p>
							 <?php } ?>	  
            <p><?php echo $this->Html->link(__l('Read the Deal FAQ'), array('controller' => 'pages', 'action' => 'view','faq', 'admin' => false), array('target'=>'_blank', 'title' => __l('Read the deal FAQ')));?> <?php echo __l(' for the basics.'); ?></p>
          </div>
          <div class="side1-r">
            <h3><?php echo __l('Highlights');?></h3>
            <?php echo $this->Html->cHtml($deal['Deal']['coupon_highlights']);?>
          </div>
        </div>
      </div>
      <div class="side2">
      	<?php if(empty($deal['Deal']['is_subdeal_available'])){ ?>
        <p class="cash"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price']));?></p>
		<?php
			}
            if($this->Html->isAllowed($this->Auth->user('user_type_id')) && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval):
                if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped):
                            if(empty($deal['Deal']['is_subdeal_available'])){
								 echo $this->Html->link(__l('Buy'), array('controller'=>'deals','action'=>'buy',$deal['Deal']['id']), array('title' => __l('Buy'),'class' =>'buy-but round-5'));
							}
							else{
							  ?>
                            	<div id="js-open-subdeal-<?php echo $deal['Deal']['id']; ?>">
                                	<ul>
                                    	<?php foreach($deal['SubDeal'] as $subdeal){ ?>
                                    	<li>                                        		
                                                <div class="clearfix deal-block">
                                                		<h3><?php echo $this->Html->cText($subdeal['name']);?></h3>
                                                        <p class="cash"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['discounted_price']));?> </p>
                                                     <?php if( !empty($subdeal['max_limit']) && $subdeal['deal_user_count'] >= $subdeal['max_limit']):?>
                                                      <p class='sold-out'><?php echo __l('sold out'); ?></p>
                                                      <?php else: ?>
                                                      <p> <?php echo $this->Html->link('Buy Now', array('controller'=>'deals','action'=>'buy', $deal['Deal']['id'], $subdeal['id']),array('title' =>sprintf(__l('%s'),$subdeal['name']), 'class' => 'buy-but round-5'));?></p>
                                                      <?php endif;?> 

                                                      <dl class="deal-list">
                                                         <dt><?php echo __l('Value');?></dt>
                                                         <dd><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['original_price']));?></dd>
                                                       </dl>
										               <dl class="deal-list">
                                                        <dt><?php echo __l('Discount');?></dt>
                                                        <dd><?php echo $this->Html->cInt($subdeal['discount_percentage']) . "%"; ?></dd>
                                                       </dl>
										               <dl class="deal-list">
                                                        <dt><?php echo __l('You Save');?></dt>
                                                        <dd><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['savings'])); ?></dd>
                                                      </dl>
                                                 </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php								 
							}
                else:
                ?>
                    <span class="no-available buy-but round-5 dc" title="<?php echo __l('No Longer Available');?>"><?php echo __l('No Longer Available');?></span>
                <?php
				
                endif;
            endif;
		 if(empty($deal['Deal']['is_subdeal_available'])){
        ?>
        <div class="clearfix deal-block">
              <dl class="deal-list">
                 <dt><?php echo __l('Value');?></dt>
                 <dd><?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['original_price'],false)) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['original_price'],false));?></dd>
              </dl>
              <dl class="deal-list">
                <dt><?php echo __l('Discount');?></dt>
                <dd><?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->cFloat($deal['Deal']['discount_percentage']) . "%" : $this->Html->cFloat($deal['SubDeal'][0]['discount_percentage']) . "%"; ?></dd>
              </dl>
              <dl class="deal-list">
                <dt><?php echo __l('You Save');?></dt>
                <dd><?php echo (empty($deal['Deal']['is_subdeal_available'])) ?  $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['savings'])) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['savings'])); ?></dd>
              </dl>
         </div>
        <?php } ?>
        <div class="l-area">
			<?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped): 
					if(empty($deal['Deal']['is_anytime_deal'])){
			?>            
                <dl class="progress-list round-5">
                    <dt><?php echo __l('Time Left To Buy');?></dt>
                    <dd>
                        <div class="js-deal-end-countdown">&nbsp;</div>
                        <span class="js-time hide"><?php
			    $date1 = date('Y-m-d H:i:s', strtotime("now") );
			    $date2 = date('Y-m-d H:i:s', strtotime($deal['Deal']['end_date'].' GMT'));
				$date1 = is_int($date1) ? $date1 : strtotime($date1);
        $date2 = is_int($date2) ? $date2 : strtotime($date2);
       
        if (($date1 !== false) && ($date2 !== false)) {
            if ($date2 >= $date1) {
                $diff = ($date2 - $date1);
               
                if ($days = intval((floor($diff / 86400))))
                    $diff %= 86400;
                if ($hours = intval((floor($diff / 3600))))
                    $diff %= 3600;
                if ($minutes = intval((floor($diff / 60))))
                    $diff %= 60;
				$seconds = intval($diff);
			   if ($days > 0)
					$i[] = sprintf('%d Days', $days);
				if ($hours > 0)
					$i[] = sprintf('%d Hours', $hours);
				if ( ($minutes > 0))
					$i[] = sprintf('%d Minutes', $minutes);
				if ( ($seconds > 0))
					$i[] = sprintf('%d Seconds', $seconds);		
				echo implode(' ', $i);		
            }
        }
                        ?></span>
                    </dd>
                 </dl>
             <?php
				  	}
					else{
			?>
            			 <dl class="progress-list round-5">
                            <dt><?php echo __l('Time Left To Buy');?></dt>
                            <dd>                               
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
            <?php elseif($deal['Deal']['deal_status_id'] == ConstDealStatus::Closed || $deal['Deal']['deal_status_id'] == ConstDealStatus::Canceled || $deal['Deal']['deal_status_id'] == ConstDealStatus::Expired): ?>
                <dl class="progress-list">
                    <dt><?php echo __l('This deal ended at:');?></dt>
                    <dd><?php echo $this->Html->cDateTime($deal['Deal']['end_date'])?></dd>
                 </dl>
            <?php endif; ?>
        </div>
       <!-- <dl class="">
          <dt>Time Left To Buy</dt>
          <dd>
            <div class="js-deal-end-countdown hasCountdown"><span class="countdown_row countdown_show4"><span class="countdown_section"><span class="countdown_amount">24</span>h </span><span class="countdown_section"><span class="countdown_amount">35</span>m </span><span class="countdown_section"><span class="countdown_amount">22</span>s </span></span></div> </dd>
        </dl>-->
      </div>
    </div>
    <h3><?php echo __l('Description');?></h3>
    <div>
		<?php echo $this->Html->cHtml($deal['Deal']['description']);?>
    </div>
    <h3><?php echo __l('Reviews');?></h3>
    <div class="big-text"><?php echo $this->Html->cHtml($deal['Deal']['review']);?></div>
