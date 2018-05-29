<?php /* SVN: $Id: index.ctp 79713 2012-10-31 09:20:55Z ananda_176at12 $ */ ?>
<?php $count = 1;?>

		<?php
			if(Configure::read('deal.index_page_compact_or_detail_view') == ConstIndexPageViewOption::Detail):
				$file_name = 'view';
		?>
		<div class="clearfix">
			<div class="deal-view-inner-block clearfix js-dialog-over-block">
				<div class="grid_18 alpha omega">
		<?php
				foreach($deals as $deal):
					echo $this->element('../deals/'.$file_name, array('deal' => $deal, 'count' => $count, 'get_current_city' => $get_current_city));
					$count++;
				endforeach;
				?>
            </div>
			<?php echo $this->element('../deals/sidebar', array('deal' => $deal, 'count' => $count, 'get_current_city' => $get_current_city));
			?>
			 </div>
		</div>
			<?php
			elseif(Configure::read('deal.index_page_compact_or_detail_view') == ConstIndexPageViewOption::Compact):
				$file_name = 'home';
				echo $this->element('../deals/'.$file_name, array('deal' => $deals, 'count' => $count, 'get_current_city' => $get_current_city));
			endif;

		  ?>    
 <?php //echo $this->element('../deals/sidebar', array('deal' => $deals, 'count' => $count, 'get_current_city' => $get_current_city, 'cache' => array('config' => 'site_element_cache', 'key' => $get_current_city))); ?>


