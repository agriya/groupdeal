<p>
<?php
// retain params
$this->Paginator->options(array(
    'url' => array_merge(array(
        'controller' => $this->params['controller'],
        'action' => $this->params['action'],
    ) , $this->params['pass'], $this->params['named'])
));

echo $this->Paginator->counter(array(
'format' => __l('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
));
?></p>
