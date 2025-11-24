# Government Complaints Management System

## 📌 Project Overview

We implemented a fully integrated **Government Complaints Management System** that enables citizens to easily submit complaints, attach supporting documents, and follow up on their status in real time.  
The system consists of a **mobile application** for citizens and a **web-based dashboard** for government employees and the system administrator.

Throughout the project, we implemented secure authentication with OTP verification, developed a structured complaint submission workflow, and built a complete tracking system that updates citizens instantly using in-app notifications.  
On the administration side, we implemented tools for government departments to manage complaints, update statuses, add notes, request additional information, and generate analytical reports. The system also supports role-based access control, logging, versioning, and scalability features.

The application was developed following a layered architecture, separating the user interface, business logic, and data management to ensure maintainability and performance.

---

## ✔️ Functional Requirements

### **1. User Registration & Login**
- Users can create accounts using email or phone number.
- OTP verification is required before activating the account.
- Accounts cannot be used until fully verified.

### **2. Complaint Submission**
- Citizens can submit detailed complaints including:
  - Complaint type  
  - Related government department  
  - Description of the issue  
  - Location information  
  - Image and document attachments
- A unique reference ID is generated for each complaint.

### **3. Complaint Status Tracking**
- Users can view real-time updates:
  - New  
  - Under Processing  
  - Completed  
  - Rejected
- Push notifications are sent for every update or status change.

### **4. Government Department Dashboard**
- Employees can:
  - View complaints assigned to their department  
  - Update complaint status  
  - Add notes  
  - Request more information from the citizen

### **5. Admin Management Panel**
- Admin can:
  - Manage employee and department accounts  
  - Assign roles and permissions  
  - Monitor all submitted complaints  
  - Export reports as PDF or CSV  
  - View system logs and performance insights

---

## ✔️ Non-Functional Requirements

### **1. Concurrency & Conflict Prevention**
- The system prevents multiple employees from editing the same complaint at the same time.  
- Once a complaint is opened for processing, it becomes “locked” until the employee completes the action.

### **2. Versioning & Audit Trail**
- Every change (status update, notes, attachments) is stored with timestamps and user identifiers.
- A full historical log is maintained for transparency.

### **3. Notification System**
- The application sends instant notifications to users for:
  - Complaint submission  
  - Status changes  
  - Additional information requests

### **4. Usability**
- Interfaces are designed to be simple, intuitive, and suitable for all user types.
- Clear buttons, labels, and step-by-step guidance are provided.

### **5. Cross-Platform Compatibility**
- Mobile app supports both iOS and Android.
- Dashboard works on all modern browsers.

### **6. Security**
- Role-based access control (Citizen – Employee – Admin)
- Protection against brute-force login attempts
- Encrypted data storage and secure communication
- Logging of failed login attempts

### **7. Backup**
- Automated daily/weekly database backups for complaints and attachments.
- Backup recovery procedures ensured.

### **8. Scalability & Performance**
- The system can handle 100+ concurrent users with no noticeable performance drop.
- Performance was tested to ensure acceptable response times.

### **9. High Availability**
- System designed to maintain stable operation and minimize downtime.

--- 
## ✔️ Installation

Steps

 ### **1.Clone the repository:**
 ```
git clone <https://github.com/OlaMorad/Government-Complaints-System.git>
```
### **2.Install dependencies:**
```
composer install
```
### **3.Create the environment file:**
```
cp .env.example .env
```
### **4.Generate application key:**
```
php artisan key:generate
```
### **5.Run migrations:**
```
php artisan migrate
```
### **6.Run seeders:**
```
php artisan db:seed
```
### **7.Start the development server:**
```
php artisan serve
```
