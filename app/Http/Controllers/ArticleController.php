<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'DESC')->paginate(10);

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:5',
            'author' => 'required|string|min:5'
        ]);

        if($validator->passes())
        {
            $article = new Article();

            $article->title = $request->title;
            $article->text = $request->text;
            $article->author = $request->author;
            $article->save();

            return redirect()->route('articles.index')->with('success', 'Article ajouté avec succès');
        }else{
            return redirect()->route('articles.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:5',
            'author' => 'required|string|min:5'
        ]);

        if ($validator->passes()) {
            $article->title = $request->title;
            $article->text = $request->text;
            $article->author = $request->author;

            $article->save();

            return redirect()->route('articles.index')->with('success', 'Article modifié avec succès');
        } else {
            return redirect()->route('articles.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        if ($article == null)
        {
            session()->flash('error', 'Article introuvable');
            return response()->json([
                'status' => false
            ]);
        }

        $article->delete();
        
        session()->flash('danger', 'Article supprimé avec succès');
        return response()->json([
            'status' => true
        ]);
    }
}
