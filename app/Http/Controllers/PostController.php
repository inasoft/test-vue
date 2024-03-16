<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Jobs\SendNotificationEmail;
use App\Jobs\upcomingTaskJob;
use Carbon\Carbon;

use Illuminate\Support\Facades\Queue;

//codeplaners 
class PostController extends Controller
{
    // all posts
    public function index()
    {
        $posts = Post::all()->toArray();
        return array_reverse($posts);
    }
  
    // add post
    public function add(Request $request)
    {
        $post = new Post([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'deadline' => $request->input('deadline')  
        ]);
        $post->save();
        $currentDateTime = Carbon::now();
        $deadlineDateTime = Carbon::parse($request->input('deadline'));
      
        if ($deadlineDateTime->isPast()) {
            // Update the status of the post to 2 (Expired)
            $post->update(['status' => 2]);
        }else{
            $post->update(['status' => 1]);
        }
        SendNotificationEmail::dispatch($post);
        return response()->json('post successfully added');
    }
  
    // edit post
    public function edit($id)
    {
        $post = Post::find($id);
        return response()->json($post);
    }
  
    // update post
    public function update($id, Request $request)
    {
        $post = Post::find($id);
        $post->update($request->all());
  
        return response()->json('post successfully updated');
    }
  
    // delete post
    public function delete($id)
    {
        $post = Post::find($id);
        $post->delete();
  
        return response()->json('post successfully deleted');
    }


    public function upcoming(){
         upcomingTaskJob::dispatch();
         return response()->json('successfully');

    }
}
