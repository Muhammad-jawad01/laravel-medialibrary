<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        // $post = Post::create([
        //     'title' => $request->title,
        //     'content' => $request->content,
        //     'create_by' => Auth::user()->id,
        //     'create_by' => auth()->user()->id,
        //     'status' => 'active',
        // ]);


        $data = request()->all();
        $data['create_by'] = auth()->user()->id;

        $data['status'] = 'active';


        // $post = Post::create($data);

        // $post= Post::new();
        $post = new Post();
        $post->fill($data);
        $post->save();

        // $post = new Post();
        // $post->title = $request->title;
        // $post->content = $request->content;
        // $post->create_by = Auth::user()->id;
        // $post->create_by = auth()->user()->id;
        // $post->status = 'active';
        // $post->save();

        return response()->json(['message' => 'Post created', 'data' => $post], 201);
    }


    public function getCurrentGuard()
    {
        foreach (['web', 'staff', 'student'] as $guard) {
            if (auth()->guard($guard)->check()) {
                return $guard;
            }
            dd(auth()->guard($guard));
        }

        return null; // no guard is logged in
    }


    public function index()
    {

        $posts = Post::with('user')->get();
        return response()->json(['message' => 'Post index', 'data' => $posts], 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);


        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $post->update($request->only(['title', 'content']));

        return response()->json(['message' => 'Post updated', 'data' => $post], 200);
    }


    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted'], 200);
    }
}
