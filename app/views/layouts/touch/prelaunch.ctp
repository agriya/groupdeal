<?php
/* SVN FILE: $Id: default.ctp 7805 2008-10-30 17:30:26Z AD7six $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<?php echo $this->Html->charset(), "\n";?>
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title><?php echo Configure::read('site.name');?> | <?php echo $this->Html->cText($title_for_layout, false);?></title>
	<?php
		echo $this->Html->meta('icon'), "\n";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n";

		echo $this->Html->css('touch/touch.cache', null, array('inline' => true));
		echo $this->Javascript->link('touch/touch.cache', true);
	?>		
</head>
<body>
<div  data-role="page"> 
    <?php require_once('header.ctp'); ?> 
	<div data-role="content">
		<?php if ($this->Session->check('Message.error')):
		echo $this->Session->flash('error');
		endif;
		if ($this->Session->check('Message.success')):
				echo $this->Session->flash('success');
		endif;
		if ($this->Session->check('Message.flash')):
				echo $this->Session->flash();
		endif;
		?>
		<?php echo $content_for_layout;?>	
	</div>	
	<?php require_once('footer.ctp'); ?> 
</div> 
</body>
</html>