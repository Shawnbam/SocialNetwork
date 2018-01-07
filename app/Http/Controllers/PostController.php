<?php
namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use App\Post;
use App\User;
use Auth;

class PostController extends Controller{

    public function getDashboard(){
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('dashboard',['posts' => $posts]);
    }

    public function postCreatePost(Request $request){
        //validation
        $this -> validate($request, [
            'body' => 'required|max:1000',
        ]);
        $post = new Post();
        $post->body = $request['body'];

        if($request->user()->posts()->save($post)){
            $message = 'Post successfully Create';
        }
        return redirect()->route('dashboard')->with(['message' => $message]);
    }

    public  function getDeletePost($post_id){
        $post = Post::where('id', $post_id)->first();
        //yaad rakh bum ye
        if( Auth::user() != $post->user ){
            return redirect()->back();
        }
        $post->delete();
        return redirect()->route('dashboard')->with(['message' => 'Successfully Deleted!']);
    }

    public function getLogout(){
        Auth::logout();
        //return redirect()->back();//using back is tricky many a times
        return redirect()->route('home');
        //return redirect()->route('user.signin');

    }

    public function postEditPost(Request $request){
        $this -> validate($request, [
            'body' => 'required|max:1000',
        ]);
        $post = Post::find($request['postId']);
        if( Auth::user() != $post->user ){
            return redirect()->back();
        }
        $post->body = $request['body'];
        $post->update();
        //return response()->json(['message' => 'Post Edited Successfully'],200);
        return response()->json(['new_body' => $post->body],200);
    }

    public function postLikePost(Request $request){
        $post_Id = $request['postId'];
        $is_Like = $request['isLike'] === 'true';
        $update = false;
        $post = Post::find($post_Id);
        if(!$post){
            return null;
        }
        $user = Auth::user();
        $like = $user->likes()->where('post_id', $post_Id)->first();
        if($like){
            $already_like = $like->like;
            $update = true;
            if($already_like == $is_Like){
                $like->delete();
                return null;
            }
        } else {
            $like = new Like();
        }
        $like->like = $is_Like;
        $like->user_id = $user->id;
        $like->post_id = $post->id;
        if($update){
            $like->update();
        } else {
            $like->save();
        }
        //return 'bumm';
        return null;
    }

}
