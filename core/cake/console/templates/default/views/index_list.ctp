<?php
/* SVN FILE: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.console.libs.templates.views
 * @since			CakePHP(tm) v 1.2.0.5234
 * @version			$Revision: 6296 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-02 03:48:17 +0530 (Wed, 02 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
echo '<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>' . "\r\n";
?>
<div class="<?php echo $pluralVar;?> index">
<h2><?php echo "<?php echo __l('{$pluralHumanName}');?>";?></h2>
<?php echo
'<?php echo $this->element(\'paging_counter\');?>
';
?>
<ol class="list" start="<?php echo "<?php echo \$paginator->counter(array(
    'format' => '%start%'
));?>";?>">
<?php
include 'wrap_methods_config.php';

echo "<?php
if (!empty(\${$pluralVar})):

\$i = 0;
foreach (\${$pluralVar} as \${$singularVar}):
	\$class = null;
	if (\$i++ % 2 == 0) {
		\$class = ' class=\"altrow\"';
	}
?>\n";
	echo "\t<li<?php echo \$class;?>>\n";
		foreach ($fields as $field) {
			$isKey = false;
			if(!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if($field === $details['foreignKey']) {
						$isKey = true;
// rajesh_04ag02 // 2008-07
                        $_slugName = $details['primaryKey'];
                        $_fieldNames = array_keys($details['fields']);
                        if (in_array('slug', $_fieldNames)) {
                            $_slugName = 'slug';
                        } else if (in_array('username', $_fieldNames)) {
                            $_slugName = 'username';
                        }
        				if (!isset($_wrapMethods[$details['fields'][$details['displayField']]['type']])) {
                            trigger_error('*** dev1framework: Fix framework. Type '.$details['fields'][$details['displayField']]['type'].' not handled in $_wrapMethods', E_USER_ERROR);
                        }
                		$_wrapMethod = $_wrapMethods[$details['fields'][$details['displayField']]['type']];
                		foreach($_currencyFields as $currencyField){
                            if (strpos($currencyField, $details['displayField'])!==false){
                                $_wrapMethod = 'cCurrency';
                                break;
                            }
                        }

						echo "\t\t<p><?php echo \$this->Html->link(\$this->Html->".$_wrapMethod."(\${$singularVar}['{$alias}']['{$details['displayField']}']), array('controller'=> '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$_slugName}']), array('escape' => false));?></p>\n";
						break;
					}
				}
			}
			if($isKey !== true) {
// rajesh_04ag02 // 2008-07
        		if (!isset($_wrapMethods[$schema[$field]['type']])) {
                    trigger_error('*** dev1framework: Fix framework. Type '.$schema[$field]['type'].' not handled in $_wrapMethods', E_USER_ERROR);
                }
        		$_wrapMethod = $_wrapMethods[$schema[$field]['type']];
        		foreach($_currencyFields as $currencyField){
                    if (strpos($currencyField, $field)!==false){
                        $_wrapMethod = 'cCurrency';
                        break;
                    }
                }

				echo "\t\t<p><?php echo \$this->Html->".$_wrapMethod."(\${$singularVar}['{$modelClass}']['{$field}']);?></p>\n";
			}
		}
		echo "\t\t<div class=\"actions\">";
	 	echo "<?php echo \$this->Html->link(__l('Edit'), array('action'=>'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>";
	 	echo "<?php echo \$this->Html->link(__l('Delete'), array('action'=>'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>";
		echo "</div>\n";
	echo "\t</li>\n";
echo '<?php
    endforeach;
else:
?>
';
echo "\t<li>\n";
echo "\t\t<p class=\"notice\"><?php echo __l('No {$pluralHumanName} available');?></p>\n";
echo "\t</li>\n";
echo '<?php
endif;
?>
';
?>
</ol>
<?php
// when at least one page is needed for paging links...
// if ($paginator->params[\'paging\'][\''.$modelClass.'\'][\'pageCount\'] > 1) {
echo '
<?php
if (!empty($'.$pluralVar.')) {
    echo $this->element(\'paging_links\');
}
?>
';
?>
</div>
