<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\UserRegistration;
use App\Models\UserFollow;
use App\Models\Like;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CommentController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:user_post,user_post_id',
            'content' => 'required|string|max:255',
        ]);
    
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->post_id = $request->post_id;
        $comment->content = $request->content;
        $comment->save();
        $comment->append('created_at_human');
    
        // load the comment with the user relationship
        $comment->load('user');
    
        // create a notification for the post owner
        $post = UserPost::find($request->post_id);
        if ($post && $post->user_id !== Auth::id()) {
            $notification = new Notification();
            $notification->user_id = $post->user_id; // the post owner
            $notification->post_id = $post->user_post_id;
            $notification->comment_by = Auth::id();
            $notification->type = 'comment';
            $notification->status = 'unread';
            $notification->save();
        }
    
        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]);
    }
    

    public function destroy(Comment $comment)
    {
        $post = $comment->post;
    
        if (Auth::id() !== $comment->user_id && Auth::id() !== $post->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }
    
        $postId = $comment->post_id;
        $comment->delete();
        return response()->json(['success' => true, 'postId' => $postId]);
    }
    
    public function toggleLike(Request $request)
    {
        // validate the request
        $request->validate([
            'post_id' => 'required|exists:user_post,user_post_id',
        ]);
    
        $postId = $request->post_id;
        $userId = Auth::id();
    
        // check if the user has already liked the post
        $like = Like::where('post_id', $postId)->where('user_id', $userId)->first();
    
        if ($like) {
            // if the like exists, delete it (unlike)
            $like->delete();
            $liked = false;
        } else {
            // if the like does not exist, create it (like)
            Like::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
            $liked = true;
    
            // create a notification for the post owner
            $postOwnerId = UserPost::findOrFail($postId)->user_id;
            if ($postOwnerId !== $userId) {
                Notification::create([
                    'user_id' => $postOwnerId, 
                    'post_id' => $postId,
                    'type' => 'like', 
                    'liked_by' => $userId,
                    'status' => 'unread', 
                ]);
            }
        }
    
        $likeCount = Like::where('post_id', $postId)->count();
    
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likeCount' => $likeCount,
        ]);
    }

    public function showNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        $notificationCount = $notifications->count();

        return response()->json([
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
        ]);
    }
    
    public function storeReply(Request $request, Comment $comment)
    {
        $validatedData = $request->validate([
            'post_id' => 'required|exists:user_post,user_post_id',
            'content' => 'required|string',
        ]);

        $reply = $comment->replies()->create([
            'user_id' => auth()->id(),
            'post_id' => $validatedData['post_id'],
            'content' => $validatedData['content'],
            'parent_id' => $comment->comment_id,
        ]);

        $reply->load('user');
        $reply->append('created_at_human');

        return response()->json([
            'success' => true,
            'comment' => $reply,
        ]);
    }
    

    public function getReplies(Comment $comment)
    {
        $replies = $comment->replies()->with('user')->get();
        return response()->json([
            'success' => true,
            'replies' => $replies
        ]);
    }

    public function getReplyCount(Comment $comment)
    {
        $replyCount = $comment->replies()->count();
        return response()->json([
            'success' => true,
            'count' => $replyCount
        ]);
    }

    public function updateReply(Request $request, Comment $comment, Comment $reply)
    {
        if (Auth::id() !== $reply->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }
    
        $validatedData = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        $reply->update($validatedData);
    
        return response()->json([
            'success' => true,
            'content' => $reply->content,
        ]);
    }

    public function destroyReply(Comment $comment, Comment $reply)
    {
        if (Auth::id() !== $reply->user_id && Auth::id() !== $comment->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

    
        $reply->delete();
    
        return response()->json([
            'success' => true,
            'replyCount' => $comment->replies()->count(), 
        ]);
    }

    public function edit(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }
    
        $validatedData = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        $comment->update($validatedData);
    
        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }
    }        
