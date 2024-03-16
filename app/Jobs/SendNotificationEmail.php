<?php
namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        //dd($this->post);
        // Logic to send email notification
        $email = 'aakash6590@gmail.com'; // Change this to your email address
        $subject = 'New post added';
        $message = 'Post : ' . $this->post->title;

        Mail::to($email)->send(new \App\Mail\NewPostNotification($subject, $message));
    }
}
