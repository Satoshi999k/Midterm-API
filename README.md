# ByteBridge Task Manager API - Complete Implementation

**Course:** ITPE-130  
**Type:** Activity-Based Examination  
**Project:** "Build, Protect, and Consume Your API"

---

## 🚀 Quick Start

### Prerequisites
- XAMPP installed and running (Apache + PHP)
- Windows environment (d: drive)

### Installation

1. **Verify Files Location:**
   - API: `d:\xampp\htdocs\midterm\api\index.php`
   - Frontend: `d:\xampp\htdocs\midterm\frontend\index.html`
   - Documentation: `d:\xampp\htdocs\midterm\DOCUMENTATION.md`

2. **Start XAMPP:**
   - Open XAMPP Control Panel
   - Start Apache and MySQL (if needed)

3. **Access the Application:**
   - Frontend: `http://localhost/midterm/frontend/`
   - API: `http://localhost/midterm/api/tasks`

---

## 📋 Project Structure

```
d:\xampp\htdocs\midterm\
├── api\
│   ├── index.php                 # Main API with all endpoints
│   └── .htaccess                 # URL rewriting rules
├── frontend\
│   └── index.html                # Web interface with buttons
├── DOCUMENTATION.md              # Detailed documentation
├── API_TESTING_GUIDE.md          # cURL testing commands
└── README.md                     # This file
```

---

## 🏗️ Architecture Overview

### Part 1: API Fundamentals
**File:** `api/index.php`

#### Endpoints:
```
GET  /midterm/api/tasks          → Get all tasks
POST /midterm/api/tasks          → Create new task
DELETE /midterm/api/tasks/{id}   → Delete task
POST /midterm/api/login          → Get JWT token
```

#### Features:
- RESTful design with appropriate HTTP methods
- Dummy data storage (3 sample tasks)
- JSON request/response format
- CORS headers for cross-origin requests
- Comprehensive status codes (200, 201, 400, 401, 404, 405)

---

### Part 2: Frontend Consumer
**File:** `frontend/index.html`

#### Buttons:
1. **View Tasks** - GET request to fetch all tasks
2. **Add Task** - POST request to create new task
3. **Delete Task** - DELETE request to remove task
4. **Get JWT Token** - POST request for authentication

#### Display Features:
- Task list with completed status
- JSON response viewer for debugging
- Error messages with status codes
- Token display area
- Status indicators (success/error)

---

### Part 3: Security & Authentication
**Implemented in:** `api/index.php`

#### Authentication Methods:

##### Method 1: API Key (Header-based)
```
Header: x-api-key: bytebride-secret-key-2024
```

##### Method 2: JWT Token (Bearer Token)
```
Header: Authorization: Bearer <token>
```

#### Protection:
- All `/tasks` endpoints require authentication
- Returns **401 Unauthorized** if credentials missing
- Two parallel validation paths
- Login endpoint provides JWT tokens

#### HTTP Status Codes:
- **200 OK** - Successful GET/DELETE
- **201 Created** - Successful POST
- **400 Bad Request** - Invalid input
- **401 Unauthorized** - Authentication failed
- **404 Not Found** - Resource not found
- **405 Method Not Allowed** - Wrong HTTP method

---

## 🔐 Security Features

### 1. Authentication Separation
Logic is cleanly separated in `authenticateRequest()` function:
- Checks API key first
- Falls back to JWT verification
- Returns true/false for controller routing

### 2. JWT Validation
- Verifies token structure (3 parts)
- Checks payload for 'verified' claim
- Validates using HMAC-SHA256

### 3. CORS Protection
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
```

### 4. Input Validation
```php
if (!isset($data['title']) || empty($data['title'])) {
    // Return 400 Bad Request
}
```

---

## 📊 Testing & Validation

### Using Web Interface:
1. Open `http://localhost/midterm/frontend/`
2. Test without authentication → See 401 error
3. Get JWT token → Copy token from display
4. Use token or API key for authenticated requests
5. Verify success messages

