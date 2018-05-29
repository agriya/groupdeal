<div class="admin-side1-tl ">
    <div class="admin-side1-tr">
        <div class="admin-side1-tc">
            <h2><?php echo __l('Recently Registered Users'); ?></h2>
        </div>
    </div>
</div>
<div class="admin-center-block dashboard-center-block">
<div>
<?php
	if (!empty($recentUsers)):
		$users = '';
		foreach ($recentUsers as $user):
			$users .= sprintf('%s, ',$this->Html->link($this->Html->cText($user['User']['username'], false), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false)));
		endforeach;
		echo substr($users, 0, -2);
	else:
?>
	<p class="notice"><?php echo __l('Recently no users registered');?></p>
<?php
	endif;
?>
</div>
</div>