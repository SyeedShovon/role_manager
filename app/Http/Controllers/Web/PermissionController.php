<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(){
        return view('permissions.list');
    }

    public function create(){
        return view('permissions.create');
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'name' => 'required|unique:permissions|min:3'
        ]);
        if($validator->passes()){
            Permission::create(['name'=>$req->name]);
            return redirect()->route('permission.index')->with('success','Permission added successfully');
        }else{
            return redirect()->route('permission.create')->withInput()->withErrors($validator);
        }
    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
