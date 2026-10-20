# PowerShell script to update PROJECT_CONTEXT.md with the latest changes
# Place this file at scripts/update_project_context.ps1

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = Resolve-Path "$projectRoot/.."
$contextFile = Join-Path $projectRoot 'PROJECT_CONTEXT.md'

# Get list of changed files in the last commit (if any)
$gitDiff = git -C $projectRoot diff --name-only HEAD~1 HEAD
if (-not $gitDiff) {
    # If there is no previous commit (e.g., first commit), use staged changes
    $gitDiff = git -C $projectRoot diff --name-only --cached
}

if (-not $gitDiff) {
    Write-Host "No changed files detected."
    exit 0
}

$changed = $gitDiff -split "`n" | Where-Object { $_ -match '\.php$' -or $_ -match '\.blade\.php$' }
if ($changed.Count -eq 0) {
    Write-Host "No PHP or Blade files changed."
    exit 0
}

$timestamp = Get-Date -Format 'yyyy-MM-dd HH:mm:ss'
$entry = "## $timestamp`n"
$entry += "Modified files:`n"
foreach ($file in $changed) {
    $entry += "- `$file`n"
}
$entry += "`n"

Add-Content -Path $contextFile -Value $entry
Write-Host "PROJECT_CONTEXT.md updated with $($changed.Count) entries."
