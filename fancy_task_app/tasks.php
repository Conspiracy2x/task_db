<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = $_POST["task"];
    
    
    if (empty($task)) {
        $error_message = "Task name cannot be empty.";
    } else {
        $sql = "INSERT INTO tasks (task, is_favorite) VALUES ('$task', 0)";
        $conn->query($sql);
        header("Location: tasks.php");  
        exit();
    }
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM tasks WHERE id = $id";
    $conn->query($sql);
    header("Location: tasks.php");  
    exit();
}


if (isset($_GET['favorite'])) {
    $id = $_GET['favorite'];
    $sql = "UPDATE tasks SET is_favorite = NOT is_favorite WHERE id = $id";
    $conn->query($sql);
    header("Location: tasks.php");  
    exit();
}


$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);

$tasks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
            body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(120deg, #a1c4fd, #c2e9fb);
            animation: gradient 5s ease infinite;
        }

        /* Animated background */
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Container styling */
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            padding: 40px;
            margin-top: 50px;
        }
       
        .task-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Task App</h1>

        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        
        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#taskModal">Add New Task</button>

        
        <div class="row">
            <?php foreach ($tasks as $task): ?>
                <div class="col-md-4 mb-4">
                    <div class="card task-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php if ($task['is_favorite']): ?>
                                    <span class="favorite-star">★</span>
                                <?php endif; ?>
                                <?php echo $task["task"]; ?>
                            </h5>
                            <div class="card-actions">
                                <a href="?delete=<?php echo $task['id']; ?>" class="btn btn-danger">Delete</a>
                                <a href="?favorite=<?php echo $task['id']; ?>" class="btn btn-warning">Favorite</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        
        <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalLabel">Add New Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="task" class="form-label">Task Name</label>
                                <input type="text" name="task" class="form-control" id="task">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Task</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
</body>
</html>