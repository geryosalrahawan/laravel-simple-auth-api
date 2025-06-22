<?php

namespace App\Http\Controllers;

use App\Models\Posts;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class PostsController extends Controller
{



    /// creating a post
    public function createPosts(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'title'     => 'required|string',
            'content'    => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $validated = $validator->validated();
        $post = $user->posts()->create($validated);
        
        return response()->json([
            'message' => 'Post created successfully.',
            'post' => $post
        ], 201);
    }
///////////////////////////////////////////////////////////////////////

//show all posts
    public function showall()
    {
    $posts = Posts::all();

    
    return response()->json([
        'posts' => $posts
    ]);

    }
    //show the current user posts
    public function showmine()
    {
    $user = auth()->user();

    $posts = $user->posts()->latest()->get();

    return response()->json([
        'posts' => $posts
    ]);

    }
//////////////////////////////////////////////////////////////
///show a spacific user post
    public function showother($id)
    {
    $user = User::findOrFail($id);

    $posts = $user->posts()->latest()->get();

    return response()->json([
        'user' => $user->name,
        'posts' => $posts
    ]);

    }
////////////////////////////////////////////////////////////////

    //update the posts
public function update(Request $request, $id)
{
    $user = auth()->user();
    $post = Posts::findOrFail($id);

    if ($post->user_id !== $user->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    //////////////////////////////////////////////////////////////
///delete the posts
    $post->update($request->validated());

    return response()->json([
        'message' => 'Post updated successfully',
        'post' => $post
    ]);
}
    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
  {
    $post = Posts::find($id);
    $post->delete();
    return 'success, Post deleted successfully';
  }
}