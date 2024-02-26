<?php
namespace App\Repositories\Tasks;

use App\Models\SubTask;
use App\Repositories\Interfaces\Tasks\SubTaskRepositoryInterface;

class SubTaskRepository implements SubTaskRepositoryInterface
{

    /**
     * stor task details.
     *  @param array of $taskData
     *  @return object of created $subTask
     */
    public function storeSubTask($taskData)
    {
        $subTask = SubTask::create($taskData);
        return $subTask;
    }
}
