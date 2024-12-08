<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(){
        $permissions = Permission::orderBy('created_at','DESC')->paginate(25);
        return view('permissions.list',[
            'permissions' => $permissions
        ]);
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
            return redirect()->route('permissions.index')->with('success','Permission added successfully');
        }else{
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }

    public function edit($id){
        $permission = Permission::findOrFail($id);
        return view('permissions.edit',[
            'permission'=>$permission
        ]);
    }

    public function update($id, Request $req){
        $permission = Permission::findOrFail($id);

        $validator = Validator::make($req->all(),[
            'name' => 'required|unique:permissions,name,'.$id.',id|min:3'
        ]);
        if($validator->passes()){
            //Permission::create(['name'=>$req->name]);
            $permission->name = $req->name;
            $permission->save();
            return redirect()->route('permissions.index')->with('success','Permission updated successfully');
        }else{
            return redirect()->route('permissions.edit',$id)->withInput()->withErrors($validator);
        }
    }

    public function destroy(Request $req){
        $id = $req->id;
        $permission = Permission::findOrFail($id);
        if($permission==null){
            session()->flash('error','Permission not found');
            return response()->json([
                'status' => false
            ]);
        }
        $permission->delete();
        session()->flash('success','Permission deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
