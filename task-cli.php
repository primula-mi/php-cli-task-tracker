<?php
if (count($argv) <= 1) {
    echoError("Insufficient number of arguments entered");
    exit;
}

require_once "class/TaskManager.php";

$fileName = "tasks.json";
$action = $argv[1];
$taskManager = new TaskManager($fileName);

switch ($action)
{
    case "add":
        $description = $argv[2];
        if (!$description)
        {
            echoError("Task description not entered");
            exit;
        }
        
        $responce = $taskManager->addTask($description);
        echoResponce($responce);
        break;

    case "update":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Incorrect issue number type");
            exit;
        }

        $description = $argv[3];
        if (!$description)
        {
            echoError("Task description not entered");
            exit;
        }

        $responce = $taskManager->updateTask((int)$taskId, $description);
        echoResponce($responce);
        break;

    case "delete":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Incorrect issue number type");
            exit;
        }
        $responce = $taskManager->deleteTask((int)$taskId, $description);
        echoResponce($responce);
        break;

    case "mark-in-progress":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Incorrect issue number type");
            exit;
        }
        $responce = $taskManager->markTask((int)$taskId, "in-progress");
        echoResponce($responce);
        break;

    case "mark-done":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Incorrect issue number type");
            exit;
        }
        $responce = $taskManager->markTask((int)$taskId, "done");
        echoResponce($responce);
        break;
    case "list":
        $status = $argv[2] ?? "";
        $tasksList = $taskManager->getTasksList($status);
        if (!count($tasksList)) {
            echoError("The list is empty");
            exit;
        }
        echo "Task list\n";
        echo taskTable($tasksList);
        break;
    default:
        echoError("Action not found");
        exit;
        break;
}

function echoError($message)
{
    echo "\e[1;31m{$message}\e[0m\n";
}

function echoSuccess($message)
{
    echo "\e[1;32m{$message}\e[0m\n";
}

function echoResponce($responce)
{
    $echoFunction = "echo" . ucfirst($responce["status"]);
    $echoFunction($responce["message"]);
}

function taskTable($tasksList)
{
    $columns = [];
    foreach ($tasksList as $row_key => $row)
    {
        foreach ($row as $cell_key => $cell)
        {
            $length = strlen($cell);
            if (empty($columns[$cell_key]) || $columns[$cell_key] < $length)
            {
             $columns[$cell_key] = $length;
            }
        }
    }
    
    $table = '';
    foreach ($tasksList as $row_key => $row)
    {
        foreach ($row as $cell_key => $cell)
        $table .= str_pad($cell, $columns[$cell_key]) . '   ';
        $table .= PHP_EOL;
    }
    return $table;
     
}