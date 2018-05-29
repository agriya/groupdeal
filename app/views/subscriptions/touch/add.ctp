<?php /* SVN: $Id: add.ctp 54697 2011-05-26 09:46:53Z aravindan_111act10 $ */ ?>
	<p>
	<?php echo sprintf(__l('Every day, %s e-mails you one exclusive offer to do, see, taste, or experience something amazing in').' '.$city_name.' '.__l('at an unbeatable price.'),Configure::read('site.name')); ?>
	</p>
	<p>
	<?php echo __l('Sign up now for free, and prepare to discover') . ' ' . $city_name . ' ' . __l('at 40% to 90% off! '); ?>
	</p>
<?php echo $this->Form->create('Subscription', array('id' => 'homeSubscriptionFrom'));?>
		<div data-role="fieldcontain">	
		<?php echo $this->Form->input('email',array('id' => 'homeEmail', 'div'=>false)); ?>
		</div>
		<div data-role="fieldcontain">	
		<?php echo $this->Form->input('city_id',array('id' => 'homeCityId', 'div'=>false, 'options' => $cities)); ?>
		</div>
<?php echo $this->Form->submit(__l('Subscribe'), array('data-theme'=>'b', 'div'=>false));?>
<?php echo $this->Form->end(); ?>
	<p>
	<?php echo __l('Our daily offers are for:'); ?>
	<?php echo __l('Restaurants, Spas, Concerts, Bars, Sporting Events, Classes, Salons,Adventures and so much more... '); ?>
	</p>
