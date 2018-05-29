<div class="user-profiles">
<h2 class="no-brd bot-mspace"><?php echo __l('Demographics'); ?></h2>
	<h3><?php echo __l('Educations'); ?></h3>
	<?php
		$education_percentages = implode(',', $educations);
		$education_names = implode('|', $education_status);
		echo $this->Html->image('https://chart.googleapis.com/chart?chs=750x150&chd=t:'.$education_percentages.'&chco=E65447&cht=p3&chl='.$education_names);
	?>
	<h3><?php echo __l('Marital Status'); ?></h3>
	<?php
		$relationship_percentages = implode(',', $relationships);
		$relationship_names = implode('|', $relationship_status);
		echo $this->Html->image('https://chart.googleapis.com/chart?chs=750x150&chd=t:'.$relationship_percentages.'&chco=E09B34&cht=p3&chl='.$relationship_names);
	?>
	<h3><?php echo __l('Employement'); ?></h3>
	<?php

		$employment_percentages = implode(',', $employments);
		$employment_names = implode('|', $employment_status);
		echo $this->Html->image('https://chart.googleapis.com/chart?chs=750x150&chd=t:'.$employment_percentages.'&chco=9C88B9&cht=p3&chl='.$employment_names);

	?>
	<h3><?php echo __l('Income'); ?></h3>
	<?php

		$income_range_percentages = implode(',', $income_ranges);
		$income_range_names = implode('|', $income_range_status);
		echo $this->Html->image('https://chart.googleapis.com/chart?chs=750x150&chd=t:'.$income_range_percentages.'&chco=E18CB5&cht=p3&chl='.$income_range_names);

	?>
	<h3><?php echo __l('Gender'); ?></h3>
	<?php

		$gender_percentages = implode(',', $user_genders);
		$gender_names = implode('|', $gender_status);
		echo $this->Html->image('https://chart.googleapis.com/chart?chs=750x150&chd=t:'.$gender_percentages.'&chco=5CC2DB&cht=p3&chl='.$gender_names);
	?>
	<h3><?php echo __l('Age'); ?></h3>
	<?php
		$age_percentages = implode(',', $ages);
		$age_names = implode('|', $age_status);
		echo $this->Html->image('https://chart.googleapis.com/chart?chs=750x150&chd=t:'.$age_percentages.'&chco=7A9D43&cht=p3&chl='.$age_names);
	?>
</div>