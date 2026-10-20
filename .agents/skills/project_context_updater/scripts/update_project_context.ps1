# PowerShell script to update PROJECT_CONTEXT.md with recent changes
# This script assumes the repository root is the current working directory.
# It detects files changed in the most recent commit (or staged changes if no commit yet)
# and appends a markdown section to PROJECT_CONTEXT.md.

$repoRoot = (git rev-parse --show-toplevel) 2>$null
if (-not $repoRoot) {
    Write-Error "Not a git repository."; exit 1
}
Set-Location $repoRoot

# Get list of changed files from the latest commit
$changedFiles = git diff --name-only HEAD~1 HEAD
if (-not $changedFiles) {
    # If no previous commit, fallback to staged changes
    $changedFiles = git diff --name-only --cached
}
if (-not $changedFiles) {
    Write-Host "No changed files detected."; exit 0
}

# Group files by extension/type for better readability
$grouped = @{}
foreach ($file in $changedFiles) {
    $ext = [System.IO.Path]::GetExtension($file).TrimStart('.')
    if (-not $grouped.ContainsKey($ext)) { $grouped[$ext] = @() }
    $grouped[$ext] += $file
}

$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
$section = "## Update on $timestamp`n"
foreach ($ext in $grouped.Keys) {
    $section += "- **$ext files:**`n"
    foreach ($f in $grouped[$ext]) {
        $section += "  - $f`n"
    }
}

# Append to PROJECT_CONTEXT.md (create if missing)
$projectContextPath = Join-Path $repoRoot "PROJECT_CONTEXT.md"
Add-Content -Path $projectContextPath -Value $section

Write-Host "PROJECT_CONTEXT.md updated with recent changes."
