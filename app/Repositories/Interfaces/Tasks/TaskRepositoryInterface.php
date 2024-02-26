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
}