### Using cURL Commands:
See `API_TESTING_GUIDE.md` for complete curl testing instructions with all 9 test scenarios.

### Key Tests:
- ✅ Unauthorized access denial (401)
- ✅ Authorized access with API key (200)
- ✅ JWT token generation (200)
- ✅ Task creation with auth (201)
- ✅ Task deletion with auth (200)
- ✅ Non-existent task handling (404)

---

## 🎯 Exam Requirements Met

### ✅ Part 1: API Fundamentals
- [x] Create simple REST API with PHP
- [x] Implement GET /tasks endpoint
- [x] Implement POST /tasks endpoint
- [x] Implement DELETE /tasks/{id} endpoint
- [x] Return all tasks with dummy data
- [x] Answer guide questions about HTTP methods
- [x] Show JSON response examples
- [x] Explain status codes used
- [x] Document endpoint routing design
- [x] Explain controller/data separation

### ✅ Part 2: API Development & Testing
- [x] Create HTML interface with 3 buttons
- [x] Connect buttons to API with fetch()
- [x] Display results on page
- [x] Implement fetch() for GET, POST, DELETE
- [x] Show data output on page
- [x] Handle JSON responses
- [x] Error handling for failed requests

### ✅ Part 3: Authentication & Security
- [x] Implement token-based authentication
- [x] Support JWT tokens
- [x] Support API key authentication
- [x] Protect all /tasks endpoints
- [x] Create login endpoint
- [x] Test with and without token
- [x] Demonstrate 401 unauthorized responses
- [x] Screenshot showing success vs failure
- [x] Document security decisions
- [x] Reflection on security mistakes

---

## 📝 Files Included

| File | Purpose | Content |
|------|---------|---------|
| `api/index.php` | Main API | All endpoints, auth, routing |
| `api/.htaccess` | URL Routing | Rewrite rules for clean URLs |
| `frontend/index.html` | Web UI | HTML, CSS, JavaScript buttons |
| `DOCUMENTATION.md` | Documentation | Detailed technical docs |
| `API_TESTING_GUIDE.md` | Testing | cURL commands for all tests |
| `README.md` | Overview | Project setup and structure |

---

## 🐛 Common Issues & Solutions

### Issue: 404 errors when accessing API
**Solution:** Ensure .htaccess is in `api/` folder and mod_rewrite is enabled in Apache

### Issue: CORS errors in frontend
**Solution:** Check that API headers include `Access-Control-Allow-Origin`

### Issue: JWT token not working
**Solution:** Verify token is copied exactly, Bearer prefix is included

### Issue: Cannot see tasks
**Solution:** Make sure API key header is set: `x-api-key: bytebride-secret-key-2024`

---

## 📚 Learning Outcomes

After completing this project, you should understand:

1. **REST API Principles**
   - Correct HTTP methods for operations (GET, POST, DELETE)
   - Proper status codes and error handling
   - JSON data format and responses

2. **Frontend-API Integration**
   - Fetch API for making HTTP requests
   - Header management for authentication
   - Error handling and user feedback

3. **Security Concepts**
   - Authentication vs Authorization
   - Token-based authentication (JWT, API keys)
   - Protecting sensitive endpoints
   - Security status codes (401, 403)

4. **Web Architecture**
   - Separation of concerns (API vs Frontend)
   - Controller-based routing
   - Stateless authentication
   - CORS and cross-origin requests

---

## ✨ Tips for Success

1. **Test Thoroughly:** Use both web interface and curl commands
2. **Read Status Codes:** They tell you what went wrong
3. **Check Headers:** Authentication lives in request headers
4. **Parse JSON:** Understand the response structure
5. **Document Everything:** Your reflections show understanding

---

## 📞 Support

Refer to:
- `DOCUMENTATION.md` for technical details
- `API_TESTING_GUIDE.md` for testing examples
- Comments in `api/index.php` for code explanations

---

**Submission Ready:** ✅  
All three parts complete with authentication, frontend consumer, and security implementation.

# Midterm-API
