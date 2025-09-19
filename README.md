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

-   [ ] Create simplified migration for `tasks` table (with soft delete support)
-   [ ] Implement basic `Task` model (with SoftDeletes trait)
-   [ ] Define Factory for `Task` without user_id
-   [ ] Create Seeder for test data

**Criteria Definition:**

-   [ ] Define basic business rules for tasks
-   [ ] Document simplified data structure
-   [ ] Validate functionality without user dependencies

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

-   [ ] Create `TaskRepositoryInterface` without user filters
-   [ ] Implement `TaskRepository` with basic operations
-   [ ] Configure QueryableRepository for Tasks
-   [ ] Add only essential filters (completed, search)

**Service Layer**

-   [ ] Create simplified `TaskServiceInterface`
-   [ ] Implement `TaskService` with basic operations
-   [ ] Add basic business validations
-   [ ] Remove user-related validations

**Configuration**

-   [ ] Register bindings in `RepositoryServiceProvider`
-   [ ] Register bindings in `ServiceClassProvider`

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

-   [ ] Create `TaskStoreRequest` without user_id validations
-   [ ] Create simplified `TaskUpdateRequest`
-   [ ] Implement only essential validations

**Response Formatting**

-   [ ] Create `TaskResource` for output formatting
-   [ ] Implement `TaskCollection` if necessary
-   [ ] Standardize responses following ApiResponse trait

**Controller**

-   [ ] Implement `TaskController` with public endpoints
-   [ ] Complete CRUD endpoints (index, store, show, update, destroy with soft delete)
-   [ ] Implement basic filters and search (excluding soft deleted)
-   [ ] Add pagination
-   [ ] Remove authorization checks
-   [ ] Add endpoint to restore soft deleted tasks (optional)

**Routes**

-   [ ] Define routes in `routes/v1/api.php`
-   [ ] Organize public route grouping

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

-   [ ] Tests for TaskService (basic operations)
-   [ ] Tests for TaskRepository
-   [ ] Tests for Request validations
-   [ ] Tests for TaskResource

**Feature Tests**

-   [ ] Tests for TaskController (all public endpoints)
-   [ ] Basic filter and search tests
-   [ ] Pagination tests
-   [ ] Basic edge case tests

**Documentation**

-   [ ] Document endpoints in Postman/Insomnia collection
-   [ ] Update README with public API information
-   [ ] Document basic use cases and examples
-   [ ] Specify that API is public (no authentication)

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

-   [ ] Create migration to add `user_id` field to tasks table
-   [ ] Update Factory to include user_id
-   [ ] Create new seeder with relationships

**Model Updates**

-   [ ] Update `Task` model with `belongsTo(User)` relationship
-   [ ] Update `User` model with `hasMany(Task)` relationship

**Authentication & Authorization**

-   [ ] Implement JWT authentication in endpoints
-   [ ] Add authentication middleware to routes
-   [ ] Implement authorization (user only accesses their tasks)

**Service Layer Updates**

-   [ ] Update `TaskService` with ownership validations
-   [ ] Add user filters in `TaskRepository`
-   [ ] Implement authorization logic

**Controller Updates**

-   [ ] Update `TaskController` to use authenticated user context
-   [ ] Add authorization checks
-   [ ] Filter tasks by logged user

**Testing Updates**

-   [ ] Update tests to include authentication
-   [ ] Authorization tests
-   [ ] Relationship tests

#### ✅ **Acceptance Criteria:**

-   Authentication system working
-   Users only access their own tasks
-   All tests updated and passing
-   API fully protected and functional

---

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
│   └── xxxx_xx_xx_xxxxxx_create_tasks_table.php (without user_id)
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

## 📈 **Success Metrics - Initial Phase**

-   **Code Coverage:** Minimum 70% (initial phase)
-   **Response Time:** < 200ms for basic endpoints
-   **Uptime:** 99.9%
-   **PSR-12 Compliance:** 100%
-   **Zero Security Vulnerabilities:** Validated by tools

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
