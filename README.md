# task Tracker

task Tracker is a simple and efficient Command Line Interface (CLI) application designed to help you manage your tasks and to-do lists. With this tool, you can easily add, update, delete, and track the status of your tasks, all from the comfort of your terminal.

## Features

- Add tasks: Quickly add new tasks with a description.
- Update tasks: Modify the description of existing tasks.
- Delete tasks: Remove tasks you no longer need.
- Mark task status: Update the status of tasks (e.g. in progress, done).
- Filter tasks: View tasks based on their status.
- Persistent Storage: tasks are saved in a JSON file, ensuring they persist between sessions.

## Installation

Clone the Repository:

```bash
git clone https://github.com/primula-mi/php-cli-task-tracker.git
cd php-cli-task-tracker
```

## Usage
### 1. Add a task

To add a new task, use the following command:

```bash
php task-cli.php add "Your task description here"
```

### 2. Update a task
To update the description of an existing task, specify the task ID and the new description:

```bash
php task-cli.php update 1 "Updated task description"
```

### 3. Delete a task
To delete a task, specify the task ID:

```bash
php task-cli.php delete 1
```

### 4. Mark "In-pogress" task status
To change the status of a task, specify the task ID:

```bash
php task-cli.php mark-in-progress 1
```

### 5. Mark "Done" task status
To change the status of a task, specify the task ID:

```bash
php task-cli.php mark-done 1
```

### 6. List tasks
To view all tasks, use:

```bash
php task-cli.php list
```

To filter tasks by status, specify the status:

```bash
php task-cli.php list done
```

Enjoy managing your tasks with task Tracker! 🚀