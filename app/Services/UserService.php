<?php

namespace App\Services;

use App\Repositories\Interfaces\Users\UserRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserService
{

    use ApiResponse;
    /**
     * @var $userRepository
     */
    protected $userRepository;

    /**
     * order constructor.
     *
     * @param Repository $userRepository
     */

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function login($requestData)
    {
        $validator = Validator::make($requestData->all(), [
            'email' => 'required|email:filter',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 401);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return $this->error(['error' => 'Unauthorized'], 401);
        }
        $user = $requestData->user();
        $tokenResult = $user->createToken('user_access_token');
        $token = $tokenResult->plainTextToken;
        $response = ['user' => $user, 'token' => $token];
        return $this->success(message: 'You are successfully logged in', content: $response);
    }

    /**
     * singup user and create token, name,email,password and confirm_password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function signup($requestData)
    {
        $validator = Validator::make($requestData->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 401);
        }
        $userData['name'] = $requestData->name;
        $userData['email'] = $requestData->email;
        $userData['password'] = Hash::make($requestData->password);
        $user = $this->userRepository->storeUser($userData);
        $tokenResult = $user->createToken('user_access_token');
        $token = $tokenResult->plainTextToken;
        $response = ['user' => $user, 'token' => $token];
        return $this->success(message: 'Your account is created successfully', content: $response);
    }

    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function logout($requestData)
    {
        $requestData->user()->tokens()->delete();
        return $this->success(message: 'You are successfully logged out', content: []);
    }
}
