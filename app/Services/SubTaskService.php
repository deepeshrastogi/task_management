<?php

namespace App\Services;

use App\Repositories\Interfaces\Tasks\SubTaskRepositoryInterface;
use App\Traits\ApiResponse;
use Validator;
use App\Jobs\TaskAttachmentJob;

class SubTaskService
{

    use ApiResponse;

    /**
     * @var $subTaskRepository
     */
    protected $subTaskRepository;
    protected $loginUser;

    /**
     * order constructor.
     *
     * @param Repository $subTaskRepository
     */

    public function __construct(SubTaskRepositoryInterface $subTaskRepository)
    {
        $this->subTaskRepository = $subTaskRepository;

    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function store($requestData)
    {
        $validator = Validator::make($requestData->all(), [
            'task_id' => 'required',
            'title' => 'required|max:100|unique:task',
            'content' => 'required',
            'status' => 'required',
            'attachment' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:4096',
        ], [
            'task_id.required' => 'The task field is required.',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 401);
        }
        $userId = $requestData->user()->id;
        $taskData['task_id'] = $requestData->task_id;
        $taskData['title'] = $requestData->title;
        $taskData['content'] = $requestData->content;
        $taskData['status'] = $requestData->status;
        $taskData['is_published'] = !empty($requestData->is_published) ? $requestData->is_published : 1;
        $taskData['user_id'] = $userId;
        if ($requestData->hasfile('attachment')) {
            TaskAttachmentJob::dispatch($requestData); //upload data through queue
            $subTask = $requestData->all();
        }else{
            $subTask = $this->subTaskRepository->storeSubTask($taskData);
        }
        $response = ['task' => $subTask];
        return $this->success(message: 'Your sub task is created successfully', content: $response);
    }
}
