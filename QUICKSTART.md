# 🚀 QUICK START - ByteBridge Task Manager

## ⚡ 5-Minute Setup

### 1. Start XAMPP
- Open XAMPP Control Panel
- Click **Start** next to Apache
- Wait for "Running" status

### 2. Open in Browser
```
http://localhost/midterm/frontend/
```

### 3. Test Security (3 Steps)

**Step 1: Unauthorized Access**
- Click **"View Tasks"** button
- You'll see red error: `✗ Unauthorized (401)`
- This is correct! ✅

**Step 2: Get Authentication**
- Click **"Get JWT Token"** button  
- Blue box shows your token
- This is authorization! ✅

**Step 3: Authorized Access**
- Click **"View Tasks"** button again
- You'll see green success: `✓ Successfully retrieved tasks`
- Tasks display below with delete buttons
- This proves security works! ✅

---

## 📁 Project Structure

```
d:\xampp\htdocs\midterm\
├── api/index.php              ← REST API (GET, POST, DELETE)
├── api/.htaccess              ← URL routing
├── frontend/index.html        ← Web interface with buttons
├── README.md                  ← Full overview
├── DOCUMENTATION.md           ← Technical details
├── API_TESTING_GUIDE.md      ← curl test examples
├── SUBMISSION_CHECKLIST.md   ← Requirements verification
├── SCREENSHOTS_GUIDE.md       ← How to screenshot
└── COMPLETION_SUMMARY.md     ← Project summary
```

---

## 🎮 Test All Features

### View Tasks
- Shows all tasks when authenticated
- Displays task ID, title, and status
- Shows 401 error if not authenticated

### Add Task
- Click "Add Task" button
- Enter task title
- Creates new task (requires auth)
- Shows new task in list

### Delete Task  
- Click "Delete" button on any task
- Confirms deletion
- Removes from list (requires auth)

### Get Token
- Click "Get JWT Token" button
- Shows token in blue box
- Use for authentication

---

## 🔐 Security Test

### Without Authentication (Should FAIL)
```
Try "View Tasks" without token → 401 Unauthorized ❌
```

### With Authentication (Should SUCCEED)
```
Click "Get JWT Token" → Get "View Tasks" with token → Success ✅
```

---

## 🧪 Test with cURL (Optional)

### Without Auth:
```bash
curl -X GET http://localhost/midterm/api/tasks
# Result: 401 Unauthorized ❌
```

### With Auth:
```bash
curl -X GET http://localhost/midterm/api/tasks \
  -H "x-api-key: bytebride-secret-key-2024"
# Result: 200 OK with tasks ✅
```

---

## 📸 Required Screenshots

### Screenshot 1: Unauthorized (RED)
- Click "View Tasks" without token
- Capture error message
- Save as: `Unauthorized_401.png`

### Screenshot 2: Authorized (GREEN)  
- Click "Get JWT Token"
- Click "View Tasks" with token
- Capture success with task list
- Save as: `Authorized_200.png`

---

## ✅ What's Implemented

| Feature | Status | Location |
|---------|--------|----------|
| GET /tasks | ✅ | api/index.php |
| POST /tasks | ✅ | api/index.php |
| DELETE /tasks/{id} | ✅ | api/index.php |
| JWT Login | ✅ | api/index.php |
| HTML Buttons | ✅ | frontend/index.html |
| Fetch API | ✅ | frontend/index.html |
| Authentication | ✅ | api/index.php |
| Error Handling | ✅ | Both |
| Documentation | ✅ | 6 MD files |

---

## 🎯 Exam Requirements

✅ **Part 1** - API with GET, POST, DELETE  
✅ **Part 2** - HTML frontend with 3 buttons  
✅ **Part 3** - JWT authentication with 401 protection  

---

## 📞 If Something Doesn't Work

### Issue: 404 Error
**Solution:** Check XAMPP Apache is running

### Issue: Cannot see tasks
**Solution:** Make sure you have auth (use API key or token)

### Issue: Button not responding
**Solution:** Check browser console (F12) for errors

### Issue: CORS Error
**Solution:** Verify API headers are correct in api/index.php

---

## 🎓 What You've Built

A **complete, secure API system** with:
- RESTful backend (3 endpoints)
- Interactive frontend (3 buttons)  
- Authentication (JWT + API key)
- Error handling (proper status codes)
- Documentation (6 guides)

**That's production-level thinking!** 🚀

---

## 🏁 Next Steps

1. ✅ Test everything in browser
2. ✅ Take screenshots of success/failure
3. ✅ Verify all files are in place
4. ✅ Submit the `d:\xampp\htdocs\midterm\` folder

---

**You're all set! Open http://localhost/midterm/frontend/ and start testing!** 🎉

