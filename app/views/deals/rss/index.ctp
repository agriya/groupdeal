<?php /* SVN: $Id: index.ctp 12757 2010-07-09 15:01:40Z jayashree_028ac09 $ */ ?>
	<?php if(!empty($deals)): ?>
            <?php
				foreach($deals as $deal):
                  $deal_image = '';
					if(!empty($deal['Attachment'])):
						$deal_image = $this->Html->showImage('Deal', $deal['Attachment'][0], array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false)));
					endif;
					$deal_image = (!empty($deal_image)) ? '<p>'.$deal_image.'</p>':'';

					echo $this->Rss->item(array() , array(
                            'title' => $deal['Deal']['name'],
                            'link' => array(
                                'controller' => 'deals',
                                'action' => 'view',
                                $deal['Deal']['slug']
                            ) ,
                          'description' => $deal_image.'<p>'.$deal['Deal']['description'].'</p>'
                        ));
            	endforeach;
			?>
    <?php endif; ?>