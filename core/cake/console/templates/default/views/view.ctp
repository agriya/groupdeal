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
<div class="<?php echo $pluralVar;?> view">
<h2><?php echo "<?php echo __l('{$singularHumanName}');?>";?></h2>
	<dl class="list"><?php echo "<?php \$i = 0; \$class = ' class=\"altrow\"';?>\n";?>
<?php
include 'wrap_methods_config.php';

foreach ($fields as $field) {
	$isKey = false;
	if(!empty($associations['belongsTo'])) {
		foreach ($associations['belongsTo'] as $alias => $details) {
			if($field === $details['foreignKey']) {
				$isKey = true;
				echo "\t\t<dt<?php if (\$i % 2 == 0) echo \$class;?>><?php echo __l('".Inflector::humanize(Inflector::underscore($alias))."');?></dt>\n";
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
				echo "\t\t\t<dd<?php if (\$i++ % 2 == 0) echo \$class;?>><?php echo \$this->Html->link(\$this->Html->".$_wrapMethod."(\${$singularVar}['{$alias}']['{$details['displayField']}']), array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$_slugName}']), array('escape' => false));?></dd>\n";
				break;
			}
		}
	}
	if($isKey !== true) {
		echo "\t\t<dt<?php if (\$i % 2 == 0) echo \$class;?>><?php echo __l('".Inflector::humanize($field)."');?></dt>\n";
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
		echo "\t\t\t<dd<?php if (\$i++ % 2 == 0) echo \$class;?>><?php echo \$this->Html->".$_wrapMethod."(\${$singularVar}['{$modelClass}']['{$field}']);?></dd>\n";
	}
}
?>
	</dl>
</div>