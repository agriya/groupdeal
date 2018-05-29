<div class="paging">
<?php
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
