<?php /* SVN: $Id: index.ctp 79490 2012-09-25 14:00:19Z rajeshkhanna_146ac10 $ */ ?>
<div class="userComments index js-response">
<h2>
<?php
if (!empty($username)):
    echo __l('Comments on ').$username;
else:
	echo __l('Comments');
endif;
?>
</h2>
<?php echo $this->element('paging_counter'); ?>
<ol class="commment-list clearfix js-comment-responses" start="<?php echo $this->Paginator->counter(array('format' => '%start%')); ?>">
<?php
if (!empty($userComments)):
    foreach($userComments as $userComment):
?>
    <li class="list-row clearfix" id="comment-<?php echo $userComment['UserComment']['id']; ?>" >
        <div class="comment-tl">
            <div class="comment-tr">
                <div class="comment-tc">
                </div>
            </div>
        </div>
        <div class="comment-cl">
            <div class="comment-cr">
                <div class="comment-cc clearfix">
                    <div class="avatar dc">
            			<?php echo $this->Html->getUserAvatarLink($userComment['PostedUser'], 'medium_thumb');?>
            			<span class="comment-arrow"></span>
                    </div>
            		<div class="data sfont">
                        <div class="clearfix comment-title-info">
                        <h4 class="textn">
                            <?php echo $this->Html->getUserLink($userComment['PostedUser']);?>
                            </h4>
                            <div class="comment-right-info">
                            <div class="comment-right-inner">
                            <?php 
								echo $this->Time->timeAgoInWords(strftime('%Y-%m-%d %H:%M:%S', strtotime($userComment['UserComment']['created'] . ' GMT')));?>
                            </div>
                            </div>
                       </div>
            		  <?php echo $this->Html->cText(nl2br($userComment['UserComment']['comment']));?>
            		  <?php if ($user['User']['id'] == $this->Auth->user('id') or $userComment['PostedUser']['id'] == $this->Auth->user('id')) { ?>
                    <div class="actions dr">
                    	<?php echo $this->Html->link(__l('Delete'), array('controller' => 'user_comments', 'action' => 'delete', $userComment['UserComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
            		</div>
            		<?php } ?>
        		  </div>
    		  </div>
    		</div>
		</div>
		 <div class="comment-bl">
            <div class="comment-br">
                <div class="comment-bc">
                </div>
            </div>
        </div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p  class="notice"><?php echo __l('No comments available'); ?></p>
	</li>
<?php
endif;
?>
</ol>
<div class="js-pagination">
<?php
if (!empty($userComments)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>