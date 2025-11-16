# 📘 API Dashborad Documentation 
# Government Complaints System

## 🔐 Authentication APIs

---

### **1. Login**

**Endpoint:**
`POST /api/login`

**Description:**

this endpoint allows both Super Admin and Employee users to log into the system.
It validates the provided credentials, and upon successful authentication, an access token is generated.
This token is required to access all protected system APIs, based on the user’s assigned role and permissions.

**Headers:**

```
Content-Type: application/json
```

**Body:**

```json
{
    "email": "admin@gmail.com",
    "password": "12345678",
    "device_token": "DEVICE_TOKEN_OPTIONAL"
}
```

**Success Response:**

```json
{
    "status": 200,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Super Admin",
            "email": "admin@gmail.com",
            "role": "Super Admin"
        },
        "token": "example_token_here"
    }
}
```

---

### **2. Logout**

**Endpoint:**
`POST /api/logout`

**Description:**

this endpoint logs the user out by invalidating the currently active authentication token.
After logging out, the user will no longer be able to access any protected API until they log in again and obtain a new token.

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "status": 200,
    "message": "Logout successful"
}
```

---

### **3. Check Session**

**Endpoint:**
`GET /api/session/check`

**Description:**

This endpoint verifies whether the current user's authentication session is still valid.
It is used to confirm that the provided token belongs to an active logged-in user, and it also returns the authenticated user’s basic information (name, email, role).
If the token is expired, revoked, or invalid, the request will fail and indicate that the session is no longer active.

**Headers:**

```
Authorization: Bearer {token}
```

**Success Response Example:**

```json
{
    "status": 200,
    "message": "Session valid",
    "data": {
        "id": 4,
        "name": "Employee User",
        "email":"Employee Email",
        "role": "Employee"
    }
}
```

---

## 👥 Super Admin APIs

### **4. List Government Entities**

**Endpoint:**

`GET /api/government-entities`

**Description:**

This endpoint retrieves a full list of all available government entities. It is used by the Super Admin when selecting which entity to assign to a newly created employee. No authentication token is required.

**Response Example:**

```
{
    "status": 200,
    "message": "Government entities retrieved successfully.",
    "data": [
        {
            "id": 1,
            "name": "Ministry of Health"
        },
        {
            "id": 2,
            "name": "Ministry of Education"
        }
    ]
}
```

### **5. Create Employee**

**Endpoint:**
`POST /api/employees/create/{governmentEntityId}`

**Description:**

This endpoint allows the Super Admin to create new employees in the system and assign them to a specific government entity.
Upon creation, the employee is automatically granted the Employee role and becomes associated with the selected government department.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Body:**

```json
{
    "name": "Employee 1",
    "email": "emp1@gmail.com",
    "phone":"employee phone",
    "password": "123456",
    "password_confirmation": "123456"
}
```

**Response:**

```json
{
    "status": 200,
    "message": "Employee created successfully.",
    "data": {
        "id": 3,
        "name": "Employee 1",
        "email": "emp1@gmail.com",
        "role": "Employee",
        "government_entities": ["Ministry of Health"]
    }
}
```

---

### **6. Assign employee to government entity**

**Endpoint:**
`POST /api/employees/assign-government-entity/{employeeId}`

**Description:**

This endpoint allows the Super Admin to assign an employee to a new government entity.
It is used when the employee is transferred or begins working at an additional government department.
The new entity is added to the employee’s list of assigned government entities without removing previous assignments.

**Body:**

```json
{
    "government_entity_id": 3
}
```

**Response:**

```json
{
    "status": 200,
    "message": "Government entity assigned to employee successfully.",
    "data": {
        "id": 2,
        "name": "employee2",
        "email": "employee2@gmail.com",
        "role": "Employee",
        "government_entities": [
            "Ministry of Health",
            "Ministry of Education"
        ]
    }
}
```

---

## 👥 Employee APIs

## 📝 Incoming Complaints

### **7. Get incoming complaints for logged-in employee**

**Endpoint:**
`GET /api/incoming-complaints`

**Description:**

This endpoint retrieves all incoming complaints for the logged-in employee based on the most recent government entity they are assigned to.
The system automatically determines the employee’s current active government department and returns all complaints directed to that entity.

**Headers:**

```
Authorization: Bearer {EmployeeToken}
```

**Response Example:**

```json
{
    "status": 200,
    "message": "Incoming complaints fetched successfully.",
    "data": [
     {
            "id": 1,
            "user_name": "ola",
            "reference_number": "CMP-ICACPYR7TR",
            "status": null,
            "complaint_type": "Service Complaint",
            "created_at": "2025-11-15 21:52",
            "updated_at": "2025-11-15 21:52"
        }
    ]
}
```
