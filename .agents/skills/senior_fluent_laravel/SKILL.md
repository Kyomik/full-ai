# SKILL.md
---
name: senior_fluent_laravel
description: Enforces senior‑level clean architecture, SOLID, Repository, Service, Domain, Command‑Bus, Event‑Listener, and Filament best practices in Laravel 10 + Filament 3 projects.
---

## Overview
This skill equips agents with a **checklist**, **audit scripts**, and **scaffolding templates** to transform a Laravel‑Filament codebase into a modular, maintainable, and testable system.

- **Single Responsibility (SRP)** – each class does one thing.
- **Open/Closed & Liskov** – extend behavior via interfaces / abstract classes.
- **Dependency Inversion** – depend on contracts, not concrete implementations.
- **Repository Pattern** – abstract Eloquent queries.
- **Service Layer / Domain Services** – encapsulate business logic.
- **Domain Layer** – value objects, entities, and domain services.
- **Command‑Bus / Jobs** – decouple request handling from execution.
- **Events & Listeners** – publish‑subscribe for side‑effects.
- **Filament Resources** – UI‑only, delegate to Services/Repositories.
- **DRY & Reusability** – Traits, Blade components, reusable FormRequests.
- **Testing** – enable unit‑testing via injected contracts.

## Usage Pattern for Agents
### 1. Load Project Context
```bash
view_file --AbsolutePath="D:/joki/palopo-budaya/PROJECT_CONTEXT.md" --toolAction="Load context" --toolSummary="Project context"
```
### 2. Run the Audit Checklist
Agents should walk the following directories and apply the checks (pseudo‑code in step 3).
- `app/Models`
- `app/Http/Controllers`
- `app/Filament/Resources`
- `app/Services`
- `app/Repositories` (create if missing)
- `app/Domain`
- `app/Commands`
- `app/Events` / `app/Listeners`
- `app/Jobs`

### 3. Audit Rules (pseudo‑code)
```python
if file contains Eloquent query in Controller:
    flag "Move query to Repository"
if Controller > 50 lines of business logic:
    flag "Extract Service"
if Filament Resource contains DB logic:
    flag "Separate to Service/Repository"
if Model has complex rules not suitable for DB layer:
    flag "Create Domain Entity/ValueObject"
if missing Command class for a heavy action:
    flag "Add Command & Job skeleton"
if missing Event/Listener for domain events:
    flag "Add Event & Listener skeleton"
if duplicate validation rules across FormRequests:
    flag "Extract to custom Rule or Trait"
```
### 4. Scaffold Templates (optional)
When a violation is detected, the agent can generate missing files using the templates located in `templates/`:
- **Repository Interface** – `app/Repositories/Contracts/<Model>RepositoryInterface.php`
- **Repository Implementation** – `app/Repositories/Eloquent/<Model>Repository.php`
- **Service Class** – `app/Services/<Model>Service.php`
- **Domain Entity** – `app/Domain/<Model>.php`
- **Command Class** – `app/Commands/<Action>Command.php`
- **Job Class** – `app/Jobs/<Action>Job.php`
- **Event Class** – `app/Events/<DomainEvent>.php`
- **Listener Class** – `app/Listeners/<DomainEvent>Listener.php`

Each generated file should include proper namespace, contract injection, and doc‑block explaining responsibilities.

### 5. Reporting
After the audit, the agent creates a markdown **artifact** `senior_laravel_audit_report.md` containing:
- File‑wise list of violations.
- Suggested refactors.
- Links to generated scaffolding files (if any).

## Extensibility
If new domains appear (e.g., `FestivalEvent`), invoke the skill with a parameter `model=FestivalEvent` to auto‑generate the corresponding Repository, Service, Command, Event, and Listener.

---
*The skill lives under `.agents/skills/senior_fluent_laravel` and is discoverable by any agent via the standard skill loading mechanism.*