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
-   [ ] v2.1 - Implement `TaskRepository` with basic operations
-   [ ] v2.2 - Configure QueryableRepository for Tasks
-   [ ] v2.3 - Add only essential filters (completed, search)

**Service Layer**

-   [ ] v2.4 - Create simplified `TaskServiceInterface`
-   [ ] v2.5 - Implement `TaskService` with basic operations
-   [ ] v2.6 - Add basic business validations
-   [ ] v2.7 - Remove user-related validations

**Configuration**

-   [ ] v2.8 - Register bindings in `RepositoryServiceProvider`
-   [ ] v2.9 - Register bindings in `ServiceClassProvider`

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

-   [ ] v3.0 - Create `TaskStoreRequest` without user_id validations
-   [ ] v3.1 - Create simplified `TaskUpdateRequest`
-   [ ] v3.2 - Implement only essential validations

**Response Formatting**

-   [ ] v3.3 - Create `TaskResource` for output formatting
-   [ ] v3.4 - Implement `TaskCollection` if necessary
-   [ ] v3.5 - Standardize responses following ApiResponse trait

**Controller**

-   [ ] v3.6 - Implement `TaskController` with public endpoints
-   [ ] v3.7 - Complete CRUD endpoints (index, store, show, update, destroy with soft delete)
-   [ ] v3.8 - Implement basic filters and search (excluding soft deleted)
-   [ ] v3.9 - Add pagination
-   [ ] v3.10 - Remove authorization checks
-   [ ] v3.11 - Add endpoint to restore soft deleted tasks (optional)

**Routes**

-   [ ] v3.12 - Define routes in `routes/v1/api.php`
-   [ ] v3.13 - Organize public route grouping

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

-   [ ] v4.0 - Tests for TaskService (basic operations)
-   [ ] v4.1 - Tests for TaskRepository
-   [ ] v4.2 - Tests for Request validations
-   [ ] v4.3 - Tests for TaskResource

**Feature Tests**

-   [ ] v4.4 - Tests for TaskController (all public endpoints)
-   [ ] v4.5 - Basic filter and search tests
-   [ ] v4.6 - Pagination tests
-   [ ] v4.7 - Basic edge case tests

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

## 📂 **Expected File Structure - Initial Phase**

```
app/
├── Models/
│   └── Task.php (without relationships)
├── Repositories/
│   └── Task/
│       ├── Contracts/
│       │   └── TaskRepositoryInterface.php
│       └── Concretes/
│           └── TaskRepository.php (without user filters)
├── Services/
│   ├── Contracts/
│   │   └── TaskServiceInterface.php
│   └── Concretes/
│       └── TaskService.php (without user validations)
├── Http/
│   ├── Controllers/Api/V1/
│   │   └── TaskController.php (public endpoints)
│   ├── Requests/Api/V1/
│   │   ├── TaskStoreRequest.php (without user_id)
│   │   └── TaskUpdateRequest.php (simplified)
│   └── Resources/Api/Task/
│       └── TaskResource.php
database/
├── migrations/
│   └── 2025_09_19_130810_create_tasks_table.php (without user_id)
├── factories/
│   └── TaskFactory.php (without user_id)
└── seeders/
    └── TaskSeeder.php (no user dependencies)
tests/
├── Feature/Http/Controllers/
│   └── TaskControllerTest.php (public tests)
└── Unit/Services/
    └── TaskServiceTest.php (basic tests)
routes/v1/
└── api.php (public routes)
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
