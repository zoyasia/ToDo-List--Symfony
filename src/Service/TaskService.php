<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskService
{
    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
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

    public function newTask(Request $request): JsonResponse
    {
        $task = new Task();
        $text = $request->getContent();
        $data = json_decode($text, true);

        $task
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setStatus($data['status'])
            ->setDeadline($data['deadline'])
            ->setIsCompleted($data['isCompleted']);


        // Si tout va bien, alors on peut persister l'entité et valider les modifications en BDD
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Tâche créée avec succès',
            'task' => $this->taskToArray($task),
        ]);
    }

    public function updateTask($taskId, Request $request): JsonResponse
    {
        $task = $this->taskRepository->find($taskId);
        $content = $request->getContent();
        $data = json_decode($content, true);

        $task
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setStatus($data['status'])
            ->setDeadline($data['deadline'])
            ->setIsCompleted($data['isCompleted']);

            $this->entityManager->persist($task);
            $this->entityManager->flush();


        return new JsonResponse([
            'message' => 'Tâche modifiée avec succès :',
            'task' => $this->taskToArray($task),
        ]);
    }

    public function deleteTask($taskId): JsonResponse
    {

        $task = $this->taskRepository->find($taskId);

        if (!$task) {
            return new JsonResponse(['message' => 'Tâche non trouvée']);
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();


        return new JsonResponse([
            'message' => 'Tâche supprimée avec succès',
        ]);
    }

    private function taskToArray(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'deadline' => $task->getDeadline(),
            'isCompleted' => $task->isIsCompleted(),
        ];
    }


}
