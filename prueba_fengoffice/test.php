<?php
/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 3/12/17
 * Time: 18:41
 */

include_once 'OrderModule.php';


$tasks = array(
    '1'=>array(
        'id'=>1,
        'name'=>'B task',
        'start_date'=>'2010-2-11 12:01:42',
        'priority'=>1
    ),
    '2'=>array(
        'id'=>2,
        'name'=>'A task',
        'start_date'=>'2017-2-11 12:01:42',
        'priority'=>3
    ),
    '3'=>array(
        'id'=>3,
        'name'=>'Z task',
        'start_date'=>'2010-2-11 11:01:42',
        'priority'=>5
    ),
    '4'=>array(
        'id'=>4,
        'name'=>'C task',
        'start_date'=>'2017-11-21 12:01:42',
        'priority'=>4
    ),
    '5'=>array(
        'id'=>5,
        'name'=>'ZZ task',
        'start_date'=>'2014-12-24 23:01:42',
        'priority'=>1
    )
);

$compare = new OrderModule($tasks);
var_dump($compare->sort_order_tasks_array('start_date',['field' => 'id', 'comparison_operator' => '<=', 'value' => '4']));
//var_dump($compare->sort_order_tasks_array('name',['field' => 'priority', 'comparison_operator' => '<=', 'value' => '3']));
//var_dump($compare->sort_order_tasks_array('start_date',['field' => 'name', 'comparison_operator' => '=', 'value' => 'task']));
//var_dump($compare->sort_order_tasks_array('priority',['field' => 'start_date', 'comparison_operator' => '>', 'value' => '2011-12-20 21:01:43']));

//var_dump($compare->sort_order_tasks_array(['field'=>'priority','order'=>'desc'],['field' => 'start_date', 'comparison_operator' => '>', 'value' => '2011-12-20 21:01:43']));


