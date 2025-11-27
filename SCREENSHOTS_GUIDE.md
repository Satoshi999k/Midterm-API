# Screenshots for Submission

## How to Generate Required Screenshots

The exam requires:
> "A screenshot showing: Unauthorized request = FAILED, Authorized request = SUCCESS"

---

## Screenshot 1: Unauthorized Request (FAILED)

### Steps to Capture:
1. Open the Frontend: `http://localhost/midterm/frontend/`
2. **DO NOT** click "Get JWT Token"
3. Click "View Tasks" button
4. Wait for result to display
5. You should see: **✗ Error: Unauthorized! Status: 401**
6. Capture the screen showing:
   - The error message
   - The response JSON showing "error": "Unauthorized"

### Expected Output Visible:
```
✗ Error: Unauthorized! Status: 401

{
  "error": "Unauthorized",
  "message": "Valid token or API key required"
}
```

### What This Shows:
- API correctly rejects requests without authentication
- Returns 401 status code
- Explains what's required (token or API key)

---

## Screenshot 2: Authorized Request (SUCCESS)

### Steps to Capture:
1. **Same Frontend Page** (continue from above or refresh)
2. Click "Get JWT Token" button
3. Wait for token to appear in the token display area
4. Click "View Tasks" button again
5. You should see: **✓ Successfully retrieved tasks**
6. Capture the screen showing:
   - The success message
   - The task list displayed below
   - The JSON response with tasks
   - The JWT token in the display area

### Expected Output Visible:
```
✓ Successfully retrieved tasks

Task List:
- ID: 1 - Review project requirements (Pending)
- ID: 2 - Set up development environment (Completed)
- ID: 3 - Create API endpoints (Pending)

{
  "success": true,
  "data": [
    {"id": 1, "title": "Review project requirements", "completed": false},
    {"id": 2, "title": "Set up development environment", "completed": true},
    {"id": 3, "title": "Create API endpoints", "completed": false}
  ]
}
```

### What This Shows:
- API successfully accepts authenticated requests
- Returns 200 status code with data
- Authentication works (either API key or JWT)
- Task data is properly formatted as JSON

---

## Screenshot 3 (Optional): Complete Flow

You can also capture a screenshot showing the entire sequence:

### Full Flow Capture:
1. Show unauthorized error (top half of page)
2. Show token obtained (middle section)
3. Show authorized success with task list (bottom half)

This can be done by:
- Taking scrollable screenshot (Windows: Windows+Shift+S)
- Or multiple screenshots stacked together

---

## How to Take Screenshots

### Windows:
1. **Quick Screenshot:** Press `Windows + Shift + S`
2. **Full Screenshot:** Press `PrtScn` then paste in Paint/Word
3. **Firefox/Chrome:** Right-click → "Take Screenshot"
4. **XAMPP:** Make sure application is visible in background

### Chrome/Firefox Developer Tools:
1. Press `F12` to open DevTools
2. Press `Ctrl+Shift+P` (command palette)
3. Type "screenshot"
4. Select "Capture full page screenshot"

### Best Practice:
- Capture the entire browser window
- Include the address bar showing `localhost/midterm/frontend/`
- Show the full results section
- Make text readable (zoom in if needed)

---

## Saving & Naming

### Filename Convention:
```
Screenshot1_Unauthorized_401.png
Screenshot2_Authorized_200.png
```

### Format:
- PNG or JPG (both acceptable)
- At least 1920x1080 resolution recommended
- Include in submission folder or document

---

## What Your Submission Folder Should Contain

```
d:\xampp\htdocs\midterm\
├── api/
│   ├── index.php
│   └── .htaccess
├── frontend/
│   └── index.html
├── screenshots/  (Optional folder)
│   ├── Screenshot1_Unauthorized.png
│   └── Screenshot2_Authorized.png
├── README.md
├── DOCUMENTATION.md
├── API_TESTING_GUIDE.md
└── SUBMISSION_CHECKLIST.md
```

---

## Summary of What Each Screenshot Proves

| Screenshot | Proves | HTTP Status |
|------------|--------|-------------|
| Unauthorized | API correctly rejects unauthenticated requests | 401 |
| Authorized | API accepts valid authentication and returns data | 200 |
| Both together | Complete security implementation works correctly | Success |

---

## Troubleshooting Screenshots

### If you see different error:
- **404 Error:** API file not found, check path `http://localhost/midterm/api/`
- **CORS Error:** Check Access-Control-Allow headers in API
- **Timeout:** Ensure Apache is running in XAMPP

### If unauthorized doesn't show:
- Make sure you're NOT using a token
- Don't have the API key in headers
- Should be completely blank headers for first request

### If authorized doesn't show:
- Make sure token was generated successfully
- Token should appear in blue box: "Current Token: ..."
- Check that Bearer prefix is included in header

---

## Alternative: Using cURL for Screenshots

If web interface isn't working, you can screenshot curl commands:

### Terminal 1: Unauthorized (for screenshot)
```
PS C:\> curl -X GET http://localhost/midterm/api/tasks
{"error":"Unauthorized","message":"Valid token or API key required"}
```

### Terminal 2: Authorized (for screenshot)
```
PS C:\> curl -X GET http://localhost/midterm/api/tasks `
  -H "x-api-key: bytebride-secret-key-2024"
{"success":true,"data":[...]}
```

Both terminal screenshots with curl output demonstrate the same security concept.

