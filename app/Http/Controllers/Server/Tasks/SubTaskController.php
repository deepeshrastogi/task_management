<?php
namespace App\Http\Controllers\Server\Tasks;

use App\Http\Controllers\Controller;
use App\Services\SubTaskService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{

    use ApiResponse;

    /**
     * @var SubTaskService
     */
    protected $subTaskService;

    /**
     * PostController Constructor
     *
     * @param SubTaskService $subSubTaskService
     *
     */
    public function __construct(SubTaskService $subTaskService)
    {
        $this->subTaskService = $subTaskService;
    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function store(Request $request)
    {
        return $this->subTaskService->store($request);
    }
}
