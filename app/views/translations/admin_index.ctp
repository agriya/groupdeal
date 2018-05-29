<?php /* SVN: $Id: admin_index.ctp 77127 2012-04-06 09:18:46Z mohanraj_109at09 $ */ ?>
<div class="translations index">
<div class="add-block">
	<?php echo $this->Html->link(__l('Make New Translation'), array('controller' => 'translations', 'action' => 'add'), array('class' => 'add', 'title'=>__l('Make New Translation'))); ?>
	<?php echo $this->Html->link(__l('Add New Text'), array('controller' => 'translations', 'action' => 'add_text'), array('class' => 'add', 'title'=>__l('Add New Text'))); ?>
</div>
<?php
if (empty($translations)): ?>
<div class = "page-info">
	<?php echo __l('Sorry, in order to translate, default English strings should be extracted and available. Please contact support.');?>
</div>
<?php endif; ?>
<table class="list">
    <tr>
		<th><?php echo __l('Language');?></th>
		<th><?php echo __l('Verified');?></th>
		<th><?php echo __l('Not Verified');?></th>
		<th><?php echo __l('Manage');?></th>
    </tr>
<?php
if (!empty($translations)):

$i = 0;
foreach ($translations as $language_id => $translation):
	$class = null;
	if ($i++ % 2 == 0):
		$class = ' class="altrow"';
    endif;
?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->cText($translation['name']);?></td>
		<td><?php 
			if($translation['verified']){
				echo $this->Html->link($translation['verified'], array('action' => 'manage', 'filter' => 'verified', 'language_id' => $language_id));
			} else {
				echo $this->Html->cText($translation['verified']);
			}
			$total = $translation['verified'] + $translation['not_verified'];
			$verified_percent = round($translation['verified'] * 100 / $total, 3);
			$unverified_percent = round($translation['not_verified'] * 100 / $total, 3);
			$translate_verfified_percentage = $verified_percent . "," . $unverified_percent;
			echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&chd=t:'.$translate_verfified_percentage.'&chs=37x37&chco=74B732|C1C1BA&chf=bg,s,FF000000', array('title' => __l('Verified: ').$verified_percent."%")); 
			?>
            </td>
		<td><?php 
			if($translation['not_verified']){
				echo $this->Html->link($translation['not_verified'], array('action' => 'manage', 'filter' => 'unverified', 'language_id' => $language_id));
			} else {
				echo $this->Html->cText($translation['not_verified']);
			}
			;?></td>
		<td>
			<span><?php echo $this->Html->link(__l('Manage'), array('action' => 'manage', 'language_id' => $language_id), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span>
			<?php if($language_id != '42'):?>
				<span><?php echo $this->Html->link(__l('Delete'), array('action' => 'index', 'remove_language_id' => $language_id), array('class' => 'delete js-delete', 'title' => __l('Delete Translation')));?></span>
			<?php endif;?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No Translations available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>