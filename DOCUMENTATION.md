# ByteBridge Task Manager - Testing & Security Documentation

## Part 1: API Implementation Summary

### Endpoints Created:
1. **GET /midterm/api/tasks** - Retrieve all tasks
2. **POST /midterm/api/tasks** - Create a new task
3. **DELETE /midterm/api/tasks/{id}** - Delete a task by ID
4. **POST /midterm/api/login** - Get JWT token

### HTTP Methods Used:
- **GET**: Used to retrieve tasks (safe, idempotent, no data modification)
- **POST**: Used to add new tasks (creates resource, not idempotent)
- **DELETE**: Used to remove tasks (destructive, idempotent, restricted)

### JSON Response Structure:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Review project requirements",
      "completed": false
    }
  ]
}
```

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

