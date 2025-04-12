<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:voir permissions', only: ['index']),
            new Middleware('permission:ajouter permissions', only: ['create']),
            new Middleware('permission:editer permissions', only: ['edit']),
            new Middleware('permission:supprimer permissions', only: ['destroy']),
        ];
    }
    public function index()
    {
        $roles = Role::orderBy('created_at', 'DESC')->paginate(10);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get(); 
        
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles|min:3'
        ], [
            'name.required' => 'Le nom du rôle est requis',
            'name.unique' => 'Le nom du rôle est unique!',
            'name.min' => 'Le nom du rôle doît dépassé ou être minimmum à 3 caractères.'
        ]);

        if ($validator->passes()) {
            $role = Role::create(['name' => $request->name]);

            if (!empty($request->permission))
            {
                foreach ($request->permission as $name)
                {
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('roles.index')->with('success', 'Rôle ajouté avec succès');
        } else {
            return redirect()->route("roles.create")->withInput()->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.edit', compact('role','permissions', 'hasPermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,'.$id.',id|min:3'
        ], [
            'name.required' => 'Le nom du rôle est requis',
            'name.unique' => 'Le nom du rôle est unique!',
            'name.min' => 'Le nom du rôle doît dépassé ou être minimmum à 3 caractères.'
        ]);

        if ($validator->passes()) {
            $role->name = $request->name;
            $role->save();

            if (!empty($request->permission)) {
                $role->syncPermissions($request->permission);
            }else{
                $role->syncPermissions([]);
            }

            return redirect()->route('roles.index')->with('success', 'Rôle modifié avec succès');
        } else {
            return redirect()->route("roles.edit",$id)->withInput()->withErrors($validator);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $role = Role::findOrFail($id);

        if ($role == null)
        {
            session()->flash('error', 'Rôle Introuvable');
            return response()->json([
                'status' => false,
            ]);
        }

        $role->delete();

        session()->flash('danger', 'Rôle supprimé avec succès');
        return response()->json([
            'status' => false,
        ]);
    }
}
