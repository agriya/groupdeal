
<div class="refer-page clearfix">
<h2><?php echo sprintf(__l('Refer Friends and Get %s %s Bucks! '),$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'))),Configure::read('site.name'));?></h2>
<div class="refer">
<p class="blue get"><?php echo sprintf(__l('Get %s in %s Bucks when someone you invite gets their first %s. There is no limit on how much you can earn!'),$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'), false)),Configure::read('site.name'),Configure::read('site.name')); ?></p>
	<?php if ($this->Auth->sessionValid()){ ?>
    <?php if(Configure::read('referral.referral_enable')):?>
	     <p class="referal-link no-pad"><?php echo __l('Share your unique referral link'); ?></p>
		<input type="text" class="refer-box" readonly="readonly" value="<?php echo Router::url(array('controller' => 'users', 'action' => 'refer', 'r' =>$this->Auth->user('username')), true);?>" onclick="this.select()"/>
    <?php endif;?>
    <?php if(Configure::read('referral.referral_enable')):?>
	<ul class="refer-share clearfix top-mspace">
		<li class="grid_left quick"><?php echo $this->Html->link(__l('Mail it'), 'mailto:?body='.sprintf(__l('Check out %ss daily deal for coolest stuff in your city. '),Configure::read('site.name')).'-'.Router::url(array('controller' => 'users', 'action' => 'refer', 'r' =>$this->Auth->user('username')), true).'&subject='.__l('I think you should get ').Configure::read('site.name'), array('class' => 'quick', 'target' => 'blank'));?></li>
		<li class="grid_left face"><?php echo $this->Html->link(__l('Share it on Facebook'), 'http://www.facebook.com/share.php?u='.Router::url(array('controller' => 'users', 'action' => 'refer', 'r' =>$this->Auth->user('username')), true), array('class' => 'face','target' => 'blank'));?></li>
		<li class="grid_left twitt"><?php echo $this->Html->link(__l('Share it on Twitter'),'http://twitter.com/share?url='.Router::url(array('controller' => 'users', 'action' => 'refer', 'r' =>$this->Auth->user('username')), true),array('class'=>'twitter-share-button','target'=>'blank')); ?></li>
		<li class="grid_left plus"><a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=<?php echo $this->Auth->user('username');?>"><img src="http://s7.addthis.com/static/btn/sm-share-en.gif" width="83" height="16" alt="Bookmark and Share" style="border:0"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=<?php echo $this->Auth->user('username');?>"></script></li>
	</ul>
    <?php endif;?>
	<?php }else { ?>
	<p class="sign-in-block"> <?php echo $this->Html->link(__l('Sign In'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Sign In'))); ?> <span><?php echo __l(' or ') ;?></span> <?php echo $this->Html->link(__l('Setup Your Account') , array('controller' => 'users',	'action' => 'register'),array('title' => __l('Signup')));?> <span> <?php echo __l(' to get your personal referral link.') ;?></span> </p>
	<?php } ?>
</div>
<div class="clearfix refer-bottom">
<div class="grid_7">
<h3>Referral FAQ What is this?</h3>
<p><?php echo sprintf(__l('We are giving %s in %s Bucks for every friend you refer when they make their first purchase. It is our way of saying "thanks" for spreading the word and increasing our collective buying power! %s Bucks can be used toward any %s purchase, and they never expire.'),$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'), false)),Configure::read('site.name'),Configure::read('site.name'),Configure::read('site.name')); ?></p>
</div>
<div class="grid_7">
<h3>How do I participate?</h3>
<p><?php echo __l('Share your personalized referral link using the tools to your left. When someone clicks that link, we will know you sent them.'); ?></p>
</div>
<div class="grid_7">
<h3>What are the rules?</h3>
<p><?php echo sprintf(__l('If someone joins %s within %s hours after clicking your link, we will notify you within %s hours of their first purchase and automatically add %s %s Bucks to your account. You can refer as many people as you like. Check your balance by clicking My Stuff > My Transactions.'),Configure::read('site.name'),Configure::read('user.referral_cookie_expire_time'),Configure::read('user.referral_deal_buy_time'),$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('user.referral_amount'), false)),Configure::read('site.name')); ?></p>
</div>
</div>
</div>

