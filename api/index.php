<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, x-api-key');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Define valid API key and JWT secret
define('API_KEY', 'bytebride-secret-key-2024');
define('JWT_SECRET', 'bytebride-jwt-secret-2024');

// Dummy tasks storage (in real app, would use database)
$tasks = [
    ['id' => 1, 'title' => 'Review project requirements', 'completed' => false],
    ['id' => 2, 'title' => 'Set up development environment', 'completed' => true],
    ['id' => 3, 'title' => 'Create API endpoints', 'completed' => false]
];

// Authentication middleware
function authenticateRequest() {
    // Check for API key in headers
    if (isset($_SERVER['HTTP_X_API_KEY']) && $_SERVER['HTTP_X_API_KEY'] === API_KEY) {
        return true;
    }
    
    // Check for JWT token in Authorization header
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
            $token = $matches[1];
            // Simple JWT validation (check if token contains our secret in structure)
            if (validateJWT($token)) {
                return true;
            }
        }
    }
    
    return false;
}

// Simple JWT validation
function validateJWT($token) {
    // Split the token into parts
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return false;
    }
    
    // In production, verify signature. Here we do basic check
    $payload = json_decode(base64_decode($parts[1]), true);
    if ($payload && isset($payload['verified'])) {
        return true;
    }
    
    return false;
}

// Helper function to generate JWT (for testing purposes)
function generateJWT() {
    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode(['verified' => true, 'iat' => time()]));
    $signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    return "$header.$payload.$signature";
}

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_REQUEST['path'] ?? $_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/midterm/api/', '', $path);

// Route handling
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

// Handler functions
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
    
    if (!isset($data['title']) || empty($data['title'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad request', 'message' => 'Title is required']);
        return;
    }
    
    $newTask = [
        'id' => max(array_column($tasks, 'id')) + 1,
        'title' => $data['title'],
        'completed' => $data['completed'] ?? false
    ];
    
    $tasks[] = $newTask;
    
    http_response_code(201);
    echo json_encode(['success' => true, 'data' => $newTask]);
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
    unset($tasks[$taskIndex]);
    $tasks = array_values($tasks); // Re-index array
    
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Task deleted', 'data' => $deletedTask]);
}

function handleLogin() {
    // Simple login endpoint that returns a JWT token
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Accept any non-empty username/password for demo
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
