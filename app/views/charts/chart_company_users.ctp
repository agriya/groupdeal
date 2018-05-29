<div class="clearfix js-responses js-load-chart-company-users-ctp">
<?php
	$chart_title = __l('Purchased Users');
	$chart_y_title = __l('Users');
	$user_type_id = 1; // for unique id
	$arrow = "down-arrow";
 	if(isset($this->request->params['named']['is_ajax_load'])){ 
 		$arrow = "up-arrow";
	}
?>
 <div class="admin-side1-tl ">
	<div class="admin-side1-tr">
	  <div class="admin-side1-tc page-title-info">
		<h2 class="chart-dashboard-title pr no-border"><?php echo __l('Purchased User'); ?>
		<span class="js-chart-showhide <?php echo $arrow; ?> {'chart_block':'company-dashboard-user', 'dataloading':'div.js-load-chart-company-users-ctp',  'dataurl':'charts/chart_company_users/is_ajax_load:1'}"></span></h2>
	  </div>
	</div>
</div>
<?php if(isset($this->request->params['named']['is_ajax_load'])){ ?>
	<div class="admin-center-block clearfix dashboard-center-block" id="company-dashboard-user">
		<?php if(!empty($chart_pie_data)): ?>
			<div class="js-load-pie-chart {'data_container':'user_pie_data<?php echo $user_type_id; ?>', 'chart_container':'user_pie_chart<?php echo $user_type_id; ?>', 'chart_title':'<?php echo $chart_title;?>', 'chart_y_title': '<?php echo $chart_y_title;?>'}">
				<div id="user_pie_chart<?php echo $user_type_id; ?>" class="company-dashboard-user-chart"></div>
				<div class="hide">
					<table id="user_pie_data<?php echo $user_type_id; ?>" class="list">
						<tbody>
							<?php foreach($chart_pie_data as $display_name => $val): ?>
							<tr>
							   <th><?php echo $display_name; ?></th>
							   <td><?php echo $val; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<h3><?php echo __l('Demographics'); ?></h3>
		<?php endif; ?>
		<?php echo $this->element('chart-user_demographics', array('chart_y_title'=> $chart_y_title, 'user_type_id' => $user_type_id)); ?>
	</div>
<?php } ?>
</div>