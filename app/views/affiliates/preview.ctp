<div class="affiliates affiliate-preview-block clearfix js-affiliate-preview-script-overblock">
    <h3><?php echo __l('Preview');?></h3>
	<?php if($is_preview): 
		$preivew_url = Router::url(array('controller'=>'affiliates', 'action'=>'widget', 'user'=>$this->Auth->user('username'), 'city_name'=>$this->request->data['Affiliate']['city_id'], 'size' => $this->request->data['Affiliate']['affiliate_widget_size_id'], 'color' => $this->request->data['Affiliate']['color'], 'ext'=>'js'),true);
	?>
	 <div class="clearfix ad-preview">
	     <?php echo $this->element('affiliates-widget');?>
	 </div>
	 <div class="clearfix js_widget_script">	 
	 <textarea readonly=true><script src="<?php echo $preivew_url; ?>"></script>
	 </textarea>
	 </div>
	<?php endif; ?>
</div>