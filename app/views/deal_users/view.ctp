<?php 
	if(!empty($dealUser['DealUserCoupon'])):
		foreach($dealUser['DealUserCoupon'] as $deal_user_coupon):
	?>
<div style="padding: 10px; width: 800px; font-size:12px;">
<div style="width:750px; margin:0px auto;margin:0px;padding:0px; font-family:Arial, Helvetica, sans-serif; font-size:12px;color:#000;">
<div style="color:#999;  border:2px solid #000; padding:20px; background:#fff;">
  <table width="100%" style="background-color:#fff;border-bottom:2px solid #ddd;padding:0px;margin:0px;">
<tr>
      <td width="70%" style="padding:10px 0px;">
		<?php echo $this->Html->image(Router::url(array(
					'controller' => 'img',
					'action' => 'blue-theme',
					'logo-black.png',
					'admin' => false
				),true), array('alt'=> __l('[Image: Logo]'), 'title' => Configure::read('site.name'))); ?>
       </td>
      <td width="30%" style="padding:10px 0px;">
      	  <strong style="width:120px;margin:0px;padding:0px 0px 0px 10px;font-size:20px;color:#000;">
			<?php echo '#'.$this->Html->cText($deal_user_coupon['coupon_code']);?>
			</strong>
      </td>
    </tr>
    <tr>	
</table>
		<p>
			<strong style="color:#000; font-size:20px;display:block;margin:15px 0px 10px 0px; ">
				<?php 
					$sub_title = '';
					 if($dealUser['Deal']['is_now_deal'] == 0) {
						$sub_title = (!empty($dealUser['SubDeal']['name']) ? ' - '.$dealUser['SubDeal']['name'] : '');
					}
						echo $this->Html->cText($dealUser['Deal']['name'].$sub_title);
					
			?></strong>
		</p>
      <div  style=" margin:0px,padding:10px;">
		<table style="" style="background-color:#fff;border-bottom:2px solid #ddd;padding:0px;margin:0px;width: 100%; font-size:12px;color:#000;">
		<tbody>
		<tr>
			<td style="width: 49%;line-height:22px;vertical-align:top;color:#000;">
				<strong style="color:#000; font-size4:16px;display:block;margin:5px 0px 0px 0px;"><?php echo __l('Recipient:');?></strong>
				<p style="font-family:arial;font-size:13px;margin:0px;padding:0px;">	<?php echo !empty($dealUser['DealUser']['gift_to']) ? $this->Html->cText($dealUser['DealUser']['gift_to']) : $this->Html->cText($dealUser['User']['username']);?></p>
				<strong style="color:#000; font-size4:16px;display:block;margin:5px 0px 0px 0px;"><?php echo __l('Valid From:');?></strong>
					<p style="font-family:arial;font-size:13px;margin:0px;padding:0px;"> <?php echo $this->Html->cDateTime($dealUser['Deal']['coupon_start_date']);?></p>
				<?php if(empty($dealUser['Deal']['is_anytime_deal'])):?>
					<strong style="color:#000; font-size4:16px;display:block;margin:5px 0px 0px 0px;"><?php echo __l('Expires:');?></strong>
						<p style="font-family:arial;font-size:13px;margin:0px;padding:0px;"> <?php echo $this->Html->cDateTime($dealUser['Deal']['coupon_expiry_date']);?></p>
				<?php endif;
                if(!empty($dealUser['Deal']['coupon_condition'])){?>
				<strong style="color:#000; font-size:16px;display:block;margin:6px 0px 0px 0px; padding:0px 0px 0px 0px"><?php echo __l('Fine Print:');?></strong>					
				  	<p style="font-family:arial;font-size:13px;margin:0px;padding:0px;"> <?php echo $this->Html->cText($dealUser['Deal']['coupon_condition']);?></p>
                <?php }?>
				<?php if($dealUser['SubDeal']['is_enable_payment_advance']):?>
					<strong style="color:#000; font-size:16px;display:block;margin:6px 0px 0px 0px; padding:0px 0px 0px 0px"><?php echo __l('Payment Remaining:');?></strong>					
						<p style="font-family:arial;font-size:13px;margin:0px;padding:0px;"> <?php echo Configure::read('site.currency').$this->Html->cText($dealUser['SubDeal']['payment_remaining']);?></p>
				<?php endif;?>
				<?php if($dealUser['Deal']['is_enable_payment_advance']):?>
					<strong style="color:#000; font-size:16px;display:block;margin:6px 0px 0px 0px; padding:0px 0px 0px 0px"><?php echo __l('Payment Remaining:');?></strong>					
						<p style="font-family:arial;font-size:13px;margin:0px;padding:0px;"> <?php echo Configure::read('site.currency').$this->Html->cText($dealUser['Deal']['payment_remaining']);?></p>
				<?php endif;?>
			</td>
