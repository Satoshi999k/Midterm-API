<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, x-api-key');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

define('API_KEY', 'bytebride-secret-key-2024');
define('JWT_SECRET', 'bytebride-jwt-secret-2024');
define('TASKS_FILE', __DIR__ . '/tasks.json');

// Load tasks from JSON file or use default
function loadTasks() {
    if (file_exists(TASKS_FILE)) {
        $json = file_get_contents(TASKS_FILE);
        $tasks = json_decode($json, true);
        return is_array($tasks) ? $tasks : getDefaultTasks();
    }
    return getDefaultTasks();
}

function getDefaultTasks() {
    return [];
}

function saveTasks($tasks) {
    file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}

$tasks = loadTasks();

function authenticateRequest() {
    if (isset($_SERVER['HTTP_X_API_KEY']) && $_SERVER['HTTP_X_API_KEY'] === API_KEY) {
        return true;
    }
    
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
            $token = $matches[1];
            if (validateJWT($token)) {
                return true;
            }
        }
    }
    
    return false;
}

function validateJWT($token) {
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return false;
    }
    
    $payload = json_decode(base64_decode($parts[1]), true);
    if ($payload && isset($payload['verified'])) {
        return true;
    }
    
    return false;
}

function generateJWT() {
    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode(['verified' => true, 'iat' => time()]));
    $signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    return "$header.$payload.$signature";
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_REQUEST['path'] ?? $_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/midterm/api/', '', $path);

if ($path === 'tasks' || $path === 'tasks/') {
    if ($method === 'GET') {
        handleGetTasks();
    } elseif ($method === 'POST') {
        handlePostTask();
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} elseif (preg_match('/^tasks\/(\d+)$/', $path, $matches)) {
    if ($method === 'DELETE') {
        handleDeleteTask($matches[1]);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} elseif ($path === 'login' || $path === 'login/') {
    if ($method === 'POST') {
        handleLogin();
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}

function handleGetTasks() {
    global $tasks;
    
    if (!authenticateRequest()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized', 'message' => 'Valid token or API key required']);
        return;
    }
    
    http_response_code(200);
    echo json_encode(['success' => true, 'data' => $tasks]);
}

function handlePostTask() {
    global $tasks;
    
    if (!authenticateRequest()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized', 'message' => 'Valid token or API key required']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || empty($data['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Bad request', 'message' => 'ID is required']);
        return;
    }
    
    if (!isset($data['title']) || empty($data['title'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Bad request', 'message' => 'Title is required']);
        return;
    }
    
    // Check if ID already exists
    $idExists = false;
    foreach ($tasks as $task) {
        if ($task['id'] == $data['id']) {
            $idExists = true;
            break;
        }
    }
    
    if ($idExists) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Bad request', 'message' => 'Task ID already exists']);
        return;
    }
    
    $newTask = [
        'id' => (int)$data['id'],
        'title' => $data['title'],
        'completed' => $data['completed'] ?? false
    ];
    
    $tasks[] = $newTask;
    saveTasks($tasks);
    
    http_response_code(201);
    echo json_encode(['success' => true, 'message' => 'Task created successfully', 'data' => $newTask]);
}

function handleDeleteTask($id) {
    global $tasks;
    
    if (!authenticateRequest()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized', 'message' => 'Valid token or API key required']);
        return;
    }
    
    $taskIndex = array_search($id, array_column($tasks, 'id'));
    
    if ($taskIndex === false) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found', 'message' => 'Task not found']);
        return;
    }
    
    $deletedTask = $tasks[$taskIndex];
    array_splice($tasks, $taskIndex, 1);
    saveTasks($tasks);
    
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Task deleted', 'data' => $deletedTask]);
}

function handleLogin() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['username']) && isset($data['password'])) {
        $token = generateJWT();
        http_response_code(200);
        echo json_encode(['success' => true, 'token' => $token]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Bad request', 'message' => 'Username and password required']);
    }
}
?>
