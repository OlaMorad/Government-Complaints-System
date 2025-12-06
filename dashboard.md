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
    "message":"تم تسجيل الدخول بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "Super Admin",
            "email": "admin@gmail.com",
            "phone": "999999999",
            "role": "المشرف العام"
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
    "message":"تم تسجيل الخروج بنجاح"
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
{
    "status": 200,
    "message": "الجلسة فعّالة.",
    "data": {
        "id": 3,
        "name": "موظف",
        "email": "employee1@gmail.com",
        "phone": "1234567890",
        "role": "الموظف"
    }
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

```json
{
    "status": 200,
    "message": "تم استرجاع الجهات الحكومية بنجاح.",
    "data": [
        {
            "id": 8,
            "name": "وزارة الاتصالات"
        },
        {
            "id": 2,
            "name": "وزارة التربية والتعليم"
        },
        {
            "id": 10,
            "name": "وزارة التعليم العالي"
        },
        {
            "id": 7,
            "name": "وزارة السياحة"
        },
        {
            "id": 9,
            "name": "وزارة الشؤون الاجتماعية و العمل"
        },
        {
            "id": 1,
            "name": "وزارة الصحة"
        },
        {
            "id": 5,
            "name": "وزارة الطاقة"
        },
        {
            "id": 4,
            "name": "وزارة المالية"
        },
        {
            "id": 6,
            "name": "وزارة الموارد البشرية"
        },
        {
            "id": 3,
            "name": "وزارة النقل"
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
    "status": 201,
    "message": "تم إنشاء الموظف بنجاح.",
    "data": {
        "id": 2,
        "name": "employee1",
        "email": "employee1@gmail.com",
        "phone": "1234567890",
        "role": "الموظف",
        "government_entities": [
            "وزارة الصحة"
        ],
        "created_at": "2025-11-19"
    }
}
```

---

### **6. update employee**

**Endpoint:**
`PUT /api/employees/update/{employeeId}`

**Description:**

This endpoint allows the Super Admin to update an employee’s information and assign them to an additional government entity.
It is used when the employee receives updated personal details or is transferred to a new department while keeping their existing government entity assignments.
The newly assigned entity is added without removing the employee’s previous government entities.

**Body:**

```json
{
   "name": "موظف",
   // "email": "newupdated@gmail.com",
   // "phone": "0933445566"
  //  "password": "12345678",
   // "password_confirmation":"12345678",
   "government_entity_id": 2
}
```

**Response:**

```json
{
    "status": 200,
    "message": "تم تحديث بيانات الموظف بنجاح.",
    "data": {
        "id": 1,
        "name": "موظف",
        "email": "newupdated@gmail.com",
        "phone": "0933445566",
        "role": "الموظف",
        "government_entities": [
            "وزارة الصحة",
            "وزارة النقل",
            "وزارة التربية والتعليم",
        ],
        "created_at": "2025-11-18"
    }
}
```

---

### **7. show all employee**

**Endpoint:**

`GET /api/employees/all`

**Description:**

This endpoint allows the Super Admin to retrieve a full list of all registered employees.
It provides detailed information for each employee, including personal data, assigned role, and the government entities they belong to.
It is used for reviewing and managing all employees within the system.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Response:**

```json
{
    "status": 200,
    "message": "تم عرض جميع الموظفين بنجاح",
    "data": [
        {
            "id": 1,
            "name": "موظف",
            "email": "newupdated@gmail.com",
            "phone": "0933445566",
            "role": "الموظف",
            "government_entities": [
                "وزارة الصحة",
                "وزارة النقل",
                "وزارة التربية والتعليم"
            ],
            "created_at": "2025-11-18"
        }
    ]
}
```

### **8. delete employee**

**Endpoint:**

`GET /api/employees/delete/{employeeId}`

**Description:**

This endpoint allows the Super Admin to delete an employee from the system.
When executed, the employee’s account and all related associations—such as assigned government entities—are removed permanently.
It is used to manage the removal of employees who no longer work within the organization.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Response:**

```json
{
    "status": 200,
    "message": "تم حذف الموظف بنجاح.",
    "data": []
}
```

### **9. Create new government Entity**

**Endpoint:**

`POST /api/government-entities`

**Description:**

This endpoint allows the Super Admin to create a new government entity in the system.
It is used to add ministries, departments, or public agencies that employees can later be assigned to.
Once created, the new government entity becomes available for selection when managing employee assignments.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Body:**

```json
{
    "name":"وزارة التربية"
}
```

**Response:**

```json
{
    "status": 201,
    "message": "تم إضافة الجهة الحكومية بنجاح.",
    "data": {
        "name": "وزارة التربية",
        "updated_at": "2025-11-24T11:18:35.000000Z",
        "created_at": "2025-11-24T11:18:35.000000Z",
        "id": 11
    }
}
```

### **10. Update government Entity**

**Endpoint:**

`PUT /api/government-entities/{id}`

**Description:**

This endpoint allows the Super Admin to update the details of an existing government entity.
It is used to modify the name or information of a ministry, department, or public agency already stored in the system.
Once updated, the changes are reflected across all employee assignments linked to that entity.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Body:**

```json
{
    "name":"وزارة التربية"
}
```

**Response:**

```json
{
    "status": 200,
    "message": "تم تعديل الجهة الحكومية بنجاح.",
    "data": {
        "id": 1,
        "name": "وزارة التجارة و الاقتصاد",
        "created_at": "2025-11-22T17:07:14.000000Z",
        "updated_at": "2025-11-24T11:20:41.000000Z"
    }
}
```

### **11. delete government Entity**

**Endpoint:**

`DELETE /api/government-entities/{id}`

**Description:**

This endpoint allows the Super Admin to delete an existing government entity from the system.
Once deleted, the entity is removed permanently and will no longer be available for employee assignments.
This operation is typically used when a government department is closed, merged, or no longer needed in the system.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Response:**

```json
{
    "status": 200,
    "message": "تم حذف الجهة الحكومية بنجاح.",
    "data": []
}
```

### **12. Admin Statistics**

**Endpoint:**

`GET /api/statistics/admin`

**Description:**

This endpoint allows the Super Admin to view system-wide statistics.  
It provides an overview of complaints, users, employees, and government entities.  
This information helps administrators monitor system activity, track performance, and analyze workload distribution across the platform.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Response:**

```json
{
    "status": 200,
    "message": "Statistics retrieved successfully",
    "data": {
        "total_complaints": 27,
        "complaints_pending": 25,
        "complaints_processing": 2,
        "complaints_rejected": 0,
        "complaints_completed": 0,
        "total_users": 1,
        "total_employees": 2,
        "total_government_entities": 10
    }
}
```

### **13. Show Complaint History**

**Endpoint:**

`GET /api/complaints/history/{complaintId}`

**Description:**

This endpoint allows the Super Admin to view the full details of a specific complaint, including its metadata, attachments, and complete status history.  
It is used to track how the complaint progressed over time, who handled it, and what actions or notes were recorded.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Response:**

```json
{
    "status": 200,
    "message": "Complaint history retrieved successfully.",
    "data": {
        "id": 31,
        "reference_number": "CMP-VY22HAMBZ4",
        "user_name": "Citizen User",
        "government_entity": "وزارة الصحة",
        "complaint_type": "شكوى خدمة",
        "status": "قيد المعالجة",
        "location_description": "12345678",
        "problem_description": "22",
        "attachments": [
            {
                "id": 1,
                "file_path": "http://127.0.0.1:8000/Storage/complaints/FZ8sS5WpzL1WMwjA0En4ADLED33CqbELo3aRo0BT.png"
            }
        ],
        "history": [
            {
                "id": 3,
                "old_status": "قيد المعالجة",
                "new_status": "قيد المعالجة",
                "notes": "يجب أن ترفق المزيد من الصور لدراسة حالة الشكوى بشكل مفصل",
                "changed_by": "موظف",
                "created_at": "2025-11-24 14:01"
            },
            {
                "id": 2,
                "old_status": "انتظار",
                "new_status": "قيد المعالجة",
                "notes": "لا يوجد ملاحظات على هذه الشكوى",
                "changed_by": "موظف",
                "created_at": "2025-11-24 14:01"
            }
        ],
        "created_at": "2025-11-24 13:46",
        "updated_at": "2025-11-24 14:01"
    }
}
```

### **14. Show All Complaints**

**Endpoint:**

`GET /api/complaints/all`

**Description:**

This endpoint allows the Super Admin to retrieve a complete list of all complaints in the system.  
The results are returned with **pagination**, making it efficient to browse large numbers of complaints.  
Each record includes essential complaint information such as status, type, reference number, and timestamps.

**Headers:**

```
Authorization: Bearer {SuperAdminToken}
Content-Type: application/json
```

**Response:**

```json
{
    "status": 200,
    "message": "All complaints retrieved successfully",
    "data": {
        "data": [
            {
                "id": 1,
                "user_name": "Citizen User",
                "reference_number": "CMP-69270D4B30FBF",
                "status": "انتظار",
                "complaint_type": "سوء سلوك موظف",
                "created_at": "2025-11-26 17:23",
                "updated_at": "2025-11-26 17:23"
            },
            {
                "id": 2,
                "user_name": "Citizen User",
                "reference_number": "CMP-69270D4B322D8",
                "status": "انتظار",
                "complaint_type": "مشكلة في الفوترة/المدفوعات",
                "created_at": "2025-11-26 17:23",
                "updated_at": "2025-11-26 17:23"
            }
        ],
        "links": {
            "first": "http://127.0.0.1:8000/api/complaints/all?page=1",
            "last": "http://127.0.0.1:8000/api/complaints/all?page=3",
            "prev": null,
            "next": "http://127.0.0.1:8000/api/complaints/all?page=2"
        },
        "meta": {
            "current_page": 1,
            "from": 1,
            "last_page": 3,
            "links": [
                {
                    "url": null,
                    "label": "« Previous",
                    "page": null,
                    "active": false
                },
                {
                    "url": "http://127.0.0.1:8000/api/complaints/all?page=1",
                    "label": "1",
                    "page": 1,
                    "active": true
                },
                {
                    "url": "http://127.0.0.1:8000/api/complaints/all?page=2",
                    "label": "2",
                    "page": 2,
                    "active": false
                },
                {
                    "url": "http://127.0.0.1:8000/api/complaints/all?page=3",
                    "label": "3",
                    "page": 3,
                    "active": false
                },
                {
                    "url": "http://127.0.0.1:8000/api/complaints/all?page=2",
                    "label": "Next »",
                    "page": 2,
                    "active": false
                }
            ],
            "path": "http://127.0.0.1:8000/api/complaints/all",
            "per_page": 10,
            "to": 10,
            "total": 30
        }
    }
}
```

## 👥 Employee APIs

### **15. Get incoming complaints for logged-in employee**

**Endpoint:**
`GET /api/incoming-complaints`

**Description:**

This endpoint retrieves all incoming complaints for the logged-in employee based on the most recent government entity they are assigned to.  
The system automatically identifies the employee’s active government department and returns all complaints directed to that entity.  
Results are returned **with pagination**, allowing efficient browsing of large complaint lists.

**Headers:**

```
Authorization: Bearer {EmployeeToken}
```

**Response Example:**

```json
{
    "status": 200,
    "message": "تم جلب الشكاوي الواردة بنجاح.",
    "data": {
        "data": [
            {
                "id": 4,
                "user_name": "Citizen User",
                "reference_number": "CMP-69270D4B3369D",
                "status": "انتظار",
                "complaint_type": "سوء سلوك موظف",
                "created_at": "2025-11-26 17:23",
                "updated_at": "2025-11-26 17:23"
            },
            {
                "id": 5,
                "user_name": "Citizen User",
                "reference_number": "CMP-69270D4B3408F",
                "status": "انتظار",
                "complaint_type": "مخالفة سياسات",
                "created_at": "2025-11-26 17:23",
                "updated_at": "2025-11-26 17:23"
            },
            {
                "id": 6,
                "user_name": "Citizen User",
                "reference_number": "CMP-69270D4B34D00",
                "status": "انتظار",
                "complaint_type": "مشكلة في الفوترة/المدفوعات",
                "created_at": "2025-11-26 17:23",
                "updated_at": "2025-11-26 17:23"
            }
        ],
        "links": {
            "first": "http://127.0.0.1:8000/api/complaints/incoming?page=1",
            "last": "http://127.0.0.1:8000/api/complaints/incoming?page=1",
            "prev": null,
            "next": null
        },
        "meta": {
            "current_page": 1,
            "from": 1,
            "last_page": 1,
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "page": null,
                    "active": false
                },
                {
                    "url": "http://127.0.0.1:8000/api/complaints/incoming?page=1",
                    "label": "1",
                    "page": 1,
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "page": null,
                    "active": false
                }
            ],
            "path": "http://127.0.0.1:8000/api/complaints/incoming",
            "per_page": 10,
            "to": 3,
            "total": 3
        }
    }
}
```

### **16. Show specific incoming complaint**

**Endpoint:**
`GET /api/complaints/incoming/{complaintId}`

**Description:**

This endpoint allows the logged-in employee to view the full details of a specific incoming complaint assigned to their government entity.  
It is used when the employee needs to inspect a single complaint, review its information, attachments, and current status before taking action on it.  
Access is restricted to complaints that belong to the employee’s assigned government entity.

**Headers:**

```
Authorization: Bearer {EmployeeToken}
Content-Type: application/json
```

**Response Example:**

```json
{
    "status": 200,
    "message": "تم جلب تفاصيل الشكوى بنجاح.",
    "data": {
        "id": 12,
        "reference_number": "CMP-40HJTPWUML",
        "user_name": "ola",
        "government_entity": "وزارة الصحة",
        "complaint_type": "شكوى خدمة",
        "status": "انتظار",
        "location_description": "12345678",
        "problem_description": "22",
        "attachments": [
            {
                "id": 23,
                "file_path": "http://127.0.0.1:8000/Storage/complaints/6a3vVKu1DfJzd39FKN5NlPC4waYbJGQZFRVW0jsq.pdf"
            }
        ],
        "created_at": "2025-11-19 21:06",
        "updated_at": "2025-11-19 21:06"
    }
}
```

### **17. Search complaints by reference number**

**Endpoint:**

`GET /api/complaints/search?reference_number={reference_number}`

**Description:**

This endpoint allows both the Super Admin and Employee to search for complaints using the complaint reference number.  
It returns all complaints matching the provided reference number and can be used to quickly locate a specific complaint in the system.

**Headers:**

```
Authorization: Bearer {AdminOrEmployeeToken}
Content-Type: application/json
```

**Query Parameters:**

| Key               | Type   | Required | Description                              |
|-------------------|--------|----------|-------------------------------------------|
| reference_number  | string | Yes      | The complaint reference number to search. |

**Response Example:**

```json
{
    "status": 200,
    "message": "تم العثور على الشكاوي بنجاح.",
    "data": [
        {
            "id": 1,
            "user_name": "ola",
            "reference_number": "CMP-UOOCLLZBQF",
            "status": "منجزة",
            "complaint_type": "شكوى خدمة",
            "created_at": "2025-11-19 14:36",
            "updated_at": "2025-11-19 16:18"
        }
    ]
}
```

### **18. Toggle complaint status**

**Endpoint:**

`PATCH /api/complaints/toggle-status/{complaintId}`

**Description:**

This endpoint allows the logged-in employee to toggle the status of a complaint between **"Pending"** and **"In Progress"**.  
It is used when the employee begins working on a complaint or pauses work and returns it to the pending queue.  
The response returns the **new updated status** after toggling.

**Headers:**

```
Authorization: Bearer {EmployeeToken}
Content-Type: application/json
```

**Response Example (Pending → In Progress):**

```json
{
    "status": 200,
    "message": "تم تغيير حالة الشكوى بنجاح.",
    "data": "قيد المعالجة"
}
```

**Response Example (In Progress → Pending):**

```json
{
    "status": 200,
    "message": "تم تغيير حالة الشكوى بنجاح.",
    "data": "انتظار"
}
```

### **19. Update complaint final status**

**Endpoint:**

`PATCH /api/complaints/status/{complaintId}`

**Description:**

This endpoint allows the logged-in employee to update the final status of a complaint **only if it is currently in the "In Progress" state**.  
The employee can change the complaint's status to either **"Completed"** or **"Rejected"** based on the result of their investigation or action.  
The response returns the **new updated status** after the change.

**Headers:**

```
Authorization: Bearer {EmployeeToken}
Content-Type: application/json
```

**Body Example:**

```json
{
    "status": "مرفوضة"
}
```

**or**

```json
{
    "status": "منجزة"
}
```

**Response Example (In Progress → Completed):**

```json
{
    "status": 200,
    "message": "تم تغيير حالة الشكوى بنجاح.",
    "data": "منجزة"
}
```

**Response Example (In Progress → Rejected):**

```json
{
    "status": 200,
    "message": "تم تغيير حالة الشكوى بنجاح.",
    "data": "مرفوضة"
}
```

### **20. Add note to complaint**

**Endpoint:**
`PUT /api/complaints/add-note/{complaintId}`

**Description:**

This endpoint allows the logged-in employee to add a note to a complaint.  
A note can only be added **if the complaint is currently in the "In Progress" state**.  
This feature is used by employees to provide explanations, requests for additional information, or any updates related to the complaint’s processing.

**Headers:**

```
Authorization: Bearer {EmployeeToken}
Content-Type: application/json
```

**Body Example:**

```json
{
    "note": "يجب أن ترفق المزيد من الصور لدراسة حالة الشكوى بشكل مفصل"
}
```

**Response Example (Complaint not in 'In Progress' state):**

```json
{
    "status": 403,
    "message": "لا يمكن إضافة ملاحظة إلا إذا كانت الشكوى قيد المعالجة."
}
```

**Response Example (Note added successfully):**

```json
{
    "status": 200,
    "message": "تم إضافة الملاحظة بنجاح.",
    "data": {
        "complaint_id": 31,
        "changed_by": 3,
        "old_status": "قيد المعالجة",
        "new_status": "قيد المعالجة",
        "notes": "يجب أن ترفق المزيد من الصور لدراسة حالة الشكوى بشكل مفصل",
        "updated_at": "2025-11-24T11:01:43.000000Z",
        "created_at": "2025-11-24T11:01:43.000000Z",
        "id": 3
    }
}
```

### **21. Government entity statistics for employee**

**Endpoint:**

`GET /api/statistics/government`

**Description:**

This endpoint allows the logged-in employee to view statistics related to the government entity they are currently assigned to.  
It provides an overview of all complaints linked to that entity, including totals and breakdown by status.  
The statistics help employees understand workload distribution and monitor progress within their department.

**Headers:**

```
Authorization: Bearer {EmployeeToken}
Content-Type: application/json
```

**Response Example:**

```json
{
    "status": 200,
    "message": "تم عرض الاحصائيات بنجاح",
    "data": {
        "total_complaints": 3,
        "complaints_pending": 1,
        "complaints_processing": 2,
        "complaints_rejected": 0,
        "complaints_completed": 0
    }
}
```

