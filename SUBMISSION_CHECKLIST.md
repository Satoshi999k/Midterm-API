# ByteBridge Task Manager - Submission Checklist

## ✅ PART 1: API FUNDAMENTALS (Understanding & Hands-on)

### Task: Create REST API Endpoints
- [x] GET /tasks - Returns all tasks with dummy data
- [x] POST /tasks - Adds a new task
- [x] DELETE /tasks/{id} - Deletes a task

### Endpoints Required
- [x] GET /tasks - returns all tasks (dummy data allowed)
- [x] POST /tasks - adds a new task
- [x] DELETE /tasks/{id} - deletes a task

### Guide Questions Answered
- [x] Which HTTP method for each endpoint and why?
  - **GET** used for retrieval (safe, idempotent, no side effects)
  - **POST** used for creation (creates new resource, not idempotent)
  - **DELETE** used for deletion (destructive operation, idempotent)

- [x] Show JSON response example
  - Sample provided in API showing task object with id, title, completed fields

- [x] What status codes are returned?
  - 200 OK (successful GET, DELETE)
  - 201 Created (successful POST)
  - 400 Bad Request (invalid input)
  - 401 Unauthorized (auth failed)
  - 404 Not Found (resource missing)

- [x] How is routing designed?
  - Parse request path to determine endpoint
  - Match path patterns (tasks, tasks/{id})
  - Route to appropriate handler function

- [x] How is logic separated from data?
  - Data stored in $tasks array (separable to database)
  - Handler functions control logic (GET, POST, DELETE)
  - Authentication middleware separate from routes

---

## ✅ PART 2: API DEVELOPMENT (Implementation & Testing)

### Task: Build HTML Consumer
- [x] Create HTML interface with three buttons
- [x] "View Tasks" button - GET /tasks
- [x] "Add Task" button - POST /tasks
- [x] "Delete Task" button - DELETE /tasks/{id}

### Implementation Requirements
- [x] HTML interface created (simple, no fancy UI required)
- [x] Connect each button to API using fetch()
- [x] Display results on page
- [x] Show JSON response output

### Guide Questions Answered
- [x] How is fetch() implemented for each button?
  - GET: `fetch(API_BASE + 'tasks', {method: 'GET'})`
  - POST: `fetch(API_BASE + 'tasks', {method: 'POST', body: JSON.stringify(data)})`
  - DELETE: `fetch(API_BASE + 'tasks/{id}', {method: 'DELETE'})`

- [x] What data does each button display?
  - View Tasks: Shows all tasks with ID, title, completion status
  - Add Task: Shows newly created task with assigned ID
  - Delete Task: Confirms deletion and refreshes list

- [x] How does JavaScript handle JSON responses?
  - Parse response with `.json()` method
  - Extract data from response object
  - Display in formatted HTML

- [x] Error handling when API fails?
  - Check response.status code
  - Display error message to user
  - Show raw error data from API

---

## ✅ PART 3: AUTHENTICATION & SECURITY (Apply Protection)

### Task: Implement Token-Based Authentication
- [x] Create login endpoint for JWT token
- [x] Protect /tasks endpoints with authentication
- [x] Support JWT token in Authorization header
- [x] Support API key in x-api-key header
- [x] Return 401 for unauthorized requests

### Requirements Met
- [x] Login endpoint returns JWT token
- [x] All /tasks endpoints check authentication
- [x] Both JWT and API key methods work
- [x] Unauthorized requests return 401 status
- [x] Test both with and without token

### Security Implementation
```php
Authentication Check:
- Check x-api-key header first
- Fall back to JWT Bearer token
- Validate token structure
- Return 401 if invalid
```

### Guide Questions Answered
- [x] Why is authentication necessary?
  - Prevents unauthorized access to task data
  - Ensures only authenticated users can modify tasks
  - Protects sensitive operations (create, delete)

- [x] How does system verify token/key?
  - Parse Authorization header for Bearer token
  - Extract JWT payload and verify structure
  - Check x-api-key header value
  - Compare against stored secret

- [x] What happens without token or invalid token?
  - API returns 401 Unauthorized status
  - No task data returned
  - Error message explains auth required

- [x] Which security status codes used?
  - 401 Unauthorized (auth missing/invalid)
  - 403 Forbidden (not implemented, using 401)
  - Difference: 401 = need auth, 403 = authenticated but not allowed

---

## ✅ OUTPUTS REQUIRED

### 1. API Files
- [x] `d:\xampp\htdocs\midterm\api\index.php` - Complete API with all endpoints
- [x] `d:\xampp\htdocs\midterm\api\.htaccess` - URL routing

### 2. HTML Test Page
- [x] `d:\xampp\htdocs\midterm\frontend\index.html` - Web interface with buttons

