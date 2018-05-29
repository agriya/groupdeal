<?php if ($this->request->params['action'] != 'display'):?>
<div data-role="header">
<?php  echo $this->Html->link(__l('Menu'), array('controller' => 'pages', 'action' => 'display', 'main-menu'), array('title' => __l('Home'), 'data-icon' => 'home', 'data-iconpos' => 'notext', 'data-direction' => 'reverse', 'class' => 'ui-btn-right jqm-home'));		
?>
<h1> 
    <?php 
             if($this->request->params['controller'] == 'cities'){
                echo __l('Cities');
             } elseif ($this->request->params['controller'] == 'subscriptions' && $this->request->params['action'] == 'add'){
                echo __l('Subscription');
             }	elseif ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'login'){
                echo __l('Login');
             } elseif ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'register'){
                echo __l('Sign Up');
             } elseif ($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'index'){
                echo __l('Today\'s Deal');             
			 } elseif ($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'buy'){
                echo __l('Your Purchase');
             } 
			 elseif ($this->request->params['controller'] == 'deal_users' && $this->request->params['action'] == 'index' && isset($this->request->params['named']['type'])){
				echo sprintf(__l('My %s - ').$this->request->params['named']['type'], Configure::read('site.name'));
			 } elseif ($this->request->params['controller'] == 'deal_users' && $this->request->params['action'] == 'view' && isset($this->request->params['named']['coupon_id'])){
                echo Configure::read('site.name').' #'.$dealUser['DealUserCoupon'][0]['coupon_code'];
             } elseif ($this->request->params['controller'] == 'deal_users' && $this->request->params['action'] == 'view' && isset($this->request->params['named']['filter_id'])){
				echo sprintf(__l('My %s - ').$this->request->params['named']['filter_id'], Configure::read('site.name'));
			 } elseif ($this->request->params['controller'] == 'deal_users' && $this->request->params['action'] == 'index'){
				echo sprintf(__l('My %s'), Configure::read('site.name'));
			 }
			  elseif ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'forgot_password'){
               echo __l('Forgot your password?');
             } else{
			 ?>
                <span><?php echo Configure::read('site.name'); ?></span>
             <?php    
             }
    ?>
</h1>              
        
		<?php
             if ($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'index'){
                echo $this->Html->link(__l('Refresh'), array('controller' => 'deals', 'action' => 'index'), array('title' => __l('refresh'), 'class' => 'ui-btn-left', 'data-icon' => 'refresh'));
             } elseif ($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'view'){
                echo $this->Html->link(__l('Back'), array('controller' => 'deals', 'action' => 'index'), array('title' => __l('deals'), 'class' => 'ui-btn-left', 'data-icon' => 'arrow-l'));
             }  elseif ($this->request->params['controller'] == 'deal_users' && $this->request->params['action'] == 'view' && isset($this->request->params['named']['coupon_id']) && isset($this->request->params['named']['filter_id'])){
				echo $this->Html->link(__l('Back'), array('controller' => 'deal_users', 'action' => 'view', 'filter_id' => $this->request->params['named']['filter_id'], $this->request->params['pass'][0]), array('title' => __l('back'), 'class' => 'ui-btn-left', 'data-icon' => 'arrow-l'));
			 } elseif ($this->request->params['controller'] == 'deal_users' && $this->request->params['action'] == 'view' && isset($this->request->params['named']['filter_id'])){
				 echo $this->Html->link(__l('Back'), array('controller' => 'deal_users', 'action' => 'index', 'type' => $this->request->params['named']['filter_id']), array('title' => __l('back'), 'class' => 'ui-btn-left', 'data-icon' => 'arrow-l'));
			 } elseif ($this->request->params['controller'] == 'deal_users' && $this->request->params['action'] == 'index' && isset($this->request->params['named']['type'])){
				echo $this->Html->link(__l('Back'), array('controller' => 'deal_users', 'action' => 'index'), array('title' => __l('back'), 'class' => 'ui-btn-left', 'data-icon' => 'arrow-l'));
			 } elseif ($this->request->params['controller'] != 'cities' && $this->request->params['controller'] != 'pages') {
                echo $this->Html->link(__l('Cities'), array('controller' => 'cities', 'action' => 'index'), array('title' => __l('Change Cities'), 'class' => ' ui-btn-left', 'data-icon' => 'arrow-l'));
             }	
        ?>
        
</div>
<?php endif; ?>