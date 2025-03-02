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
            throw new Exception("The path to the task file is not specified");
        }
        
        if (!$this->fp = fopen($this->taskFilePath, 'a'))
        {
            throw new Exception("Cannot open file ($this->taskFilePath)");
        }
        return true;
    }

    public function addTask($description)
    {
        $tasks = $this->importTasks();
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
        return ["status" => "success", "code" => 200, "message" => "Added a new task"];
    }
    
    public function updateTask($id, $description)
    {
        $tasks = $this->importTasks();
        if (!$tasks[$id]) {
            return ["status" => "error", "code" => 404, "message" => "The task with the $id number does not exist"];
        }
        $tasks[$id]->description = $description;
        $tasks[$id]->updatedAt = strtotime("now");
        $content = json_encode($tasks);
        file_put_contents($this->taskFilePath, $content);
        fclose($this->fp);
        return ["status" => "success", "code" => 200, "message" => "Task description $id updated"];
    }

    public function deleteTask($id)
    {
        $tasks = $this->importTasks();
        if (!$tasks[$id]) {
            return ["status" => "error", "code" => 404, "message" => "The task with the $id number does not exist"];
        }
        
        unset($tasks[$id]);
        
        $content = json_encode($tasks);
        file_put_contents($this->taskFilePath, $content);
        fclose($this->fp);
        return ["status" => "success", "code" => 200, "message" => "Task $id deleted"];
    }

    public function markTask($id, $status)
    {
        $tasks = $this->importTasks();
        
        if (!$tasks[$id]) {
            return ["status" => "error", "code" => 404, "message" => "The task with the $id number does not exist"];
        }
        
        $tasks[$id]->status = $status;
        $tasks[$id]->updatedAt = strtotime("now");
        
        $content = json_encode($tasks);
        file_put_contents($this->taskFilePath, $content);
        fclose($this->fp);
        return ["status" => "success", "code" => 200, "message" => "The task status $id has been changed to'$status'"];
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
        if (!is_array($tasks)) {
            $tasks = get_object_vars($tasks);
        }
        return $tasks;
    } 
}