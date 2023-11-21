<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    private $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    #[Route('/tasks', name: 'app_tasks')]
    public function index(TaskService $taskService): JsonResponse
    {
        return $taskService->showTasks();
    }
    
        // $tasks = $this->taskRepository->findAll();

        // $tasksArray = [];
        // foreach ($tasks as $task) {
        //     $tasksArray[] = [
        //         'id' => $task->getId(),
        //         'title' => $task->getTitle(),
        //         'description' => $task->getDescription(),
        //         'status' => $task->getStatus(),
        //         'deadline' => $task->getDeadline(),
        //         'isCompleted' => $task->isIsCompleted(),
        //     ];
        // }
        // return $this->json([
        //     'message' => 'Voici toutes vos tâches',
        //     'path' => 'src/Controller/TaskController.php',
        //     'tasks' => $tasksArray,
        // ]);
    // }


    #[Route('/new', name: 'app_new', methods: ['POST', 'GET'])]
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
    
    return $this->json([
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

