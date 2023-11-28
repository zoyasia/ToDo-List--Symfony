<?php

namespace App\Controller;

use App\Entity\Task;
use App\Normalizer\TaskNormalizer;
use App\Service\TaskService;
use Normalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TaskController extends AbstractController
{

    private TaskService $taskService;
    private TaskNormalizer $normalizer;

    public function __construct(TaskService $taskService, TaskNormalizer $taskNormalizer)
    {
        $this->taskService = $taskService;
        $this->normalizer = $taskNormalizer;
    }

    #[Route('/tasks', name: 'app_tasks')]
    public function index(TaskNormalizer $taskNormalizer): JsonResponse
    {
        $tasks = $this->taskService->showTasks();
        $normalizedTasks = array_map([$taskNormalizer, 'normalize'], $tasks);

        return $this->json($normalizedTasks);
    }

    #[Route('/new', name: 'app_new', methods: ['POST', 'GET'])]
    public function newTask(Request $request)
    {
        $this->taskService->newTask($request);
        $data = $this->normalizer->normalize($request);
        return $this->json(['task' => $data], Response::HTTP_CREATED);
    }

    #[Route('/update/{task}', name: 'app_update', methods: ['PATCH'])]
    public function updateTask(Task $task, Request $request): JsonResponse
    {
        $this->taskService->updateTask($task, $request->toArray());
        return new JsonResponse($task, Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'app_delete', methods: ['DELETE'])]

    public function deleteTask($id): JsonResponse
    {
        return $this->taskService->deleteTask($id);
    }
}
