<?php
namespace App\Repositories\Tasks;

use App\Models\Task;
use App\Repositories\Interfaces\Tasks\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{

    /**
     * stor task details.
     *  @param array of $taskData
     *  @return object of created $task
     */
    public function storeTask($taskData)
    {
        $task = Task::create($taskData);
        return $task;
    }

    public function getTask($id)
    {
        $task = Task::find($id);
        return $task;
    }

    public function getUserTaskList($userId, $filter)
    {
        extract($filter);
        $tasks = Task::where(['user_id' => $userId])->whereNull('task_id');
        if (!empty($search)) {
            $tasks = $tasks->where('title', 'LIKE', '%' . $search . '%');
        }
        if (!empty($status)) {
            $tasks = $tasks->where('status', $status);
        }
        $tasks = $tasks->orderBy('title', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate($per_page);
        return $tasks;
    }

    public function getTaskWithSubTask($id)
    {
        $task = Task::with('subTasks')->find($id);
        return $task;
    }

    public function updateTaskStatus($id, $taskData)
    {
        $task = $this->getTask($id);
        $task->status = $taskData['status'];
        $task->update();
        return $task;
    }

    public function getTaskNameList($userId)
    {
        $tasks = Task::select('id', 'title')->where(["user_id" => $userId])->whereNull('task_id')
        ->orderBy('title','asc')->orderBy('created_at','asc')->get();
        return $tasks;
    }

    public function getUserTrashedTaskList($userId, $filter)
    {
        extract($filter);
        $tasks = Task::where(['user_id' => $userId])->whereNull('task_id')->onlyTrashed();
        if (!empty($search)) {
            $tasks = $tasks->where('title', 'LIKE', '%' . $search . '%');
        }
        if (!empty($status)) {
            $tasks = $tasks->where('status', $status);
        }
        $tasks = $tasks->orderBy('title', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate($per_page);
        return $tasks;
    }

    public function getTrashedTaskWithSubTaskList()
    {
        $tasks = Task::with('subTasks')->onlyTrashed()->get();
        return $tasks;
    }

    
}
