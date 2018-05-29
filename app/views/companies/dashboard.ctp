<div class="users stats clearfix">

    <div class="">
    
    	    <div class="side1-tl">
                <div class="side1-tr">
                  <div class="side1-tm"> </div>
                </div>
             </div>
             <div class="side1-cl">
                <div class="side1-cr">
                    <div class="block1-inner js-admin-stats-block">
                    <div class="admin-stats-block">
						<?php echo $this->element('chart-chart_company_transactions', array('cache' => array('config' => 'site_element_cache_15_min', 'key' => $this->Auth->user('id')))); ?>
					</div>
					 <div class="admin-stats-block">
						<?php echo $this->element('chart-chart_deals', array('cache' => array('config' => 'site_element_cache_15_min', 'key' => $this->Auth->user('id')))); ?>
					</div>
					 <div class="admin-stats-block">
						<?php echo $this->element('chart-chart_company_users', array('cache' => array('config' => 'site_element_cache_15_min', 'key' => $this->Auth->user('id')))); ?>
                       </div>
                	</div>
            	</div>
        	</div>
            <div class="side1-bl">
                <div class="side1-br">
                  <div class="side1-bm"> </div>
                </div>
          </div>
    </div>
<?php
if($this->Auth->sessionValid()  and  $this->Auth->user('user_type_id') == ConstUserTypes::Company):
	$company = $this->Html->getCompany($this->Auth->user('id'));
endif;
?>

</div>
