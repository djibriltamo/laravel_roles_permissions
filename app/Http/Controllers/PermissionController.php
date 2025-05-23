<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PermissionController extends Controller implements HasMiddleware
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
    public function index(Request $request)
    {
        if($request->filled('search'))
        {
            $permissions = Permission::search($request->search)->paginate(10);
        }else{
            $permissions = Permission::orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('permissions.list', compact('permissions', 'request'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions|min:3'
        ],[
            'name.required' => 'Le nom de la permission est requise',
            'name.unique' => 'Le nom de la permission est unique!',
            'name.min' => 'Le nom de la permission doît dépassé ou être minimmum à 3 caractères.'
        ]);

        if($validator->passes())
        {
            Permission::create(['name' => $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission ajoutée avec succès');
        }else{
            return redirect()->route("permissions.create")->withInput()->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,'.$id.',id|min:3'
        ],[
            'name.required' => 'Le nom de la permission est requise',
            'name.unique' => 'Le nom de la permission est unique!',
            'name.min' => 'Le nom de la permission doît dépassé ou être minimmum à 3 caractères.'
        ]);

        if($validator->passes())
        {
            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('permissions.index')->with('success', 'Permission modifiée avec succès');
        }else{
            return redirect()->route("permissions.edit", $id)->withInput()->withErrors($validator);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $permission = Permission::find($id);

        if($permission == null) 
        {
            session()->flash('error', 'Permission introuvable');
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la suppression de la permission !'
            ]);
        }

        $permission->delete();

        session()->flash('danger', 'Permission supprimée avec succès');
            return response()->json([
                'status' => true,
                'message' => 'Suppression de la permission !'
            ]);
    }
}