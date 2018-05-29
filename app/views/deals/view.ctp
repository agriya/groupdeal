<?php /* SVN: $Id: view.ctp 80105 2013-05-21 12:15:15Z amala_187at12 $ */ ?>
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<?php 
	if($this->request->params['action'] !='index'):
		if($this->Html->isAllowed($this->Auth->user('user_type_id')) and   $deal['Deal']['deal_status_id'] != ConstDealStatus::Open && $deal['Deal']['deal_status_id'] != ConstDealStatus::Tipped && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval  && $deal['Deal']['deal_status_id'] != ConstDealStatus::Upcoming ):?>
			<div  class="view-outer-block pr">
				<p class="groupon-info pa textb">
					<?php echo __l('Oh no... You\'re too late for this ').' '.Configure::read('site.name').'!';?>
				</p>
				<div class="announcement-info round-10 clearfix">
					<div class="left sfont top-mspace">
						<p>
							<?php echo __l('Sign up for our daily email so you never miss another').' '.Configure::read('site.name').'!';?>
						</p>
					</div>
				</div>
			</div>
<?php 
		endif;
	endif; 
?>
<?php
if($this->request->params['action'] == 'view')
                        {
     ?>

       <div class="clearfix">
     <div class="deal-view-inner-block clearfix js-dialog-over-block">
				<div class="grid_18 alpha omega">
				<?php } ?>
					<div class="block1 clearfix">
					<?php
						if(empty($deal['Deal']['is_redeem_at_all_branch_address'])){
							if(count($deal['CompanyAddressesDeal']) == 1 && empty($deal['Deal']['is_redeem_in_main_address'])) {
								$id =0;
								foreach($deal['Company']['CompanyAddress'] as $key => $company_address){
									if($deal['CompanyAddressesDeal'][0]['company_address_id'] == $company_address['id'])
										$id = $key;
								}
								$multiple_loc_message = $deal['Company']['CompanyAddress'][$id]['address1'];
							} else if(count($deal['CompanyAddressesDeal']) <= 0 && !empty($deal['Deal']['is_redeem_in_main_address'])) {
								$multiple_loc_message = $deal['Company']['address1'];
							} else {
								$multiple_loc_message = __l('Multiple Location');
							}
						} else {
							if(!empty($deal['Deal']['is_redeem_in_main_address']) && empty($deal['Company']['CompanyAddress']) ){
								$multiple_loc_message = $deal['Company']['address1'];
							} else {
								$multiple_loc_message = __l('Multiple Location');
							}
						}
					?>						
                    <h2 class="title no-mar">
						<span class ="today-deal">
							<?php 
								if($this->request->params['action'] =='index'):
									echo __l("Today's Deal").': ';
								endif; 
							?>
            			</span>
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
                		<?php
							echo $this->Html->link($deal_name, array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']), array('escape' => false, 'title' =>sprintf(__l('%s'),$deal['Deal']['name'])));
                		?>
    	           	</h2>
                    <p class="company-msg-info textn">
						<span class="c-name"><?php echo $deal['Company']['name'] ;?></span>
        				<span class="c-message"><?php echo $multiple_loc_message;?></span>
					</p>
                    <div class="gallery-block" style="overflow:hidden; height:272px;">
						<div id='js-gallery'>
							<?php foreach($deal['Attachment'] as $attachment){?>
								<a><?php echo $this->Html->showImage('Deal', $attachment, array('dimension' => 'medium_big_thumb', 'class' => 'show-case-image', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false)));?></a>
            				<?php } ?>
            		    </div>
                    </div>
					
					<div class="price-block pa clearfix">
						<ul class="location-list grid_left">
							<li>

                              <?php 
							    echo $this->Html->link($deal['DealCategory']['name'] , array('controller' => 'deals', 'action' => 'index', 'category' => $deal['DealCategory']['slug']),array('title' => $deal['DealCategory']['name']));
							  
				  ?>

                            </li>
							
						</ul>
						<div class="grid_right clearfix">
							<?php 
								$class1='';
								if(!empty($deal['Deal']['is_enable_payment_advance'])) {
									  $class1='payment-price';
								}
							?>
							<?php if(!empty($deal['Deal']['is_enable_payment_advance'])): ?>
									<p class="price grid_left"><span class="pay-advance"> <?php echo __l('Pay in Advance'); ?></span></p>
								<?php endif;?>
							<p class="price grid_left <?php echo $class1; ?> ">
								
								<span>
									<?php echo (empty($deal['Deal']['is_subdeal_available']) || $deal['Deal']['is_now_deal']) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price'])) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['discounted_price']));?>
								</span>
							</p>
							<?php
								if($this->Html->isAllowed($this->Auth->user('user_type_id')) && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval):
									if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped):
										if(empty($deal['Deal']['is_subdeal_available'])){
											echo $this->Html->link(__l('Buy Now'), array('controller'=>'deals','action'=>'buy',$deal['Deal']['id']), array('title' => __l('Buy Now'),'class' =>'button dc'));
										} elseif($deal['Deal']['is_now_deal']) {
											if(!empty($deal['SubDeal']) && $deal['Deal']['is_subdeal_available'] && !$deal['Deal']['is_hold']) {
												if(!empty($deal['SubDeal'][0]['maxmium_purchase_per_day']) && $deal['SubDeal'][0]['deal_user_count'] >= $deal['SubDeal'][0]['maxmium_purchase_per_day']):
							?>
													<span class="no-available dc" title="<?php echo __l('No Longer Available');?>"><?php echo __l('No Longer Available');?></span>
							<?php
												else:
													echo $this->Html->link(__l('Buy Now'), array('controller'=>'deals','action'=>'buy', $deal['Deal']['id'], $deal['SubDeal'][0]['id']), array('title' => __l('Buy Now'),'class' =>'button dc'));
												endif;
											} elseif(empty($deal['SubDeal']) || ($deal['Deal']['is_hold'])) { 
							?>
												<span class="no-available dc" title="<?php echo __l('Now Not Available');?>"><?php echo __l('Now Not Available');?></span>
							<?php 
											}
										} else {
											echo $this->Html->link(__l('Buy Now'), '#', array('title' => __l('Buy Now'),'class' =>"button1 dc js-multiple-sub-deal {'opendialog': 'js-open-subdeal-".$deal['Deal']['id']."'}"));
							?>
											<div  class="hide" id="js-open-subdeal-<?php echo $deal['Deal']['id']; ?>">
												<h2><?php echo ' '.__l('Choose your deal').':'; ?> </h2>
												<ol class="near-list multi-near-list clearfix">
													<?php 
														foreach($deal['SubDeal'] as $subdeal){ ?>
														<li class="clearfix ver-space">
															<div class="multi-left-block grid_left">
																<h3 class="sub-deal-title textb"> <?php echo $this->Html->cText($subdeal['name']);?></h3>
																	<div class="clearfix">
																		<dl class="price-count-list">
																			<dt class="dc sfont"><?php echo __l('Value');?></dt>
																			<dd class="dc sfont"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['original_price']));?></dd>
																		</dl>
																		<dl class="price-count-list">
																			<dt class="dc sfont"><?php echo __l('Discount');?></dt>
																			<dd class="dc sfont"><?php echo $this->Html->cInt($subdeal['discount_percentage']) . "%"; ?></dd>
																		</dl>
																		<dl class="price-count-list">
																			<dt class="dc sfont"><?php echo __l('Save');?></dt>
																			<dd class="dc sfont"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['savings'])); ?></dd>
																		</dl>
																	</div>
																</div>
																<div class="multi-right-block textb grid_right">
																	<div class="clearfix near-price-block">
																		<div class="grid_3 omega alpha ">
																			<p class="near-bought main-bought">
																				<?php echo $this->Html->siteCurrencyFormat(($subdeal['discounted_price'])); ?>
																			</p>
																		</div>
																		<div class="clearfix near-deal-buy-block grid_3  omega">
																			<?php 
																				if( !empty($subdeal['max_limit']) && $subdeal['deal_user_count'] >= $subdeal['max_limit']):
																			?>
																				<p class='sold-out'><?php echo __l('sold out'); ?></p>
																			<?php else: ?>
																				<?php if(!empty($subdeal['is_enable_payment_advance'])):?>
																					<p class='sold-out'> <?php echo __l('Pay in Advance');?> </p>
																			<?php 
																					endif; 
																					echo $this->Html->link(__l('Buy Now'), array('controller'=>'deals','action'=>'buy', $deal['Deal']['id'], $subdeal['id']),array('class'=>'button dc','title' => __l('Buy').' - '.$this->Html->siteCurrencyFormat($subdeal['discounted_price']),'escape' => false));
																				endif;
																			?>
																		</div>
																	</div>
																	<?php if(Configure::read('deal.is_sold_count_per_deal_view_page_enabled')) : ?>
																		<div class="bought-count dc main-bought-count">
																			<?php echo $this->Html->cInt($subdeal['deal_user_count']); ?>
																			<span class="sold-info"> <?php echo ' '.__l('Bought');?></span>
																		</div>
																	<?php endif; ?>
																</div>
																<?php if(!empty($subdeal['is_enable_payment_advance'])):?>
																	<div class="clearfix">
																		<?php echo __l('Pay remaining').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['original_price'] - $subdeal['discount_amount'] - $subdeal['pay_in_advance'])).' ('.$this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['original_price'] - $subdeal['discount_amount'])).' - '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['pay_in_advance'])).') '.__l('directly to the merchant'); ?>
																	</div>
																<?php endif;?>
															</li>
														<?php } ?>
													</ol>
												</div>
								<?php
											} elseif($this->Html->isAllowed($this->Auth->user('user_type_id')) && $deal['Deal']['deal_status_id'] == ConstDealStatus::Upcoming):
								?>
												<span class="no-available dc" title="<?php echo __l('Upcoming');?>"><?php echo __l('Upcoming');?></span>
								<?php
											else:
								?>
												<span class="no-available dc" title="<?php echo __l('No Longer Available');?>"><?php echo __l('No Longer Available');?></span>
								<?php
											endif;
										endif;
								?>
							</div>
							<?php if(!empty($deal['Deal']['is_enable_payment_advance'])):?>
								<div class="clearfix pay-remaing-block pa dc sfont">
									<?php
										echo __l('Pay remaining').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['payment_remaining'])).' ('.$this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['original_price'] - $deal['Deal']['discount_amount'])).' - '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['pay_in_advance'])).') '.__l('directly to the merchant');
									?>
								</div>
							<?php endif;?>
						</div>
						<div class="buy-block clearfix">
							<?php if(!$deal['Deal']['is_now_deal']) { ?>
								 <?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped || $deal['Deal']['deal_status_id'] == ConstDealStatus::Closed): ?>
									  <div class="progress-block grid_left clearfix">
										  <?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped || $deal['Deal']['deal_status_id'] == ConstDealStatus::Closed): ?>
											<div class="bought-block">
													<?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped): ?>
													  <p class="deal-on no-mar textb"><?php echo __l('The deal is on!');?></p>
													<?php endif; ?>
													<?php if(Configure::read('deal.is_sold_count_per_deal_view_page_enabled')) : ?>
													 <p class="bought-amount"><?php echo $this->Html->cInt($deal['Deal']['deal_user_count']);?> <?php echo __l('offers sold so far');?></p>
													<?php endif; ?>
													<?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped): ?>
													 <p class="quick-info"> <?php echo __l('Get in quick or miss out!');?> </p>
													<?php endif; ?>
											</div>
											   <?php else: ?>
											<div class="progress-inner clearfix">
												  <h3 class="bought"><?php echo $this->Html->cInt($deal['Deal']['deal_user_count']);?> <?php echo __l('Bought');?></h3>
													<?php
														$pixels = round(($deal['Deal']['deal_user_count']/$deal['Deal']['min_limit']) * 100);
													?>
													<p class="progress-bar round-5"><?php /*<span class="arrow" style="left:<?php echo $pixels; ?>%"><?php echo $pixels; ?></span> */?><span class="progress-status" style="width:<?php echo $pixels; ?>%" title="<?php echo $pixels; ?>%">&nbsp;</span></p>
													<?php /*<p class="progress-value clearfix"><span class="progress-from">0</span><span class="progress-to"><?php echo $this->Html->cInt($deal['Deal']['min_limit']); ?></span></p> */?>
													<p class="progress-desc"><?php echo sprintf(__l('%s more needed to get the deal'),($deal['Deal']['min_limit'] - $deal['Deal']['deal_user_count'])) ?></p>
											 </div>
											<?php endif; ?>
									  </div>
							<?php endif; ?>
							<?php if($deal['Deal']['deal_status_id'] != ConstDealStatus::Upcoming && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval): ?>
								<?php if(($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped)):
            							if(empty($deal['Deal']['is_anytime_deal'])){
            					?>
											<dl class="progress-list progress-list2">
												<dt><?php //echo __l('Time left to buy');?></dt>
												<dd>
													<div class="js-deal-end-countdown">&nbsp;</div>
													<span class="js-time hide"><?php
														echo $end_time = intval(strtotime($deal['Deal']['end_date'].' GMT') - time());
													?></span>
												</dd>
											 </dl>
                               <?php } else { ?>
										 <dl class="progress-list grid_left clearfix">
											<dt class="time-left"><?php echo __l('Time left to buy');?></dt>
											<dd>
												<span class="unlimited"><?php echo __l('Unlimited'); ?></span>
											</dd>
										 </dl>
                                <?php }
                                    $per = (strtotime($deal['Deal']['end_date']) - strtotime($deal['Deal']['start_date']))  / 10;
                                    $next =  round((strtotime(date('Y-m-d H:i:s')) - strtotime($deal['Deal']['start_date'])) / $per);
                                    if($next <= 0){
                                        $next = 1;
                                    }
                                    if($next >= 10){
                                        $next = 10;
                                    }
                                ?>
                                <?php /*<div class="pg-img"><?php echo $this->Html->image("clock-img.png", array('alt'=> __l('[Image: Progress]'), 'title' => __l('Progress'))); ?></div> */ ?>
                                <?php elseif($deal['Deal']['deal_status_id'] == ConstDealStatus::Closed || $deal['Deal']['deal_status_id'] == ConstDealStatus::Canceled || $deal['Deal']['deal_status_id'] == ConstDealStatus::Expired || $deal['Deal']['deal_status_id'] == ConstDealStatus::PaidToCompany): ?>
                                    <dl class="progress-list progress-list1 clearfix">
                                        <dt><?php echo __l('This deal ended at:');?></dt>
                                        <dd><?php echo $this->Html->cDateTime($deal['Deal']['end_date'])?></dd>
                                     </dl>
                                <?php endif; ?>
            			   <?php endif; ?>
						<?php } else { ?>
							 <dl class="progress-list progress-list1">
									<dt><?php echo __l('Use Between');?></dt>
									<dd class="grid_left">
										<?php
										$coupon_start_date_detail = explode(" ",$deal['Deal']['coupon_start_date']);
										$coupon_start_time_detail = explode(":", $coupon_start_date_detail[1]);

										$coupon_expiry_date_detail = explode(" ",$deal['Deal']['coupon_expiry_date']);
										$coupon_expiry_time_detail = explode(":", $coupon_expiry_date_detail[1]);
										?>
										<?php echo _formatDate("h:i A" ,mktime($coupon_start_time_detail[0], $coupon_start_time_detail[1]))." - "._formatDate("h:i A" ,mktime($coupon_expiry_time_detail[0], $coupon_expiry_time_detail[1])); ?>
									</dd>
									<dd class="grid_left">
									  <?php echo __l('or we\'ll automatically refund you'); ?>
									</dd>
							 </dl>
						<?php } ?>
						<div class="grid_right deal-list-block clearfix">
							<dl class="deal-value dc grid_2 omega alpha clearfix">
								<dt class="sfont"><?php echo __l('Value');?></dt>
								<dd class="textb"><?php echo (empty($deal['Deal']['is_subdeal_available']) || $deal['Deal']['is_now_deal']) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['original_price'],false)) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['original_price'],false));?></dd>
							</dl>
							<dl class="deal-discount dc grid_2  omega alpha clearfix">
								<dt class="sfont"><?php echo __l('Discount');?></dt>
								<dd class="textb"><?php echo (empty($deal['Deal']['is_subdeal_available']) || $deal['Deal']['is_now_deal']) ? $this->Html->cFloat($deal['Deal']['discount_percentage']) . "%" : $this->Html->cFloat($deal['SubDeal'][0]['discount_percentage']) . "%"; ?></dd>
							</dl>
							<dl class="deal-save dc grid_2 omega alpha  clearfix">
								<dt class="sfont"><?php echo __l('You Save');?></dt>
								<dd class="textb"><?php echo (empty($deal['Deal']['is_subdeal_available']) || $deal['Deal']['is_now_deal']) ?  $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['savings'])) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal'][0]['savings'])); ?></dd>
							</dl>
						</div>
					</div>
					<div class="tipped-left">
						<div class="tipped-right">
							<div class="tipped-mid clearfix">
								<?php if(!$deal['Deal']['is_now_deal']) { ?>
									<?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped || $deal['Deal']['deal_status_id'] == ConstDealStatus::Closed): ?>
										<?php if($deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped || $deal['Deal']['deal_status_id'] == ConstDealStatus::Closed): ?>
											<p class="tipped-info grid_left">
												<?php echo sprintf(__l('Tipped at %s with %s bought'),$this->Html->cDateTime($deal['Deal']['deal_tipped_time']),$this->Html->cInt($deal['Deal']['min_limit']));?>
											</p>
										<?php endif; ?>
									<?php endif; ?>
								<?php } ?>
								<p class="sold-amount grid_right">
									<!--Amount solde per deal block start-->
									<?php if(Configure::read('deal.is_amount_sold_per_deal_view_page_enabled')) {
										$dealSoldAmount = 0;
										if(!empty($deal['Deal']['is_subdeal_available'])):
											if(!empty($deal['Deal']['is_now_deal'])) {
												$dealSoldAmount = $deal['Deal']['deal_user_count']*$deal['Deal']['discounted_price'];
											} else {
												foreach($deal['SubDeal'] as $subdeal) {
													$subdeal_Amount = $subdeal['deal_user_count']*$subdeal['discounted_price'];
													$dealSoldAmount = $dealSoldAmount+$subdeal_Amount;
												}
											}
										else:
											$dealSoldAmount = $deal['Deal']['deal_user_count']*$deal['Deal']['discounted_price'];
										endif;
										if($dealSoldAmount > 0) {
											echo __l('Total Amount Sold  ').'<span class="sold-amount">' .$this->Html->siteCurrencyFormat($this->Html->cCurrency($dealSoldAmount)) .'</span>'; 
										}
									}
								?>
							</p>
						</div>
					</div>
				</div>
				<div class="clearfix share-block">
					<div class="grid_right">
						<?php if(($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped) && $this->Html->isAllowed($this->Auth->user('user_type_id'))): ?>
							<div class="buy-it-block textb ver-space">
							<?php
								if(empty($deal['Deal']['is_subdeal_available'])){
									echo $this->Html->link(__l('Buy it for a friend!'), array('controller'=>'deals','action'=>'buy',$deal['Deal']['id'],'type' => 'gift'), array('title' => __l('Buy it for a friend'),'class' =>'buy-it sfont'));
								} elseif($deal['Deal']['is_now_deal'] && !empty($deal['SubDeal']) && $deal['Deal']['is_subdeal_available']) {
									echo $this->Html->link(__l('Buy it for a friend!'), array('controller'=>'deals','action'=>'buy', $deal['Deal']['id'], $deal['SubDeal'][0]['id'], 'type' => 'gift'), array('title' => __l('Buy it for a friend'),'class' =>'buy-it sfont'));
								}else{
									echo $this->Html->link(__l('Buy it for a friend!'), '#', array('title' => __l('Buy it for a friend'),'class' =>"buy-it sfont  js-multiple-sub-deal {'opendialog': 'js-open-subdeal-gift-".$deal['Deal']['id']."'}"));
								}
								if(!$deal['Deal']['is_now_deal'] && !empty($deal['SubDeal'])) { 
							?>
									<div id="js-open-subdeal-gift-<?php echo $deal['Deal']['id']; ?>">
										<h2><?php if(!empty($deal['SubDeal'])) { echo ' '.__l('Choose your deal').':'; } ?> </h2>
											<ol class="near-list multi-near-list clearfix">
												<?php foreach($deal['SubDeal'] as $subdeal){ ?>
													<li class="clearfix">
														<div class="multi-left-block">
															<h3 class="sub-deal-title textb"> <?php echo $this->Html->cText($subdeal['name']);?></h3>
															<div class="clearfix">
																<dl class="price-count-list">
																	<dt class="dc sfont"><?php echo __l('Value');?></dt>
																	<dd class="dc sfont"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['original_price']));?></dd>
																</dl>
																<dl class="price-count-list">
																	<dt class="dc sfont"><?php echo ' - '.__l('Discount');?></dt>
																	<dd class="dc sfont"><?php echo $this->Html->cInt($subdeal['discount_percentage']) . "%"; ?></dd>
																</dl>
																<dl class="price-count-list">
																	<dt class="dc sfont"><?php echo ' - '.__l('Save');?></dt>
																	<dd class="dc sfont"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['savings'])); ?></dd>
																</dl>
															</div>
														</div>
														<?php if(Configure::read('deal.is_sold_count_per_deal_view_page_enabled')) : ?>
															<div class="multi-right-block textb">
																<div class="clearfix near-price-block">
																	<div class="grid_3 omega alpha ">
																		<p class="main-bought">
																			<?php echo $this->Html->cInt($subdeal['deal_user_count']); ?>
																			<?php echo ' '.__l('Bought');?>
																			<?php endif; ?>
																		</p>
																	</div>
																	<div class="clearfix near-deal-buy-block grid_3  omega">
																		<?php if( !empty($subdeal['max_limit']) && $subdeal['deal_user_count'] >= $subdeal['max_limit']):?>
																			<p class='sold-out'><?php echo __l('sold out'); ?></p>
																		<?php else: ?>
																			<?php if(!empty($subdeal['is_enable_payment_advance'])):?>
																				<span class="pay-advance"> <?php echo __l('Pay in Advance');?> </span>
																			<?php endif;?>
																			<p class="deal-buy"><?php echo $this->Html->link($this->Html->siteCurrencyFormat(($subdeal['discounted_price'])), array('controller'=>'deals','action'=>'buy', $deal['Deal']['id'], $subdeal['id'], 'type' => 'gift'),array('title' => __l('Buy').' - '.$this->Html->siteCurrencyFormat($subdeal['discounted_price']),'escape' => false));?></p>
																		<?php endif;?>
																	</div>
																</div>
															</div>
															<?php if(!empty($subdeal['is_enable_payment_advance'])):?>
																<div class="clearfix">
																	<?php
																		echo __l('Pay remaining').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['payment_remaining'])).' ('.$this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['original_price'] - $subdeal['discount_amount'])).' - '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($subdeal['pay_in_advance'])).') '.__l('directly to the merchant');
																	?>
																</div>
															<?php endif;?>
														</li>
													<?php } ?>
												</ul>
											</ol>
										</div>
									<?php } ?>
								</div>
							<?php endif; ?>
							<?php
									/**************Get bitly url from city_deals******************/
									foreach($deal['City'] as $deal_city) {
										if($deal_city['slug'] == $get_current_city)	{
											if(Configure::read('site.city_url') == 'prefix'):
												$bityurl = $deal_city['CitiesDeal']['bitly_short_url_prefix'];
											else:
												$bityurl = $deal_city['CitiesDeal']['bitly_short_url_subdomain'];
											endif;
										}
									}
									// If currenct city is not the deal viewing city, showing first city as the default city //
									if(empty($bityurl)):
										if(Configure::read('site.city_url') == 'prefix'):
											$bityurl = $deal['City'][0]['CitiesDeal']['bitly_short_url_prefix'];
										else:
											$bityurl = $deal['City'][0]['CitiesDeal']['bitly_short_url_subdomain'];
										endif;
									endif;
								?>
						</div>
						<ul class="shar-list1 grid_left clearfix">							
							<li class="flike hor-mspace">
								<fb:like href="<?php echo Router::url('/', true).$get_current_city.'/deal/'.$deal['Deal']['slug'];?>" layout="button_count" font="tahoma"></fb:like>
							</li>
							<li class="twitter-frame pr hor-mspace">
								<a href="https://twitter.com/share?url=<?php echo $bityurl;?>&amp;lang=en&amp;via=<?php echo Configure::read('site.name'); ?>" class="twitter-share-button" data-lang="en" data-count="none" class="twitter-share-button"><?php echo __l('Tweet!');?></a>
							</li>
							<li class="quick hor-mspace">
								<?php echo $this->Html->link(__l('Quick! Email a friend!'), 'mailto:?body='.__l('Check out the great deal on ').Configure::read('site.name').' - '.Router::url('/', true).$get_current_city.'/deal/'.$deal['Deal']['slug'].'&amp;subject='.__l('I think you should get ').Configure::read('site.name').__l(': ').$deal['Deal']['discount_percentage'].__l('% off at ').$deal['Company']['name'], array('target' => 'blank', 'title' => __l('Send a mail to friend about this deal')));?>
							</li>
							<li class="gplus hor-mspace"><g:plusone size="medium"></g:plusone></li>
							<li class="pinit hor-mspace">
								<a href="http://pinterest.com/pin/create/button/?url=http://<?php echo $_SERVER['HTTP_HOST'].$this->here;?>&media=<?php echo $img_url;?>" class="pin-it-button" count-layout="horizontal"></a><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
							</li>
						</ul>
					</div>
				</div>
				<div class="block2 clearfix">
					<div class="fineprint-highlight-block clearfix">
					  <div class="fine-print-block grid_8 alpha">
							<h3 class="ver-space"><?php echo __l('THE FINE PRINT');?></h3>
							<ul>
							
							<?php
								if(!empty($deal['Deal']['coupon_expiry_date']) && empty($deal['Deal']['is_subdeal_available'])){
								?>
								<li>
								<?php
								echo __l('Expires ');
								echo  $this->Html->cDateTime($deal['Deal']['coupon_expiry_date']);
								?>
								</li>
								<?php
									 
								} else if(!empty($deal['Deal']['is_subdeal_available']) && !empty($deal['SubDeal'][0]['coupon_expiry_date']) ){
								?>
								<li>
								<?php
									 echo __l('Expires ');
									 echo  $this->Html->cDateTime($deal['SubDeal'][0]['coupon_expiry_date']);
								?>
								</li>
								<?php
								}
								if(!empty($deal['Deal']['coupon_condition']))
								{
								?>
								
								<li>
								<?php
								echo ' '.$this->Html->cHtml($deal['Deal']['coupon_condition']);
								?>
								</li>
								<?php
								}
								?>
							<li>
							<?php echo $this->Html->link(__l('Read the Deal FAQ'), array('controller' => 'pages', 'action' => 'view','faq', 'admin' => false), array('target'=>'_blank', 'title' => __l('Read the deal FAQ')));?> <?php echo __l(' for the basics.'); ?>
							</li>
							</ul>
						</div>
						<div class="fine-print-block grid_right grid_8 omega">
							<h3 class="ver-space"><?php echo __l('HIGHLIGHTS');?></h3>
							<?php
							if(!empty($deal['Deal']['coupon_highlights'])){
							$highlights=explode(".",$deal['Deal']['coupon_highlights']);
							?>
							<ul>
							<?php
							for($i=0;$i<(count($highlights)-1);$i++)
							{
							?>
							<li>
							<?php echo $this->Html->cHtml(rtrim($highlights[$i])).".";?>
							</li>
							<?php
							}
							?>
							</ul>
							<?php
							}
							?>
						</div>
					</div>
					<div class="location-block">
						<h3><?php echo __l('Location');?></h3>
						<div class="map-block round-4 top-mspace">
							<?php $map_zoom_level = !empty($deal['Company']['map_zoom_level']) ? $deal['Company']['map_zoom_level'] : Configure::read('GoogleMap.static_map_zoom_level');?>
							<?php if(Configure::read('GoogleMap.embedd_map') == 'Static'){ ?>
								<a href="http://maps.google.com/maps?q=<?php echo $this->Html->url(array('controller' => 'companies', 'action' => 'view',$deal['Company']['slug'],'ext' => 'kml'),true).'&amp;z='.$map_zoom_level?>" title="<?php echo $deal['Company']['name'] ?>" target="_blank">
							<?php } ?>
							<?php
								$company = $deal['Company'];
								//$company['CompanyAddress']= (!empty($allowed_branch_addresses) ? $allowed_branch_addresses : '');
								if(Configure::read('GoogleMap.embedd_map') == 'Static'):
									echo $this->Html->image($this->Html->formGooglemap($company,'619x196'));
								else:
									echo $this->Html->formGooglemap($company,'619x196');
								endif;
							?>
							<?php if(Configure::read('GoogleMap.embedd_map') == 'Static'): ?>
								</a>
							<?php endif; ?>
							<?php if(Configure::read('GoogleMap.embedd_map') != 'Static'):?>
								<small>
									<?php if(env('HTTPS')) { ?>
										<a href="https://maps-api-ssl.google.com/maps?q=<?php echo $this->Html->url(array('controller' => 'companies', 'action' => 'view',$deal['Company']['slug'],$deal['Deal']['slug'],'ext' => 'kml'),true).'&amp;z='.$map_zoom_level.'&amp;source=embed' ?>" title="<?php echo $deal['Company']['name'] ?>" target="_blank" style="color:#0000FF;text-align:left"><?php echo __l('View Larger Map');?></a>
									<?php } else { ?>
										<a href="http://maps.google.com/maps?q=<?php echo $this->Html->url(array('controller' => 'companies', 'action' => 'view',$deal['Company']['slug'],$deal['Deal']['slug'],'ext' => 'kml'),true).'&amp;z='.$map_zoom_level.'&amp;source=embed' ?>" title="<?php echo $deal['Company']['name'] ?>" target="_blank" style="color:#0000FF;text-align:left"><?php echo __l('View Larger Map');?></a>
									<?php } ?>
								</small>
							<?php endif;?>
						</div>
						<p class="web-url ver-mspace">
							<?php
								if($deal['Company']['is_company_profile_enabled'] && $deal['Company']['is_online_account']):
									echo $this->Html->link($this->Html->cText($deal['Company']['name'], false), array('controller' => 'companies', 'action' => 'view',   $deal['Company']['slug']),array('title' =>$this->Html->cText($deal['Company']['name'],false)), null, false);
								else:
									echo $this->Html->cText($deal['Company']['name']);
								endif;
							?>
							<?php if(!empty($deal['Company']['url'])): ?>
								<a href="<?php echo $deal['Company']['url'];?>" title="<?php echo $this->Html->cText($deal['Company']['url'],false);?>" target="_blank"><?php echo $this->Html->cText($deal['Company']['url'],false);?></a>
							<?php endif; ?>
                        </p>
						
								<?php if($deal['Deal']['is_redeem_in_main_address'] == 1) : ?>
								<ul class="branch-list address-list">
							
								<li class="no-mar">
									<address>
										<?php echo $this->Html->cText($deal['Company']['address1']);?>
										<?php echo !empty($deal['Company']['City']['name']) ? $this->Html->cText($deal['Company']['City']['name']) : '';?><?php echo !empty($deal['Company']['State']['name']) ? $this->Html->cText($deal['Company']['State']['name']) : '';?> <?php echo $this->Html->cText($deal['Company']['zip']);?>
									</address>
									</li>
									</ul>
								<?php endif; ?>
							
						
						<?php if(!empty($deal['Company']['CompanyAddress'])):?>
							<h3 class="branch"><?php echo __l('Branches:');?></h3>
							<?php
								$branch_address = array();
								foreach($deal['CompanyAddressesDeal'] as $company_address_deal){
									$branch_address[$company_address_deal['company_address_id']] = $company_address_deal['company_address_id'];
								}
							?>
							<ul class="branch-list">
								<?php $allowed_branch_addresses = array();
									foreach($deal['Company']['CompanyAddress'] as $address):
										if(in_array($address['id'], $branch_address) && (empty($deal['Deal']['is_redeem_at_all_branch_address']) || !empty($deal['Deal']['is_redeem_at_all_branch_address']))):
											$allowed_branch_addresses[] = $address;
								?>
											<li>
												<address class="address<?php echo $count;?>">
													<?php if (!empty($address['address1'])): ?>
														<span class="street-name"><?php echo ((!empty($address['address1'])) ? $address['address1'] : '') ; ?></span>
													<?php endif; ?>
													<?php if (!empty($address['City']['name']) || !empty($address['State']['name'])): ?>
														<span><?php echo (!empty($address['City']['name'])) ? $address['City']['name'] . ', ' : ''; ?> <?php echo (!empty($address['State']['name'])) ? $address['State']['name'] : ''; ?></span>
														<span><?php echo (!empty($address['Country']['name'])) ? $address['Country']['name'] : ''; ?></span>
													<?php endif; ?>
													<?php if (!empty($address['zip'])): ?>
														<span><?php echo $address['zip']; ?></span>
													<?php endif; ?>
												</address>
											</li>
								<?php 
										endif;  
									endforeach; 
								?>
							</ul>
						<?php endif; ?>
					</div>
					<div class="description-block top-space">
						<h3><?php echo __l('Description');?></h3>
							<?php echo $this->Html->cHtml($deal['Deal']['description']);?>
							<?php if(Configure::read('charity.is_enabled') == 1 && $deal['Deal']['charity_percentage'] > 0):?>
								<div class="charity-block clearfix">
									<h3><?php echo __l('Charity');?></h3>
									<?php 
										if(Configure::read('charity.who_will_choose') == ConstCharityWhoWillChoose::Buyer): 
											echo sprintf(__l('For every deal purchased, %s will donate %s of amount to charity'),Configure::read('site.name'),$deal['Deal']['charity_percentage'].'%'); 
										else:  
											echo sprintf(__l('For every deal purchased, %s will donate %s of amount to'),Configure::read('site.name'),$deal['Deal']['charity_percentage'].'%'); 
											if(!empty($deal['Charity'])): 
									?>
												<a href="<?php echo $deal['Charity']['url']; ?>" target="_blank"><?php echo $this->Html->cText($deal['Charity']['name']); ?></a>
									<?php 
											else:
												echo __l('charity');
											endif; 
										endif; 
									?>
								</div>
							<?php endif; ?>
							<?php if(!empty($deal['Deal']['review'])){?>
								<div class="review-block clearfix">
									<h3><?php echo __l('Reviews');?></h3>
									<?php echo $this->Html->cHtml($deal['Deal']['review']);?>
								</div>
							<?php } ?>							
							<ul class="share-link  top-mspace clearfix">
								<?php
									if(!empty($city_slug)):
										$tmpURL= $this->Html->getCityTwitterFacebookURL($city_slug);
									endif;
								?>
								<li><a href="<?php echo !empty($tmpURL['City']['twitter_url']) ? $tmpURL['City']['twitter_url'] : Configure::read('twitter.site_twitter_url'); ?>" title="<?php echo __l('Follow Us in Twitter'); ?>" target="_blank" class="twitter1"><?php echo __l('follow @');?><?php echo Configure::read('site.name');?><?php echo __l(' on Tweet'); ?></a></li>
								<li><a href="<?php echo !empty($tmpURL['City']['facebook_url']) ? $tmpURL['City']['facebook_url'] : Configure::read('facebook.site_facebook_url'); ?>" title="<?php echo __l('See Our Profile in Facebook'); ?>" target="_blank" class="facebook1"><?php echo __l('follow @');?><?php echo Configure::read('site.name');?><?php echo __l(' on Facebook it'); ?></a></li>
							</ul>
							<?php if(!empty($deal['Deal']['comment'])) { ?>
								<h3><?php echo Configure::read('site.name').' '.__l('says'); ?></h3>
								<?php echo $this->Html->cHtml($deal['Deal']['comment']); ?>
							<?php } ?>
							<div class="join-discussion-block  top-mspace"><div class="fb-comments" data-href="<?php echo Router::url('/', true).'deal/'.$deal['Deal']['slug'];?>" data-num-posts="2" data-width="630"></div></div>
						</div>
					</div>
					<div class="main-block-inner">
						<?php if (($this->Auth->user('user_type_id') == ConstUserTypes::Company && $deal['Company']['user_id'] == $this->Auth->user('id')) ):?>
							<?php echo $this->element('deals-stats', array('deal_id' => $deal['Deal']['id']));?>
							<div class="js-tabs">
								<ul class="clearfix">
									<li><?php echo $this->Html->link(__l('Deal Orders/Coupons'), '#tabs-'.$deal['Deal']['id']);?></li>
								</ul>
								<div id="tabs-<?php echo $deal['Deal']['id']; ?>" ><?php echo $this->element('deal_users-index', array('deal_id' => $deal['Deal']['id'])); ?></div>
							</div>
						<?php endif; ?>
					</div>
					
					<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
					<div class="main-block-inner admin-tabs-block">
						<div class="js-tabs">
							<div class="pptab-menu-left">
								<div class="pptab-menu-right">
									<div class="pptab-menu-center clearfix">							
										<ul class="clearfix menu-tabs admin-property-menu">
											<li><?php echo $this->Html->link(__l('Action'), '#admin-action'); ?></li>
											<li><?php echo $this->Html->link(__l('Deal Orders/Coupons'), '#deal-orders'); ?></li>
											<li><?php echo $this->Html->link(__l('Deal Summary'), '#deal-info'); ?></li>
									</div>
								</div>
							</div>
							<div class="pptview-mblock-ll">
								<div class="pptview-mblock-rr">
									<div class="pptview-mblock-mm clearfix">
										<div class="admin-properties">
											 <div id="admin-action">
											 <ul class="action-link action-link-view clearfix">
													
												
												<li>
												<?php
													echo $this->Html->link(__l('Edit'), array('controller' => 'deals', 'action'=>'edit', $deal['Deal']['id'], 'admin' => true), array('class' => 'edit js-edit', 'title' => __l('Edit')));
												?>
												</li>
												<li>
											  <?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $deal['Deal']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
												</li>
											   <?php if($deal['Deal']['is_subdeal_available'] && $deal['Deal']['sub_deal_count'] > 0): ?>
												<li><?php echo $this->Html->link(__l('Sub Deals'). ' (' .$deal['Deal']['sub_deal_count'].')', array('controller' => 'deals', 'action' => 'sub_deals', $deal['Deal']['id']), array('title' => __l('Sub Deals'), 'class' => 'js-thickbox subdeals-list')); ?></li>
												<?php endif; ?>
												<?php if(empty($deal['Deal']['is_subdeal_available'])):?>
												<li><?php echo $this->Html->link(__l('Clone Deal'),array('controller'=>'deals', 'action'=>'add', 'clone_deal_id'=>$deal['Deal']['id']), array('class' => 'add', 'title' => __l('Clone Deal')));?></li>
											  <?php endif;?>
											  <li>
												<?php echo $this->Html->link(__l('Stats'), array('controller'=>'charts', 'action'=>'chart_deal_stats', $deal['Deal']['id'], 'admin' => true),array('title' => __l('Stats'),'class' => 'deal-stats', 'target' => '_blank'));?>
												</li>
												<li>
													<?php echo $this->Html->link(__l('List Allocated Coupons'), array('controller' => 'deal_coupons', 'action' => 'index', 'deal_id' =>  $deal['Deal']['id']), array('class' =>'js-thickbox list-coupon', 'target' => '_blank', 'title' => __l('List Allocated Coupons')));?>
												</li>
											
											</ul>
											</div>
											<div id="deal-orders">
												<div id="tabs-<?php echo $deal['Deal']['id']; ?>" ><?php echo $this->element('deal_users-index', array('deal_id' => $deal['Deal']['id'])); ?></div>
											</div>
											<div id="deal-info">
												<?php echo $this->element('deals-stats', array('deal_id' => $deal['Deal']['id']));?>
											</div>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<?php 
						if (($count == 1 || !empty($from_page)) && $this->request->params['action'] == 'view') {
                           echo "</div>";
							echo $this->element('../deals/sidebar', array('deal' => $deal, 'count' => $count, 'get_current_city' => $get_current_city));
						}
                        if($this->request->params['action'] == 'view')
                        {
                            echo "</div></div>";
                        }
					?>
<div id="fb-root"></div>
<script type="text/javascript">
	  window.fbAsyncInit = function() {
		FB.init({appId: '<?php echo Configure::read('facebook.app_id');?>', status: true, cookie: true,
				 xfbml: true});
	  };
	  (function() {
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol +
		  '//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);
	  }());
</script>
<div id="fb-root"></div>
<script type="text/javascript">
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
</div>
<!-- Place this render call where appropriate -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>