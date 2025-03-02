<?php


class TaskManager
{

    private $fp;
    private $taskFilePath;

    public function __construct(string $path = "")
    {
        $this->taskFilePath = $path;
        if (!$this->taskFilePath)
        {
            throw new Exception("Не указан путь до файла c задачами");
        }
        
        if (!$this->fp = fopen($this->taskFilePath, 'a'))
        {
            throw new Exception("Cannot open file ($this->taskFilePath)");
        }
        return true;
    }

    public function addTask($description)
    {
        $content = file_get_contents($this->taskFilePath);
        $tasks = json_decode($content) ?? [];
        $newTask = [
            "id" => count($tasks),
            "description" => $description,
            "status" => "todo",
            "createdAt" => strtotime("now"),
            "updatedAt" => strtotime("now"),
        ];
        $tasks[count($tasks)] = $newTask;
        $content = json_encode($tasks);
        file_put_contents($this->taskFilePath, $content);
        fclose($this->fp);
        return true;
    }
    
    public function updateTask($id, $description)
    {
        $content = file_get_contents($this->taskFilePath);
        $tasks = json_decode($content) ?? [];
        if (!$tasks[$id]) {
            echoError("Задачи с номером $id не существует");
            exit;
        }
        $tasks[$id]->description = $description;
        $tasks[$id]->updatedAt = strtotime("now");
        $content = json_encode($tasks);
        file_put_contents($this->taskFilePath, $content);
        fclose($this->fp);
    }

    public function deleteTask($id)
    {
        $tasks = $this->importTasks();
        if (!$tasks[$id]) {
            echoError("Задачи с номером $id не существует");
            exit;
        }
        
        unset($tasks[$id]);
        
        $content = json_encode($tasks);
        file_put_contents($this->taskFilePath, $content);
        fclose($this->fp);
    }

    public function markTask($id, $status)
    {
        $tasks = $this->importTasks();
        
        if (!$tasks[$id]) {
            echoError("Задачи с номером $id не существует");
            exit;
        }
        
        $tasks[$id]->status = $status;
        $tasks[$id]->updatedAt = strtotime("now");
        
        $content = json_encode($tasks);
        file_put_contents($this->taskFilePath, $content);
        fclose($this->fp);
    }

    public function getTasksList($status = "")
    {
        $tasks = $this->importTasks();
        $output = [];
        if ($status) {
            foreach ($tasks as $key => $task) {
                if ($task->status == $status) {
                    $output[] = $task;
                }
            }
            return $output;
        }
        else
        {
            return $tasks;
        }
    }

    private function importTasks()
    {
        $content = file_get_contents($this->taskFilePath);
        $tasks = json_decode($content) ?? [];
        return $tasks;
    } 
}