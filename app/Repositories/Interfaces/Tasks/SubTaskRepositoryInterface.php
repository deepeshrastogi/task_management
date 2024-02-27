<?php
namespace App\Repositories\Interfaces\Tasks;

/*
 * Interface SubTaskRepositoryInterface
 * @package App\Repositories
 */
interface SubTaskRepositoryInterface
{
    public function storeSubTask($taskData);
    public function getSubTasks($taskId);
}
