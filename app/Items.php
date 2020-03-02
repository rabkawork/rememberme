<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Items extends Model
{
    protected $table = 'items';

    protected $fields = ['items.id', 'items.name', 'items_history.location', 'items_history.created_at'];

    public function dataTable($search, $length, $start, $orderField, $orderStatus)
    {

        $fields = $this->fields;

        $count     = count($fields);
        $whereLike = '';

        $limit = '';
        $order = '';

        if (!empty($orderField) and !empty($orderStatus)) {
            $order = ' ORDER BY ' . $fields[$orderField] . ' ' . $orderStatus;
        } elseif (!empty($orderField) || !empty($orderStatus)) {
            $order = ' ORDER BY ' . 'items.name' . ' ' . $orderStatus;
        } else {
            $order = ' ORDER BY ' . 'items.id' . ' ASC ';
        }

        if (!empty($length) || !empty($start))
            $limit = ' LIMIT ' . $length . ' OFFSET ' . $start;


        $i = 0;

        if (!empty($search)) {
            $whereLike = ' where ';
            foreach ($fields as $data) {
                ++$i;
                if ($i == $count) {
                    $whereLike .= " " . $data . " LIKE '%" . $search . "%' ";
                } else {
                    $whereLike .= " " . $data . " LIKE '%" . $search . "%' OR";
                }
            }

            $whereLike .= '';
        }

        $json['data']       = DB::select("select items.id, items.name, items_history.location, items_history.created_at
                            from items LEFT JOIN items_history ON items.items_history_id = items_history.id " . $whereLike . $order . $limit);
        $json['count']     = DB::select("select count(*) as count from items LEFT JOIN items_history ON items.items_history_id = items_history.id " . $whereLike);
        return $json;
    }
}
