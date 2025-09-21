# TODO LIST API - Sprint Planning

## 🎯 Project Overview

RESTful API for task management (TODO LIST) built with Laravel, following a layered architecture (Service Pattern, Repository Pattern) and best practices from the API Starter Kit. Based on hdeawy’s API Starter Kit — https://github.com/hdeawy/api-starter-kit/

**Initial approach:** Simplified TODO list system without authentication, preparing for future scalability.

## 📐 Implementation Strategy

### 1. **Existing Structure Analysis**

Boilerplate Architecture:

-   **Models:** Domain entities
-   **Repositories:** Data access abstraction
-   **Services:** Business logic
-   **Controllers:** HTTP presentation layer
-   **Requests:** Input validation
-   **Resources:** Output transformation

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

### 3. **Planned Development Flow**

#### **Phase 1: Initial Setup (Simplified)**

-   Migration to create `tasks` table without relationships
-   Basic `Task` model (without relationships)
-   Repository interface and simplified implementation
-   Basic service interface and implementation

#### **Phase 2: Application Layer (Public)**

-   Request classes for simple validation
-   Resource for data transformation
-   Controller with public CRUD endpoints
-   Routes without authentication middleware

#### **Phase 3: Testing (Basic)**

-   Basic unit and feature tests
-   Public endpoints documentation

#### **Phase 4: User Implementation (Future)**

-   Add authentication system
-   Relationships with User
-   Protection middleware
-   Authorization and ownership validations

### 4. **Why this approach?**

-   **MVP First:** Deliver value quickly with core functionality
-   **SOLID:** Each class has a single responsibility
-   **PSR-12:** Clean namespace structure and code
-   **Testability:** Well-separated layers facilitate testing
-   **Scalability:** Prepared to add authentication later

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

-   [ ] v4.8 - Document endpoints in Postman/Insomnia collection
-   [ ] v4.9 - Update README with public API information
-   [ ] v4.10 - Document basic use cases and examples
-   [ ] v4.11 - Specify that API is public (no authentication)

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

<!--
## 🚀 **Definition of Ready**

For each backlog item, check:

-   [ ] Requirements clearly defined
-   [ ] Acceptance criteria specified
-   [ ] Dependencies identified
-   [ ] Effort estimation defined

## ✅ **Definition of Done**

To consider a task complete:

-   [ ] Code implemented following PSR-12
-   [ ] Unit and integration tests passing
-   [ ] Code review approved
-   [ ] Documentation updated
-   [ ] Test environment deployment completed
-   [ ] Performance validated
        -->

## 🛠️ **Useful Commands**

```bash
# Run tests
php artisan test

# Check code style
composer pint

# Static analysis
composer stan

# Generate migrations
php artisan make:migration create_tasks_table

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# View soft deleted records (via tinker)
php artisan tinker
>>> App\Models\Task::onlyTrashed()->get()
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
│   │   │       ├── AuthController.php
│   │   │       ├── TaskController.php
│   │   │       └── UserController.php
│   │   └── Controller.php
│   ├── Requests
│   │   └── Api/V1
│   │       ├── Auth
│   │       │   ├── LoginRequest.php
│   │       │   └── RegisterRequest.php
│   │       ├── TaskStoreRequest.php
│   │       ├── TaskUpdateRequest.php
│   │       ├── UserStoreRequest.php
│   │       └── UserUpdateRequest.php
│   └── Resources
│       └── Api
│           ├── Task
│           │   ├── TaskCollection.php
│           │   └── TaskResource.php
│           └── User
│               └── UserResource.php
├── Models
│   ├── Task.php
│   └── User.php
├── Providers
│   ├── AppServiceProvider.php
│   ├── RepositoryServiceProvider.php
│   ├── RouteServiceProvider.php
│   ├── ServiceClassProvider.php
│   └── TelescopeServiceProvider.php
├── Repositories
│   ├── Base/Concretes
│   │   ├── BaseRepository.php
│   │   └── QueryableRepository.php
│   ├── Base/Contracts
│   │   ├── BaseRepositoryInterface.php
│   │   └── QueryableRepositoryInterface.php
│   ├── Task
│   │   ├── Concretes
│   │   │   └── TaskRepository.php
│   │   └── Contracts
│   │       └── TaskRepositoryInterface.php
│   └── User
│       ├── Concretes
│       │   └── UserRepository.php
│       └── Contracts
│           └── UserRepositoryInterface.php
├── Services
│   ├── Base/Concretes
│   │   └── BaseService.php
│   ├── Base/Contracts
│   │   └── BaseServiceInterface.php
│   ├── Concretes
│   │   ├── AuthService.php
│   │   ├── TaskService.php
│   │   └── UserService.php
│   └── Contracts
│       ├── AuthServiceInterface.php
│       ├── TaskServiceInterface.php
│       └── UserServiceInterface.php
└── Traits
    └── ApiResponse.php

database/
├── factories
│   ├── TaskFactory.php
│   └── UserFactory.php
├── migrations
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2025_03_23_160911_add_password_reset_fields_to_users_table.php
│   ├── 2025_04_22_175326_create_telescope_entries_table.php
│   └── 2025_09_19_130810_create_tasks_table.php
└── seeders
    ├── DatabaseSeeder.php
    ├── TaskSeeder.php
    └── TestUsersSeeder.php

routes/
└── v1
    └── api.php

tests/
├── Feature
│   ├── Http
│   │   └── Controllers
│   │       ├── AuthControllerTest.php
│   │       └── UserControllerTest.php
│   └── Utils
│       └── UserTestUtils.php
├── Unit
│   └── Services
│       └── UserServiceTest.php
├── Pest.php
└── TestCase.php
```

<!--## 📈 **Success Metrics - Initial Phase**

-   **Code Coverage:** Minimum 70% (initial phase)
-   **Response Time:** < 200ms for basic endpoints
-   **Uptime:** 99.9%
-   **PSR-12 Compliance:** 100%
-   **Zero Security Vulnerabilities:** Validated by tools
-->

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
