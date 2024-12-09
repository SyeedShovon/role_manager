<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(){
        return Permission::orderByDesc('id')->get();
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'name' => 'required|unique:permissions|min:3'
        ]);
        if($validator->passes()){
            Permission::create(['name'=>$req->name]);
            return ["success" => "Permission added successfully"];
        }else{
            return $validator->errors();
        }
    }

    public function update(Request $req){
        if(!$req->id || $req->id==''){
            return ["error" => "Permission ID not available"];
        }
        $permission = Permission::findOrFail($req->id);
        $validator = Validator::make($req->all(),[
            'name' => 'required|unique:permissions,name,'.$req->id.',id|min:3'
        ]);
        if($validator->passes()){
            $permission->name = $req->name;
            $permission->save();
            return ["success" => "Permission Updated successfully"];
        }else{
            return $validator->errors();
        }
    }

    public function destroy(Request $req){
        if(!$req->id || $req->id=='' || $req->id==null || !isset($req->id)){
            return ["error" => "Permission ID not available"];
        }
        $permission = Permission::find($req->id);
        if($permission==null){
            return ["error" => "Permission Not Found"];
        }
        else{
            $permission->delete();
            return ["success" => "Permission successfully Deleted"];
        }
    }
}
