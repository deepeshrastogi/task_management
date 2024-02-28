<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $userId = $data->user()->id;
        $taskData['title'] = $data->title;
        $taskData['content'] = $data->content;
        $taskData['status'] = $data->status;
        $taskData['is_published'] = !empty($data->is_published) ? $data->is_published : 1;
        $taskData['user_id'] = $userId;
        if(!empty($data->task_id)){
            $taskData['task_id'] = $data->task_id;
        }
        $attachFile = $data->file('attachment');
        $destinationPath = public_path('uploads');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true); // Create the directory recursively
        }
        $fileExtension = time() . '.' . $attachFile->getClientOriginalExtension();
        $uniqueFname = rand() . time() . "_" . $fileExtension;
        $attachFile->move($destinationPath, $uniqueFname);
        $pulicUrlPath = url('/') . '/uploads/' . $uniqueFname;
        $taskData['attachment'] = $pulicUrlPath;
        $this->data = $taskData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Task::create($this->data);  
    }
}
