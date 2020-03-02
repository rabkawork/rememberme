<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Items;
use Validator;
use DateTime;
use DateTimeZone;
use DB;

class ItemsController extends Controller
{
    public $successStatus = 200;
    public $failStatus = 400;

    public function data()
    {
        $model    = new Items;
        $request  = Input::all();
        $orderCol = !empty($request['order'][0]['column']) ? $request['order'][0]['column'] : '';
        $orderDir = !empty($request['order'][0]['dir']) ? $request['order'][0]['dir'] : '';
        $search   = !empty($request['search']['value']) ? $request['search']['value'] : '';
        $length   = !empty($request['length']) ? $request['length'] : 10;
        $start    = !empty($request['start']) ? $request['start'] : 0;
        $draw     = !empty($request['draw']) ? (int) $request['draw'] : 0;
        $data     = $model->datatable($search, $length, $start, $orderCol, $orderDir);

        foreach ($data['sql'] as $key => $value) {
            $data['sql'][$key]->no = ++$start;

            $id = $data['sql'][$key]->id;
            $urlEdit   = url('kategori/update/' . $id);

            $menu = '<center><a href="' . $urlEdit . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>  <a href="#" class="btn btn-danger btn-sm" onclick="hapusItem(' . $id . ')"><i class="fa fa-trash"></i> Hapus</a></center>';

            $data['sql'][$key]->view = $menu;
        }


        $json = array(
            "draw"            => $draw,
            "recordsTotal"    => $data['count'][0]->count,
            "recordsFiltered" => $data['count'][0]->count,
            "data"            => $data['sql'],
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
                return response()->json(['msg' => 'Invalid API', 'data' => $validator->errors(), 'code' => 400], 400);
            } else {
                $date               = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
                $today              = $date->format('Y_m_d_H_i_s');
                $data['name']       = $request->name;
                $data['created_at'] = $today;
                $id = DB::table('items')->insertGetId($data);
                return response()->json(['msg' => 'sukses', 'data' => $data, 'code' => $this->successStatus], $this->successStatus);
            }
            return response()->json($request, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Bug', 'data' => $e->getMessage(), 'code' => 400], 400);
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
                return response()->json(['msg' => 'Invalid API', 'data' => $validator->errors(), 'code' => 400], 400);
            } else {
                $date               = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
                $today              = $date->format('Y_m_d_H_i_s');
                $data['name']       = $request->name;
                $data['created_at'] = $today;
                $id = DB::table('items_history')->insertGetId($data);
                return response()->json(['msg' => 'sukses', 'data' => $data, 'code' => $this->successStatus], $this->successStatus);
            }
            return response()->json($request, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Bug', 'data' => $e->getMessage(), 'code' => 400], 400);
        }
    }


    public function deleteItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|exists:items',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'Invalid API', 'data' => $validator->errors(), 'code' => 400], 400);
            } else {
                $id = DB::table('items')->where('id', $request->id)->delete();
                return response()->json(['msg' => 'sukses', 'data' => [], 'code' => $this->successStatus], $this->successStatus);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => 'Invalid API', 'data' => $validator->errors(), 'code' => 400], 400);
        }
    }


}
