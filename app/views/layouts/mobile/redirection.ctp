<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(), "\n";?>
	<title><?php echo Configure::read('site.name');?> | <?php echo $this->Html->cText($title_for_layout, false);?></title>
	<?php
		echo $this->Html->meta('icon'), "\n";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n";

		echo $this->Html->css('mobile/mobile.cache', null, array('inline' => true));
	?>
</head>
<body id="authorize">
	<div id="<?php echo $this->Html->getUniquePageId();?>" class="content">
		    <div class="authorize-page">
    			<?php echo $content_for_layout;?>
            </div>
	</div>
    <?php echo $this->element('site_tracker');?>
</body>
</html>