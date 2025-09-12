<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //

    public function post()
    {
        // dd('Post Page');

        $posts = Post::all();
        return view('post', compact('posts'));
    }

    public function create()
    {
        return view('createpost');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'postfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->save();

            if ($request->hasFile('postfile')) {
                $post->addMediaFromRequest('postfile')->toMediaCollection('postfile');
            }

            return redirect()->route('post')->with('success', 'Post created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());


            return redirect()->back()->with('error', 'Error: ' . $th->getMessage());
        }

    }
}
