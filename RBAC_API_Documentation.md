# RBAC API Documentation

## 1. Sign Up
**POST** `/api/signup`

**Request:**
```json
{
  "tenant_name": "Acme Corp",
  "tenant_slug": "ACME",
  "name": "John Doe",
  "email": "john@acme.com",
  "password": "Password123!",
  "password_confirmation": "Password123!"
}
```

**Success Response:**
```json
{
  "success": true,
  "status": 201,
  "message": "Registration successful",
  "data": {
    "tenant": { /* tenant object */ },
    "user": { /* user object */ },
    "token": "..."
  },
  "errors": [],
  "meta": []
}
```

---

## 2. Log In
**POST** `/api/login`

**Request:**
```json
{
  "tenant_slug": "ACME",
  "email": "john@acme.com",
  "password": "Password123!"
}
```

**Success Response:**
```json
{
  "success": true,
  "status": 200,
  "message": "Login successful",
  "data": {
    "user": { /* user object */ },
    "tenant": { /* tenant object */ },
    "token": "..."
  },
  "errors": [],
  "meta": []
}
```

---

## 3. Get User Roles & Permissions
**GET** `/api/user/permissions`

**Headers:**
`Authorization: Bearer <TOKEN>`

**Success Response:**
```json
{
  "success": true,
  "status": 200,
  "message": "User roles and permissions fetched.",
  "data": {
    "user_id": 1,
    "user_name": "John Doe",
    "roles": ["Admin", "Editor"],
    "permissions": [
      { "module": "Invoices", "action": "view" },
      { "module": "Invoices", "action": "delete" }
    ]
  },
  "errors": [],
  "meta": []
}
```

---

## 4. Create Role
**POST** `/api/roles`

**Headers:**
`Authorization: Bearer <TOKEN>`

**Request:**
```json
{
  "role_name": "Editor"
}
```

---

## 5. Update Role
**PUT** `/api/roles/{id}`

**Headers:**
`Authorization: Bearer <TOKEN>`

**Request:**
```json
{
  "role_name": "Manager"
}
```

---

## 6. Delete Role
**DELETE** `/api/roles/{id}`

**Headers:**
`Authorization: Bearer <TOKEN>`

---

## 7. Assign Permissions to Role
**POST** `/api/roles/{id}/permissions`

**Headers:**
`Authorization: Bearer <TOKEN>`

**Request:**
```json
{
  "permission_ids": [1, 2, 3]
}
```

---

## 8. Assign Role to User
**POST** `/api/roles/assign`

**Headers:**
`Authorization: Bearer <TOKEN>`

**Request:**
```json
{
  "user_id": 5,
  "role_ids": [1, 2]
}
```

**Success Response:**
```json
{
  "success": true,
  "status": 200,
  "message": "Roles assigned to user successfully.",
  "data": {
    "User_Id": 5,
    "User_Name": "Faiz",
    "Roles": ["Editor", "Viewer"]
  },
  "errors": [],
  "meta": []
}
```

---

## 9. Create Permission
**POST** `/api/permissions`

**Headers:**
`Authorization: Bearer <TOKEN>`

**Request:**
```json
{
  "module": "Invoices",
  "action": "delete"
}
```

---

## 10. List Permissions
**GET** `/api/permissions`

**Headers:**
`Authorization: Bearer <TOKEN>`

**Success Response:**
```json
{
  "success": true,
  "status": 200,
  "message": "Permissions fetched successfully.",
  "data": [
    { "id": 1, "module": "Invoices", "action": "view" },
    { "id": 2, "module": "Invoices", "action": "delete" }
  ],
  "errors": [],
  "meta": []
}
```

---

Replace `<TOKEN>` with the token received from the login/signup response. All error responses follow the same structure, with `"success": false` and appropriate error messages in the `"errors"` field.
