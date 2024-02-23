<?php

namespace App\Services;

use App\Repositories\Interfaces\Tasks\TaskRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Validator;

class TaskService
{

    use ApiResponse;
    /**
     * @var $taskRepository
     */
    protected $taskRepository;

    /**
     * order constructor.
     *
     * @param Repository $taskRepository
     */

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function store($requestData)
    {
        $validator = Validator::make($requestData->all(), [
            'title' => 'required|max:100|unique:task',
            'content' => 'required',
            'status' => 'required',
            'attachment' => 'required|image|mimes:jpeg,jpg,png,bmp,gif,svg|max:4096',
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
     * singup user and create token, name,email,password and confirm_password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function update($requestData)
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
        $taskData['name'] = $requestData->name;
        $taskData['email'] = $requestData->email;
        $taskData['password'] = Hash::make($requestData->password);
        $user = $this->taskRepository->storeTask($taskData);
        $tokenResult = $user->createToken('user_access_token');
        $token = $tokenResult->plainTextToken;
        $response = ['user' => $user, 'token' => $token];
        return $this->success(message: 'Your account is created successfully', content: $response);
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
