<?php
if (count($argv) <= 1) {
    echoError("Введено недостаточное количество аргументов");
    exit;
}

require_once "class/TaskManager.php";

$fileName = "tasks.json";
// $fileName = "";
$action = $argv[1];
$taskManager = new TaskManager($fileName);

switch ($action)
{
    case "add":
        $description = $argv[2];
        if (!$description)
        {
            echoError("Не введено описание задачи");
            exit;
        }
        
        $taskManager->addTask($description);
        break;

    case "update":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Неверный тип номера задачи");
            exit;
        }

        $description = $argv[3];
        if (!$description)
        {
            echoError("Не введено описание задачи");
            exit;
        }

        $taskManager->updateTask($taskId, $description);
        break;

    case "delete":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Неверный тип номера задачи");
            exit;
        }
        $taskManager->deleteTask($taskId, $description);
        break;

    case "mark-in-progress":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Неверный тип номера задачи");
            exit;
        }
        $taskManager->markTask($taskId, "in-progress");
        break;

    case "mark-done":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Неверный тип номера задачи");
            exit;
        }
        $taskManager->markTask($taskId, "done");
        break;
    case "list":
        $status = $argv[2] ?? "";
        $list = $taskManager->getTasksList($status);
        var_dump($list);
        break;
    
    default:
        echoError("Действие не найдено");
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