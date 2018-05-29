<?php /* SVN: $Id: $ */ ?>
<div class="apnsMessages index js-response js-responses">
		<div>
			<ul class="clearfix filter-list">
					<li class="filter-giftcard"><?php echo $this->Html->link(__l('Queued').': '.$this->Html->cInt($queued), array('action' => 'index', 'main_filter_id' => ConstMoreAction::Queued),array('title' => __l('Queued'), 'escape' => false)); ?></li>
					<li class="filter-active"><?php echo $this->Html->link(__l('Delivered').': '.$this->Html->cInt($delivered),array('action'=>'index', 'main_filter_id' => ConstMoreAction::Delivered),array('title' => __l('Delivered'), 'escape' => false)); ?></li>
					<li class="filter-foursquare"><?php echo $this->Html->link(__l('Failed').': '.$this->Html->cInt($failed),array('action'=>'index', 'main_filter_id' => ConstMoreAction::Failed),array('title' => __l('Failed'), 'escape' => false)); ?></li>
					<li class="filter-all"><?php echo $this->Html->link(__l('All').': '.$this->Html->cInt($all), array('action' => 'index', 'main_filter_id' => 'all'),array('title' => __l('All'), 'escape' => false));?></li>
                </ul>
		</div>
    <div class="js-search-responses">
  
    <?php if(empty($this->request->params['named']['stat'])): ?>
        <h2><?php echo $pageTitle; ?></h2>
    <?php endif; ?>
    <div class="clearfix">
         <div class="page-count-block  grid_left clearfix">
            <?php echo $this->element('paging_counter');?>
        </div>
        <div class="grid_left clearfix">
        <?php
        	echo $this->Form->create('ApnsMessage' , array('action' => 'index', 'type' => 'post', 'class' => 'normal search-form js-ajax-form clearfix {"container" : "js-search-responses"}')); //
        	echo $this->Form->input('ApnsMessage.q', array('label' => __l('Keyword')));
    		echo $this->Form->input('main_filter_id', array('type' => 'hidden', 'value' => !empty($this->request->params['named']['main_filter_id'])? $this->request->params['named']['main_filter_id']:''));
        	echo $this->Form->submit(__l('Search'));
        	echo $this->Form->end();
        ?>
        </div>
      </div>
        <table class="list">
            <tr>
                        <th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
                        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'),'User.username');?></div></th>
                        <th><div class="js-pagination"><?php echo $this->Paginator->sort('message');?></div></th>
                        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Delivered'), 'delivery');?></div></th>
                        <th><div class="js-pagination"><?php echo $this->Paginator->sort('status');?></div></th>
                    </tr>
            <?php
        if (!empty($apnsMessages)):
            $i = 0;
            foreach ($apnsMessages as $apnsMessage):
                $class = null;
                if ($i++ % 2 == 0) {
                    $class = ' class="altrow"';
                }
            ?>
            <tr<?php echo $class;?>>
                <td class="dc">
                    <?php echo $this->Html->cDateTimeHighlight($apnsMessage['ApnsMessage']['created']); ?>
                </td>
                <td class="dl">
                    <?php echo $this->Html->link($this->Html->cText($apnsMessage['User']['username']), array('controller' => 'users', 'action' => 'view', $apnsMessage['User']['username'], 'admin' => false), array('escape' => false )); ?>
                </td>
                <td class="dl">
                    <?php $message_text = json_decode($apnsMessage['ApnsMessage']['message'], 1);
                     echo $this->Html->cText($message_text['aps']['alert']); ?>
                </td>
                <td class="dc">
                    <?php 
                    if($apnsMessage['ApnsMessage']['status'] != 'queued') {
                        echo $this->Html->cDateTime($apnsMessage['ApnsMessage']['delivery']); 
                    }
                    ?>
                </td>
                <td class="dc">
                    <?php echo $this->Html->cText($apnsMessage['ApnsMessage']['status']); ?>
                </td>
            </tr>
        <?php
            endforeach;
        else:
        ?>
            <tr>
                <td colspan="9" class="notice"><?php echo __l('No Push Messages available');?></td>
            </tr>
        <?php
        endif;
        ?>
        </table>

<?php
if (!empty($apnsMessages)) {
?>
 <div class="js-pagination">
<?php

    echo $this->element('paging_links');
?>
 </div>
<?php
}
?>
</div>
</div>