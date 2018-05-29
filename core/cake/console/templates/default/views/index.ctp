<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake.console.libs.templates.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
echo '<?php /* SVN: $Id: $ */ ?>' . "\r\n";
?>
<div class="<?php echo $pluralVar;?> index">
	<h2><?php echo "<?php echo __l('{$pluralHumanName}');?>";?></h2>
<?php echo
'<?php echo $this->element(\'paging_counter\');?>
';
?>
<table class="list">
	<tr>
		<th class="actions"><?php echo "<?php echo __l('Actions');?>";?></th>
		<?php foreach ($fields as $field):?>
		<th><?php echo "<?php echo \$this->Paginator->sort('{$field}');?>";?></th>
		<?php endforeach;?>
	</tr>
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
	echo "\t<tr<?php echo \$class;?>>\n";
		echo "\t\t<td class=\"actions\">";
		echo "<span>";
	 	echo "<?php echo \$this->Html->link(__l('Edit'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>";
		echo "</span>";
		echo " <span>";
	 	echo "<?php echo \$this->Html->link(__l('Delete'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>";
		echo "</span>";
		echo "</td>\n";
		foreach ($fields as $field) {
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
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
                		$_class = $_classes[$details['fields'][$details['displayField']]['type']];
                		foreach($_currencyFields as $currencyField) {
                            if (strpos($currencyField, $details['displayField']) !== false) {
                                $_wrapMethod = 'cCurrency';
								$_class = 'dr';
                                break;
                            }
                        }
						echo "\t\t<td class=\"".$_class."\">\n\t\t\t<?php echo \$this->Html->link(\$this->Html->".$_wrapMethod."(\${$singularVar}['{$alias}']['{$details['displayField']}']), array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
// rajesh_04ag02 // 2008-07
        		if (!isset($_wrapMethods[$schema[$field]['type']])) {
                    trigger_error('*** dev1framework: Fix framework. Type '.$schema[$field]['type'].' not handled in $_wrapMethods', E_USER_ERROR);
                }
        		$_wrapMethod = $_wrapMethods[$schema[$field]['type']];
        		$_class = $_classes[$schema[$field]['type']];
        		foreach($_currencyFields as $currencyField){
                    if (strpos($currencyField, $field)!==false){
                        $_wrapMethod = 'cCurrency';
                        $_class = 'dr';
                        break;
                    }
                }
				echo "\t\t<td class=\"".$_class."\">\n\t\t\t<?php echo \$this->Html->".$_wrapMethod."(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n\t\t</td>\n";
			}
		}

	echo "\t</tr>\n";
echo '<?php
    endforeach;
else:
?>
';
echo "\t<tr>\n";
echo "\t\t<td colspan=\"".(count($fields) + 1)."\" class=\"notice\"><?php echo __l('No {$pluralHumanName} available');?></td>\n";
echo "\t</tr>\n";
echo '<?php
endif;
?>
';
?>
</table>
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
