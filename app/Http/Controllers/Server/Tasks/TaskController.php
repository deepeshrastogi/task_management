<?php
namespace App\Http\Controllers\Server\Tasks;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    use ApiResponse;

    /**
     * @var TaskService
     */
    protected $taskService;

    /**
     * PostController Constructor
     *
     * @param TaskService $taskService
     *
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Tasks List
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function index(Request $request)
    {
        return $this->taskService->index($request);
    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function store(Request $request)
    {
        return $this->taskService->store($request);
    }

    /**
     * Tasks List
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function show(Request $request)
    {
        return $this->taskService->show($request);
    }


    /**
     * destroy through get
     * @return [json] \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        return $this->taskService->destroy($request,$id);
    }

     /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function updateTaskStatus(Request $request)
    {
        return $this->taskService->updateTaskStatus($request);
    }

    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function getTaskNameList(Request $request)
    {
        return $this->taskService->getTaskNameList($request);
    }

    
}
