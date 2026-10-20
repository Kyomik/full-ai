# SKILL.md
---
name: clean_laravel_fillet
description: Provides guidelines and actionable steps for agents to enforce clean code, modular architecture, and proper design patterns (SOLID, Repository, Service, Domain) in Laravel 10 + Filament 3 projects.
---

## Overview
This skill equips an agent with a **checklist and actionable scripts** to audit, refactor, and maintain a Laravel 10 + Filament 3 codebase so that it follows senior‑level development standards:

- **Single Responsibility Principle (SRP)** – each class does one thing.
- **Open/Closed & Liskov** – extend behavior without modifying core classes.
- **Interface Segregation & Dependency Inversion** – rely on contracts, not concrete implementations.
- **Repository Pattern** – abstract Eloquent queries.
- **Service Layer / Domain Services** – encapsulate business logic.
- **Filament Resources** – keep UI concerns separate from domain logic.
- **DRY & Reusability** – reusable Traits, view components, Blade components.
- **Testability** – inject dependencies to enable unit testing.

The skill can be invoked by any agent to:
1. **Audit** existing files for violations.
2. **Suggest** refactorings (e.g., move query logic to a Repository, extract Service class). 
3. **Generate** scaffold files (Repository, Service, Interface) following project conventions.
4. **Validate** that Filament Resources only contain UI configuration, not business logic.

## Usage Pattern for Agents
### 1. Load Project Context
```bash
view_file --AbsolutePath="D:/joki/palopo-budaya/PROJECT_CONTEXT.md" --toolAction="Load context" --toolSummary="Project context"
```
### 2. Run the Audit Checklist
Agents should iterate over the following directories and apply the checks:
- `app/Models`
- `app/Http/Controllers`
- `app/Filament/Resources`
- `app/Services`
- `app/Repositories` (create if missing)

For each file, perform these checks (pseudocode):
```python
if file contains Eloquent query in Controller:
    flag "Move query to Repository"
if Controller contains business logic > 50 lines:
    flag "Extract Service"
if Filament Resource contains DB logic:
    flag "Separate to Service/Repository"
if class implements multiple responsibilities (e.g., handling API + UI):
    flag "Split into separate classes"
if there are duplicate validation rules across FormRequests:
    extract to reusable Trait or custom Rule class
```
### 3. Generate Boilerplate (optional)
When a violation is detected, the agent can generate the missing scaffolding using the following templates:
- **Repository Interface** (`app/Repositories/Contracts/<Model>NameRepositoryInterface.php`)
- **Repository Implementation** (`app/Repositories/Eloquent/<Model>NameRepository.php`)
- **Service Class** (`app/Services/<Model>NameService.php`)

Each generated file should include:
```php
<?php

namespace App\Repositories\Contracts;

use App\Models\{{Model}};

interface {{Model}}RepositoryInterface {
    public function all(): \Illuminate\Database\Eloquent\Collection;
    public function find(int $id): ?{{Model}};
    public function create(array $data): {{Model}};
    public function update({{Model}} $model, array $data): bool;
    public function delete({{Model}} $model): bool;
}
```
And the concrete implementation:
```php
<?php

namespace App\Repositories\Eloquent;

use App\Models\{{Model}};
use App\Repositories\Contracts\{{Model}}RepositoryInterface;

class {{Model}}Repository implements {{Model}}RepositoryInterface {
    public function all() { return {{Model}}::all(); }
    public function find(int $id) { return {{Model}}::find($id); }
    public function create(array $data) { return {{Model}}::create($data); }
    public function update({{Model}} $model, array $data) { return $model->update($data); }
    public function delete({{Model}} $model) { return $model->delete(); }
}
```
The Service class then injects the repository via constructor type‑hinting.

### 4. Enforce Naming Conventions
- Controllers: `*Controller`
- Services: `*Service`
- Repositories: `*Repository` with interface `*RepositoryInterface`
- Filament Resources: `*Resource` placed under `app/Filament/Resources`
- Form Requests: `*Request`

### 5. Reporting
After audit, the agent should produce a markdown **artifact** named `clean_code_audit_report.md` containing:
- File‑wise list of violations.
- Suggested refactor actions.
- Generated scaffolding files (if any).

## Example Invocation
```json
{
  "toolAction": "Audit Laravel project",
  "toolSummary": "Clean architecture skill",
  "Message": "Run clean_laravel_fillet skill on the current workspace"
}
```
The receiving agent will:
1. Load `PROJECT_CONTEXT.md`.
2. Walk through the code directories.
3. Emit a report artifact.
4. Optionally create missing Repository/Service files.

## Extensibility
If the project adds new domains (e.g., `FestivalEvent`), simply call the skill with a **parameter** `model=FestivalEvent` to auto‑generate the corresponding Repository & Service. Agents can extend the skill by adding new template files under `templates/` inside the skill directory.

---
*This skill is stored under the workspace customization root (`.agents/skills/clean_laravel_fillet`). Other agents can load it via the standard skill discovery mechanism.*
