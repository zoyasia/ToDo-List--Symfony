<?php

namespace App\Service;

use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function showTasks(): JsonResponse
    {
        $tasks = $this->taskRepository->findAll();

        $tasksArray = [];
        foreach ($tasks as $task) {
            $tasksArray[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'deadline' => $task->getDeadline(),
                'isCompleted' => $task->isIsCompleted(),
            ];
        }

        return new JsonResponse([
            'message' => 'Voici toutes vos tâches',
            'tasks' => $tasksArray,
        ]);

    
    }
}
