<div class="paging">
<?php
$this->Paginator->options(array(
    'url' => array_merge(array(
        'controller' => $this->request->params['controller'],
        'action' => $this->request->params['action'],
    ) , $this->request->params['pass'], $this->request->params['named'])
));

echo $this->Paginator->prev('&laquo; ' . __l('Prev') , array(
    'class' => 'prev',
    'escape' => false
) , null, array(
    'tag' => 'span',
    'escape' => false,
    'class' => 'prev'
)), "\n";
echo $this->Paginator->numbers(array(
    'modulus' => 2,
    'first' => 3,
	'last' => 3,
	'ellipsis' => '<span class="ellipsis">&hellip;.</span>',
    'separator' => " \n",
    'before' => null,
    'after' => null,
    'escape' => false
));
echo $this->Paginator->next(__l('Next') . ' &raquo;', array(
    'class' => 'next',
    'escape' => false
) , null, array(
    'tag' => 'span',
    'escape' => false,
    'class' => 'next'
)), "\n";
?>
</div>
