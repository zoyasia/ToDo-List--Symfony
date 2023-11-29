<?php

namespace App\Service;

use App\Entity\Task;
use App\Factory\TaskFactory\TaskFactory;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    public function showTasks(): array
    {
        $tasks = $this->taskRepository->findAll();

        $tasksArray = [];
        foreach ($tasks as $task) {
            $tasksArray[] = $task;
        }

        return $tasksArray;
    }
    public function newTask(Request $request): Task
    {
        $text = $request->getContent();
        $data = json_decode($text, true);

        $task = TaskFactory::createTask($data);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function updateTask(Task $task, array $data): void
    {
        if (!empty($data['title']) && $data['title'] !== $task->getTitle()) {
            $task->setTitle($data['title']);
        }

        if (!empty($data['description']) && $data['description'] !== $task->getDescription()) {
            $task->setDescription($data['description']);
        }

        if (!empty($data['deadline']) && $data['deadline'] !== $task->getDeadline()) {
            $task->setDeadline($data['deadline']);
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function deleteTask($taskId): Task
    {

        $task = $this->taskRepository->find($taskId);

        if (!$task) {
            throw new \Doctrine\ORM\EntityNotFoundException('Tâche non trouvée');
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $task;
    }
}
