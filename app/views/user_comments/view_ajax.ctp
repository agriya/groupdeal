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
                    <div class="avatar">
            			<?php echo $this->Html->getUserAvatarLink($userComment['PostedUser'], 'medium_thumb');?>
            			<span class="comment-arrow"></span>
                    </div>
            		<div class="data">
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
						<div class="actions">
							<?php echo $this->Html->link(__l('Delete'), array('controller' => 'user_comments', 'action' => 'delete', $userComment['UserComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
						</div>
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