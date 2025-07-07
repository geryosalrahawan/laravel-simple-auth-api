<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\comments;
use App\Models\Posts;

class CommentsController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $post = Posts::find($postId);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        //  dd(auth()->user());
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
    }

    public function index($postId)
    {
        $post = Posts::with('comments.user')->find($postId);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return response()->json($post->comments);
    }
////////////////////////////////////////////////
        public function update(Request $request,$id)
    {
 $user = auth()->user();
    $comment = comments::findOrFail($id);

    if ($comment->user_id !== $user->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $data = $request->validate([
        'content' => 'required|string'
    ]);

    $comment->update($data);

    return response()->json([
        'message' => 'Comment updated successfully',
        'comment' => $comment
    ]);
    }
public function destroy($id)
  {
    $comment = comments::find($id);
    $user = auth()->user();
    if ($comment->user_id !== $user->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    $comment->delete();
    return 'success, comments deleted successfully';
  }
}