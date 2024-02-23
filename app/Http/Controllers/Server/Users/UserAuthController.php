<?php
namespace App\Http\Controllers\Server\Users;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{

    use ApiResponse;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * PostController Constructor
     *
     * @param userService $userService
     *
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function login(Request $request)
    {
        return $this->userService->login($request);
    }

    public function signup(Request $request)
    {
        return $this->userService->signup($request);
    }

    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        return $this->userService->logout($request);
    }
}
