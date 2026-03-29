# API Endpoints Documentation

## 📋 Overview

This document defines the API contract for the Event Management System. All endpoints return JSON responses.

**Base URL:** `http://localhost:8000/api`  
**Authentication:** Bearer Token (JWT) for protected routes

---

## 🔐 Authentication Endpoints

### 1. Register User
```http
POST /api/register
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "username": "john_doe",
  "password": "securePassword123"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "username": "john_doe",
    "roles": ["ROLE_USER"]
  }
}
```

**Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Email already exists"
}
```

---

### 2. Login
```http
POST /api/login
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "securePassword123"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "username": "john_doe",
    "roles": ["ROLE_USER"]
  }
}
```

**Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

### 3. Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## 📅 Event Endpoints

### 4. Get All Events (Public)
```http
GET /api/events
```

**Query Parameters:**
| Parameter | Type | Description |
| :--- | :--- | :--- |
| `page` | Integer | Page number (default: 1) |
| `limit` | Integer | Items per page (default: 10) |
| `category` | String | Filter by category name |
| `search` | String | Search by title |

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Symfony Workshop",
      "description": "Learn Symfony basics",
      "date": "2025-02-15T10:00:00Z",
      "end_date": "2025-02-15T16:00:00Z",
      "location": "Room 101",
      "capacity": 50,
      "price": 0,
      "status": "published",
      "image": "/uploads/events/workshop.jpg",
      "owner": {
        "id": 1,
        "username": "john_doe"
      },
      "category": {
        "id": 1,
        "name": "Technology"
      },
      "participants_count": 25
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 5,
    "total_items": 50
  }
}
```

---

### 5. Get Single Event (Public)
```http
GET /api/events/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Symfony Workshop",
    "description": "Learn Symfony basics",
    "date": "2025-02-15T10:00:00Z",
    "end_date": "2025-02-15T16:00:00Z",
    "location": "Room 101",
    "capacity": 50,
    "price": 0,
    "status": "published",
    "image": "/uploads/events/workshop.jpg",
    "owner": {
      "id": 1,
      "username": "john_doe",
      "email": "user@example.com"
    },
    "category": {
      "id": 1,
      "name": "Technology"
    },
    "participants": [
      {
        "id": 2,
        "username": "jane_doe",
        "joined_at": "2025-01-10T08:00:00Z",
        "status": "confirmed"
      }
    ],
    "created_at": "2025-01-01T10:00:00Z",
    "updated_at": "2025-01-05T14:00:00Z"
  }
}
```

**Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Event not found"
}
```

---

### 6. Create Event (Private)
```http
POST /api/events
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Symfony Workshop",
  "description": "Learn Symfony basics",
  "date": "2025-02-15T10:00:00Z",
  "end_date": "2025-02-15T16:00:00Z",
  "location": "Room 101",
  "capacity": 50,
  "price": 0,
  "category_id": 1,
  "image": "workshop.jpg"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Event created successfully",
  "data": {
    "id": 1,
    "title": "Symfony Workshop",
    "owner": {
      "id": 1,
      "username": "john_doe"
    }
  }
}
```

**Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Authentication required"
}
```

**Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "You do not have permission to create events"
}
```

---

### 7. Update Event (Private - Owner Only)
```http
PUT /api/events/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Updated Symfony Workshop",
  "description": "Updated description",
  "capacity": 100,
  "status": "published"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Event updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Symfony Workshop",
    "updated_at": "2025-01-15T10:00:00Z"
  }
}
```

**Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "You can only edit your own events"
}
```

---

### 8. Delete Event (Private - Owner Only)
```http
DELETE /api/events/{id}
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Event deleted successfully"
}
```

**Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "You can only delete your own events"
}
```

---

## 🎟️ Participation Endpoints

### 9. Join Event (Private)
```http
POST /api/events/{id}/join
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Successfully joined the event",
  "data": {
    "event_id": 1,
    "user_id": 2,
    "status": "confirmed",
    "joined_at": "2025-01-15T10:00:00Z"
  }
}
```

**Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Event is at full capacity"
}
```

