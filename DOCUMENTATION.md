# ByteBridge Task Manager - Testing & Security Documentation

## Part 1: API Implementation Summary

### Endpoints Created:
1. **GET /midterm/api/tasks** - Retrieve all tasks
2. **POST /midterm/api/tasks** - Create a new task
3. **DELETE /midterm/api/tasks/{id}** - Delete a task by ID
4. **POST /midterm/api/login** - Get JWT token

### HTTP Methods Used:

**GET /tasks**
We use GET for retrieving tasks because it's designed for fetching data without changing anything on the server. When you want to see what tasks exist, you use GET. It's safe—meaning your request won't accidentally modify the database. It's also idempotent, so hitting the same endpoint multiple times gives you the same result. You can bookmark a GET URL and nothing bad happens.

**POST /tasks**
We use POST when creating a new task because POST is meant for sending data to the server that creates a new resource. Each time you POST a new task, you get a new entry in the database. This is why it's not idempotent—if you accidentally send the same POST request twice, you'll end up with two identical tasks. That's different from GET, where sending the same request a hundred times just shows you the same data.

**DELETE /tasks/{id}**
We use DELETE for removing tasks because DELETE is specifically designed for destructive operations—it tells the server "remove this resource." Using DELETE makes it clear in the code and to anyone reading the API that this operation will permanently delete data. It's idempotent because deleting the same task twice is safe—the first request removes it, and the second request just returns "not found" but doesn't cause an error.

### 2. JSON Response Structure & Data Design

**How We Structure Our JSON Responses:**

Every response from our API follows a consistent format with three main parts: a success flag to tell the frontend whether the request worked, a data object containing the actual information, and a message for additional context. This consistent structure makes it easy for the frontend to know exactly where to find information.

When you ask for tasks, here's what you get:

```json
{
  "success": true,
  "message": "Tasks retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Review project requirements",
      "completed": false
    },
    {
      "id": 2,
      "title": "Submit final report",
      "completed": true
    }
  ]
}
```

**Why This Structure?**

- **success**: The frontend can check this boolean to know if something went wrong without parsing the entire response
- **message**: Gives the user-friendly description of what happened
- **data**: Contains the actual task objects with id, title, and completed status

When creating a new task, you get back the newly created task:

```json
{
  "success": true,
  "message": "Task created successfully",
  "data": {
    "id": 3,
    "title": "New task title",
    "completed": false
  }
}
```

When there's an error, the structure stays the same but success is false:

```json
{
  "success": false,
  "message": "Unauthorized: Missing authentication",
  "data": null
}
```

---

### 3. HTTP Status Codes & When They're Used

**Understanding Status Codes:**

Status codes are like traffic lights for your API. They tell the client what happened with their request at a glance.

**Success Status Codes (200-299):**

