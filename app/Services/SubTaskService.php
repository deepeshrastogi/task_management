<?php

namespace App\Services;

use App\Repositories\Interfaces\Tasks\SubTaskRepositoryInterface;
use App\Traits\ApiResponse;
use App\Models\Task;
use Validator;

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
 
    public function index($requestData)
    {
        $loginUser = $this->loginUser($requestData);
        $perPage = !empty($requestData->per_page) ? $requestData->per_page  : 10;
        $search = !empty($requestData->search)?$requestData->search:'';
        $status = !empty($requestData->status)?$requestData->status:'';
        $tasks = Task::where(['user_id' => $loginUser->id]);
        if(!empty($search)){
            $tasks = $tasks->where('title','LIKE','%'.$search.'%');
        }
        if(!empty($status)){
            $tasks = $tasks->where('status',$status);
        }
        $tasks = $tasks->orderBy('title','asc')
        ->orderBy('created_at','asc')
        ->paginate($perPage);
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
        $validator = Validator::make($requestData->all(), [
            'task_id' => 'required',
            'title' => 'required|max:100|unique:task',
            'content' => 'required',
            'status' => 'required',
            'attachment' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:4096',
        ],[
            'task_id.required' => 'The task field is required.'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 401);
        }
        $userId = $requestData->user()->id;
        $taskData['task_id'] = $requestData->task_id;
        $taskData['title'] = $requestData->title;
        $taskData['content'] = $requestData->content;
        $taskData['status'] = $requestData->status;
        $taskData['user_id'] = $userId;

        if ($requestData->hasfile('attachment')) {
            $destinationPath = public_path('uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Create the directory recursively
            }
            $attachFile = $requestData->file('attachment');
            $fileExtension = time() . '.' . $attachFile->getClientOriginalExtension();
            $uniqueFname = rand() . time() . "_" . $fileExtension;
            $attachFile->move($destinationPath, $uniqueFname);
            $pulicUrlPath = url('/') . '/uploads/' . $uniqueFname;
            $taskData['attachment'] = $pulicUrlPath;
        }

        $subTask = $this->subTaskRepository->storeSubTask($taskData);
        $response = ['subTask' => $subTask];
        return $this->success(message: 'Your sub task is created successfully', content: $response);
    }

    public function show($requestData)
    {
        $loginUser = $this->loginUser();
        $task = Task::with('subtask')->find($requestData->id);
        if($task->user_id == $loginUser->id){
            
        }else{
            // unauthorized
        }
    }

    /**
     * singup user and create token, name,email,password and confirm_password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function update($requestData)
    {
        $validator = Validator::make($requestData->all(), [
            'title' => 'required|max:100|unique:task',
            'content' => 'required',
            'status' => 'required',
            'attachment' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:4096',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 401);
        }
        $userId = $requestData->user()->id;
        $taskData['title'] = $requestData->title;
        $taskData['content'] = $requestData->content;
        $taskData['status'] = $requestData->status;
        $taskData['user_id'] = $userId;

        if ($requestData->hasfile('attachment')) {
            $destinationPath = public_path('uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Create the directory recursively
            }
            $attachFile = $requestData->file('attachment');
            $fileExtension = time() . '.' . $attachFile->getClientOriginalExtension();
            $uniqueFname = rand() . time() . "_" . $fileExtension;
            $attachFile->move($destinationPath, $uniqueFname);
            $pulicUrlPath = url('/') . '/uploads/' . $uniqueFname;
            $taskData['attachment'] = $pulicUrlPath;
        }

        $task = $this->taskRepository->storeTask($taskData);
        $response = ['user' => $task];
        return $this->success(message: 'Your task is created successfully', content: $response);
    }

    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function destroy($requestData)
    {
        $requestData->user()->tokens()->delete();
        return $this->success(message: 'You are successfully logged out', content: []);
    }
}
