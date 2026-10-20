# SKILL.md
---
name: project_context_updater
description: |
  This skill ensures that *PROJECT_CONTEXT.md* stays up‑to‑date with every code change.
  It provides a guideline and a helper script that appends a concise summary of the
  latest Git commit (or a manual list of changed files) to the context file.  The
  skill is intended to be used by agents when they modify code files (via `write_file`
  or `edit_file`).  After a successful file operation the agent should invoke the
  provided PowerShell script `scripts/update_project_context.ps1` which reads the
  changed files from the last Git commit and writes a markdown entry into
  `PROJECT_CONTEXT.md`.

  **Guideline**
  1. Perform your file changes (create, edit, delete) as usual.
  2. Immediately run `powershell -File scripts/update_project_context.ps1`.
  3. The script will:
     - Detect the files changed in the most recent commit (or, if no commit yet,
       the modifications made in the current session via Git’s index).
     - Generate a bullet‑list of those files, grouped by type (Seeder, Model,
       Migration, etc.) when possible.
     - Append a timestamped markdown section to `PROJECT_CONTEXT.md`.
  4. Commit the changes together with the updated `PROJECT_CONTEXT.md`.

  **Why**
  *PROJECT_CONTEXT.md* acts as the single source of truth describing the current
  state of the project – its architecture, major components, and recent
  modifications. Keeping it in sync reduces onboarding friction and helps the
  Antigravity skills make more accurate recommendations.

  **Usage example**
  ```bash
  php artisan make:seeder AnnouncementSeeder
  # ... edit the newly created file ...
  powershell -File scripts/update_project_context.ps1
  git add .
  git commit -m "Add AnnouncementSeeder and update project context"
  ```
---
This skill lives under `.agents/skills/project_context_updater` and can be loaded
by any agent via the standard skill‑loading mechanism.
