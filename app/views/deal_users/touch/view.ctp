
<?php 
	if(!empty($dealUser['DealUserCoupon'])){
		if(!isset($this->request->params['named']['coupon_id'])) {
	?>
	<ul data-role="listview"><li>
	<?php echo $this->Html->showImage('Deal', $dealUser['Deal']['Attachment'][0], array('dimension' => 'iphone_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($dealUser['Deal']['name'], false)), 'title' => $this->Html->cText($dealUser['Deal']['name'], false)));?> <?php echo $this->Html->cText($dealUser['Deal']['name']); ?></li>
	<li class="address-content">
	<span><?php echo $dealUser['Deal']['Company']['address1'].', '. $dealUser['Deal']['Company']['address1']; ?> </span>
	<span> <?php echo $dealUser['Deal']['Company']['City']['name']; ?></span>
	<span><?php echo $dealUser['Deal']['Company']['State']['name'].' - '.$dealUser['Deal']['Company']['zip'];?></span>
    </li>
	</ul>
    <ul data-role="listview">
	<?php
		$used_coupon_count =0;
		foreach($dealUser['DealUserCoupon'] as $deal_user_coupon){
			if($deal_user_coupon['is_used'])
			{
				$used_coupon_count +=1;
			}
		}
		$total_coupon_count = count($dealUser['DealUserCoupon']);
		$available_coupon_count = $total_coupon_count-$used_coupon_count;
		if($available_coupon_count){?>
		
		<li data-role="list-divider"><?php echo __l('Available ').$available_coupon_count.' '.__l('of').' '.$total_coupon_count; ?></li>
		<?php
			foreach($dealUser['DealUserCoupon'] as $deal_user_coupon):
				if(!$deal_user_coupon['is_used'])
				{
		?>
				<li>
				<?php echo $this->Html->link($deal_user_coupon['unique_coupon_code'],array('controller' => 'deal_users', 'action' => 'view',$dealUser['DealUser']['id'],'coupon_id' => $deal_user_coupon['id'], 'filter_id' => $this->request->params['named']['filter_id'], 'admin' => false),array('title' => __l('View Coupon'), 'class'=>'js-thickbox'));?>
				</li>
		<?php	
				}
			endforeach;
		?>
		<?php	
		}
		
		if($used_coupon_count){?>
		<li data-role="list-divider"><?php echo __l('Used ').$used_coupon_count.' '.__l('of').' '.$total_coupon_count; ?></li>
		<?php
			foreach($dealUser['DealUserCoupon'] as $deal_user_coupon):
				if($deal_user_coupon['is_used'])
				{
		?>
				<li>
				<?php echo $this->Html->link($deal_user_coupon['unique_coupon_code'],array('controller' => 'deal_users', 'action' => 'view',$dealUser['DealUser']['id'],'coupon_id' => $deal_user_coupon['id'],'admin' => false),array('title' => __l('View Coupon'), 'class'=>'js-thickbox'));?>
				</li>
		<?php	
				}
			endforeach;
		?>
		
		<?php	
		}
		?>
        </ul>
	<?php 
		} else {  ?>
			<h3 class="heading-middle"><?php  echo $this->Html->cText($dealUser['Deal']['name']); ?></h3>
			
			<?php   foreach($dealUser['DealUserCoupon'] as $deal_user_coupon){ ?>
				<div  class="bar-code">
				<?php if(Configure::read('barcode.is_barcode_enabled') == 1){
					  $barcode_width = Configure::read('barcode.width');
					  $barcode_height = Configure::read('barcode.height');
					  if(Configure::read('barcode.symbology') == 'qr'):
						  $parsed_url = parse_url($this->Html->url('/', true));
						  $qr_mobile_site_url = str_ireplace($parsed_url['host'], 'm.' . $parsed_url['host'], Router::url(array(
								'controller' => 'deal_user_coupons',
								'action' => 'check_qr',
								$deal_user_coupon['coupon_code'],
								$deal_user_coupon['unique_coupon_code'],
								'admin' => false
							) , true));

						  ?>
					   <img src="http://chart.apis.google.com/chart?cht=qr&chs=<?php echo $barcode_width; ?>x<?php echo $barcode_height; ?>&chl=<?php echo $qr_mobile_site_url; ?>" alt = "[Image: Deal qr code]"/>
					  <?php endif;
					  if(Configure::read('barcode.symbology') == 'c39'): ?>
						 <img style="margin:0px;padding:0px;" src="<?php echo Router::url(array('controller' => 'deals', 'action' => 'barcode',$dealUser['Deal']['id'])); ?>" alt = "[Image: Deal # <?php echo $dealUser['Deal']['id'];?>]"/>
					  <?php endif; ?><p style="margin:0px 0px 0px 28px;padding:0px;font-weight:bold;"><?php
					  echo $deal_user_coupon['unique_coupon_code'];?></p><?php
				}
			?>
            </div>
            <?php   
				$user = $this->Html->getCompany($this->Auth->user('id'));
				$uselink = Router::url(array('controller' => 'deal_user_coupons', 'action' => 'coupon_update_status', $dealUser['DealUser']['id'], 'coupon_id' => $deal_user_coupon['id'],'use'), true);
				$undolink = Router::url(array('controller' => 'deal_user_coupons', 'action' => 'coupon_update_status', $dealUser['DealUser']['id'], 'coupon_id' => $deal_user_coupon['id'],'undo'), true);

				if($deal_user_coupon['is_used'] == 1) {
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
				if (!empty($deal_user_coupon['is_used']) && $dealUser['Deal']['company_id'] == $user['Company']['id']) {
				?>
						<div data-inline="true">
						<?php
							if(!empty($deal_user_coupon['is_used'])){
								$use_now = __l('Used');
								$types = Configure::read('deal.deal_coupon_used_type');
																								
								if($types == 'click'){
									echo $use_now;
									echo $this->Html->link(__l('Undo'), array('controller' => 'deal_user_coupons', 'action' => 'update_status', $dealUser['DealUser']['id'], 'coupon_id' => $deal_user_coupon['id'],'is_used'), array('title' => $statusMessage));
								}
								// coupon code submit type
								if($types == 'submit' && $this->Auth->user('user_type_id') == ConstUserTypes::Company){
									$code_type = (Configure::read('deal.deal_coupon_code_show_type') == 'top')? 'UniqueCouponCode' : 'CouponCode';
									if($dealUser['Deal']['company_id'] == $user['Company']['id']) {
										$confirmation_message =  "{'divClass':'js-company-confirmation', 'uselink':'".$uselink."', 'undolink':'".$undolink."', 'code_get':'".'DealUserCoupon'.$deal_user_coupon['id'].$code_type."', 'process':'undo'}";
									} else {
										$confirmation_message = "{'divClass':'js-user-confirmation', 'uselink':'".$uselink."', 'undolink':'".$undolink."', 'code_get':'".'DealUserCoupon'.$deal_user_coupon['id'].$code_type."', 'process':'undo'}";
									}
										echo $this->Html->link(__l('Undo'), array('controller' => 'deal_user_coupons', 'action' => 'coupon_update_status', $dealUser['DealUser']['id'], 'coupon_id' => $deal_user_coupon['id'],'undo'), array('title' => $statusMessage));
								}
							}else{
								if(!empty($dealUser['Deal']['coupon_start_date'])):
									if(date('Y-m-d H:i:s') >= $dealUser['Deal']['coupon_start_date']):
										$use_now = __l('Use Now');
										echo $this->Html->link($use_now, array('controller' => 'deal_user_coupons', 'action' => 'update_status', $dealUser['DealUser']['id'], 'coupon_id' => $deal_user_coupon['id'],'is_used'), array('title' => $statusMessage, 'data-theme'=>'b', 'data-role' => 'button'));
									endif;
								endif;
							}
							
						?>
						</div>
					<?php } ?>
					<?php if ($class == 'not-used')  { ?>
						<div data-inline="true">
						<?php
							if(!empty($deal_user_coupon['is_used'])){
								$use_now = __l('Used');
								echo $use_now;
								echo $this->Html->link(__l('Undo'), array('controller' => 'deal_user_coupons', 'action' => 'update_status', $dealUser['DealUser']['id'], 'coupon_id' => $deal_user_coupon['id'], 'is_used'), array('title' => $statusMessage));
							}else {
								 if(!empty($dealUser['Deal']['coupon_start_date'])):
									if(date('Y-m-d H:i:s') >= $dealUser['Deal']['coupon_start_date']):
										$types = Configure::read('deal.deal_coupon_used_type');
										$user_check = true;
										if(!Configure::read('deal.is_user_can_change_coupon_type') && $this->Auth->user('user_type_id') == ConstUserTypes::User){
											$user_check = false;
										}
										if($types == 'click' && $user_check){
											$use_now = __l('Use Now');
											echo $this->Html->link($use_now, array('controller' => 'deal_user_coupons', 'action' => 'update_status', $dealUser['DealUser']['id'], 'coupon_id' => $deal_user_coupon['id'], 'is_used'), array('title' => $statusMessage, 'data-theme'=>'b', 'data-role' => 'button'));
										}
										// coupon code submit type
										if($types == 'submit' && $this->Auth->user('user_type_id') == ConstUserTypes::Company){
											$code_type = (Configure::read('deal.deal_coupon_code_show_type') == 'top')? 'UniqueCouponCode' : 'CouponCode';
											if($dealUser['Deal']['company_id'] == $user['Company']['id']) {
												$confirmation_message =  "{'divClass':'js-company-confirmation', 'uselink':'".$uselink."', 'undolink':'".$undolink."', 'code_get':'".'DealUserCoupon'.$deal_user_coupon['id'].$code_type."', 'process':'undo'}";
											} else {
												$confirmation_message = "{'divClass':'js-user-confirmation', 'uselink':'".$uselink."', 'undolink':'".$undolink."', 'code_get':'".'DealUserCoupon'.$deal_user_coupon['id'].$code_type."', 'process':'undo'}";
											}
												echo $this->Html->link(__l('Use Now'), array('controller' => 'deal_user_coupons', 'action' => 'coupon_update_status', $dealUser['DealUser']['id'], 'coupon_id' => $deal_user_coupon['id'],'use'), array('title' => $statusMessage, 'data-theme'=>'b', 'data-role' => 'button'));
										}						
									endif;
								endif;
							}
						?>
						</div>
					<?php } 
			}	 
		}	
	}
?>