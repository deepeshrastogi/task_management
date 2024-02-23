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
}
