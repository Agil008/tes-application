<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function data()
    {
        $data = User::all();
        return response()->json(['status'=>200,'data'=>$data]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'phone_number'  => 'required',
            'email'         => 'required',
            'address'       => 'required'
        ]);

        DB::beginTransaction();
        try {
            $insert = new User();
            $insert->name = $request->name;
            $insert->phone_number = $request->phone_number;
            $insert->email = $request->email;
            $insert->address = $request->address;
            $insert->save();

            DB::commit();
            return response()->json(['status'=>200,'message'=>'Succes Store Data']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=>400,'message'=>'Canot Create Data','error'=>$th]);
        }

    }

    public function update(Request $request)
    {
        $request->validate([
            'id'            => 'required',
            'name'          => 'required',
            'phone_number'  => 'required',
            'email'         => 'required',
            'address'       => 'required'
        ]);

        DB::beginTransaction();
        try {
            $update = User::find($request->id);
            $update->name = $request->name;
            $update->phone_number = $request->phone_number;
            $update->email = $request->email;
            $update->address = $request->address;
            $update->save();

            DB::commit();
            return response()->json(['status'=>200,'message'=>'Succes Update Data']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=>400,'message'=>'Canot Update Data','error'=>$th]);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id'            => 'required'
        ]);

        DB::beginTransaction();
        try {
            $delete = User::find($request->id);
            $delete->delete();

            DB::commit();
            return response()->json(['status'=>200,'message'=>'Succes Delete Data']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=>400,'message'=>'Canot Delete Data','error'=>$th]);
        }
    }
}
