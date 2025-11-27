# 📊 PROJECT OVERVIEW DIAGRAM

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                      BYTEBRIDGESERVICES                         │
│                    Task Manager API System                      │
└─────────────────────────────────────────────────────────────────┘

                            ▲
                            │
                    HTTP REQUESTS
                            │
        ┌───────────────────┴───────────────────┐
        │                                       │
        ▼                                       ▼
  ┌──────────────────┐              ┌──────────────────┐
  │  FRONTEND        │              │  BROWSER         │
  ├──────────────────┤              │  (Developer      │
  │ • HTML/CSS       │◄─────────────┤   Testing)       │
  │ • JavaScript     │              └──────────────────┘
  │ • 3 Buttons      │
  │ • Fetch API      │
  │ • Result Display │
  └────────┬─────────┘
           │
           │ GET/POST/DELETE + Headers
           │ (with auth token or API key)
           │
           ▼
  ┌──────────────────────────────────┐
  │         BACKEND API              │
  ├──────────────────────────────────┤
  │  /midterm/api/index.php          │
  │  • Router                        │
  │  • Auth Middleware               │
  │  • Endpoint Handlers             │
  │  • Error Handling                │
  └────┬─────────────┬──────────┬────┘
       │             │          │
   ┌───▼──┐    ┌────▼─┐   ┌───▼──┐
   │ GET  │    │ POST │   │DELETE│
   │tasks │    │tasks │   │task/{id}
   └──────┘    └──────┘   └──────┘
       │
       ▼
  ┌──────────────────┐
  │  DUMMY DATA      │
  │  (Task Array)    │
  │  • ID            │
  │  • Title         │
  │  • Completed     │
  └──────────────────┘
```

---

## Request Flow - Unauthorized

```
USER                    FRONTEND              API
 │                        │                    │
 ├──Click View Tasks──────>│                    │
 │                        ├──GET /tasks───────>│
 │                        │  (no auth header)  │
 │                        │                    │
 │                        │<──401 Response─────┤
 │                        │ error: unauthorized│
 │<─Error Message────────┤                    │
 │ RED: ✗ Unauthorized    │
```

---

## Request Flow - Authorized

```
USER                    FRONTEND              API
 │                        │                    │
 ├──Click Get JWT Token──>│                    │
 │                        ├──POST /login──────>│
 │                        │                    │
 │                        │<──JWT Token────────┤
 │<─Token Display────────┤                    │
 │ Blue box: "ey..."      │                    │
 │                        │                    │
 ├──Click View Tasks──────>│                    │
 │                        ├──GET /tasks───────>│
 │                        │  Bearer: JWT       │
 │                        │  (or x-api-key)    │
 │                        │                    │
 │                        │<──200 OK + Tasks──┤
 │<─Task List display───┤                    │
 │ GREEN: ✓ Success       │
```

---

## Authentication Methods Supported

```
METHOD 1: API KEY
┌─────────────────────────────────────┐
│ Header: x-api-key                   │
│ Value: bytebride-secret-key-2024    │
│ Works for: ALL endpoints            │
│ Advantage: Simple, no expiration    │
└─────────────────────────────────────┘

METHOD 2: JWT TOKEN
┌─────────────────────────────────────┐
│ Header: Authorization               │
│ Value: Bearer eyJhbGc...            │
│ Obtained: POST /login               │
│ Works for: ALL endpoints            │
│ Advantage: Tokens can expire        │
└─────────────────────────────────────┘

RESULT: Both methods work simultaneously
        Choose either one for any request
```

---

## HTTP Methods & Status Codes

```
GET /tasks
├─ 200 OK (with auth) ────> Return task list [1,2,3]
└─ 401 Unauthorized (no auth) ──> Error message

POST /tasks
├─ 201 Created (with auth) ────> New task created
├─ 400 Bad Request (no title) ──> Missing field error
└─ 401 Unauthorized (no auth) ──> Error message

DELETE /tasks/{id}
├─ 200 OK (with auth) ────> Task deleted, confirm
├─ 404 Not Found (bad id) ──> Task doesn't exist
└─ 401 Unauthorized (no auth) ──> Error message

