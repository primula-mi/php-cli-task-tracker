<?php
if (count($argv) <= 1) {
    echoError("Введено недостаточное количество аргументов");
    exit;
}

$fileName = "tasks.json";
$action = $argv[1];

switch ($action)
{
    case "add":
        $description = $argv[2];
        if (!$description)
        {
            echoError("Не введено описание задачи");
            exit;
        }
        addTask($description);
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
        updateTask($taskId, $description);
        break;

    case "delete":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Неверный тип номера задачи");
            exit;
        }

    case "mark-in-progress":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Неверный тип номера задачи");
            exit;
        }

    case "mark-done":
        $taskId = $argv[2];
        if (!is_numeric($taskId))
        {
            echoError("Неверный тип номера задачи");
            exit;
        }
    case "list":
    
    default:
        echoError("Действие не найдено");
        exit;
        break;
}

function addTask($description)
{
    global $fileName;
    
    if (!$fp = fopen($fileName, 'a'))
    {
        echoError("Cannot open file ($fileName)");
        exit;
    }
    $content = file_get_contents($fileName);
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
    file_put_contents($fileName, $content);
    fclose($fp);
}

function updateTask($id, $description)
{
    global $fileName;
    
    if (!$fp = fopen($fileName, 'a'))
    {
        echoError("Cannot open file ($fileName)");
        exit;
    }
    $content = file_get_contents($fileName);
    $tasks = json_decode($content) ?? [];
    if (!$tasks[$id]) {
        echoError("Задачи с номером $fileName не существует");
        exit;
    }
    $tasks[$id]->description = $description;
    $tasks[$id]->updatedAt = strtotime("now");
    $content = json_encode($tasks);
    file_put_contents($fileName, $content);
    fclose($fp);
}


function echoError($message)
{
    echo "\e[1;31m{$message}\e[0m\n";
}

function echoSuccess($message)
{
    echo "\e[1;32m{$message}\e[0m\n";
}