<?php
/* SVN FILE: $Id: ajax.ctp 66746 2011-09-22 09:58:48Z josephine_065at09 $ */
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
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */?>
	<div class="js-flash-message ajax-flash-message-block flash-message-block">
     <?php
			if ($this->Session->check('Message.error')):
    				echo $this->Session->flash('error');
    		endif;
    		if ($this->Session->check('Message.success')):
    				echo $this->Session->flash('success');
    		endif;
			if ($this->Session->check('Message.flash')):
					echo $this->Session->flash();
			endif;
		?>
    </div>
    <?php  echo $content_for_layout; ?>