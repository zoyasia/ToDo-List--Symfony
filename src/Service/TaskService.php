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

    public function newTask(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = new Task();
        $text = $request->getContent();
        $data = json_decode($text,true);

        $task
        ->setTitle($data['title'])
        ->setDescription($data['description'])
        ->setStatus($data['status'])
        ->setDeadline($data['deadline'])
        ->setIsCompleted($data['isCompleted']);


            // Si tout va bien, alors on peut persister l'entité et valider les modifications en BDD
            $entityManager->persist($task);
            $entityManager->flush();
    
    return new JsonResponse([
        'message' => 'Tâche créée avec succès',
        'task' => [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'deadline' => $task->getDeadline(),
            'isCompleted' => $task->isIsCompleted(),
        ],
    ]);
}




}
