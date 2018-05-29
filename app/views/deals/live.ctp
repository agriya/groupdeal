<?php /* SVN: $Id: index.ctp 59566 2011-07-08 10:39:56Z aravindan_111act10 $ */ ?>
<?php $count = 1;
$current_latitude = $current_longitude ='';

?>
  
    <?php
if((!empty($this->request->params['named']['latitude']) && !empty($this->request->params['named']['longitude'])) || (!empty($this->request->data['Deal']['latitude']) && !empty($this->request->data['Deal']['longitude'])))
{
	if(empty($current_latitude))
	{
		$current_latitude=!empty($this->request->params['named']['latitude'])?$this->request->params['named']['latitude']:$this->request->data['Deal']['latitude'];
		$current_longitude=!empty($this->request->params['named']['longitude'])?$this->request->params['named']['longitude']:$this->request->data['Deal']['longitude'];
	}
}
?>
<span class="js-search-lat {'cur_lat':'<?php echo $current_latitude; ?>','cur_lng':'<?php echo $current_longitude; ?>'}"></span>
<div class="search-list-block js-request-responses js-responses js-response clearfix  deals-index-page ">
	<div class="grid_18 omega grid_right alpha">
	   <div class="side1-tl">
                        <div class="side1-tr">
                          <div class="side1-tm"> </div>
                        </div>
                     </div>
                     <div class="side1-cl">
                        <div class="side1-cr">
        <div class="block1-inner">


            <?php 
			 if(count($deals) >0){
			  echo $this->element('paging_counter');?>
<ol class="live-list clearfix" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
       <?php } ?>

