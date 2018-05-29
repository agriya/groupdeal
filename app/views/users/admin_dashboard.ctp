<div class="">
	<table class="list">
	<tr>
		<th colspan='2'>&nbsp;</th>
		<?php foreach($periods as $key => $period){ ?>
		<th>
			<?php echo $period['display']; ?>
		</th>
		<?php } ?>
	</tr>
	<?php
	$j = 0;
	foreach($models as $unique_model){				 
		 foreach($unique_model as $model => $fields){
			if(isset($fields['rowspan'])){
				$j = 0;
			}	
			$aliasName = isset($fields['alias']) ? $fields['alias'] : $model;
		?>
				<?php $element = isset($fields['rowspan']) ? 'rowspan ="'.$fields['rowspan'].'"' : ''; ?>
				<?php $element .= isset($fields['colspan']) ? 'colspan ="'.$fields['colspan'].'"' : ''; ?>
				<?php if(!isset($fields['isSub'])): ?>
					<tr>
					<td class="dr sub-title" <?php echo $element;?>>
						<?php echo $fields['display']; ?>  
					</td>
				<?php endif;?>
				<?php if(isset($fields['isSub'])):
							if($j != 0){
				?>
								<tr>
				<?php 		} ?>
					<td class="dr">  
						<?php echo $fields['display']; ?>  
					</td>
				<?php 
					$j++;						
					endif; ?>
				<?php if(!isset($fields['rowspan'])): ?>
					<?php foreach($periods as $key => $period) { ?>
							<td>
								<span class="<?php echo (!empty($fields['class']))? $fields['class'] : ''; ?>">
								<?php
									if(empty($fields['type'])) {
										$fields['type'] = 'cInt';
									}
									if (!empty($fields['link'])):
										$fields['link']['stat'] = $key;
										echo $this->Html->link($this->Html->{$fields['type']}(${$aliasName.$key}), $fields['link'], array('escape' => false, 'title' => __l('Click to View Details')));
									else:
										echo $this->Html->{$fields['type']}(${$aliasName.$key});
									endif;
								?>
								</span>
							</td>
					<?php } ?>
					</tr>
				<?php endif; ?>

		 <?php } ?>
	<?php } ?>
	</table>
</div>