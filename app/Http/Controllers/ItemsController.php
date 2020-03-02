<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Items;
use Validator;
use DateTime;
use DateTimeZone;
use DB;

class ItemsController extends Controller
{
    public $successStatus = 200;

    public function data(Request $request)
    {
        $model    = new Items;
        $orderCol = !empty($request->order[0]['column']) ? $request->order[0]['column'] : '';
        $orderDir = !empty($request->order[0]['dir']) ? $request->order[0]['dir'] : '';
        $search   = !empty($request->search['value']) ? $request->search['value'] : '';
        $length   = !empty($request->length) ? $request->length : 10;
        $start    = !empty($request->start) ? $request->start : 0;
        $draw     = !empty($request->draw) ? (int) $request->draw : 0;
        $data     = $model->datatable($search, $length, $start, $orderCol, $orderDir);

        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]->no = ++$start;
            $id   = $data['data'][$key]->id;
            $menu = '<a onclick="setLocation('.$id.');" class="btn btn-outline-primary btn-sm">Update Location</a> <a onclick="removeItems('.$id.');" class="btn btn-outline-danger btn-sm">Remove</a>';
            $data['data'][$key]->view = $menu;
        }

        $json = array(
            "draw"            => $draw,
            "recordsTotal"    => $data['count'][0]->count,
            "recordsFiltered" => $data['count'][0]->count,
            "data"            => $data['data'],
        );
        return response()->json($json, 200);
    }


    public function index()
    {
        return view('items');
    }


    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'Invalid API', 'data' => $validator->errors(), 'code' => 400], 200);
            } else {
                $date                     = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
                $today                    = $date->format('Y_m_d_H_i_s');
                $data['name']             = $request->name;
                $data['created_at']       = $today;
                $data['items_history_id'] = 0;
                $id = DB::table('items')->insertGetId($data);
                return response()->json(['msg' => 'success', 'data' => $data, 'code' => $this->successStatus], $this->successStatus);
            }
            return response()->json($request, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Bug', 'data' => $e->getMessage(), 'code' => 400], 200);
        }
    }


    public function updateHistory(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'id'    => 'required|exists:items',
            'name'  => 'required',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'Invalid API', 'data' => $validator->errors(), 'code' => 400], 200);
            } else {
                $date               = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
                $today              = $date->format('Y_m_d_H_i_s');
                $data['location']   = $request->name;
                $data['created_at'] = $today;
                $id = DB::table('items_history')->insertGetId($data);

                $update['items_history_id'] = $id;
                DB::table('items')->where('id', $request->id)->update($update);

                return response()->json(['msg' => 'success', 'data' => $data, 'code' => $this->successStatus], $this->successStatus);
            }
            return response()->json($request, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Bug', 'data' => $e->getMessage(), 'code' => 400], 200);
        }
    }


    public function deleteItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|exists:items',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'Invalid API', 'data' => $validator->errors(), 'code' => 400], 200);
            } else {
                $id = DB::table('items')->where('id', $request->id)->delete();
                return response()->json(['msg' => 'success', 'data' => [], 'code' => $this->successStatus], $this->successStatus);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => 'Invalid API', 'data' => $validator->errors(), 'code' => 400], 200);
        }
    }


}
