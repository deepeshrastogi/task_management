<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Services\TaskService;
class TruncateTrashedTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:truncate-trashed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will automatically removed trashed task within 30 days';
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        parent::__construct();
        $this->taskService = $taskService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = $this->taskService->deleteTrashedTaskWithSubTaskList(); 
        echo $response->original['message'];
    }
}