**Response (409 Conflict):**
```json
{
  "success": false,
  "message": "You have already joined this event"
}
```

---

### 10. Leave Event (Private)
```http
DELETE /api/events/{id}/join
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Successfully left the event"
}
```

**Response (404 Not Found):**
```json
{
  "success": false,
  "message": "You are not participating in this event"
}
```

---

### 11. Get My Events (Private)
```http
GET /api/my-events
Authorization: Bearer {token}
```

**Query Parameters:**
| Parameter | Type | Description |
| :--- | :--- | :--- |
| `type` | String | `created` or `joined` |

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "created": [
      {
        "id": 1,
        "title": "Symfony Workshop",
        "date": "2025-02-15T10:00:00Z",
        "participants_count": 25
      }
    ],
    "joined": [
      {
        "id": 2,
        "title": "PHP Conference",
        "date": "2025-03-01T09:00:00Z",
        "status": "confirmed"
      }
    ]
  }
}
```

---

## 📂 Category Endpoints

### 12. Get All Categories (Public)
```http
GET /api/categories
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Technology",
      "event_count": 15
    },
    {
      "id": 2,
      "name": "Sports",
      "event_count": 8
    },
    {
      "id": 3,
      "name": "Music",
      "event_count": 12
    }
  ]
}
```

---

## 🔑 Authentication Requirements Summary

| Endpoint | Method | Auth Required | Role Required |
| :--- | :--- | :--- | :--- |
| `/api/register` | POST | ❌ No | - |
| `/api/login` | POST | ❌ No | - |
| `/api/logout` | POST | ✅ Yes | USER |
| `/api/events` | GET | ❌ No | - |
| `/api/events/{id}` | GET | ❌ No | - |
| `/api/events` | POST | ✅ Yes | USER |
| `/api/events/{id}` | PUT | ✅ Yes | OWNER |
| `/api/events/{id}` | DELETE | ✅ Yes | OWNER |
| `/api/events/{id}/join` | POST | ✅ Yes | USER |
| `/api/events/{id}/join` | DELETE | ✅ Yes | USER |
| `/api/my-events` | GET | ✅ Yes | USER |
| `/api/categories` | GET | ❌ No | - |

---

## 📊 HTTP Status Codes

| Code | Meaning | When Used |
| :--- | :--- | :--- |
| `200` | OK | Successful GET, PUT, DELETE |
| `201` | Created | Successful POST (resource created) |
| `400` | Bad Request | Invalid input data |
| `401` | Unauthorized | Missing or invalid token |
| `403` | Forbidden | Valid token but no permission |
| `404` | Not Found | Resource doesn't exist |
| `409` | Conflict | Resource already exists (e.g., already joined) |
| `500` | Server Error | Backend error |

---

## 🧪 Testing with cURL

### Example: Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"securePassword123"}'
```

### Example: Create Event
```bash
curl -X POST http://localhost:8000/api/events \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {your_token}" \
  -d '{"title":"My Event","description":"Desc","date":"2025-02-15T10:00:00Z","location":"Room 101","capacity":50}'
```

### Example: Join Event
```bash
curl -X POST http://localhost:8000/api/events/1/join \
  -H "Authorization: Bearer {your_token}"
```

---

## 📝 Notes for Student A (Backend)

1. **Security:** Use Symfony Security Bundle for authentication
2. **Validation:** Validate all input data (e.g., date format, capacity > 0)
3. **Pagination:** Implement pagination for `/api/events`
4. **Serialization:** Use Symfony Serializer for JSON responses
5. **CORS:** Enable CORS for frontend API calls

---

## 📝 Notes for Student B (Frontend)

1. **Token Storage:** Store JWT token in localStorage or sessionStorage
2. **Auth Header:** Include `Authorization: Bearer {token}` in all protected requests
3. **Error Handling:** Handle 401 (redirect to login) and 403 (show permission error)
4. **Loading States:** Show loading spinners during API calls

---

**Document Version:** 1.0  
**Last Updated:** [Today's Date]  
**Author:** Student C (Documentation)