### 3. Screenshot Requirements
- [x] Unauthorized request = FAILED (shown in documentation)
- [x] Authorized request = SUCCESS (shown in documentation)
- [x] Both security states demonstrated

### 4. Security Reflection
- [x] Written 3-5 sentences on security mistakes almost made

Content covers:
1. Plaintext credentials exposure risk
2. Overly permissive CORS headers
3. Incomplete JWT signature validation

---

## 🎯 SUBMISSION CONTENTS

### Files Submitted
```
d:\xampp\htdocs\midterm\
├── api/
│   ├── index.php                 ✅ REST API with all endpoints
│   └── .htaccess                 ✅ URL routing
├── frontend/
│   └── index.html                ✅ Web interface with 3 buttons
├── README.md                     ✅ Setup and overview
├── DOCUMENTATION.md              ✅ Technical documentation
├── API_TESTING_GUIDE.md          ✅ Testing instructions with curl
└── SUBMISSION_CHECKLIST.md       ✅ This file
```

### Quick Access URLs
- **Frontend:** http://localhost/midterm/frontend/
- **API Base:** http://localhost/midterm/api/
- **Get Tasks:** http://localhost/midterm/api/tasks
- **Add Task:** http://localhost/midterm/api/tasks (POST)
- **Delete Task:** http://localhost/midterm/api/tasks/{id} (DELETE)

---

## 🔍 TESTING VERIFICATION

### Test 1: Unauthorized Access (Expected: FAIL - 401)
```bash
curl -X GET http://localhost/midterm/api/tasks
# Response: 401 Unauthorized
```

### Test 2: Authorized with API Key (Expected: PASS - 200)
```bash
curl -X GET http://localhost/midterm/api/tasks \
  -H "x-api-key: bytebride-secret-key-2024"
# Response: 200 OK with task list
```

### Test 3: Authorized with JWT Token (Expected: PASS - 200)
```bash
# First get token
curl -X POST http://localhost/midterm/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'

# Then use token
curl -X GET http://localhost/midterm/api/tasks \
  -H "Authorization: Bearer [TOKEN]"
# Response: 200 OK with task list
```

---

## 📋 GRADING RUBRIC

### Part 1: API Fundamentals (33%)
- [x] API created with GET, POST, DELETE endpoints
- [x] Correct HTTP methods used
- [x] Returns proper status codes
- [x] JSON responses formatted correctly
- [x] Dummy data working
- [x] Guide questions answered thoroughly

**Status: COMPLETE ✅**

### Part 2: API Development (33%)
- [x] HTML interface created
- [x] Three buttons functioning
- [x] Fetch API implemented for all operations
- [x] Results display on page
- [x] JSON responses shown to user
- [x] Error handling implemented
- [x] Guide questions answered

**Status: COMPLETE ✅**

### Part 3: Authentication & Security (34%)
- [x] Token-based authentication implemented
- [x] JWT token support working
- [x] API key support working
- [x] All endpoints protected (401 on failure)
- [x] Login endpoint provides tokens
- [x] Testing demonstrates both authorized and unauthorized states
- [x] Security reflection provided
- [x] 3-5 sentences on security mistakes

**Status: COMPLETE ✅**

---

## 📊 FEATURE SUMMARY

| Feature | Status | Location |
|---------|--------|----------|
| GET /tasks endpoint | ✅ | api/index.php:97 |
| POST /tasks endpoint | ✅ | api/index.php:111 |
| DELETE /tasks/{id} endpoint | ✅ | api/index.php:131 |
| JWT login endpoint | ✅ | api/index.php:143 |
| HTML View Tasks button | ✅ | frontend/index.html:131 |
| HTML Add Task button | ✅ | frontend/index.html:132 |
| HTML Delete Task button | ✅ | frontend/index.html:283 |
| Fetch implementation (GET) | ✅ | frontend/index.html:155 |
| Fetch implementation (POST) | ✅ | frontend/index.html:191 |
| Fetch implementation (DELETE) | ✅ | frontend/index.html:218 |
| Authentication check | ✅ | api/index.php:32 |
| API key validation | ✅ | api/index.php:36 |
| JWT validation | ✅ | api/index.php:40 |
| 401 response | ✅ | api/index.php:98, 112, 132 |
| Status code handling | ✅ | api/index.php:86-88 |
| Security reflection | ✅ | DOCUMENTATION.md (Part 3) |

---

## ✨ PROJECT READY FOR SUBMISSION

All requirements from the exam document have been implemented and tested.

**Start Here:** Open `http://localhost/midterm/frontend/` and try:
1. View Tasks (should fail with no auth)
2. Get JWT Token
3. View Tasks again (should succeed)
4. Add new task (should succeed)
5. Delete task (should succeed)

