<?php /* SVN: $Id: unsubscribe.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<?php echo $this->Form->create('Subscription', array('action'=>'unsubscribe_mailchimp', 'class' => 'normal'));?>
	<fieldset>
    	<h2><?php echo __l('Manage Your Subscription'); ?></h2>
        <div class="wallet-amount-block textb">
			<?php 
                echo __l('Are sure you want to unsubscribe?');
                echo $this->Form->input('email',array('type' => 'hidden'));
                echo $this->Form->input('city',array('type' => 'hidden'));
            ?>
        </div>
	<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Unsubscribe'), array('title' => __l('Unsubscribe'))); ?>
        <div class="cancel-block">
            <?php echo $this->Html->link(__l('Oops, i changed my mind'), array('controller'=>'deals','action' => 'index'), array('class' => 'cancel-button', 'title' => __l('Oops, i changed my mind'))); ?>
        </div>
    </div>
    <?php echo $this->Form->end();?>
	</fieldset>