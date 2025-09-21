# TODO LIST API - Sprint Planning

## 🎯 Project Overview

RESTful API for task management (TODO LIST) built with Laravel, following a layered architecture (Service Pattern, Repository Pattern) and best practices from the API Starter Kit. Based on hdeawy’s API Starter Kit — https://github.com/hdeawy/api-starter-kit/

**Initial approach:** Simplified TODO list system without authentication, preparing for future scalability.

## 📐 Implementation Strategy

### 1. **Existing Structure Analysis**

Boilerplate Architecture:

-   **Framework:** Laravel 12.x
-   **Starter Kit Base:** [hdeawy/api-starter-kit](https://github.com/hdeawy/api-starter-kit)
-   **PHP:** ^8.2
-   **SQLite:** 3

## 🗺️ API Request Flow & Folder Structure

```text

[Client Request]
   |
   v
[routes/v1/api]
   |
   v
[TaskController] <-> [TaskStoreRequest / TaskUpdateRequest]
   |
   v
[TaskServiceInterface / TaskService]
   |
   v
[TaskRepositoryInterface / TaskRepository]
   |
   v
[Model / Database]
   |
   v
[TaskCollection / TaskResource]
   |
   v
[ApiResponse]
   |
   v
[TaskController]
   |
   v
[Client Response]
```

> **Legend:**

-   **routes/**: API endpoints
-   **Controllers/**: Request/response handling
-   **Requests/**: Data validation
-   **Services/Contracts/**: Service interfaces
-   **Services/Concretes/**: Business logic
-   **Repositories/Task/Contracts/**: Repository interfaces
-   **Repositories/Task/Concretes/**: Data access implementation
-   **Models/**: Database entities
-   **Resources/**: Output formatting

### 2. **Architecture Decisions - Simplified Initial Phase**

**Scope Change:** Initial implementation without user system, leaving authentication for future sprint. Focus on building an MVP first.

`Task` entity with minimal structure:

| Field        | Type        | Description           |
| ------------ | ----------- | --------------------- |
| `id`         | Primary Key | Unique identifier     |
| `title`      | String      | Task title            |
| `completed`  | Boolean     | Completion status     |
| `deleted_at` | Timestamp   | Soft delete timestamp |
| `created_at` | Timestamp   | Creation date         |
| `updated_at` | Timestamp   | Last update date      |

### 3. **Planned Development**

-   **MVP First:** Deliver value quickly with core functionality
-   **SOLID:** Each class has a single responsibility
-   **PSR-12:** Clean namespace structure and code
-   **Testability:** Well-separated layers facilitate testing
-   **Scalability:** Prepared to add authentication later

## 🛠️ **Start Project**

```bash
# 1. Clone the repository
git clone https://github.com/danielbelle/todo-list-laravel-backend.git todoapi
cd todoapi

# 2. Install dependencies
composer install

# 3. Copy and configure environment file
cp .env.example .env
# Edit .env with sqlite configuration ready

# 4. Generate application key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Run seeders (populate database with sample data)
php artisan db:seed

# 7. Run the development server
php artisan serve

# 8. Scribe API documentation
http://localhost/docs

# 9. Run tests (unit and feature)
php artisan test
```

## 📂 **Expected File Structure - Current Project**

```text
app/
├── Exceptions
│   └── Handler.php
├── Http
│   ├── Controllers
│   │   ├── Api
│   │   │   ├── BaseApiController.php
│   │   │   └── V1
│   │   │       └── TaskController.php
│   │   └── Controller.php
│   ├── Requests
│   │   └── Api/V1
│   │       ├── TaskStoreRequest.php
│   │       └── TaskUpdateRequest.php
│   └── Resources
│       └── Api
│           └── Task
│               ├── TaskCollection.php
│               └── TaskResource.php
├── Models
│   └── Task.php
├── Providers
│   ├── AppServiceProvider.php
│   ├── RepositoryServiceProvider.php
│   ├── RouteServiceProvider.php
│   ├── ServiceClassProvider.php
│   └── TelescopeServiceProvider.php
├── Repositories
│   └── Task
│       ├── Concretes
│       │   └── TaskRepository.php
│       └── Contracts
│           └── TaskRepositoryInterface.php
├── Services
│   ├── Base/Concretes
│   │   └── BaseService.php
│   ├── Base/Contracts
│   │   └── BaseServiceInterface.php
│   ├── Concretes
│   │   └── TaskService.php
│   └── Contracts
│       └── TaskServiceInterface.php
└── Traits
    └── ApiResponse.php

database/
├── factories
│   └── TaskFactory.php
├── migrations
│   └── 2025_09_19_130810_create_tasks_table.php
└── seeders
    ├── DatabaseSeeder.php
    └── TaskSeeder.php


routes/
└── v1
    └── api.php

tests/
├── Feature
│   ├── Acceptance
│   │   └── TaskAcceptanceTest.php
│   ├── Concurrency
│   │   └── TaskConcurrencyTest.php
│   ├── EdgeCases
│   │   ├── TaskEdgeCasesTest.php
│   │   ├── TaskFilterEdgeCasesTest.php
│   │   ├── TaskPaginationEdgeCasesTest.php
│   │   ├── TaskResponseStructureTest.php
│   │   └── TaskSoftDeleteEdgeCasesTest.php
│   ├── EndToEnd
│   │   └── TaskWorkflowTest.php
│   ├── Http
│   │   └── Controllers
│   │       └── Api
│   │           └── V1
│   │               └── TaskControllerTest.php
│   ├── Performance
│   │   └── TaskPerformanceTest.php
│   ├── Requests
│   │   ├── TaskStoreRequestTest.php
│   │   └── TaskUpdateRequestTest.php
│   ├── Resources
│   │   └── TaskResourceTest.php
│   └── Smoke
│       └── ApiSmokeTest.php

├── Unit
│   ├── Repositories
│   │   └── Task
│   │       └── TaskRepositoryTest.php
│   └── Services
│       └── TaskServiceTest.php
├── Pest.php
└── TestCase.php
```

## 📋 Sprint Structure

### **Sprint 1: Setup and Modeling**

#### 🎯 **Objective:**

Configure the base structure for task management without user system.

#### 📝 **Tasks:**

**Database & Models**

-   [x] v1.0 - Create simplified migration for `tasks` table (with soft delete support)
-   [x] v1.1 - Implement basic `Task` model (with SoftDeletes trait)
-   [x] v1.2 - Define Factory for `Task` without user_id
-   [x] v1.3 - Create Seeder for test data

**Criteria Definition:**

-   [x] v1.4 - Define basic business rules for tasks
-   [x] v1.5 - Document simplified data structure
-   [x] v1.6 - Validate functionality without user dependencies

#### ✅ **Acceptance Criteria:**

-   Migration executed without errors
-   Task model with minimum necessary fields
-   Seeds working without user dependencies
-   Factory generating consistent data

---

### **Sprint 2: Repository and Service Layer**

#### 🎯 **Objective:**

Implement data access and business logic layers without authentication.

#### 📝 **Tasks:**

**Repository Layer**

-   [x] v2.0 - Create `TaskRepositoryInterface` without user filters
-   [x] v2.1 - Implement `TaskRepository` with basic operations
-   [x] v2.2 - Configure QueryableRepository for Tasks
-   [x] v2.3 - Add only essential filters (completed, search)

**Service Layer**

-   [x] v2.4 - Create simplified `TaskServiceInterface`
-   [x] v2.5 - Implement `TaskService` with basic operations
-   [x] v2.6 - Add basic business validations

**Configuration**

-   [x] v2.7 - Register bindings in `RepositoryServiceProvider` and `ServiceClassProvider`

#### ✅ **Acceptance Criteria:**

-   Repository working with basic filters
-   Service with essential validations implemented
-   Dependency injection configured
-   CRUD operations working without user context

---

### **Sprint 3: HTTP Layer (Public API Endpoints)**

#### 🎯 **Objective:**

Create public RESTful endpoints for task management.

#### 📝 **Tasks:**

**Request Validation**

-   [x] v3.0 - Create `TaskStoreRequest` without user_id validations
-   [x] v3.1 - Create simplified `TaskUpdateRequest`
-   [x] v3.2 - Implement only essential validations

**Response Formatting**

-   [x] v3.3 - Create `TaskResource` for output formatting
-   [x] v3.4 - Implement `TaskCollection` if necessary
-   [x] v3.5 - Standardize responses following ApiResponse trait

**Controller**

-   [x] v3.6 - Implement `TaskController` with public endpoints
-   [x] v3.7 - Complete CRUD endpoints (index, store, show, update, destroy with soft delete)
-   [x] v3.8 - Implement basic filters and search. Simplify Task structure

**Routes**

-   [x] v3.9 - Define routes in `routes/v1/api.php`
-   [x] v3.10 - Organize public route grouping

#### ✅ **Acceptance Criteria:**

-   All CRUD endpoints working publicly
-   Basic validations implemented and tested
-   Standardized responses
-   Endpoints accessible without authentication

---

### **Sprint 4: Testing and Documentation (Basic)**

#### 🎯 **Objective:**

Ensure quality through basic tests and clear documentation.

#### 📝 **Tasks:**

**Unit Tests**

-   [x] v4.0 - Tests for TaskService (basic operations)
-   [x] v4.1 - Tests for TaskRepository
-   [x] v4.2 - Tests for Request validations
-   [x] v4.3 - Tests for TaskResource

**Feature Tests**

-   [x] v4.4 - Tests for TaskController (all public endpoints)
-   [x] v4.5 - Basic filter and search tests
-   [x] v4.6 - Performance and Pagination Tests
-   [x] v4.7 - Edge case tests

**Documentation**

-   [x] v4.8 - Document endpoints in Postman/Insomnia collection
-   [x] v4.9 - Update README with public API information
-   [x] v4.10 - Document basic use cases and examples
-   [x] v4.11 - Specify that API is public (no authentication)

#### ✅ **Acceptance Criteria:**

-   Test coverage > 70% (basic)
-   All tests passing
-   Complete public endpoints documentation
-   README updated with current phase information

---

### **Sprint 5: User Implementation and Authentication (Future)**

#### 🎯 **Objective:**

Implement user system and authentication in the existing API.

#### 📝 **Tasks:**

**Database Migration**

-   [ ] v5.0 - Create migration to add `user_id` field to tasks table
-   [ ] v5.1 - Update Factory to include user_id
-   [ ] v5.2 - Create new seeder with relationships

**Model Updates**

-   [ ] v5.3 - Update `Task` model with `belongsTo(User)` relationship
-   [ ] v5.4 - Update `User` model with `hasMany(Task)` relationship

**Authentication & Authorization**

-   [ ] v5.5 - Implement JWT authentication in endpoints
-   [ ] v5.6 - Add authentication middleware to routes
-   [ ] v5.7 - Implement authorization (user only accesses their tasks)

**Service Layer Updates**

-   [ ] v5.8 - Update `TaskService` with ownership validations
-   [ ] v5.9 - Add user filters in `TaskRepository`
-   [ ] v5.10 - Implement authorization logic

**Controller Updates**

-   [ ] v5.11 - Update `TaskController` to use authenticated user context
-   [ ] v5.12 - Add authorization checks
-   [ ] v5.13 - Filter tasks by logged user

**Testing Updates**

-   [ ] v5.14 - Update tests to include authentication
-   [ ] v5.15 - Authorization tests
-   [ ] v5.16 - Relationship tests

#### ✅ **Acceptance Criteria:**

-   Authentication system working
-   Users only access their own tasks
-   All tests updated and passing
-   API fully protected and functional

---

## 🔄 **Evolution Roadmap**

### **Current Phase (Sprints 1-4):** Basic Public API

-   ✅ Task CRUD without authentication
-   ✅ Scalable structure prepared
-   ✅ Basic tests implemented

### **Future Phase (Sprint 5+):** API with Authentication

-   🔄 Integrated user system
-   🔄 JWT Authentication
-   🔄 Authorization and ownership validations
-   🔄 Complete tests with user context

---

_This planning implements an MVP (Minimum Viable Product) first approach, following the architecture already established in the Laravel API Starter Kit and preparing for future scalability._
