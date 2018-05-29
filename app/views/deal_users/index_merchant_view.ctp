<?php /* SVN: $Id: index.ctp 73173 2011-11-29 05:10:30Z josephine_065at09 $ */ ?>
<div class="dealUsers index js-response js-responses js-update-status-over-block">
<?php if(!empty($pageTitle)): ?>
        <h2><?php echo $pageTitle;?></h2>
<?php endif; ?>
	<?php if(!empty($show_tab)){?>
		<div class="clearfix">
        <div class="grid_15 coupon-list-block omega alpha">
			  <ul class="coupon-chart clearfix">
			<li class="common-link clearfix">
				<div class="coupon-top-block clearfix">
				  <div class="coupon-bottom-block clearfix">
				  <div class="coupon-inner-block clearfix">
				  <span class="pending-approval">
					  <div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Pending (%s)'),$open),array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'open', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Pending-'.$open)); ?></div>					
					</span>

				 <ul class="coupon-cancel clearfix">
				 <li>
					<div class="clearfix">
					<ul class="coupon-canceled">
						<li class="canceled clearfix">
						<span class="canceled">
							<div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Canceled (%s)'),$canceled),array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'canceled', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Canceled-'.$canceled)); ?></div>
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
							  <div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Available (%s)'),$available),array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'available', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Available-'.$available)); ?></div>
							</span>
							<ul class="expired-block">
								<li class="expired clearfix">
									<span class="expired">
										<div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Expired (%s)'),$expired),array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'expired', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Expired-'.$expired)); ?></div>
									</span>
								</li>
								<li class="closed clearfix">
									<span class="used">
										<div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Used (%s)'),$used),array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'used', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Used-'.$used)); ?></div>
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
						   <div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Refund (%s)'),$refund),array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'refund', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Refund-'.$refund)); ?>
						 </div>
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
					<?php if(!empty($deal_id)) {?>
						<li class="gifted"><div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Gifted (%s)'),$gifted_deals),array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'gifted_deals', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Gifted Coupons-'.$gifted_deals)); ?></div></li>
					<?php }else{ ?>
						<li class="gifted"><div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Gifted (%s)'),$gifted_deals),array('controller' => 'deal_users', 'action' => 'index', 'user_id' => $this->Auth->user('id'), 'type' => 'gifted_deals', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Gifted -'.$gifted_deals)); ?></div></li>
						<li class="received"><div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('Received (%s)'),$recieved_gift), array('controller' => 'deal_users', 'action' => 'index', 'user_id' => $this->Auth->user('id'), 'type' => 'recieved_gift_deals', 'deal_user_view' => $deal_user_view, 'view' => $view), array('title' => 'Received -'.$recieved_gift)); ?></div></li>
					<?php }?>
					<li class="all"><div class="js-pagination"><?php echo $this->Html->link(sprintf(__l('All (%s)'),$all_deals),array('controller'=> 'deal_users','deal_id'=>$deal_id, 'action'=>'index','type' => 'all', 'deal_user_view' => $deal_user_view, 'view' => $view),array('title' => 'All-'.$all_deals)); ?></div></li>
				</ul>
			</div>
		</div>
	<?php } ?>
	<div class="clearfix">
	       <div>
              <?php echo $this->element('paging_counter');?>
          </div>
          	<div>
		<?php if(($this->Auth->user('user_type_id') == ConstUserTypes::Company) && (!empty($deal_id) && (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used')) ||  (!empty($show_coupon_code) && $deal_user_view == 'coupon'))){ ?>
			<?php echo $this->Form->create('DealUser', array('type' => 'post', 'class' => 'normal search-form clearfix js-ajax-form', 'action'=>'index')); ?>
				<div>
					<?php 
						echo $this->Form->input('coupon_code', array('label' => __l('Coupon code')));
						echo $this->Form->input('deal_id', array('type' => 'hidden', 'value' => $deal_id));
						echo $this->Form->input('deal_user_view', array('type' => 'hidden', 'value' => $deal_user_view));
						echo $this->Form->input('view', array('type' => 'hidden', 'value' => $view));
						if(!empty($this->request->data['DealUser']['type'])):
							echo $this->Form->input('type', array('type' => 'hidden'));
						endif;
						echo $this->Form->submit(__l('Search'));
					?>
				</div>
			<?php echo $this->Form->end(); ?>
		<?php } ?>
		    </div>
            </div>
		<?php
			echo $this->Form->create('DealUser' , array('class' => 'normal js-ajax-form','action' => 'update'));
			echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
			echo $this->Form->input('deal_user_view', array('type' => 'hidden', 'value' => $deal_user_view));
			echo $this->Form->input('view', array('type' => 'hidden', 'value' => $view));
			if (!empty($deal_id)):
				echo $this->Form->input('deal_id', array('type' => 'hidden', 'value' => $deal_id));
			elseif (!empty($this->request->params['named']['type'])):
				echo $this->Form->input('type', array('type' => 'hidden', 'value' => $this->request->params['named']['type']));
			endif;
		?>
   
	
		<table class="list">
			<tr>
				<?php
                if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'open' && ($this->Auth->user('user_type_id') == ConstUserTypes::User || ($this->Auth->user('user_type_id') == ConstUserTypes::Company && empty($deal_id)))) { ?>
                    <th rowspan="2" class="actions"><?php echo __l('Action'); ?></th>
                    <?php
                }
 ?>
                
                <?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used')) { ?>
				  <?php if($moreActions) { ?>
                  <th rowspan="2" class="select"></th>
                  <?php } ?>
				<?php } ?>
                <?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used') || (!empty($show_coupon_code) && $deal_user_view == 'coupon')) { ?>
					<th rowspan="2" class="actions"><?php echo __l('Action');?></th>
				<?php } ?>
				<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Purchased Date'), 'created');?></div></th>
				<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'canceled') { ?>
					<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Canceled Date'), 'modified');?></div></th>
				<?php } ?>
				<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'recieved_gift_deals'): ?>
					<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Gift From'),'gift_from');?></div></th>
				<?php endif;?>
                <?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'gifted_deals'): ?>
					<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Gift To'),'gift_to');?></div></th>
				<?php endif;?>
                <?php if(($this->Auth->user('user_type_id') == ConstUserTypes::Company) && !empty($deal_id)): ?>
					<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
					<?php if(!empty($deal_info['Deal']['is_subdeal_available'])):?>	
						<th rowspan="2"><div class="js-pagination"><?php echo __l('Sub Deal');?></div></th>
					<?php endif;?>
                <?php endif; ?>
                <?php if(!empty($deal_id)): ?>
					<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Amount'), 'discount_amount') . ' ('.Configure::read('site.currency').')';?></div></th>
				<?php else: ?>					
					<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Deal'), 'deal_id');?></div></th>
				<?php endif; ?>
				<?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used') ||  (!empty($show_coupon_code) && $deal_user_view == 'coupon')): ?>
					<?php if(($this->Auth->user('user_type_id') == ConstUserTypes::Company) ||  ($this->Auth->user('user_type_id') == ConstUserTypes::Admin)):?>
						<th colspan='3'><div class="js-pagination"><?php echo __l('Coupon code');?></div></th>
					<?php elseif(($this->Auth->user('user_type_id') == ConstUserTypes::User)):?>
						<th colspan='3'><div class="js-pagination"><?php echo __l('Coupon code');?></div></th>
					<?php else:?>
						<th rowspan="2" ><div class="js-pagination"><?php echo __l('Coupon code');?></div></th>
					<?php endif;?>
				<?php endif;?>
				<th rowspan="2"><?php echo __l('Quantities');?></th>				
				<?php if(Configure::read('charity.is_enabled') == 1):?>
				<th rowspan="2"><?php echo __l('Charity Contributions');?></th>
				<?php endif; ?>
            </tr>
			<tr>
			<?php if(($this->Auth->user('user_type_id') == ConstUserTypes::Company) ||  ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) ||  ($this->Auth->user('user_type_id') == ConstUserTypes::User)):?>
				<?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used') ||  (!empty($show_coupon_code) && $deal_user_view == 'coupon')): ?>
					<th class='actions'><div class="js-pagination"><?php echo __l('Action');?></div></th>
                    <th><div class="js-pagination"><?php echo __l('Bottom Code');?></div></th>
					<th><div class="js-pagination"><?php echo __l('Top Code');?></div></th>
				<?php endif;?>
			<?php endif;?>	
			</tr>				
			<?php
				if (!empty($dealUsers)):
					$i = 0;
					foreach ($dealUsers as $dealUser):
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
						if($dealUser['DealUser']['deal_user_coupon_count'] != 0):
							$status_class = 'js-checkbox-active';
						else:
							$status_class = 'js-checkbox-inactive';
						endif;
			?>
			<tr<?php echo $class;?>>
                <?php
                if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'open' && ($this->Auth->user('user_type_id') == ConstUserTypes::User || ($this->Auth->user('user_type_id') == ConstUserTypes::Company && empty($deal_id)))) { ?>
                    <td>
                        <?php
							if (!empty($dealUser['DealUser']['is_gift']) && $dealUser['DealUser']['user_id'] != $this->Auth->user('id')):
								echo __l('N/A');
                            elseif(!empty($dealUser['DealUser']['is_canceled'])) :
                                echo __l('Canceled');
                            else :
                                echo $this->Html->link(__l('Cancel'), array('controller' => 'deal_users', 'action' => 'cancel_deal', $dealUser['DealUser']['id']), array('title' => __l('Cancel'), 'class' => 'js-deal-cancel deal-cancel'));
                            endif;
                            ?>
                    </td>
                    <?php
                }
                ?>
				<?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used')) { ?>
					<?php if($moreActions) { ?>
                    <td class="select">
						<?php echo $this->Form->input('DealUser.'.$dealUser['DealUser']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$dealUser['DealUser']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?>
					</td>
                    <?php } ?>
				<?php } ?>
				<?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used') || (!empty($show_coupon_code) && $deal_user_view == 'coupon')) { ?>
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
                                      <li>
                                        <?php
                							echo $this->Html->link(__l('View Coupon'),array('controller' => 'deal_users', 'action' => 'view', 'filter_id' => (!empty($this->request->params['named']['type'])) ? $this->request->params['named']['type'] : '', $dealUser['DealUser']['id'],'admin' => false),array('title' => __l('View Coupon'), 'class'=>'js-thickbox','target' => '_blank', 'class'=>'view-icon js-thickbox'));
                                        ?>
                                        </li>
                                        <li>
                                        <?php
                                        	echo $this->Html->link(__l('Print'),array('controller' => 'deal_users', 'action' => 'view', 'filter_id' => (!empty($this->request->params['named']['type'])) ? $this->request->params['named']['type'] : '', $dealUser['DealUser']['id'],'type' => 'print'),array('target'=>'_blank', 'title' => __l('Print'), 'class'=>'print-icon'));
                						?>
                						</li>
        							 </ul>
        							</div>
        						<div class="action-bottom-block"></div>
							  </div>

							 </div>
                      
					</td>
                <?php } ?>
				<td class="dc"><?php echo $this->Html->cDateTime($dealUser['DealUser']['created']);?></td>
				<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'canceled'): ?>
					<td><?php echo $this->Html->cDateTime($dealUser['DealUser']['modified']);?></td>
				<?php endif;?>
				<?php if(!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'recieved_gift_deals')): ?>
					<td><?php echo $this->Html->cText($dealUser['DealUser']['gift_from']);?></td>
				<?php endif;?>
				 <?php if(!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'gifted_deals')): ?>
					<td><?php echo $this->Html->cText($dealUser['DealUser']['gift_to']);?></td>
				<?php endif;?>
                <?php if(($this->Auth->user('user_type_id') == ConstUserTypes::Company) && !empty($deal_id)): ?>
                    <td class="dc"><?php echo $this->Html->cText($dealUser['User']['username']);?></td>
					<?php if(!empty($deal_info['Deal']['is_subdeal_available']) && !empty($dealUser['SubDeal']['name'])):?>	
						<td class="dl"><?php echo $this->Html->cText($dealUser['SubDeal']['name']);?></td>
					<?php endif;?>
                <?php endif; ?>
                <?php if(!empty($deal_id) || !empty($deal_id)): ?>
                    <td class="dr"><?php echo $this->Html->cCurrency($dealUser['DealUser']['discount_amount']);?></td>
                <?php else: ?>
                    <td class="deal-user-gift">
						<?php echo $this->Html->link($this->Html->showImage('Deal', (!empty($dealUser['Deal']['Attachment'][0]) ? $dealUser['Deal']['Attachment'][0] :''), array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($dealUser['Deal']['name'], false)), 'title' => $this->Html->cText($dealUser['Deal']['name'], false))),array('controller' => 'deals', 'action' => 'view', $dealUser['Deal']['slug']), array('title' => $dealUser['Deal']['name'], 'escape' => false)); ?>
						<?php echo $this->Html->link($this->Html->cText($dealUser['Deal']['name'].(!empty($dealUser['SubDeal']['name']) ? ' - '.$dealUser['SubDeal']['name'] : '')), array('controller' => 'deals', 'action' => 'view', $dealUser['Deal']['slug']), array('escape' => false, 'title' => $dealUser['Deal']['name'].(!empty($dealUser['SubDeal']['name']) ? ' - '.$dealUser['SubDeal']['name'] : '')));?>
						<?php 
							if(!empty($dealUser['Deal']['coupon_start_date'])):
								if(date('Y-m-d H:i:s') < $dealUser['Deal']['coupon_start_date']):
								?>
									<span class="pending-coupons" title="<?php echo __l('Coupon code can be used from'.' '.$this->Html->cDateTime($dealUser['Deal']['coupon_start_date'], false));?>"></span>
								<?php endif;?>
						<?php endif;?>
					</td>
				<?php endif; ?>
				<?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used') ||  (!empty($show_coupon_code) && $deal_user_view == 'coupon') ):?>
						<?php if(($this->Auth->user('user_type_id') == ConstUserTypes::Company) ||  ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) ||  ($this->Auth->user('user_type_id') == ConstUserTypes::User)):?>
						<td class='actions'>
						<?php if (empty($dealUser['DealUser']['is_gift']) || (!empty($dealUser['DealUser']['is_gift']) && $dealUser['DealUser']['gift_email'] == $this->Auth->user('email')) || !empty($deal_id)):?>
                                <ul class="action-link clearfix">
								<?php foreach ($dealUser['DealUserCoupon'] as $dealUserCoupon) { ?>
									<?php if ((!empty($coupon_find_id) && in_array($dealUserCoupon['id'], $coupon_find_id)) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'available' && empty($dealUserCoupon['is_used'])) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'used' && !empty($dealUserCoupon['is_used'])) ||  (!empty($show_coupon_code) && $deal_user_view == 'coupon')) { ?>
									
											<?php
                                        	$uselink = Router::url(array('controller' => 'deal_user_coupons', 'action' => 'coupon_update_status', $dealUser['DealUser']['id'], 'coupon_id' => $dealUserCoupon['id'],'use'), true);
                                        	$undolink = Router::url(array('controller' => 'deal_user_coupons', 'action' => 'coupon_update_status', $dealUser['DealUser']['id'], 'coupon_id' => $dealUserCoupon['id'],'undo'), true);

												if($dealUserCoupon['is_used'] == 1) {
													$class = 'used';
													$statusMessage = 'Change status to not used';
												} else {
													$class = 'not-used';
													$statusMessage = 'Change status to used';
												}
												if($dealUser['Deal']['company_id'] == $user['Company']['id']) {
													$confirmation_message =  "{'divClass':'js-company-confirmation', }";
												} else {
													$confirmation_message = "{'divClass':'js-user-confirmation'}";
												}
											?>
											<?php if(empty($deal_id)) { ?>
											<li><?php echo $this->Html->link(__l('Print'),array('controller' => 'deal_users', 'action' => 'view',$dealUser['DealUser']['id'],'coupon_id' => $dealUserCoupon['id'],'type' => 'print'),array('target'=>'_blank', 'title' => __l('Print'), 'class'=>'print-icon'));?></li>
											<li>	<?php echo $this->Html->link(__l('View Coupon'),array('controller' => 'deal_users', 'action' => 'view',$dealUser['DealUser']['id'],'coupon_id' => $dealUserCoupon['id'],'admin' => false),array('title' => __l('View Coupon'), 'class'=>'js-thickbox','target' => '_blank', 'class'=>'view-icon js-thickbox'));?></li>
											<?php } ?>
											<?php
												$user = $this->Html->getCompany($this->Auth->user('id'));
												if ((!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='available') || !empty($deal_id)) {
													if (!empty($dealUserCoupon['is_used']) && $dealUser['Deal']['company_id'] == $user['Company']['id']) {
												?>
                                                 	<?php
                                                            if(!empty($dealUserCoupon['is_used'])){
                                                                $use_now ='<span class="used-info">'. __l('Used').'</span>';
																$types = Configure::read('deal.deal_coupon_used_type');
                                                                																
																if($types == 'click'){ ?>
													     	<li>
													     	<span class="<?php echo 'status-'.$dealUserCoupon['is_used']?> clearfix">
																<?php echo $use_now;
                                                                	echo $this->Html->link(__l('Undo'), array('controller' => 'deal_user_coupons', 'action' => 'update_status', $dealUser['DealUser']['id'], 'coupon_id' => $dealUserCoupon['id'],'is_used', 'u' => 'index_merchant_view'), array('class' => $class.' js-update-status','title' => $statusMessage)); ?>
                                                                </span>
                                                                </li>
                                                                <?php }
																// coupon code submit type
																if($types == 'submit' && $this->Auth->user('user_type_id') == ConstUserTypes::Company){
																	$code_type = (Configure::read('deal.deal_coupon_code_show_type') == 'top')? 'UniqueCouponCode' : 'CouponCode';
																	if($dealUser['Deal']['company_id'] == $user['Company']['id']) {
																		$confirmation_message =  "{'divClass':'js-company-confirmation', 'uselink':'".$uselink."', 'undolink':'".$undolink."', 'code_get':'".'DealUserCoupon'.$dealUserCoupon['id'].$code_type."', 'process':'undo'}";
																	} else {
																		$confirmation_message = "{'divClass':'js-user-confirmation', 'uselink':'".$uselink."', 'undolink':'".$undolink."', 'code_get':'".'DealUserCoupon'.$dealUserCoupon['id'].$code_type."', 'process':'undo'}";
																	} ?>
														        <li>
														        <span class="<?php echo 'status-'.$dealUserCoupon['is_used']?> clearfix">
																<?php echo $this->Html->link(__l('Undo'), array('controller' => 'deal_user_coupons', 'action' => 'coupon_update_status', $dealUser['DealUser']['id'], 'coupon_id' => $dealUserCoupon['id'],'undo'), array('class' => $class.' '.$confirmation_message.' js-coupon-update-status','title' => $statusMessage)); ?>
																</span>
																</li>
															<?php }
                                                            }else{
																if(!empty($dealUser['Deal']['coupon_start_date'])):
																	if((date('Y-m-d H:i:s') >= _formatDate('Y-m-d H:i:s', strtotime($dealUser['SubDeal']['coupon_start_date'])) && date('Y-m-d H:i:s') <= _formatDate('Y-m-d H:i:s', strtotime($dealUser['Deal']['coupon_expiry_date']))) || (date('Y-m-d H:i:s') >= _formatDate('Y-m-d H:i:s', strtotime($dealUser['Deal']['coupon_start_date'])) && empty($dealUser['Deal']['coupon_expiry_date'])) || (date('Y-m-d H:i:s') >= _formatDate('Y-m-d H:i:s', strtotime($dealUser['SubDeal']['coupon_start_date'])) && date('Y-m-d H:i:s') <= _formatDate('Y-m-d H:i:s', strtotime($dealUser['SubDeal']['coupon_expiry_date'])) && $dealUser['Deal']['is_now_deal'] == 1)):
																		$use_now = __l('Use Now'); ?>
                                                                  <li>
													        	<span class="<?php echo 'status-'.$dealUserCoupon['is_used']?> clearfix">
																	<?php echo $this->Html->link($use_now, array('controller' => 'deal_user_coupons', 'action' => 'update_status', $dealUser['DealUser']['id'], 'u' => 'index_merchant_view'), array('class' => $class.' '.$confirmation_message.' js-update-status','title' => $statusMessage)); ?>
																	</span>
																	</li> <?php
																	endif;
																endif;
                                                            }
															
?>													<?php } ?>
													<?php if ($class == 'not-used')  { ?>
                                                  
														<?php
                                                            if(!empty($dealUserCoupon['is_used'])){
                                                                $use_now = $use_now ='<span class="used-info">'. __l('Used').'</span>'; ?>
                                                           <li>
													     	<span class="<?php echo 'status-'.$dealUserCoupon['is_used']?> clearfix">
                                                                <?php echo $use_now;
															    echo $this->Html->link(__l('Undo'), array('controller' => 'deal_user_coupons', 'action' => 'update_status', $dealUser['DealUser']['id'], 'coupon_id' => $dealUserCoupon['id'], 'is_used', 'u' => 'index_merchant_view'), array('class' => $class.' '.$confirmation_message.' js-update-status', 'title' => $statusMessage));
															    ?>
															    </span>
															    </li>
														<?php }else { ?>

															<?php
																 if(!empty($dealUser['Deal']['coupon_start_date'])):
																	if((date('Y-m-d H:i:s') >= _formatDate('Y-m-d H:i:s', strtotime($dealUser['SubDeal']['coupon_start_date'])) && date('Y-m-d H:i:s') <= _formatDate('Y-m-d H:i:s', strtotime($dealUser['Deal']['coupon_expiry_date']))) || (date('Y-m-d H:i:s') >= _formatDate('Y-m-d H:i:s', strtotime($dealUser['Deal']['coupon_start_date'])) && empty($dealUser['Deal']['coupon_expiry_date'])) || (date('Y-m-d H:i:s') >= _formatDate('Y-m-d H:i:s', strtotime($dealUser['SubDeal']['coupon_start_date'])) && date('Y-m-d H:i:s') <= _formatDate('Y-m-d H:i:s', strtotime($dealUser['SubDeal']['coupon_expiry_date'])) && $dealUser['Deal']['is_now_deal'] == 1)):

																		$types = Configure::read('deal.deal_coupon_used_type');
																		$user_check = true;
																		if(!Configure::read('deal.is_user_can_change_coupon_type') && $this->Auth->user('user_type_id') == ConstUserTypes::User){
																			$user_check = false;
																		}
																		if($types == 'click' && $user_check){
																			$use_now = __l('Use Now'); ?>
																       <li>
													     	            <span class="<?php echo 'status-'.$dealUserCoupon['is_used']?> clearfix">
                                                                        <?php echo $this->Html->link($use_now, array('controller' => 'deal_user_coupons', 'action' => 'update_status', $dealUser['DealUser']['id'], 'coupon_id' => $dealUserCoupon['id'], 'is_used', 'u' => 'index_merchant_view'), array('class' => $class.' '.$confirmation_message.' js-update-status', 'title' => $statusMessage)); ?>
												                    	</span>
																		</li>
                                                                     <?php } ?>
					                 								<?php
																		// coupon code submit type
																		if($types == 'submit' && $this->Auth->user('user_type_id') == ConstUserTypes::Company){
																			$code_type = (Configure::read('deal.deal_coupon_code_show_type') == 'top')? 'UniqueCouponCode' : 'CouponCode';
																			if($dealUser['Deal']['company_id'] == $user['Company']['id']) {
																				$confirmation_message =  "{'divClass':'js-company-confirmation', 'uselink':'".$uselink."', 'undolink':'".$undolink."', 'code_get':'".'DealUserCoupon'.$dealUserCoupon['id'].$code_type."', 'process':'undo'}";
																			} else {
																				$confirmation_message = "{'divClass':'js-user-confirmation', 'uselink':'".$uselink."', 'undolink':'".$undolink."', 'code_get':'".'DealUserCoupon'.$dealUserCoupon['id'].$code_type."', 'process':'undo'}";
																			} ?>
																	       <li>
													                    	<span class="<?php echo 'status-'.$dealUserCoupon['is_used']?>  clearfix">
																			<?php echo $this->Html->link(__l('Use Now'), array('controller' => 'deal_user_coupons', 'action' => 'coupon_update_status', $dealUser['DealUser']['id'], 'coupon_id' => $dealUserCoupon['id'],'use'), array('class' => $class.' '.$confirmation_message.' js-coupon-update-status','title' => $statusMessage)); ?>
														             	  </span>
																	    	</li>
            												      	<?php }	?>
																			<?php endif;
																endif;
                                                            }
														?>
												
													<?php } ?>
												<?php } ?>
										<?php } ?>
									<?php }?>
								</ul>
							<?php else: ?>
								<?php echo '-';?>
							<?php endif;?>
						</td>
                        <td class="actions">
						<?php if (empty($dealUser['DealUser']['is_gift']) || (!empty($dealUser['DealUser']['is_gift']) && $dealUser['DealUser']['gift_email'] == $this->Auth->user('email')) || !empty($deal_id)):?>
					       <ul class="action-link clearfix">
								<?php foreach ($dealUser['DealUserCoupon'] as $dealUserCoupon) { ?>
									<?php if ((!empty($coupon_find_id) && in_array($dealUserCoupon['id'], $coupon_find_id)) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'available' && empty($dealUserCoupon['is_used'])) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'used' && !empty($dealUserCoupon['is_used'])) ||  (!empty($show_coupon_code) && $deal_user_view == 'coupon')) { ?>
										<li class="clearfix">
                                        	<?php if(Configure::read('deal.deal_coupon_used_type') == 'click'){ ?>
														<span class="coupon-code"><?php echo $dealUserCoupon['unique_coupon_code']; ?></span>
                                            <?php } else { ?>
												 <?php  
												 	if( $this->Auth->user('user_type_id') == ConstUserTypes::Company ){
														if( Configure::read('deal.deal_coupon_code_show_type') == 'top') {
															 echo $this->Form->input('DealUserCoupon.'.$dealUserCoupon['id'].'.unique_coupon_code', array('type' => 'text', 'label' => false, 'div' => false)); 
														}
														else{
												?>
	                                                		<span class="coupon-code"><?php echo $dealUserCoupon['unique_coupon_code']; ?></span>
                                                	
														<?php } 
													}	
													else{		
											?>
                                                      <span class="coupon-code"><?php echo $dealUserCoupon['unique_coupon_code']; ?></span>  
											<?php	}
											} ?>
										</li>
										<?php } ?>
									<?php }?>
								</ul>
								
							<?php else: ?>
								<?php echo '-';?>
							<?php endif;?>
						</td>
						<?php endif;?>
					<td class="actions">
					
						<?php if (empty($dealUser['DealUser']['is_gift']) || (!empty($dealUser['DealUser']['is_gift']) && $dealUser['DealUser']['gift_email'] == $this->Auth->user('email')) || !empty($deal_id)):?>
					          <ul class="coupon-code clearfix">
								<?php foreach ($dealUser['DealUserCoupon'] as $dealUserCoupon) { ?>
									<?php if ((!empty($coupon_find_id) && in_array($dealUserCoupon['id'], $coupon_find_id)) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'available' && empty($dealUserCoupon['is_used'])) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'used' && !empty($dealUserCoupon['is_used'])) || (!empty($show_coupon_code) && $deal_user_view == 'coupon')) { ?>
										<li class="clearfix">
                                        <?php if(Configure::read('deal.deal_coupon_used_type') == 'click'){ ?>
														<span class="coupon-code"><?php echo $dealUserCoupon['coupon_code']; ?></span>
                                            <?php } else { ?>
												 <?php  
												 	if( $this->Auth->user('user_type_id') == ConstUserTypes::Company ){
														if( Configure::read('deal.deal_coupon_code_show_type') == 'bottom') {
															 echo $this->Form->input('DealUserCoupon.'.$dealUserCoupon['id'].'.coupon_code', array('type' => 'text', 'label' => false, 'div' => false)); 
														}
														else{
												?>
                                                		<span class="coupon-code"><?php echo $dealUserCoupon['coupon_code']; ?></span>
                                                	
											<?php 		}
													}
													else{
											?>
                                            		<span class="coupon-code"><?php echo $dealUserCoupon['coupon_code']; ?></span>
                                            <?php
													}	
												} ?>
											
										</li>
										<?php } ?>
									<?php }?>
								</ul>
							
							<?php else: ?>
								<?php echo '-';?>
							<?php endif;?>
						</td>
						<?php endif; ?>
					<td class="dc">
						<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='available'):?>
							<?php echo $dealUser['DealUser']['quantity'] - $dealUser['DealUser']['deal_user_coupon_count'];?>
						<?php elseif(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='used'):?>
							<?php echo $dealUser['DealUser']['deal_user_coupon_count'];?>
						<?php else:?>
							<?php echo $dealUser['DealUser']['quantity'];?>
						<?php endif;?>
					</td>					
					<?php if(Configure::read('charity.is_enabled') == 1):?>
					<td class="dc"><?php
					if(empty($dealUser['CharitiesDealUser']['Charity']['name']) && $dealUser['CharitiesDealUser']['amount'] == 0.00):
						echo '-';
					else:	
						echo Configure::read('site.currency') . $this->Html->cCurrency($dealUser['CharitiesDealUser']['amount']); echo (!empty($dealUser['CharitiesDealUser']['Charity']['name']))? ' '.'for'.' '.$dealUser['CharitiesDealUser']['Charity']['name']:'';?>
                    <?php endif ?>
                    </td>		
					<?php endif ?>
				</tr>
			<?php
			endforeach;
		else:
	?>
			<tr>
				<td colspan="14" class="notice"><?php echo sprintf(__l('No coupons available'));?></td>
			</tr>
	<?php
		endif;
	?>
	</table>
        <?php if (!empty($dealUsers) && !empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'available' || $this->request->params['named']['type'] == 'used')):?>
            <?php if(!empty($dealUser['Deal']['deal_status_id']) && ($dealUser['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval || $dealUser['Deal']['deal_status_id'] != ConstDealStatus::Expired) && ((!empty($this->request->params['named']['type']) && ($this->request->params['named']['type']!='gifted_deals')))){?>
				<?php if($moreActions) { ?>
                <div class="admin-select-block">
					<div>
						<?php echo __l('Select:'); ?>
						<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
						<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
					</div>
				<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('options' => $moreActions, 'type' => 'select', 'class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
				</div>
                <?php } ?>
            <?php } ?>    
            <div class="hide">
                <?php echo $this->Form->submit('Submit'); ?>
            </div>
		<?php endif;?>
        <?php if (!empty($dealUsers) && !empty($deal_user_view) && $deal_user_view == 'coupon' && ($this->Auth->user('user_type_id') == ConstUserTypes::Company) && (Configure::read('deal.deal_coupon_used_type') == 'submit')):?>
       	<?php if(!empty($dealUser['Deal']['deal_status_id']) && ($dealUser['Deal']['deal_status_id'] == ConstDealStatus::Tipped || $dealUser['Deal']['deal_status_id'] == ConstDealStatus::Closed && $dealUser['Deal']['deal_status_id'] == ConstDealStatus::PaidToCompany)){?>
        				<div class="admin-select-block">
				<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('options' => $moreActions, 'type' => 'select', 'class' => 'js-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
				</div>

        <?php } ?>
        <div class="hide">
                <?php echo $this->Form->submit('Submit'); ?>
            </div>
        <?php endif;?>
		<?php if (!empty($dealUsers)):?>
			<div class="js-pagination">
				<?php echo $this->element('paging_links'); ?>
			</div>    
		<?php endif;?>
        <?php echo $this->Form->end();?>
    </div>
