<?php

namespace App\Services;

use App\Repositories\Interfaces\Tasks\SubTaskRepositoryInterface;
use App\Repositories\Interfaces\Tasks\TaskRepositoryInterface;
use App\Traits\ApiResponse;
use App\Jobs\TaskAttachmentJob;
use Validator;

class TaskService
{

    use ApiResponse;
    /**
     * @var $taskRepository
     */
    protected $taskRepository;
    protected $subTaskRepository;

    /**
     * order constructor.
     *
     * @param Repository $taskRepository
     */

    public function __construct(TaskRepositoryInterface $taskRepository, SubTaskRepositoryInterface $subTaskRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->subTaskRepository = $subTaskRepository;

    }

    public function index($requestData)
    {
        $loginUser = $this->loginUser($requestData);
        $filter = [
            'per_page' => !empty($requestData->per_page) ? $requestData->per_page : 10,
            'search' => !empty($requestData->search) ? $requestData->search : '',
            'status' => !empty($requestData->status) ? $requestData->status : '',
        ];
        $tasks = $this->taskRepository->getUserTaskList($loginUser->id, $filter);
        $response = ['tasks' => $tasks];
        return $this->success(message: 'Your task has been fetched successfully', content: $response);
    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function store($requestData)
    {
        $validateArr = [
            // 'title' => 'required|max:100|unique:task',
            'title' => 'required|max:100',
            'content' => 'required',
            'status' => 'required',
            'attachment' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:4096',
        ];
        if (array_key_exists("task_id", $requestData->toArray())) {
            $validateArr = array_merge($validateArr, ['task_id' => 'required']);
        }
        $validator = Validator::make($requestData->all(), $validateArr, [
            'task_id.required' => 'The task field is required.',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 401);
        }
        $userId = $requestData->user()->id;
        $taskData['title'] = $requestData->title;
        $taskData['content'] = $requestData->content;
        $taskData['status'] = $requestData->status;
        $taskData['is_published'] = !empty($requestData->is_published) ? $requestData->is_published : 1;
        $taskData['user_id'] = $userId;
        if (!empty($requestData->task_id)) {
            $taskData['task_id'] = $requestData->task_id;
        }
        if ($requestData->hasfile('attachment')) {
            TaskAttachmentJob::dispatch($requestData); //upload data through queue
            $task = (object)[];
        }else{
            $task = $this->taskRepository->storeTask($taskData);
        }
        $response = ['user' => $task];
        return $this->success(message: 'Your task is created successfully', content: $response);
    }

    public function show($requestData, $id)
    {
        $loginUser = $this->loginUser($requestData);
        $task = $this->taskRepository->getTaskWithSubTask($id);
        if (!empty($task->id) && ($task->user_id == $loginUser->id)) {
            $response = ['task' => $task];
            return $this->success(message: 'This task has been fetched successfully', content: $response);
        } else {
            return $this->error(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function destroy($requestData, $id)
    {
        $loginUser = $this->loginUser($requestData);
        $task = $this->taskRepository->getTask($id);
        if (!empty($task->id) && ($task->user_id == $loginUser->id)) {
            $task->delete();
        }
        $data = [];
        return $this->success(message: 'Task deleted successfully', content: $data);
    }

    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function updateTaskStatus($requestData, $id)
    {
        $returnFlag = 0;
        $loginUser = $this->loginUser($requestData);
        $task = $this->taskRepository->getTask($id);
        if ($task->user_id == $loginUser->id) {
            $task->status = $requestData->status;
            $task->update();
            if (!empty($requestData->task_id)) {
                $returnFlag = $this->getSubTaskStatus($requestData->task_id);
            }
        }
        $data = ["task" => $task, 'task_status' => $returnFlag];
        return $this->success(message: 'Status updated successfully', content: $data);
    }

    public function getSubTaskStatus($id)
    {
        $returnFlag = 0;
        $tasks = $this->subTaskRepository->getSubTasks($id);
        $statusArr = array_column($tasks->toArray(), 'status');
        $statusCount = count($statusArr);
        $statuCoutValue = 0;
        foreach ($statusArr as $status) {
            if ($status == 'done') {
                $statuCoutValue++;
            }
        }
        if ($statuCoutValue == $statusCount) {
            $task = $this->taskRepository->updateTaskStatus($id, ['status' => 'done']);
            if ($task) {
                $returnFlag = 1;
            }
        }
        return $returnFlag;
    }

    public function getTaskNameList($requestData)
    {
        $loginUser = $this->loginUser($requestData);
        $task = $this->taskRepository->getTaskNameList($loginUser->id);
        $data = ["task" => $task];
        return $this->success(message: 'Task List has been fetched successfully', content: $data);
    }

    public function trashedTasks($requestData)
    {
        $loginUser = $this->loginUser($requestData);
        $filter = [
            'per_page' => !empty($requestData->per_page) ? $requestData->per_page : 10,
            'search' => !empty($requestData->search) ? $requestData->search : '',
            'status' => !empty($requestData->status) ? $requestData->status : '',
        ];
        $tasks = $this->taskRepository->getUserTrashedTaskList($loginUser->id, $filter);
        $response = ['tasks' => $tasks];
        return $this->success(message: 'Your trashed task has been fetched successfully', content: $response);
    }
}
