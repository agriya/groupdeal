<?php /* SVN: $Id: $ */ ?>
<div class="BusinessSuggestions view">
<h2><?php echo __l('Business Suggestion');?></h2>
        <div>
           <?php
                 echo $this->Html->link($businessSuggestion['User']['username'], array('controller' => 'users', 'action' => 'view', $businessSuggestion['User']['username']),array('title' =>$businessSuggestion['User']['username']));
            ?>
               <dl>
                  <dt> <?php echo $businessSuggestion['BusinessSuggestion']['suggestion'];  ?> </dt>
               </dl>
        </div>
</div>