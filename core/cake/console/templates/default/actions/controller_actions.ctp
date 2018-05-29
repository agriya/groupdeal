<?php
/**
 * Bake Template for Controller action generation.
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
 * @package       cake.console.libs.template.objects
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

	public function <?php echo $admin ?>index() {
		$this->pageTitle = __l('<?php echo $pluralHumanName; ?>');
		$this-><?php echo $currentModelName ?>->recursive = 0;
		$this->set('<?php echo $pluralName ?>', $this->paginate());
	}

	public function <?php echo $admin ?>view($<?php echo $slugName; ?> = null) {
		$this->pageTitle = __l('<?php echo $singularHumanName; ?>');
		$this-><?php echo $currentModelName; ?>-><?php echo $slugName; ?> = $<?php echo $slugName; ?>;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__l('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		$<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->find('first', array(
            'conditions' => array(
                '<?php echo $currentModelName; ?>.<?php echo $slugName; ?> = ' => $<?php echo $slugName; ?>
            ) ,
            'fields' => array(
<?php
$_fieldNames = array_keys($modelObj->schema(true));
foreach($_fieldNames as $_fieldName):
?>
			<?php echo "'{$currentModelName}.{$_fieldName}',\n"; ?>
<?php
endforeach;
$_recursive = -1;
foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
	foreach ($modelObj->{$assoc} as $associationName => $relation):
		if (!empty($associationName)):
			$_recursive = 0;
			$otherModelName = $this->_modelName($associationName);
			App::import('Model', $otherModelName);
			$otherModelObj = new $otherModelName();
			$_fieldNames = array_keys($otherModelObj->schema(true));
			foreach($_fieldNames as $_fieldName):
?>
			<?php echo "'{$otherModelName}.{$_fieldName}',\n"; ?>
<?php
			endforeach;
		endif;
	endforeach;
endforeach;
?>
			) ,
            'recursive' => <?php echo $_recursive; ?>,
        ));
		if (empty($<?php echo $singularName; ?>)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$this->pageTitle.= ' - ' . $<?php echo $singularName; ?>['<?php echo $currentModelName; ?>']['<?php echo $modelObj->displayField; ?>'];
		$this->set('<?php echo $singularName; ?>', $<?php echo $singularName; ?>);
	}

<?php $compact = array(); ?>
	public function <?php echo $admin ?>add() {
		$this->pageTitle = __l('Add <?php echo $singularHumanName; ?>');
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__l('<?php echo strtolower($singularHumanName); ?> has been added'), 'default', null, 'success');
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(__l('<?php echo ucfirst(strtolower($currentModelName)); ?> added.'), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__l('<?php echo strtolower($singularHumanName); ?> could not be added. Please, try again.'), 'default', null, 'error');
<?php endif; ?>
			}
		}
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
	public function <?php echo $admin; ?>edit($id = null) {
		$this->pageTitle = __l('Edit <?php echo $singularHumanName; ?>');
		if (is_null($id)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__l('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__l('<?php echo strtolower($singularHumanName); ?> has been updated'), 'default', null, 'success');
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(__l('<?php echo strtolower($singularHumanName); ?> has been updated.'), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__l('<?php echo strtolower($singularHumanName); ?> could not be updated. Please, try again.'), 'default', null, 'error');
<?php endif; ?>
			}
		} else {
			$this->data = $this-><?php echo $currentModelName; ?>->read(null, $id);
			if (empty($this->data)) {
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$this->pageTitle .= ' - ' . $this->data['<?php echo $currentModelName; ?>']['<?php echo $modelObj->displayField; ?>'];
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

	public function <?php echo $admin; ?>delete($id = null) {
		if (is_null($id)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__l('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this-><?php echo $currentModelName; ?>->delete()) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__l('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'), 'default', null, 'success');
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(__l('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'), array('action' => 'index'));
<?php endif; ?>
		}
<?php if ($wannaUseSession): ?>
		$this->Session->setFlash(__l('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'), 'default', null, 'error');
<?php else: ?>
		$this->flash(__l('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'), array('action' => 'index'));
<?php endif; ?>
		$this->redirect(array('action' => 'index'));
	}