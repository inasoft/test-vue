<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class upcomingTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $email = 'aakash6590@gmail.com'; // Change this to your email address
        $currentDateTime = Carbon::now();
        $twentyFourHoursLater = $currentDateTime->copy()->addHours(24);
        $posts = Post::where('deadline', '<', $twentyFourHoursLater)->get()->toArray();
        foreach ($posts as $key => $value) {
           $subject = 'Task Going To Expired : ' . $value['title'];
           $message = 'task has been expired in 24 hours  : ' .  $value['title'];
           Mail::to($email)->send(new \App\Mail\NewPostNotification($subject, $message));
        }

    }
}
