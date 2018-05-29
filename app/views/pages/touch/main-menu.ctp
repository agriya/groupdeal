<div class="home-header">
     <h1><?php echo Configure::read('site.name'); ?></h1>

</div>
<ul data-role="listview">
<li>
<?php echo $this->Html->link(__l('Today\'s Deal'), array('controller' => 'deals', 'action' => 'index'), array('title' => __l('refresh'), 'class' => 'fade')); ?>
</li>
<li>
<?php echo $this->Html->link(__l('Cities'), array('controller' => 'cities', 'action' => 'index'), array('title' => __l('Change Cities'), 'class' => 'fade'));?>
</li>
<?php if (!$this->Auth->sessionValid()){ ?>
<li>
<?php echo $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Login'),'class'=>'login-link'));?>
</li>
<li>
<?php echo $this->Html->link(__l('Register'), array('controller' => 'users', 'action' => 'register', 'admin' => false), array('title' => __l('Register'),'class'=>'login-link'));?>
</li>
<?php } else { ?>
<li>
<?php  echo $this->Html->link(sprintf(__l('My %s'), Configure::read('site.name')), array('controller' => 'deal_users', 'action' => 'index'), array('title' => 'My Purchases', 'rel' => 'address:/' . __l('My_Purchases')));?>
</li>
<li>
<?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('class' => 'logout-link', 'title' => __l('Logout'))); ?>
</li>
</ul>
<?php } ?>