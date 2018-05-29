<div class="static-page-block clearfix">
<?php 
	if(Configure::read('referral.refund_type') == ConstReferralRefundType::RefundDealAmount):
		$refund_type = __l('Get a Free Deal!!!');
	else:
		$refund_type = __l('Get').' '.$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('referral.refund_amount'), false)).' '.__l('');
	endif;
	$msg = __l('Refer').' '.Configure::read('referral.no_of_refer_to_get_a_refund').' '.__l('Friends').', '.$refund_type;
?>
<?php if(Configure::read('referral.referral_enable')):?>
<h2><?php echo $msg;?></h2>
<?php endif;?>
	<?php if ($this->Auth->sessionValid()){ ?>
		<p><?php echo __l('Share your unique referral link that you\'ll get after your purchase a deal.'); ?>
			<?php if(Configure::read('referral.referral_enable')):?>
				<input type="text" class="refer-box" readonly="readonly" value="<?php echo Router::url(array('controller' => 'users', 'action' => 'refer', 'r' =>$this->Auth->user('username')), true);?>" onclick="this.select()"/>
			<?php endif;?>
		</p>
		<?php if(Configure::read('referral.referral_enable')):?>
			<div class="clearfix">
				<ul class="share-list left-space">
					<li class="quick"><?php echo $this->Html->link(__l('Mail it'), 'mailto:?body='.sprintf(__l('Check out %ss daily deal for coolest stuff in your city. '),Configure::read('site.name')).'-'.Router::url(array('controller' => 'users', 'action' => 'refer', 'r' =>$this->Auth->user('username')), true).'&subject='.__l('I think you should get ').Configure::read('site.name'), array('class' => 'quick', 'target' => 'blank'));?></li>
					<li class="face"><?php echo $this->Html->link(__l('Share it on Facebook'), 'http://www.facebook.com/share.php?u='.Router::url(array('controller' => 'users', 'action' => 'refer', 'r' =>$this->Auth->user('username')), true), array('class' => 'face','target' => 'blank'));?></li>
					<li><a href="http://twitter.com/share?url=<?php echo Router::url(array('controller' => 'users', 'action' => 'refer', 'r' =>$this->Auth->user('username')), true);?>&amp;lang=en" data-count="none" class="twitter-share-button"><?php echo __l('Tweet it');?></a></li>
				</ul>
			</div>
		<?php endif;?>
	<?php }else { ?>
	<p class="sign-in-block"> <?php echo $this->Html->link(__l('Sign In'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Sign In'))); ?> <span><?php echo __l(' or ') ;?></span> <?php echo $this->Html->link(__l('Setup Your Account') , array('controller' => 'users',	'action' => 'register'),array('title' => __l('Signup')));?> <span> <?php echo __l(' to get your personal referral link.') ;?></span> </p>
	<?php } ?>
<h3><?php echo __l('Referral FAQ');?> <?php echo __l('What is this?'); ?></h3>
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam posuere imperdiet nisl vel feugiat. Nam vitae leo sit amet nisl egestas tristique porttitor et dolor. Integer varius tempus lectus sed adipiscing. Proin leo diam, venenatis ac tincidunt iaculis, elementum non nisl. Pellentesque nec dolor tellus. Phasellus vehicula tempor lectus eu placerat. Aliquam erat volutpat. Vestibulum ligula dolor, tincidunt eu imperdiet ut, cursus at ipsum. Praesent quis ante nisi. Pellentesque in odio arcu, facilisis adipiscing lorem. Vivamus iaculis fermentum ipsum ut convallis. Suspendisse condimentum urna tincidunt massa adipiscing imperdiet. Praesent accumsan turpis eu felis elementum malesuada. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi elit tortor, fermentum tincidunt auctor tristique, sollicitudin in sapien. Duis rutrum tellus ut orci iaculis luctus. Ut aliquet leo eu enim rutrum non malesuada mauris vehicula. Etiam ut tortor a velit accumsan posuere sit amet vel dolor. Praesent bibendum egestas odio a fringilla. Praesent cursus, velit nec tincidunt semper, leo nisi ultricies lacus, eget malesuada orci turpis sed urna.
</p>
</div>
