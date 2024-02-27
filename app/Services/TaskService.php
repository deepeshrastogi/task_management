<?php

namespace App\Services;

use App\Models\SubTask;
use App\Repositories\Interfaces\Tasks\TaskRepositoryInterface;
use App\Traits\ApiResponse;
use App\Models\Task;
use Validator;

class TaskService
{

    use ApiResponse;
    /**
     * @var $taskRepository
     */
    protected $taskRepository;
    protected $loginUser;

    /**
     * order constructor.
     *
     * @param Repository $taskRepository
     */

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
       
    }
    
    public function index($requestData)
    {
        $loginUser = $this->loginUser($requestData);
        $perPage = !empty($requestData->per_page) ? $requestData->per_page  : 10;
        $search = !empty($requestData->search)?$requestData->search:'';
        $status = !empty($requestData->status)?$requestData->status:'';
        $tasks = Task::where(['user_id' => $loginUser->id])->whereNull('task_id');
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
        $validateArr = [
            'title' => 'required|max:100|unique:task',
            'content' => 'required',
            'status' => 'required',
            'attachment' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:4096',
        ];
        if(array_key_exists("task_id",$requestData->toArray())){
            $validateArr = array_merge($validateArr,['task_id' => 'required']);
        }
        $validator = Validator::make($requestData->all(), $validateArr,[
            'task_id.required' => 'The task field is required.'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 401);
        }
        $userId = $requestData->user()->id;
        $taskData['title'] = $requestData->title;
        $taskData['content'] = $requestData->content;
        $taskData['status'] = $requestData->status;
        $taskData['is_published'] = !empty($requestData->is_published) ? $requestData->is_published : 1 ;
        $taskData['user_id'] = $userId;
        if(!empty($requestData->task_id)){
            $taskData['task_id'] = $requestData->task_id;
        }

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

    public function show($requestData)
    {
        $loginUser = $this->loginUser($requestData);
        $task = Task::with('subTasks')->find($requestData->id);
        if(!empty($task->id) && ($task->user_id == $loginUser->id)){
            $response = ['task' => $task];
            return $this->success(message: 'This task has been fetched successfully', content: $response);
        }else{
            return $this->error(['error' => 'Unauthorized'], 401);
        }
    }

    
    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function destroy($requestData,$id)
    {
        $loginUser = $this->loginUser($requestData);
        $task = $this->taskRepository->getTask($id);
        if(!empty($task->id) && ($task->user_id == $loginUser->id)){
            $task->delete();
        }
        $data = [];
        return $this->success(message: 'Task deleted successfully', content: $data);
    }

    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function updateTaskStatus($requestData)
    {
        $loginUser = $this->loginUser($requestData);
        $task = $this->taskRepository->getTask($requestData->id);
        if($task->user_id == $loginUser->id){
            $task->status = $requestData->status;
            $task->update();
            if(!empty($requestData->task_id)){
                $returnFlag = $this->getChildTaskStatus($requestData->task_id);
            }
        }
        $data = ["task" => $task,'task_status' => $returnFlag];
        return $this->success(message: 'Status updated successfully', content: $data);
    }

    public function getChildTaskStatus($id){
        $returnFlag = 0;
        $tasks = SubTask::where(['task_id' => $id])->get();
        $statusArr = array_column($tasks->toArray(),'status');
        $statusCount = count($statusArr);
        $statuCoutValue = 0;
        foreach($statusArr as $status){
            if($status == 'done'){
                $statuCoutValue ++;
            }
        }
        if($statuCoutValue == $statusCount){
            Task::where(['id' => $id])->update(['status' => '1']);
            $returnFlag = 1;
        }
        return $returnFlag;
    }

    public function getTaskNameList($requestData){
        $task = Task::select('id','title')->whereNull('task_id')->get();
        $data = ["task" => $task];
        return $this->success(message: 'Task List has been fetched successfully', content: $data);
    }

    public function trashedTasks($requestData)
    {
        $loginUser = $this->loginUser($requestData);
        $perPage = !empty($requestData->per_page) ? $requestData->per_page  : 10;
        $search = !empty($requestData->search)?$requestData->search:'';
        $status = !empty($requestData->status)?$requestData->status:'';
        $tasks = Task::where(['user_id' => $loginUser->id])->whereNull('task_id')->onlyTrashed();
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
        return $this->success(message: 'Your trashed task has been fetched successfully', content: $response);
    }
}
