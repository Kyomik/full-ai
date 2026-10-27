$repoRoot = (git rev-parse --show-toplevel) 2>$null
if (-not $repoRoot) {
    Write-Error "Not a git repository."
    exit 1
}
Set-Location $repoRoot

# Determine changed files
$changedFiles = @()
try {
    # Try to get changes from previous commit
    $changedFiles = git diff --name-only HEAD~1 HEAD 2>$null
} catch {
    # Ignore errors
}
if (-not $changedFiles) {
    # If no previous commit or no changes, use staged changes or all files for initial commit
    $changedFiles = git diff --name-only --cached 2>$null
    if (-not $changedFiles) {
        $changedFiles = git ls-files
    }
}
if (-not $changedFiles) {
    Write-Host "No changed files detected."
    exit 0
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
