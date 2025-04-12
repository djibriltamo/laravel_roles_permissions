<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:voir users', only: ['index']),
            new Middleware('permission:ajouter users', only: ['create']),
            //new Middleware('permission:editer users', only: ['edit']),
            //new Middleware('permission:supprimer users', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'DESC')->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|same:confirm_password',
            'confirm_password' => 'required|min:8',
        ],[
            'name.required' => "Le nom d'utilisateur est requis",
            'name.min' => "Le nom doit dépasser 03 caractères",
            'email.required' => "L'adresse mail est requise",
            'email.unique' => "Cette adresse mail existe déjà",
            'password.required' => "Le mot de passe est requis",
            'password.min' => "Le mot de passe doit dépasser 08 caractères",
            'confirm_password.same' => "Le mot de passe doit être semblable au mot de passe de confirmation",
            'confirm_password.min' => "Le mot de passe doit dépasser 08 caractères",
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'DESC')->get();
        $hasRoles = $user->roles->pluck('id');

        return view('users.edit', compact('user', 'roles', 'hasRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $id . ',id'
        ], [
            'name.required' => "Le nom d'utilisateur est requis",
            'name.min' => "Le nom doit dépasser 03 caractères",
            'email.required' => "L'adresse mail est requise",
            'email.unique' => "Cette adresse mail existe déjà",
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.edit', $id)->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'Utilisateur modifié');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $user = User::findOrFail($id);

        if ($user == null) {
            session()->flash('error', 'Utilisateur Introuvable');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();

        session()->flash('danger', 'Utilisateur supprimé avec succès');
        return response()->json([
            'status' => false,
        ]);
    }
}
