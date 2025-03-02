<?php

/**
* * Class TaskManager
*
* Manages tasks stored in a JSON file.
*/
class TaskManager
{
    /**
     * @var resource resource Pointer to the opened file.
    */
    private $fp;
    /**
     * @var string Path to the task file.
    */
    private $taskFilePath;

    /**
     * TaskManager constructor.
     * 
     * @param string $path Path to the task file.
     * @throws InvalidArgumentException If the path is not specified.
     * @throws RuntimeException If the file cannot be opened.
    */
    public function __construct(string $path = "")
    {
        $this->taskFilePath = $path;
        if (!$this->taskFilePath)
        {
            throw new InvalidArgumentException("The path to the task file is not specified");
        }
        
        if (!$this->fp = fopen($this->taskFilePath, 'a'))
        {
            throw new RuntimeException("Cannot open file ($this->taskFilePath)");
        }
    }

    /**
     * Adds a new task.
     * 
     * @param string $description Task description.
     * @return array Operation result.
    */  
    public function addTask($description)
    {
        $tasks = $this->importTasks();
        $newTask = [
            "id" => count($tasks),
            "description" => $description,
            "status" => "todo",
            "createdAt" => time(),
            "updatedAt" => time(),
        ];
        $tasks[count($tasks)] = $newTask;
        
        $this->saveTasks($tasks);
        return ["status" => "success", "code" => 200, "message" => "Added a new task"];
    }
    
    /**
     * Updates a task's description.
     * 
     * @param int $id Task ID.
     * @param string $description New task description.
     * @return array Operation result.
    */
    public function updateTask($id, $description)
    {
        $tasks = $this->importTasks();
        if (!$tasks[$id]) {
            return ["status" => "error", "code" => 404, "message" => "The task with the $id number does not exist"];
        }
        $tasks[$id]["description"] = $description;
        $tasks[$id]["updatedAt"] = time();

        $this->saveTasks($tasks);
        return ["status" => "success", "code" => 200, "message" => "Task description $id updated"];
    }

    /**
     * Deletes a task.
     * 
     * @param int $id Task ID.
     * @return array Operation result.
     */
    public function deleteTask($id)
    {
        $tasks = $this->importTasks();
        if (!$tasks[$id]) {
            return ["status" => "error", "code" => 404, "message" => "The task with the $id number does not exist"];
        }
        
        unset($tasks[$id]);
        
        $this->saveTasks($tasks);
        return ["status" => "success", "code" => 200, "message" => "Task $id deleted"];
    }

    /**
     * Changes a task's status.
     * 
     * @param int $id Task ID.
     * @param string $status New task status.
     * @return array Operation result.
     */
    public function markTask($id, $status)
    {
        $tasks = $this->importTasks();
        
        if (!$tasks[$id]) {
            return ["status" => "error", "code" => 404, "message" => "The task with the $id number does not exist"];
        }
        
        $tasks[$id]["status"] = $status;
        $tasks[$id]["updatedAt"] = time();

        $this->saveTasks($tasks);
        return ["status" => "success", "code" => 200, "message" => "The task status $id has been changed to '$status'"];
    }

    /**
     * Returns a list of tasks.
     * 
     * @param string $status Filter by task status.
     * @return array List of tasks.
    */
    public function getTasksList($status = "")
    {
        $tasks = $this->importTasks();
        $output = [];
        if ($status) {
            foreach ($tasks as $key => $task) {
                if ($task["status"] == $status) {
                    $output[] = $task;
                }
            }
            return $output;
        }
        return $tasks;
    }

    /**
     * Imports tasks from the file.
     * 
     * @return array Array of tasks.
    */
    private function importTasks()
    {
        $content = file_get_contents($this->taskFilePath);
        $tasks = json_decode($content, true) ?? [];
        return $tasks;
    } 

    /**
     * Saves tasks to the file.
     * 
     * @param array $tasks Array of tasks.
     */
    private function saveTasks(array $tasks): void
    {
        file_put_contents($this->taskFilePath, json_encode($tasks));
    }
    
    /**
     * Closes the file when the object is destroyed.
     */
    public function __destruct()
    {
        if ($this->fp) {
            fclose($this->fp);
        }
    }
}