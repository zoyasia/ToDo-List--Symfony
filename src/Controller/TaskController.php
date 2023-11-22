<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function newTask(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->taskService->newTask($request, $entityManager);
}


}

