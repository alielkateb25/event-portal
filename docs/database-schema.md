# 📊 Database Schema Documentation

## Overview
This document describes the database structure for the Event Management System. The database consists of **4 main tables** that manage users, events, participation, and categories.

---

## 1. 👤 USER Table

**Purpose:** Stores all registered user accounts and authentication information.

| Field | Type | Description | Constraints |
| :--- | :--- | :--- | :--- |
| `id` | Integer | Unique identifier for each user | **PRIMARY KEY**, Auto-increment |
| `email` | String (180) | User's email address (used for login) | **UNIQUE**, Not null |
| `password` | String (255) | Hashed user password | Not null |
| `username` | String (50) | Display name for the user | Not null |
| `roles` | JSON | User permissions (e.g., `["ROLE_USER"]`) | Default: `["ROLE_USER"]` |
| `created_at` | DateTime | Account creation timestamp | Not null, Default: NOW() |
| `updated_at` | DateTime | Last profile update timestamp | Nullable |

**Relationships:**
- **One-to-Many** with EVENT (as owner): One user can create many events
- **One-to-Many** with PARTICIPATION: One user can join many events

**Example Record:**

for simple user
```
id: 1
email: "john@example.com"
password: "$2y$13$hashedpassword..."
username: "JohnDoe"
roles: ["ROLE_USER"]
created_at: "2026-03-01 10:30:00"
updated_at: "2026-03-15 14:20:00"
```

or for an admin

```
id: 2
email: "janedoe@example.com"
password: "$2y$25$hashedpassword..."
username: "JaneDoe"
roles: ["ROLE_USER","ROLE_ADMIN"]
created_at: "2026-03-01 10:30:00"
updated_at: "2026-03-15 14:20:00"
```

---

## 2. 📅 EVENT Table

**Purpose:** Stores all event information including details, timing, and availability.

| Field | Type | Description | Constraints |
| :--- | :--- | :--- | :--- |
| `id` | Integer | Unique identifier for each event | **PRIMARY KEY**, Auto-increment |
| `title` | String (255) | Event name/title | Not null |
| `description` | Text | Detailed event information | Not null |
| `date` | DateTime | Event start date and time | Not null |
| `end_date` | DateTime | Event end date and time | Nullable |
| `location` | String (255) | Event venue or address | Not null |
| `capacity` | Integer | Maximum number of participants | Default: 100, Not null |
| `price` | Decimal (10,2) | Event cost (0.00 = free) | Default: 0.00, Not null |
| `status` | String (20) | Event status | Default: 'published', Not null |
| `image` | String (255) | Cover image URL/path | Nullable |
| `owner_id` | Integer | User who created the event | **FOREIGN KEY** → USER.id, Not null |
| `category_id` | Integer | Event category | **FOREIGN KEY** → CATEGORY.id, Nullable |
| `created_at` | DateTime | Event creation timestamp | Not null, Default: NOW() |
| `updated_at` | DateTime | Last modification timestamp | Nullable |

**Status Values:**
- `draft` - Not yet published
- `published` - Visible to all users
- `cancelled` - Event cancelled
- `completed` - Event finished

**Relationships:**
- **Many-to-One** with USER (owner): Each event has one creator
- **Many-to-One** with CATEGORY: Each event belongs to one category (optional)
- **One-to-Many** with PARTICIPATION: Each event can have many participants

**Example Record:**
```
id: 1
title: "Symfony Workshop 2026"
description: "Learn Symfony from scratch..."
date: "2026-04-15 09:00:00"
end_date: "2026-04-15 17:00:00"
location: "Tech Hub, Paris"
capacity: 50
price: 25.00
status: "published"
image: "/uploads/events/symfony-workshop.jpg"
owner_id: 1
category_id: 3
created_at: "2026-03-10 11:00:00"
updated_at: "2026-03-20 09:30:00"
```

---

## 3. 🎟️ PARTICIPATION Table

**Purpose:** Tracks which users are attending which events (junction table for Many-to-Many relationship).

