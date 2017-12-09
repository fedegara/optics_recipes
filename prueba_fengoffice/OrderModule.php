<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 3/12/17
 * Time: 16:35
 */
class OrderModule
{
    private $array;

    /**
     * OrderModule constructor.
     */
    public function __construct(array $array)
    {
        if (is_null($array) or empty($array) or count($array) == 0) {
            throw new Exception("Array invalid or empty");
        }
        $this->array = $array;
    }

    /**
     * @param String|array $order Field name of the array with is gonna be ordered asc. If is array need to have two keys Ex: ['field'=>id,'order'=>desc]. The order value could be asc o desc exclusive
     * @param array $filter An associative with the followed keys: field, comparison_operator, value.
     * @return array
     *
     * @throws Exception
     */
    public function sort_order_tasks_array($order, array $filter)
    {
        if (is_null($filter) or !is_array($filter) or empty($filter) or count($filter) == 0 or (['field', 'comparison_operator', 'value'] != array_keys($filter))) {
            throw new Exception('Array $filter invalid or empty');
        }
        if (is_null($order) or empty($order)) {
            throw new Exception('$order invalid or null');
        }
        reset($this->array);
        if (!in_array($filter['field'], array_keys($this->array[key($this->array)]))) {
            throw new Exception('Field value passed in parameter $filter is not in the array');
        }
        if (!in_array($filter['comparison_operator'], ['<', '<=', '=', '>', '>='])) {
            throw new Exception('Field comparison_operator passed in parameter $filter is not a valid comparison operator');
        }
        $return = [];
        foreach ($this->array as $row) {
            foreach ($row as $key => $value) {
                if ($key === $filter['field']) {
                    $result = false;
                    switch ($filter['comparison_operator']) {
                        case '<':
                            $result = $value < $filter['value'];
                            break;
                        case '<=':
                            $result = $value <= $filter['value'];
                            break;
                        case '=':
                            $result = $value == $filter['value'];
                            break;
                        case '>':
                            $result = $value > $filter['value'];
                            break;
                        case '>=':
                            $result = $value >= $filter['value'];
                            break;
                    }
                    if ($result === true) {
                        $return[] = $row;
                        break;
                    }
                }
            }
        }
        if (is_array($order)) {
            if (['field', 'order'] != array_keys($order) or !in_array($order['order'], ['asc', 'desc'])) {
                throw new Exception('Bad field or missing fields in $order array');
            } else {
                return $this->order_result($return, $order['field'], $order['order']);
            }
        } else {
            return $this->order_result($return, $order, 'asc');
        }

    }

    /**
     * @param array $array The array to order
     * @param string $order_field Name of the field to order
     * @param string $order Type of order to apply
     * @return array
     */
    private function order_result($array, $order_field, $order)
    {
        usort($array, function ($a, $b) use ($order_field, $order) {


            if (is_string($a[$order_field]) and is_string($b[$order_field])) {
                return strcmp($a[$order_field], $b[$order_field]);
            } else {
                if($order_field =='start_date'){
                    $a[$order_field] = (new DateTime($a[$order_field]))->getTimestamp();
                    $b[$order_field] = (new DateTime($b[$order_field]))->getTimestamp();
                }

                if ($a[$order_field] == $b[$order_field]) {
                    return 0;
                }
                if ($order === 'asc') {
                    return ($a[$order_field] < $b[$order_field]) ? -1 : 1;
                } else {
                    return ($a[$order_field] > $b[$order_field]) ? -1 : 1;
                }
            }
        });
        return $array;
    }


}