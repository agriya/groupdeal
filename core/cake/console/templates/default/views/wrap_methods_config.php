<?php
// helper to wrap
$_wrapMethods = array(
    'string' => 'cText',
    'text' => 'cText',
    'integer' => 'cInt',
    'date' => 'cDate',
    'datetime' => 'cDateTime',
    'time' => 'cTime',
    'float' => 'cFloat',
    'boolean' => 'cBool'
);
$_classes = array(
    'string' => 'dl',
    'text' => 'dl',
    'integer' => 'dc',
    'date' => 'dc',
    'datetime' => 'dc',
    'time' => 'dc',
    'float' => 'dc',
    'boolean' => 'dc'
);
$_currencyFields = array(
    'amount',
    'price',
    'currency',
    'amt',
    'commission'
);
?>