| Field | Type | Description | Constraints |
| :--- | :--- | :--- | :--- |
| `id` | Integer | Unique identifier for each participation | **PRIMARY KEY**, Auto-increment |
| `user_id` | Integer | User who is joining | **FOREIGN KEY** → USER.id, Not null |
| `event_id` | Integer | Event being joined | **FOREIGN KEY** → EVENT.id, Not null |
| `status` | String (20) | Participation status | Default: 'confirmed', Not null |
| `joined_at` | DateTime | When user registered | Not null, Default: NOW() |
| `updated_at` | DateTime | Status change timestamp | Nullable |

**Status Values:**
- `pending` - Awaiting confirmation
- `confirmed` - Registration confirmed
- `cancelled` - User cancelled attendance

**Relationships:**
- **Many-to-One** with USER: Each participation belongs to one user
- **Many-to-One** with EVENT: Each participation is for one event

**Unique Constraint:**
- A user can only join an event **once** (unique combination of `user_id` + `event_id`)

**Example Record:**
```
id: 1
user_id: 5
event_id: 1
status: "confirmed"
joined_at: "2026-03-12 14:30:00"
updated_at: null
```

---

## 4. 🏷️ CATEGORY Table

**Purpose:** Organizes events into types or themes for better filtering and discovery.

| Field | Type | Description | Constraints |
| :--- | :--- | :--- | :--- |
| `id` | Integer | Unique identifier for each category | **PRIMARY KEY**, Auto-increment |
| `name` | String (100) | Category name | **UNIQUE**, Not null |

**Example Categories:**
- Technology
- Sports
- Music
- Business
- Education
- Arts & Culture
- Health & Wellness

**Relationships:**
- **One-to-Many** with EVENT: One category can have many events

**Example Record:**
```
id: 1
name: "Technology"

id: 2
name: "Sports"
```

## 3. 🎟️ REVIEW Table

**Purpose:** Tracks which users are attending which events (junction table for Many-to-Many relationship).

| Field | Type | Description | Constraints |
| :--- | :--- | :--- | :--- |
| `id` | Integer | Unique identifier for each review | **PRIMARY KEY**, Auto-increment |
| `user_id` | Integer | User who is writing the review | **FOREIGN KEY** → USER.id, Not null |
| `event_id` | Integer | Event being where the review is | **FOREIGN KEY** → EVENT.id, Not null |
| `comment` | text | the review comment of a user | Default: 'confirmed', Not null |
| `created_at` | DateTime | When user makes a review | Not null, Default: NOW() |

### Relationship Rules:

1. **USER → EVENT (One-to-Many)**
   - One user can create multiple events
   - Each event has exactly one owner

2. **USER → PARTICIPATION → EVENT (Many-to-Many)**
   - One user can join multiple events
   - One event can have multiple participants
   - PARTICIPATION table links them

3. **CATEGORY → EVENT (One-to-Many)**
   - One category can classify multiple events
   - Each event belongs to one category (optional)

4. **USER → REVIEW (One-to-Many)**
   - A user can can write multiple reviews

5. **EVENT → REVIEW (One-to-Many)**
   - An event has multiple reviews

---

## 📌 Business Rules & Constraints

1. **User Registration:**
   - Email must be unique
   - Password must be hashed (bcrypt)
   - Default role is `ROLE_USER`

2. **Event Creation:**
   - Only authenticated users can create events
   - Event date must be in the future
   - Capacity must be > 0
   - Price must be ≥ 0

3. **Participation:**
   - User cannot join the same event twice
   - User cannot join if event is full (`COUNT(participations) >= capacity`)
   - User cannot join if event status ≠ 'published'
   - Event owner automatically counted as participant

4. **Category:**
   - Category name must be unique
   - Events can exist without a category (nullable)

---

## 🔐 Security Notes

1. **Passwords:** Never store plain text passwords. Use Symfony's password hasher.
2. **SQL Injection:** Use Doctrine ORM's parameterized queries (built-in protection).
3. **Access Control:** 
   - Users can only edit/delete their own events
   - Users can only cancel their own participation
   - Admins (`ROLE_ADMIN`) can manage all content

---

**Version:** 1.0  
**Last Updated:** March 27, 2026  
**Maintained by:** Student C (Documentation Team)