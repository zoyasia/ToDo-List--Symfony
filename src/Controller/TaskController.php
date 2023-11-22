<?php

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    #[Route('/tasks', name: 'app_tasks')]
    public function index(): JsonResponse
    {
        return $this->taskService->showTasks();
    }

    #[Route('/new', name: 'app_new', methods: ['POST', 'GET'])]
    public function newTask(Request $request): JsonResponse
    {
        return $this->taskService->newTask($request);
    }

    #[Route('/update/{id}', name: 'app_update', methods: ['POST', 'GET'])]
    public function updateTask($id, Request $request): JsonResponse
    {
        return $this->taskService->updateTask($id, $request);
    }

    #[Route('/delete/{id}', name: 'app_delete', methods: ['POST', 'GET'])]

    public function deleteTask($id): JsonResponse
    {
        return $this->taskService->deleteTask($id);
    }
    


}