<?php
if (!empty($deals)):
			$i = 0;
			$num=1;
			$class = null;
			foreach($deals as $deal):
				if(!empty($deal['SubDeal'])){

?>

        <li class="clearfix  <?php echo $class;?> js-map-num<?php echo $num; ?>  clearfix">
              <h2>
                        <?php
                    		$lat = $lng = $distance = '';
                    		foreach($company_deals as $company){
                    			if(isset($company['id']) && $company['id'] == $deal['Deal']['id']){
                    				$lat = $company['latitude'];
                    				$lng = $company['longitude'];
									$distance = $company['distance'];
                    			}
                    			$id = $deal['Deal']['id'];
                    		}
					
							$deal_name=$this->Html->cInt($deal['Deal']['discount_percentage'])."% Off on ".$deal['Deal']['name'];
							
                    		echo $this->Html->link($this->Html->cText($deal_name, false), array('controller'=>'deals','action' => 'view', $deal['Deal']['slug']), array('id'=>"js-map-side-$id",'class'=>"js-map-data {'lat':'$lat','lng':'$lng'}",'title'=>$this->Html->cText($deal['Deal']['name'], false),'escape' => false));
                    		?>
              </h2>
              <div class="livedeal-wrapper pr clearfix">
                <div class="livedeal-image-block pr grid_left js-lazyload"><?php echo $this->Html->link($this->Html->showImage('Deal', $deal['Deal']['Attachment'][0], array('dimension' => 'live_deal_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false))),array('controller' => 'deals', 'action' => 'view', $deal['Deal']['slug'], 'admin' => false), array('title'=>$this->Html->cText($deal['Deal']['name'],false),'escape' => false)); ?>
                  <div class="miles-away dc pa"> <span><?php echo __("Miles Away") ;?></span><?php echo $this->Html->cFloat($distance) ;?></div>
                  <div class="price-block pa clearfix">
                    <ul class="location-list grid_left">
                        <?php
                        $deal_category=substr($deal['Deal']['DealCategory']['name'],0,11);

                        ?>
                      <li><?php echo $this->Html->link($deal_category."..", array('controller' => 'deals', 'action' => 'live', 'category' => $deal['Deal']['DealCategory']['slug']),array('title' => $deal['Deal']['DealCategory']['name']));?></li>
                    </ul>
                    <div class="grid_right clearfix">
                       <?php
                            $class1='';
                        if(!empty($deal['Deal']['is_enable_payment_advance'])){
                                  $class1='payment-price';

                        }?>
                     <div class="clearfix">
              
                      <p class="price grid_left <?php echo $class1; ?> ">
	                   <?php if(!empty($deal['Deal']['is_enable_payment_advance'])):?>
						  <span class="pay-advance"> <?php echo __l('Pay in Advance');?> </span>
					    <?php endif;?>

                      <?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['discounted_price'])) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal']['discounted_price']));?>
                      </p>
                   
             
                    	<?php
					if($this->Html->isAllowed($this->Auth->user('user_type_id')) && $deal['Deal']['deal_status_id'] != ConstDealStatus::Draft && $deal['Deal']['deal_status_id'] != ConstDealStatus::PendingApproval):
						if($deal['Deal']['deal_status_id'] == ConstDealStatus::Open || $deal['Deal']['deal_status_id'] == ConstDealStatus::Tipped):
							$subdeal_coupon_expiry_detail = explode(" ",$deal['SubDeal']['coupon_expiry_date']);
							$subdeal_coupon_expiry_date_detail = explode("-", $subdeal_coupon_expiry_detail[0]);
							$subdeal_coupon_expiry_time_detail = explode(":", $subdeal_coupon_expiry_detail[1]);
                    	if(_formatDate("Y-m-d H:i:s" ,mktime($subdeal_coupon_expiry_time_detail[0], $subdeal_coupon_expiry_time_detail[1], $subdeal_coupon_expiry_time_detail[2],$subdeal_coupon_expiry_date_detail[1],$subdeal_coupon_expiry_date_detail[2],$subdeal_coupon_expiry_date_detail[0])) >= _formatDate(date('Y-m-d H:i:s'), true)):
                    	echo $this->Html->link(__l('Buy Now'), array('controller'=>'deals','action'=>'buy', $deal['Deal']['id'], $deal['SubDeal']['id']), array('title' => __l('Buy Now'),'class' =>'button dc'));
                           else:
								?>
        	<span class="no-available dc" title="<?php echo __l('Not Now Available');?>"><?php echo __l('Not Now Available');?></span>
								<?php
							endif;
						elseif($this->Html->isAllowed($this->Auth->user('user_type_id')) && $deal['Deal']['deal_status_id'] == ConstDealStatus::Upcoming):
						?>
							<span class="no-available dc" title="<?php echo __l('Upcoming');?>"><?php echo __l('Upcoming');?></span>
						<?php
						else:
     	?>
							<span class="no-available dc" title="<?php echo __l('Not Now Available');?>"><?php echo __l('Not Now Available');?></span>
						<?php
						endif;
					endif;
                ?>
                	<?php if(!empty($deal['Deal']['is_enable_payment_advance'])):?>
						<div class="clearfix pay-remaing-block pa dc sfont">
							<?php
								echo __l('Pay remaining').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['payment_remaining'])).' ('.$this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['original_price'] - $deal['Deal']['discount_amount'])).' - '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['pay_in_advance'])).') '.__l('directly to the merchant');
							?>
						</div>
					<?php endif;?>
				
					</div>

                       </div>
                  </div>
                  <div class="deal-list-block clearfix">
                    <dl class="deal-value dc grid_3 omega alpha clearfix">
                      <dt class="sfont"><?php echo __l('Value');?></dt>
                      <dd class="textb"><?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['original_price'],false)) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal']['original_price'],false));?></dd>
                    </dl>
                    <dl class="deal-discount dc grid_2  omega alpha clearfix">
                      <dt class="sfont"><?php echo __l('Discount');?></dt>
                      <dd class="textb"><?php echo (empty($deal['Deal']['is_subdeal_available'])) ? $this->Html->cFloat($deal['Deal']['discount_percentage']) . "%" : $this->Html->cFloat($deal['SubDeal']['discount_percentage']) . "%"; ?></dd>
                    </dl>
                    <dl class="deal-save dc grid_3 omega alpha  clearfix">
                      <dt class="sfont">You Save</dt>
                      <dd class="textb"><span title="Twenty Dollars" class="c cr"><?php echo (empty($deal['Deal']['is_subdeal_available'])) ?  $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['Deal']['savings'])) : $this->Html->siteCurrencyFormat($this->Html->cCurrency($deal['SubDeal']['savings'])); ?></span></dd>
                    </dl>
                  </div>
                  <p class="redeem-time"><?php echo  __l('Redeem Time:');?>
                      <span>
                            <?php
            				$is_redeem_tomorrow = 0;
            				$coupon_start_date_detail = explode(" ",$deal['Deal']['coupon_start_date']);
            				$coupon_start_time_detail = explode(":", $coupon_start_date_detail[1]);
            				$subdeal_coupon_start_date = explode(" ",$deal['SubDeal']['coupon_start_date']);
            				if($subdeal_coupon_start_date[0] == date('Y-m-d'))
            				{
            					echo "Today ";
            				} else {
            					echo "Tomorrow ";
            				}

            				$coupon_expiry_date_detail = explode(" ",$deal['Deal']['coupon_expiry_date']);
            				$coupon_expiry_time_detail = explode(":", $coupon_expiry_date_detail[1]);
            				echo _formatDate("h:i A" ,mktime($coupon_start_time_detail[0], $coupon_start_time_detail[1]))." - "._formatDate("h:i A" ,mktime($coupon_expiry_time_detail[0], $coupon_expiry_time_detail[1]));
        				?>
                    </span>
                </p>
				  <!--
                  <div class="deal-run textn clearfix">
                    <?php if($deal['Deal']['deal_repeat_type_id'] != 1) { ?>
                    <h4 class="grid_left"><?php echo __l('Deal running:');?></h4>
					<ul class="day-list grid_left">
                    <?php 
            			$days = '';
        				if(!empty($deal['Deal']['RepeatDate'])){
        					foreach($deal['Deal']['RepeatDate'] as $RepeatDate) {
        						$days .= $RepeatDate['name'].", ";

        					}
        				}
            			if(!empty($days)){
							$days_arr=explode(",",$days);
							for($i=0;$i<count($days_arr)-1;$i++)
							{	$class="";
								$day=substr(trim($days_arr[$i]),0,3);
								if(date("D")==$day)
								{
									$class="active";
								}
								?>
								<li class="grid_left <?php echo $class;?>">
								 <span><?php echo substr(trim($days_arr[$i]),0,3);?></span>
								</li>
								<?php
								
							}
            			}
                     } ?>
					 </ul>
                  </div>
				  -->
                </div>
                <div class="livedeal-desc h-over grid_left js-now-deal-more {'container': 'js-description-<?php echo $num; ?>' }">
				
                <h3><?php echo __l('Description');?></h3>
                    <?php
                    $deal_description=explode("</p>",$deal['Deal']['description']);
                    ?>

                   <?php echo $deal_description[0];?>
				   
			<div class="hide js-description-<?php echo $num; ?>">
            <?php
            for($des=1;$des<count($deal_description);$des++)
            {
                 echo $deal_description[$des]."</br>";
            }
            ?>
            
            <?php if(!empty($deal['Deal']['coupon_highlights'])) { ?>
                <h3><?php echo __l('Highlights');?></h3>
                <?php echo $this->Html->cHtml($deal['Deal']['coupon_highlights']);?>
            <?php } ?>
                        <h3><?php echo __l('The Fine Print');?></h3>
            <?php
                  if(!empty($deal['Deal']['coupon_expiry_date']) && empty($deal['Deal']['is_subdeal_available'])){
                     echo __l('Expires ');
                     echo  $this->Html->cDateTime($deal['Deal']['coupon_expiry_date']);
                  }
            ?>
            <?php echo $this->Html->link(__l('Read the Deal FAQ'), array('controller' => 'pages', 'action' => 'view','faq', 'admin' => false), array('target'=>'_blank', 'title' => __l('Read the deal FAQ')));?> <?php echo __l(' for the basics.'); ?>
                <h3><?php echo __l('Use Coupon Between');?></h3>
				<?php
                $coupon_start_date_detail = explode(" ",$deal['Deal']['coupon_start_date']);
                $coupon_start_time_detail = explode(":", $coupon_start_date_detail[1]);

                $coupon_expiry_date_detail = explode(" ",$deal['Deal']['coupon_expiry_date']);
                $coupon_expiry_time_detail = explode(":", $coupon_expiry_date_detail[1]);
                ?>
                <div>
				<?php echo _formatDate("h:i A" ,mktime($coupon_start_time_detail[0], $coupon_start_time_detail[1]))." - "._formatDate("h:i A" ,mktime($coupon_expiry_time_detail[0], $coupon_expiry_time_detail[1])); ?>
				<?php echo __l('or we\'ll automatically refund you'); ?>
                </div>
                <div>
				<?php echo __l('If you didn\'t used any coupon we\'ll automatically refund you'); ?>
                </div>
                <div>
				<?php echo __l('If you used only one coupon for this deal then other coupon amount will be added to your wallet.'); ?>
                </div>
                </div>
				</div>
              </div>

            </li>
            <?php
				$count++;
				$num++;
			}
			endforeach;
		?>
           </ol>
           <?php
 endif;
 if (empty($deals)) {
?>
<ol><li class="notice"><?php echo __l('No deals available'); ?></li></ol>
<?php
}

if (!empty($deals)) {

	if(count($deals)>0) {
		//$this->request->params['named'] = array();
	?>
<div class="">
<?php
    echo $this->element('paging_links'); ?>
	</div>
<?php
	}
}
?>
            </div>
   				</div>
        				</div>
                        <div class="side1-bl">
                            <div class="side1-br">
                              <div class="side1-bm"> </div>
                            </div>
                      </div>
	</div>
    <div class="grid_6 omega alpha">
        <div class="clearfix">
            <div class="tweet-tl">
                <div class="tweet-tr">
                    <div class="tweet-tm">
                  	  <h3 class="location-title"><?php echo __l('Location'); ?></h3>
                    </div>
                </div>
            </div>
            <div class="side1-cl">
                <div class="side1-cr">
                    <div class="block1-inner bot-mspace blue-bg-inner clearfix">
                    <?php if(empty($this->request->params['isAjax'])) { ?>
                    <?php echo $this->element('search', array('config' => 'sec','type'=>'search')); ?>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <div class="side1-bl">
                <div class="side1-br">
               		 <div class="side1-bm"> </div>
                </div>
            </div>
        </div>
    </div>

</div>

