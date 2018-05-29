<div class="js-mystuff-tabs">
    <ul class="clearfix">
        <?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company):?>    
        	<?php $user = $this->Html->getCompany($this->Auth->user('id')); ?>
            <li><em></em><?php echo $this->Html->link(__l('My Account'), array('controller' => 'companies', 'action' => 'edit', $user['Company']['id']), array('title' => 'My Account', 'rel' => 'address:/' . __l('My_Account'))); ?></li>
        <?php else: ?>
            <li><em></em><?php echo $this->Html->link(__l('My Account'), array('controller' => 'user_profiles', 'action' => 'my_account', $this->Auth->user('id')), array('title' => 'My Account', 'rel' => 'address:/' . __l('My_Account'))); ?></li>
        <?php endif; ?>
        <?php if($this->Auth->sessionValid() && $this->Html->isAllowed($this->Auth->user('user_type_id'))):?>
              <li><em></em><?php  echo $this->Html->link(sprintf(__l('My %s'), Configure::read('site.name')), array('controller' => 'deal_users', 'action' => 'index'), array('title' => 'My Purchases', 'rel' => 'address:/' . __l('My_Purchases')));?></li>
              <li><em></em><?php echo $this->Html->link(__l('My Gift Cards'), array('controller' => 'gift_users', 'action' => 'index', 'admin' => false), array('title' => 'My Gift Cards', 'rel' => 'address:/' . __l('My_Gift_Cards')));?></li>
              <li><em></em><?php echo $this->Html->link(__l('My Transactions'), array('controller' => 'transactions', 'action' => 'index', 'admin' => false), array('title' => 'My Transactions', 'rel' => 'address:/' . __l('My_Transactions')));?></li>
              <?php if(Configure::read('friend.is_enabled')): ?>
              <li><em></em><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends', 'rel' => 'address:/' . __l('My_Friends')));?></li>
               <li><em></em><?php echo $this->Html->link(__l('Import Friends'), array('controller' => 'user_friends', 'action' => 'import', 'admin' => false), array('title' => 'Import Friends', 'rel' => 'address:/' . __l('Import_Friends'))); ?></li>
              <?php endif; ?>
        <?php endif; ?>
    </ul>
</div>