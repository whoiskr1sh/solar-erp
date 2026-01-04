# Solar ERP - User Credentials & Role Access

## 🔐 **Login Credentials for All Roles**

**Default Password for All Users:** `password123`

---

### **1. SUPER ADMIN**
- **Email:** `superadmin@solarerp.com`
- **Employee ID:** `EMP001`
- **Name:** Super Administrator
- **Department:** IT
- **Salary:** ₹1,50,000
- **Permissions:** 203 (All system permissions)
- **Phone:** +91-9876543210

---

### **2. SALES MANAGER**
- **Email:** `sales.manager@solarerp.com`
- **Employee ID:** `EMP002`
- **Name:** Rajesh Kumar
- **Department:** Sales
- **Salary:** ₹1,20,000
- **Permissions:** 34 (Sales management, leads, projects, quotations)
- **Phone:** +91-9876543212

---

### **3. TELE SALES**
- **Email:** `tele.sales@solarerp.com`
- **Employee ID:** `EMP003`
- **Name:** Priya Sharma
- **Department:** Sales
- **Salary:** ₹45,000
- **Permissions:** 17 (Lead management, quotations, customer management)
- **Phone:** +91-9876543214

---

### **4. FIELD SALES**
- **Email:** `field.sales@solarerp.com`
- **Employee ID:** `EMP004`
- **Name:** Amit Singh
- **Department:** Sales
- **Salary:** ₹55,000
- **Permissions:** 24 (Field sales, project viewing, commission tracking)
- **Phone:** +91-9876543216

---

### **5. PROJECT MANAGER**
- **Email:** `project.manager@solarerp.com`
- **Employee ID:** `EMP005`
- **Name:** Vikram Patel
- **Department:** Projects
- **Salary:** ₹1,00,000
- **Permissions:** 35 (Complete project management, tasks, materials, budget)
- **Phone:** +91-9876543218

---

### **6. PROJECT ENGINEER**
- **Email:** `project.engineer@solarerp.com`
- **Employee ID:** `EMP006`
- **Name:** Suresh Reddy
- **Department:** Projects
- **Salary:** ₹70,000
- **Permissions:** 25 (Project execution, task management, quality checks)
- **Phone:** +91-9876543220

---

### **7. LIASONING EXECUTIVE**
- **Email:** `liaisoning@solarerp.com`
- **Employee ID:** `EMP007`
- **Name:** Meera Joshi
- **Department:** Liaisoning
- **Salary:** ₹60,000
- **Permissions:** 17 (Regulatory compliance, permits, approvals, documents)
- **Phone:** +91-9876543222

---

### **8. QUALITY ENGINEER**
- **Email:** `quality.engineer@solarerp.com`
- **Employee ID:** `EMP008`
- **Name:** Ravi Kumar
- **Department:** Quality
- **Salary:** ₹65,000
- **Permissions:** 19 (Quality checks, inventory audits, material verification)
- **Phone:** +91-9876543224

---

### **9. PURCHASE MANAGER/EXECUTIVE**
- **Email:** `purchase.manager@solarerp.com`
- **Employee ID:** `EMP009`
- **Name:** Deepak Gupta
- **Department:** Purchase
- **Salary:** ₹90,000
- **Permissions:** 32 (Vendor management, purchase orders, RFQ, GRN)
- **Phone:** +91-9876543226

---

### **10. ACCOUNT EXECUTIVE**
- **Email:** `account.executive@solarerp.com`
- **Employee ID:** `EMP010`
- **Name:** Sunita Agarwal
- **Department:** Accounts
- **Salary:** ₹75,000
- **Permissions:** 38 (Financial management, invoices, payments, budgets)
- **Phone:** +91-9876543228

---

### **11. STORE EXECUTIVE**
- **Email:** `store.executive@solarerp.com`
- **Employee ID:** `EMP011`
- **Name:** Manoj Verma
- **Department:** Store
- **Salary:** ₹50,000
- **Permissions:** 31 (Inventory management, warehouse, stock, GRN)
- **Phone:** +91-9876543230

---

### **12. SERVICE ENGINEER**
- **Email:** `service.engineer@solarerp.com`
- **Employee ID:** `EMP012`
- **Name:** Kiran Nair
- **Department:** Service
- **Salary:** ₹60,000
- **Permissions:** 24 (Service requests, complaints, AMC, maintenance)
- **Phone:** +91-9876543232

---

### **13. HR MANAGER**
- **Email:** `hr.manager@solarerp.com`
- **Employee ID:** `EMP013`
- **Name:** Anita Desai
- **Department:** HR
- **Salary:** ₹1,10,000
- **Permissions:** 45 (Complete HR management, employees, payroll, attendance)
- **Phone:** +91-9876543234

---

## 🚀 **Quick Access Commands**

### **Login to System**
```bash
# Use any email above with password: password123
# Example: superadmin@solarerp.com / password123
```

### **Check User Roles**
```bash
php artisan roles:manage list-user-roles --user="sales.manager@solarerp.com"
```

### **Assign Additional Roles**
```bash
php artisan roles:manage assign-role --role="SALES MANAGER" --user="newuser@example.com"
```

### **List All Roles**
```bash
php artisan roles:manage list-roles
```

---

## 📊 **Role Summary**

| Role | Users | Permissions | Department |
|------|-------|-------------|------------|
| SUPER ADMIN | 1 | 203 | IT |
| SALES MANAGER | 1 | 34 | Sales |
| TELE SALES | 1 | 17 | Sales |
| FIELD SALES | 1 | 24 | Sales |
| PROJECT MANAGER | 1 | 35 | Projects |
| PROJECT ENGINEER | 1 | 25 | Projects |
| LIASONING EXECUTIVE | 1 | 17 | Liaisoning |
| QUALITY ENGINEER | 1 | 19 | Quality |
| PURCHASE MANAGER/EXECUTIVE | 1 | 32 | Purchase |
| ACCOUNT EXECUTIVE | 1 | 38 | Accounts |
| STORE EXECUTIVE | 1 | 31 | Store |
| SERVICE ENGINEER | 1 | 24 | Service |
| HR MANAGER | 1 | 45 | HR |

---

## 🔒 **Security Notes**

1. **Change Default Passwords:** All users have the default password `password123`. Change these immediately after first login.

2. **Role-Based Access:** Each user can only access features based on their assigned role permissions.

3. **Employee ID Uniqueness:** Each employee has a unique ID (EMP001-EMP013) for identification.

4. **Department Segregation:** Users are organized by departments with appropriate access levels.

5. **Emergency Contacts:** All users have emergency contact information on file.

---

## 📞 **Contact Information**

- **Corporate Office:** Mumbai
- **IT Support:** superadmin@solarerp.com
- **HR Queries:** hr.manager@solarerp.com
- **Sales Inquiries:** sales.manager@solarerp.com

---

## 🎯 **Next Steps**

1. **Login Testing:** Test login with each role to verify access
2. **Password Updates:** Change default passwords for all users
3. **Permission Testing:** Verify each role can access only their assigned features
4. **User Training:** Provide role-specific training to users
5. **Documentation:** Share this credential list with relevant stakeholders

---

**Generated on:** {{ date('Y-m-d H:i:s') }}  
**System:** Solar ERP v1.0  
**Status:** ✅ All users created and roles assigned successfully


