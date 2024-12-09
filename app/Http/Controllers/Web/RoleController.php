<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(){
        $roles =Role::orderBy('name','ASC')->paginate(10);
        return view('roles.list',[
            'roles' =>$roles
        ]);
    }

    public function create(){
        $permissions = Permission::orderBy('name','ASC')->get();

        return view('roles.create',[
            'permissions' => $permissions
        ]);
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'name' => 'required|unique:roles|min:3'
        ]);
        if($validator->passes()){
            $role = Role::create(['name'=>$req->name]);
            if(!empty($req->permission)){
                foreach($req->permission as $name){
                    $role->givePermissionTo($name);
                }
            }
            return redirect()->route('roles.index')->with('success','Role added successfully');
        }else{
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }

    public function edit($id){
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name','ASC')->get();

        return view('roles.edit',[
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions,
            'role' => $role
        ]);
    }

    public function update($id, Request $req){
        $role = Role::findOrFail($id);

        $validator = Validator::make($req->all(),[
            'name' => 'required|unique:roles,name,'.$id.',id|min:3'
        ]);
        if($validator->passes()){
            $role->name = $req->name;
            $role->save();
            if(!empty($req->permission)){
                $role->syncPermissions($req->permission);
            }else{
                $role->syncPermissions([]);
            }
            return redirect()->route('roles.index')->with('success','Role updated successfully');
        }else{
            return redirect()->route('roles.edit',$id)->withInput()->withErrors($validator);
        }
    }

    public function destroy(Request $req){
        $role = Role::find($req->id);
        if($role == null){
            session()->flash('error','Role not found');
            return response()->json([
                'status' => false
            ]);
        }

        $role->delete();
        session()->flash('success','Role deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