<td style="width: 49%;line-height:22px; color:#000;" valign="top">
				<dl style="margin:0px;padding:0px;">
               	  <dt style="width:120px; float:left; margin:0px; padding:0px; text-align:right; font-weight:bold; color:#000;"><?php echo __l('Purchased:  ')?> </dt>
				  <dd style="width:157px;float:left;margin:0px;padding:0px 0px 0px 10px;font-family:arial;font-size:13px;"><?php echo $this->Html->cDateTime($dealUser['DealUser']['created']);?></dd>
				</dl>
				<?php
				$i = 1;
				 if(!empty($dealUser['Deal']['is_redeem_in_main_address'])){ ?>
				<dl style="margin:0px;padding:0px;">
				
					<dt style="width:120px; float:left; margin:0px; padding:0px; text-align:right; font-weight:bold; color:#000;"><?php echo __l('Redeem At').' #'.$i.': '; ?></dt>
				  <dd style="width:157px;float:left;margin:0px;padding:0px 0px 0px 10px; font-family:arial;font-size:13px;">
					<?php echo $this->Html->cText($dealUser['Deal']['Company']['name']);?><br />
					<?php if(!empty($dealUser['Deal']['Company']['address1'])) { ?>
						<?php echo $this->Html->cText($dealUser['Deal']['Company']['address1']);?><br />
					<?php } ?>
					<?php /*?><?php if(!empty($dealUser['Deal']['Company']['address2'])) { ?>
						<?php echo $this->Html->cText($dealUser['Deal']['Company']['address2']);?><br />
					<?php } ?><?php */?>
					<?php if(!empty($dealUser['Deal']['Company']['City']['name'])) { ?>
						<?php echo $this->Html->cText($dealUser['Deal']['Company']['City']['name']);?><br />
					<?php } ?>
					<?php if(!empty($dealUser['Deal']['Company']['State']['name'])) { ?>
						<?php echo $this->Html->cText($dealUser['Deal']['Company']['State']['name']);?><br />
					<?php } ?>
					<?php if(!empty($dealUser['Deal']['Company']['Country']['name'])) { ?>
						<?php echo $this->Html->cText($dealUser['Deal']['Company']['Country']['name']);?><br />
					<?php } ?>
					<?php if(!empty($dealUser['Deal']['Company']['zip'])) { ?>
						<?php echo $this->Html->cText($dealUser['Deal']['Company']['zip']);?><br/>
					<?php } ?>
				  </dd>
				</dl>
                <?php 
						$i++;
					} ?>
                <?php	
					$branch_address = array();
					if(!empty($dealUser['Deal']['CompanyAddressesDeal'])){
						foreach($dealUser['Deal']['CompanyAddressesDeal'] as $address){
							$branch_address[$address['id']] = $address['company_address_id'];
						}
					}				 
					if(!empty($dealUser['Deal']['is_redeem_at_all_branch_address']) || ( empty($dealUser['Deal']['is_redeem_at_all_branch_address']) && (!empty($dealUser['Deal']['CompanyAddressesDeal']))) ){ 
						foreach($dealUser['Deal']['Company']['CompanyAddress'] as $company){
							if((in_array($company['id'], $branch_address) && empty($dealUser['Deal']['is_redeem_at_all_branch_address'])) || !empty($dealUser['Deal']['is_redeem_at_all_branch_address'])){
								$company_branch_address = $this->Html->getCompanyAddress($company['id']);
					?>
				<dl style="margin:0px;padding:0px;">
					<?php $content = (empty($dealUser['Deal']['is_redeem_in_main_address'])) ? __l('Redeem At'). ' ' : ''; ?>
					<dt style="width:120px; float:left; margin:0px; padding:0px; text-align:right; font-weight:bold; color:#000;"> <?php echo $content .'#'.$i.': '; ?></dt>
				  <dd style="width:157px;float:left;margin:0px;padding:0px 0px 0px 10px; font-family:arial;font-size:13px;">
					<?php 
						if($i == 1){
							echo $this->Html->cText($dealUser['Deal']['Company']['name']);
						}
						?><br />                    
					<?php if(!empty($company_branch_address['CompanyAddress'])) { ?>
						<?php echo $this->Html->cText($company_branch_address['CompanyAddress']['address1']);?><br />
					<?php } ?>
					<?php /*?><?php if(!empty($company_branch_address['CompanyAddress']['address2'])) { ?>
						<?php echo $this->Html->cText($company_branch_address['CompanyAddress']['address2']);?><br />
					<?php } ?><?php */?>
					<?php if(!empty($company_branch_address['City']['name'])) { ?>
						<?php echo $this->Html->cText($company_branch_address['City']['name']);?><br />
					<?php } ?>
					<?php if(!empty($company_branch_address['State']['name'])) { ?>
						<?php echo $this->Html->cText($company_branch_address['State']['name']);?><br />
					<?php } ?>
					<?php if(!empty($company_branch_address['Country']['name'])) { ?>
						<?php echo $this->Html->cText($company_branch_address['Country']['name']);?><br />
					<?php } ?>
					<?php if(!empty($company_branch_address['zip'])) { ?>
						<?php echo $this->Html->cText($company_branch_address['zip']);?><br/>
					<?php } ?>
				  </dd>
				</dl>
                <?php
							}
							$i++;
						}
					}
				?>
				<div style="clear:both"></div>
				<div style="margin:0px 0px 0px 82px;padding:0px 0px 0px 10px;font-family:arial;font-size:13px;">
					<?php
				if(Configure::read('barcode.is_barcode_enabled') == 1){
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
			</td>
		</tr>
		</tbody>
		</table>
		<table style="padding:0px; margin:10px 0px 0px 0px;">
		<tr>
 	      <td  width="60%">
 	        <strong style="color:#000; font-size:16px; padding:0px 0px 0px 0px"><?php echo __l('Universal Fine Print:');?></strong><br/>

            <p style="margin:0px;padding:0px;font-size:12px;color:#000;"><?php echo __l('Not valid for cash back (unless required by law). Must use in one visit. Doesn\'t cover tax or gratuity. Can\'t be combined with other offers.');?><br/>
                </p>
	       	</td>
		<td  width="40%" style="text-aling:left;">
		</td>
		</tr>
		</table>
        </div>
</div>
	<?php if(!empty($dealUser['DealUser']['is_gift'])):?>
		<table width="90%"  style="margin:10px 0px 10px 40px;padding:0px 0px 10px 12px;border-left:8px solid #DDDDDD;">
		<tr>
		<td width="80%" style="margin:0px;padding:0px;">
		   <p><?php echo $this->Html->image(Router::url(array(
					'controller' => 'img',
					'action' => 'gift.png',
					'admin' => false
				),true), array('alt'=> __l('[Image: Logo]'), 'title' => Configure::read('site.name'))); ?>
				<?php echo __l('A gift from').' ';?><strong style="color:#000;"><?php echo $dealUser['DealUser']['gift_from'];?></strong></p>
		  <p style="padding:10px; background:#fff; color:#000;"><?php echo $dealUser['DealUser']['message'];?></p>
		</td>
		</tr>
		</table>
	<?php endif;?>
    <table style="margin:10px 0px 0px 0px;padding:10px;">
        <tr>
        <td width="60%" style="vertical-align:top;">
          	<strong style="color:#000;padding:10px 0px 0px 0px;font-size: 16px; "><?php echo __l('How to use this:');?></strong><br/>
				<ol style="list-style-type:decimal;list-style-position:inside;margin:10px 0px; font-family:arial;font-size:14px;">
					<li style="margin:3px 0px;padding:0px;"><?php echo __l('Print your').' ';?><?php echo Configure::read('site.name');?></li>
					<li style="margin:3px 0px;padding:0px;"><?php echo __l('Present').' ';?> <?php echo Configure::read('site.name');?><?php echo ' '.__l('upon arrival.');?></li>
					<li style="margin:3px 0px;padding:0px;"><?php echo __l('Enjoy!');?></li>
				</ol>
				<p style=" font-family:arial;font-size:14px;">
				<?php echo __l('*Remember:');?>
				<?php echo Configure::read('site.name');?>
				<?php echo __l('customers tip on the full amount of the pre-discounted service (and tip generously). That\'s why we are the coolest customers out there.');?></p>

				</td>
				
				<td width="40%" style="vertical-align:top;">
					<strong style="color:#000; font-size:16px;padding:10px 0px 0px 0px;"><?php echo __l('Map:');?></strong>
						<div  style="margin:5px 0px 0px 0px;">
						<?php $map_zoom_level = !empty($deal['Company']['map_zoom_level']) ? $deal['Company']['map_zoom_level'] : Configure::read('GoogleMap.static_map_zoom_level');?>
						<?php 
							if(!empty($this->request->params['named']['type']) == 'print'):
								echo $this->Html->image($this->Html->formGooglemap($dealUser['Deal'],'320x250'));
							else:
								if(Configure::read('GoogleMap.embedd_map') == 'Static'):
									echo $this->Html->image($this->Html->formGooglemap($dealUser['Deal']['Company'],'320x250'));
								else:
									echo $this->Html->formGooglemap($dealUser['Deal']['Company'],'320x250');
								endif;	
								?>
								<?php if(Configure::read('GoogleMap.embedd_map') != 'Static'):?>
									<small>
										<a href="http://maps.google.com/maps?q=<?php echo $this->Html->url(array('controller' => 'companies', 'action' => 'view',$company['Company']['slug'],'ext' => 'kml'),true).'&amp;z='.$map_zoom_level.'&amp;source=embed' ?>" title="<?php echo $company['Company']['name'] ?>" target="_blank" style="color:#0000FF;text-align:left"><?php echo __l('View Larger Map');?></a>
									</small>
								<?php endif;?>
								<?
							endif;	
						?>
					</div>
				</td>
			</tr>
		
		</table>
			<div style="background:#ccc;text-align:center;padding:10px 0px; margin:10px 0px 0px 0px">
					Email <?php echo Configure::read('site.name').': '."<a href='mailto:".Configure::read('site.contact_email')."'>".Configure::read('site.contact_email')."</a>";?>
			</div>

	<div style="margin:0px 0px;padding:20px 10px; text-align:center;line-height:15px;font-size:10px;text-align:left">
		<h6 style="font-size:13px;margin:0px;padding:0px;font-family:Arial, Helvetica, sans-serif;"><?php echo __l('Legal Stuff We Have To Say:');?></h6>
			<p style="font-family:Arial, Helvetica, sans-serif; font-size:11px;color:#000;">
		
<?php echo __l('General terms applicable to all Vouchers (unless otherwise set forth below, in').' ' .Configure::read('site.name').' '.__l('Terms of Sale, or in the Fine Print): Unless prohibited by applicable law the following restrictions also apply. See below for
further details. However, even if the promotional offer stated on your').' ' . Configure::read('site.name').' '.__l('has expired, applicable law may require the merchant to allow you to redeem your Voucher
beyond its expiration date for goods/services equal to the amount you paid for it. If you have gone to the merchant and the merchant has refused to redeem the cash value of your expired Voucher, and if applicable
law entitles you to such redemption').' '.Configure::read('site.name').' '.__l('will refund the purchase price of the Voucher per its Terms of Sale. Partial Redemptions: If you redeem the Voucher for less than its face value, you only will be entitled to
a credit or cash equal to the difference between the face value and the amount you redeemed from the merchant if applicable law requires it.If you redeem this').' '. Configure::read('site.name').' '.__l('Voucher for less than the total face value, you
will not be entitled to receive any credit or cash for the difference between the face value and the amount you redeemed, (unless otherwise required by applicable law). You will only be entitled to a redemption value
equal to the amount you paid for the').' '.Configure::read('site.name').' '.__l('less the amount actually redeemed. The redemption value will be reduced by the amount of purchases made. This').' '.Configure::read('site.name').' '.__l('expiration date above,
the merchant will, in its discretion: (1) allow you to redeem this Voucher for the product or service specified on the Voucher or (2) allow you to redeem the Voucher to purchase other goods or services from the
merchant for up to the amount you paid for the Voucher. This Voucher can only can be used for making purchases of goods/services at the named merchant. Merchant is solely responsible for Voucher
redemption. Vouchers cannot be redeemed for cash or applied as payment to any account unless required by applicable law. Neither').' '.Configure::read('site.name').' '.__l(', Inc. nor the named merchant shall be responsible for').' '.Configure::read('site.name').' '.
__l('Vouchers that are lost or damaged. Use of Vouchers are subject to ').' '.Configure::read('site.name').' '.__l('Terms of Sale');?>
</p>
	</div>
	</div>
</div>
	<?php
	endforeach;
endif;
?>
<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'print'): ?>
	<script>
         window.print();
    </script>
<?php endif; ?>
