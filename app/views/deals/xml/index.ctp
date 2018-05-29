<?php /* SVN: $Id: index.ctp 4098 2010-05-06 08:04:25Z senthilkumar_017ac09 $ */ ?>
<response code="0" message="OK">
  <deals>
    <?php 
		foreach($deals as $deal):
                $image_options = array(
                    'dimension' => 'small_thumb',
                    'class' => '',
                    'alt' => $deal['Deal']['name'],
                    'title' => $deal['Deal']['name'],
                    'type' => 'jpg'
                );
                $small_image_url = $this->Html->getImageUrl('Deal', $deal['Attachment'], $image_options);
                $image_options = array(
                    'dimension' => 'small_big_thumb',
                    'class' => '',
                    'alt' => $deal['Deal']['name'],
                    'title' => $deal['Deal']['name'],
                    'type' => 'jpg'
                );
                $medium_image_url = $this->Html->getImageUrl('Deal', $deal['Attachment'], $image_options);
                $image_options = array(
                    'dimension' => 'medium_big_thumb',
                    'class' => '',
                    'alt' => $deal['Deal']['name'],
                    'title' => $deal['Deal']['name'],
                    'type' => 'jpg'
                );
                $large_image_url = $this->Html->getImageUrl('Deal', $deal['Attachment'], $image_options);
		?>
            <deal>
                  <id><?php echo $deal['Deal']['id'] ?></id>
                  <deal_url><?php echo Router::url(array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug']),true); ?></deal_url>
                  <title><?php echo $deal['Deal']['name'] ?></title>
                  <small_image_url><?php echo $small_image_url; ?></small_image_url>
                  <medium_image_url><?php echo $medium_image_url; ?></medium_image_url>
                  <large_image_url><?php echo $large_image_url; ?></large_image_url>
                  <division_id><?php echo $deal['City']['id'] ?></division_id>
                  <division_name><?php echo $deal['City']['name'] ?></division_name>
                  <division_lat><?php echo $deal['City']['latitude'] ?></division_lat>
                  <division_lng><?php echo $deal['City']['longitude'] ?></division_lng>
                  <vendor_id><?php echo $deal['Company']['id'] ?></vendor_id>
                  <vendor_name><?php echo $deal['Company']['name'] ?></vendor_name>
                  <vendor_website_url><?php echo $deal['Company']['url'] ?></vendor_website_url>
                  <status><?php echo $deal['DealStatus']['name'] ?></status>
                  <start_date><?php echo date(Configure::read('site.datetime.format'), strtotime($deal['Deal']['start_date'])) ?></start_date>
                  <end_date><?php echo date(Configure::read('site.datetime.format'), strtotime($deal['Deal']['end_date'])) ?></end_date>
                  <tipped><?php echo ($deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped) ? __l('true') : __l('false'); ?></tipped>
                  <tipping_point><?php echo $deal['Deal']['min_limit'] ?></tipping_point>
                  <tipped_date><?php echo ($deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped) ? date(Configure::read('site.datetime.format'), $deal['Deal']['deal_tipped_time']) : __l('Not Yet Tipped') ?></tipped_date>
                  <quantity_sold><?php echo $deal['Deal']['deal_user_count'] ?></quantity_sold>
                  <price><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price'],false));?></price>
                  <value><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['original_price'],false));?></value>
                  <discount_amount><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['savings'],false)); ?></discount_amount>
                  <discount_percent><?php echo $this->Html->cInt($deal['Deal']['discount_percentage'],false) . "%"; ?></discount_percent>
                  <conditions>
                    <limited_quantity><?php echo (!empty($deal['Deal']['max_limit'])) ? __l('true') : __l('false'); ?></limited_quantity>
                    <initial_quantity><?php echo $deal['Deal']['min_limit'] ?></initial_quantity>
                    <quantity_remaining><?php echo (empty($deal['Deal']['max_limit'])) ? __l('No Limit') : ($deal['Deal']['max_limit'] - $deal['Deal']['deal_user_count']); ?></quantity_remaining>
                    <minimum_purchase><?php echo $deal['Deal']['buy_min_quantity_per_user'] ?></minimum_purchase>
                    <maximum_purchase><?php echo $deal['Deal']['buy_max_quantity_per_user'] ?></maximum_purchase>
                    <expiration_date><?php echo date(Configure::read('site.datetime.format'), strtotime($deal['Deal']['coupon_expiry_date'])) ?></expiration_date>
                    <details>
                      <detail><?php echo $this->Html->cHtml($deal['Deal']['coupon_condition'],false);?>   </detail>
                    </details>
                  </conditions>
                </deal>
       <?php
		endforeach; 
	?>    
  </deals>
</response>