POST /login
└─ 200 OK ────> Return JWT token
```

---

## Frontend User Interface

```
┌─────────────────────────────────────────────────┐
│   ByteBridge Services - Task Manager            │
│                                                 │
│ ┌─────────────────────────────────────────────┐ │
│ │ 🔐 AUTHENTICATION                           │ │
│ │ API Key: x-api-key: bytebride-secret...     │ │
│ │ [Get JWT Token]                             │ │
│ │ Current Token: eyJhbGciOiJ...               │ │
│ └─────────────────────────────────────────────┘ │
│                                                 │
│ [View Tasks] [Add Task] [Cancel]               │
│                                                 │
│ ┌─────────────────────────────────────────────┐ │
│ │ RESULTS                                     │ │
│ │ ✓ Successfully retrieved tasks              │ │
│ │                                             │ │
│ │ ID: 1 - Review project... [Delete]         │ │
│ │ ID: 2 - Set up environment [Delete]        │ │
│ │ ID: 3 - Create API... [Delete]             │ │
│ │                                             │ │
│ │ {                                           │ │
│ │   "success": true,                          │ │
│ │   "data": [...]                             │ │
│ │ }                                           │ │
│ └─────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────┘
```

---

## Security Layers

```
LAYER 1: Authentication (Is user valid?)
    ├─ API Key Header Check
    │  └─ x-api-key: bytebride-secret-key-2024?
    └─ JWT Token Check
       └─ Authorization: Bearer {token}?

LAYER 2: Validation (Is input valid?)
    └─ POST /tasks requires "title" field

LAYER 3: Resource Check (Does resource exist?)
    └─ DELETE /tasks/{id} → Check task exists

LAYER 4: CORS Policy (Is origin allowed?)
    └─ Access-Control-Allow-Origin headers
```

---

## File Dependencies

```
index.html (frontend)
    │
    ├─ Loads when browser opens
    │
    └─ Makes fetch() calls to:
        │
        └─ api/index.php
            │
            ├─ .htaccess (URL routing)
            │  └─ Rewrites URLs to index.php
            │
            └─ Routes to:
                ├─ GET /tasks ──> handleGetTasks()
                ├─ POST /tasks ──> handlePostTask()
                ├─ DELETE /tasks/{id} ──> handleDeleteTask()
                └─ POST /login ──> handleLogin()
```

---

## Testing Checklist

```
UNAUTHORIZED ACCESS (Should Fail)
┌──────────────────────────────────┐
│ ✅ Click View Tasks (no token)   │
│ ✅ Get 401 Response              │
│ ✅ See error: "Unauthorized"     │
│ ✅ Capture Screenshot            │
└──────────────────────────────────┘

AUTHORIZED ACCESS (Should Succeed)
┌──────────────────────────────────┐
│ ✅ Get JWT Token                 │
│ ✅ Click View Tasks (with token) │
│ ✅ Get 200 Response              │
│ ✅ See tasks displayed           │
│ ✅ Capture Screenshot            │
└──────────────────────────────────┘

FUNCTIONALITY TESTS
┌──────────────────────────────────┐
│ ✅ Add new task (POST)           │
│ ✅ Delete existing task (DELETE) │
│ ✅ Refresh task list (GET)       │
│ ✅ See updated results           │
└──────────────────────────────────┘
```

---

## Submission Package Contents

```
📦 d:\xampp\htdocs\midterm\
│
├── 🔧 APPLICATION FILES
│   ├── api/index.php              (206 lines - REST API)
│   ├── api/.htaccess              (8 lines - URL routing)
│   └── frontend/index.html        (330 lines - Web UI)
│
├── 📚 DOCUMENTATION
│   ├── README.md                  (Setup & overview)
│   ├── DOCUMENTATION.md           (Technical details)
│   ├── QUICKSTART.md             (5-min guide)
│   ├── SCREENSHOTS_GUIDE.md       (How to screenshot)
│   ├── SUBMISSION_CHECKLIST.md   (Requirements check)
│   ├── API_TESTING_GUIDE.md      (9 test scenarios)
│   ├── COMPLETION_SUMMARY.md     (Project summary)
│   └── PROJECT_OVERVIEW.md       (This file)
│
└── 📸 SCREENSHOTS (Optional)
    ├── Screenshot1_Unauthorized.png
    └── Screenshot2_Authorized.png
```

---

## Success Indicators

```
✅ Frontend loads at http://localhost/midterm/frontend/
✅ Buttons are clickable
✅ Unauthorized shows 401 error (red)
✅ Get JWT Token works
✅ Authorized shows task list (green)
✅ Tasks can be deleted
✅ New tasks can be added
✅ Task list updates properly
✅ JSON responses shown
✅ Status codes are correct
```

---

## Project Completion Status

```
PART 1: API FUNDAMENTALS
████████████████████ 100% ✅
  - 3 endpoints working
  - Proper HTTP methods
  - Status codes correct
  - Dummy data included

PART 2: API DEVELOPMENT
████████████████████ 100% ✅
  - 3 buttons working
  - Fetch API implemented
  - Results displayed
  - JSON shown to user

PART 3: AUTHENTICATION
████████████████████ 100% ✅
  - JWT support added
  - API key support added
  - Endpoints protected
  - 401 responses working

DOCUMENTATION
████████████████████ 100% ✅
  - 7 documentation files
  - Screenshots guide
  - Testing guide
  - Setup instructions

OVERALL PROJECT
████████████████████ 100% ✅
  READY FOR SUBMISSION
```

---

**System Status: ✅ COMPLETE & TESTED**

