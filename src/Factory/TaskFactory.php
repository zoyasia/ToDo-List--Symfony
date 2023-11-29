<?php

namespace App\Factory\TaskFactory;

use App\Entity\Task;

class TaskFactory
{
    public static function createTask(array $data): Task
    {
        $task = new Task();
        $task
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setStatus($data['status'] ?? 'à faire')
            ->setDeadline($data['deadline'])
            ->setIsCompleted($data['isCompleted'] ?? false);

        return $task;
    }
}
