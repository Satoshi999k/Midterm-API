# API Testing Guide - cURL Commands

## Setup Instructions

1. Make sure XAMPP is running with Apache
2. Place files in: d:\xampp\htdocs\midterm\
3. Access Frontend: http://localhost/midterm/frontend/
4. API Base: http://localhost/midterm/api/

---

## Test 1: Get Tasks WITHOUT Authentication (Should Return 401)

```bash
curl -X GET http://localhost/midterm/api/tasks
```

**Expected Response:**
```json
{
  "error": "Unauthorized",
  "message": "Valid token or API key required"
}
```

---

## Test 2: Get Tasks WITH API Key (Should Return 200)

```bash
curl -X GET http://localhost/midterm/api/tasks \
  -H "x-api-key: bytebride-secret-key-2024"
```

**Expected Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Review project requirements",
      "completed": false
    },
    {
      "id": 2,
      "title": "Set up development environment",
      "completed": true
    },
    {
      "id": 3,
      "title": "Create API endpoints",
      "completed": false
    }
  ]
}
```

---

## Test 3: Get JWT Token

```bash
curl -X POST http://localhost/midterm/api/login \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "password"}'
```

**Expected Response:**
```json
{
  "success": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

---

## Test 4: Get Tasks WITH JWT Token (Should Return 200)

```bash
curl -X GET http://localhost/midterm/api/tasks \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

*(Replace token with actual JWT from Test 3)*

**Expected Response:** Same as Test 2 (successful task list)

---

## Test 5: Add Task WITHOUT Authentication (Should Return 401)

```bash
curl -X POST http://localhost/midterm/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title": "New Task", "completed": false}'
```

**Expected Response:**
```json
{
  "error": "Unauthorized",
  "message": "Valid token or API key required"
}
```

---

## Test 6: Add Task WITH API Key (Should Return 201)

```bash
curl -X POST http://localhost/midterm/api/tasks \
  -H "Content-Type: application/json" \
  -H "x-api-key: bytebride-secret-key-2024" \
  -d '{"title": "Complete security testing", "completed": false}'
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "id": 4,
    "title": "Complete security testing",
    "completed": false
  }
}
```

---

## Test 7: Delete Task WITHOUT Authentication (Should Return 401)

```bash
curl -X DELETE http://localhost/midterm/api/tasks/1
```

**Expected Response:**
```json
{
  "error": "Unauthorized",
  "message": "Valid token or API key required"
}
```

---

## Test 8: Delete Task WITH API Key (Should Return 200)

```bash
curl -X DELETE http://localhost/midterm/api/tasks/1 \
  -H "x-api-key: bytebride-secret-key-2024"
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Task deleted",
  "data": {
    "id": 1,
    "title": "Review project requirements",
    "completed": false
  }
}
```

---

## Test 9: Delete Non-Existent Task (Should Return 404)

```bash
curl -X DELETE http://localhost/midterm/api/tasks/999 \
  -H "x-api-key: bytebride-secret-key-2024"
```

**Expected Response:**
```json
{
  "error": "Not found",
  "message": "Task not found"
}
```

---

## Summary of Security Tests

| Test | Method | Authentication | Expected Status | Purpose |
|------|--------|-----------------|-----------------|---------|
| 1 | GET | None | 401 | Verify endpoints reject unauthenticated requests |
| 2 | GET | API Key | 200 | Verify API key authentication works |
| 3 | POST | - | 200 | Verify JWT token generation |
| 4 | GET | JWT Token | 200 | Verify JWT authentication works |
| 5 | POST | None | 401 | Verify POST requires authentication |
| 6 | POST | API Key | 201 | Verify authenticated POST creates resource |
| 7 | DELETE | None | 401 | Verify DELETE requires authentication |
| 8 | DELETE | API Key | 200 | Verify authenticated DELETE works |
| 9 | DELETE | API Key | 404 | Verify proper error for missing resource |

