<?php
namespace App\Repositories\Interfaces\Tasks;

/*
 * Interface TaskRepositoryInterface
 * @package App\Repositories
 */
interface TaskRepositoryInterface
{
    public function storeTask($taskData);
    public function getTask($id);
    public function getUserTaskList($userId, $filter);
    public function getTaskWithSubTask($id);
    public function updateTaskStatus($id, $taskData);
    public function getTaskNameList($userId);
    public function getUserTrashedTaskList($userId, $filter);
    public function getTrashedTaskWithSubTaskList();
}