**200 OK** - This means the request succeeded and everything is good. We return 200 when:
- You GET tasks (the request worked, here's your data)
- You DELETE a task (the deletion happened successfully)

**201 Created** - This is a special success code that says "not only did your request succeed, but a new resource was created on the server." We return 201 when:
- You POST a new task (the task was successfully added to the database)

**Error Status Codes (400-499):**

**400 Bad Request** - This means the client sent something wrong in their request. We return 400 when:
- You try to create a task but forget to include the title (missing required data)
- You send malformed JSON that we can't parse
- The input data doesn't meet our requirements

**401 Unauthorized** - This means the client needs to authenticate but hasn't provided valid credentials. We return 401 when:
- You try to view, create, or delete tasks WITHOUT providing an API key or JWT token
- You provide a token that's invalid or expired
- The credentials don't match what we have on file

**404 Not Found** - This means the server couldn't find the resource you're asking for. We return 404 when:
- You try to DELETE task #999 but only tasks 1, 2, and 3 exist
- The endpoint path doesn't exist

**405 Method Not Allowed** - This means you used the wrong HTTP method. We return 405 when:
- You try to POST to /tasks/{id} (should use DELETE instead)
- You try to GET to /login (should use POST)

**Code Example:**
```php
// Return 200 for successful GET
http_response_code(200);

// Return 201 for successfully creating
http_response_code(201);

// Return 400 for bad data
http_response_code(400);

// Return 401 for missing authentication
http_response_code(401);

// Return 404 for not found
http_response_code(404);
```

---

### 4. Endpoint Routing Design

**How Routing Works in Our API:**

When a request comes to our API, it goes through several steps to figure out where to send it. Here's the journey:

**Step 1: Parse the Request Path**

First, we look at what the client is asking for. The URL might be `/api/tasks` or `/api/tasks/5`. We extract just the path part and remove extra query parameters.

```php
// GET request to /api/tasks?debug=1
// We extract: "tasks"

// DELETE request to /api/tasks/5
// We extract: "tasks/5"
```

**Step 2: Determine the HTTP Method**

Next, we check what type of operation they want: GET, POST, or DELETE. This tells us what action to perform.

**Step 3: Match the Pattern & Route to Handler**

This is where the magic happens. We match the path against our endpoint patterns and call the right function:

```php
// If path is "tasks" and method is "GET"
→ Call handleGetTasks()

// If path is "tasks" and method is "POST"
→ Call handlePostTask()

// If path is "tasks/5" and method is "DELETE"
→ Call handleDeleteTask(5)

// If path is "login" and method is "POST"
→ Call handleLogin()
```

**How It's Implemented:**

```php
$path = parse_url($_SERVER['REQUEST_URI'])['path'];
$path = str_replace('/midterm/api/', '', $path);
$method = $_SERVER['REQUEST_METHOD'];

if ($path === 'tasks' && $method === 'GET') {
    handleGetTasks();
} elseif ($path === 'tasks' && $method === 'POST') {
    handlePostTask();
} elseif (strpos($path, 'tasks/') === 0 && $method === 'DELETE') {
    handleDeleteTask(substr($path, 6)); // Extract the ID
} elseif ($path === 'login' && $method === 'POST') {
    handleLogin();
}
```

---

### 5. Separation of Logic (Controller) from Data

**Architecture Overview:**

Our API separates concerns into distinct layers:

**Layer 1: Data Storage**

```php
// This is our "database" - just an array of tasks
$tasks = [
    ["id" => 1, "title" => "Review requirements", "completed" => false],
    ["id" => 2, "title" => "Write code", "completed" => false],
    ["id" => 3, "title" => "Test API", "completed" => true]
];
```

The data storage is completely separate. If we wanted to switch from an array to a real database like MySQL, we'd only change this part, not the business logic.

**Layer 2: Business Logic (Controllers/Handlers)**

Each handler function contains the logic for what to do:

```php
function handleGetTasks() {
    global $tasks;
    // Logic: Check if user is authenticated
    if (!authenticateRequest()) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        return;
    }
    // Logic: Return the tasks
    http_response_code(200);
    echo json_encode(["success" => true, "data" => $tasks]);
}

function handlePostTask() {
    global $tasks;
    // Logic: Authenticate
    if (!authenticateRequest()) {
        http_response_code(401);
        return;
    }
    // Logic: Validate input (title required)
    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input['title'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Title required"]);
        return;
    }
    // Logic: Create new task
    $newTask = [
        "id" => count($tasks) + 1,
        "title" => $input['title'],
        "completed" => $input['completed'] ?? false
    ];
    $tasks[] = $newTask;
    http_response_code(201);
    echo json_encode(["success" => true, "data" => $newTask]);
}
```

**Layer 3: Helper Functions**

Functions that do one job really well:

```php
// Authentication helper - checks credentials
function authenticateRequest() {
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? null;
    $token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    
    if ($apiKey === 'bytebride-secret-key-2024') {
        return true;
    }
    if (validateJWT($token)) {
        return true;
    }
    return false;
}

// JWT validation helper
function validateJWT($token) {
    if (!$token) return false;
    $token = str_replace('Bearer ', '', $token);
    $parts = explode('.', $token);
    return count($parts) === 3; // Has 3 parts: header.payload.signature
}
```

**Why This Matters:**

- **Data Layer**: Can be swapped out without touching code logic
- **Handler Layer**: Contains all business rules (who can do what, validation)
- **Helper Layer**: Reusable functions that multiple handlers can use

If you need to add a database, you only change the $tasks array initialization. If you need to change authentication, you only touch authenticateRequest(). This is clean architecture.

---

## Part 2: Frontend Implementation

### HTML Features:
- Three main buttons: View Tasks, Add Task, Delete Task
- Authentication display showing current API key and JWT token
- Simple task list display with delete options
- JSON response viewer for debugging
- Error handling with visual feedback

### JavaScript Implementation:
- Fetch API usage for all HTTP requests
- Header management for authentication (x-api-key and Authorization)
- Token retrieval via login endpoint
- Dynamic result display

---

## Part 3: Authentication & Security

### Security Implementation:

#### 1. Two Authentication Methods Supported:
```
Method 1 - API Key Header:
Header: x-api-key: bytebride-secret-key-2024

Method 2 - JWT Token Header:
Header: Authorization: Bearer <JWT_TOKEN>
```

#### 2. Token Verification Process:
- Check for x-api-key header first
- If not found, parse Authorization Bearer token
- Validate JWT structure (has 3 parts: header.payload.signature)
- Check payload for 'verified' claim

#### 3. Protected Endpoints:
All /tasks endpoints require authentication:
- GET /tasks → 401 if unauthorized
- POST /tasks → 401 if unauthorized
- DELETE /tasks/{id} → 401 if unauthorized

### Status Codes:
- **200 OK**: Successful GET, DELETE request
- **201 Created**: Successful POST request
- **400 Bad Request**: Invalid data (e.g., missing title)
- **401 Unauthorized**: Missing/invalid token or API key
- **404 Not Found**: Task ID doesn't exist
- **405 Method Not Allowed**: Wrong HTTP method for endpoint

---

## Testing Instructions

### Test Scenario 1: Unauthorized Access (Should FAIL)
1. Open frontend at: http://localhost/midterm/frontend/
2. Click "View Tasks" WITHOUT getting a token
3. Expected Result: Error message showing 401 Unauthorized

**Screenshot Target**: Shows failed request without authentication

### Test Scenario 2: Authorized Access with API Key (Should SUCCEED)
1. Click "View Tasks" 
2. Should display tasks because code uses x-api-key by default
3. Expected Result: List of all tasks displayed successfully

**Screenshot Target**: Shows successful task retrieval

### Test Scenario 3: Authorized Access with JWT Token (Should SUCCEED)
1. Click "Get JWT Token" button
2. Token appears in display area
3. Click "View Tasks"
4. Expected Result: Tasks displayed successfully using JWT

**Screenshot Target**: Shows JWT token and successful retrieval

---

## Security Reflection: Almost-Made Mistakes

### Security Mistake #1: Storing Plaintext Credentials
During implementation, the initial thought was to store all API keys and secrets directly in the code without any encryption. This was avoided by using environment-based approach where secrets are defined as constants. However, in production, these should be stored in environment variables (.env files) that are never committed to version control. Using plaintext credentials in source code exposes them if the repository is leaked or accessed by unauthorized personnel.

### Security Mistake #2: Missing CORS Protection
Originally, the API had overly permissive CORS headers allowing requests from ANY origin. This was corrected by carefully controlling the Access-Control-Allow-Origin header. In production, this should specify only the exact domain serving the frontend (e.g., 'https://yourdomain.com') rather than '*', preventing cross-site request attacks from malicious domains.

### Security Mistake #3: JWT Signature Validation Incomplete
The JWT validation initially only checked the token structure without verifying the cryptographic signature. While the simplified version checks for the 'verified' claim, a production system MUST use HMAC-SHA256 to verify the signature matches the secret key, preventing token tampering. An attacker could otherwise forge tokens by modifying the payload without detection.

