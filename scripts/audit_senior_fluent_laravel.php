<?php
/**
 * Simple audit script for the senior_fluent_laravel skill.
 * It scans Controllers and Filament Resources for Eloquent query usage
 * and flags long business‑logic methods (>50 lines).
 * It also checks for the presence of key directories (Repositories, Domain).
 */
$base = __DIR__ . '/../';
$reportLines = [];
function add($msg) { global $reportLines; $reportLines[] = $msg; }
add("# Senior Laravel Filament Audit Report");
add("Generated on " . date('Y-m-d H:i:s'));
add("");
// 1. Check required directories
$requiredDirs = [
    'app/Repositories',
    'app/Domain',
    'app/Commands',
    'app/Events',
    'app/Listeners',
    'app/Jobs',
];
foreach ($requiredDirs as $dir) {
    if (!is_dir($base . $dir)) {
        add("- **Missing directory**: `$dir` (consider creating it for proper clean architecture).");
    }
}
add("");
// 2. Scan Controllers
$controllerFiles = glob($base . 'app/Http/Controllers/*.php');
foreach ($controllerFiles as $file) {
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    $hasQuery = preg_match('/::\s*where|::\s*latest|::\s*paginate|::\s*find|::\s*first|::\s*all/', $content);
    $methodLines = [];
    $inMethod = false;
    $methodStart = 0;
    foreach ($lines as $i => $line) {
        if (preg_match('/function\s+.*\(/', $line)) {
            $inMethod = true;
            $methodStart = $i;
        }
        if ($inMethod && preg_match('/^\s*}\s*$/', $line)) {
            $len = $i - $methodStart + 1;
            $methodLines[] = $len;
            $inMethod = false;
        }
    }
    $longMethod = max($methodLines ?: [0]) > 50;
    $relPath = str_replace($base, '', $file);
    if ($hasQuery) {
        add("- **Controller** `$relPath` contains Eloquent query – *suggest moving to Repository*.");
    }
    if ($longMethod) {
        add("- **Controller** `$relPath` has a method >50 lines – *suggest extracting Service*.");
    }
}
add("");
// 3. Scan Filament Resources
$resourceFiles = glob($base . 'app/Filament/Resources/**/*.php', GLOB_BRACE);
foreach ($resourceFiles as $file) {
    $content = file_get_contents($file);
    $hasQuery = preg_match('/::\s*where|::\s*latest|::\s*paginate|::\s*find|::\s*first|::\s*all/', $content);
    if ($hasQuery) {
        $relPath = str_replace($base, '', $file);
        add("- **Filament Resource** `$relPath` contains DB logic – *suggest delegating to Service/Repository*.");
    }
}
add("");
// 4. Summary
if (empty($reportLines)) {
    add("No issues detected. Your codebase already follows the senior clean‑architecture guidelines.");
}
// Write report to artifact location
$artifactPath = 'C:/Users/SUPRIANA/.gemini/antigravity-cli/brain/38bdc41d-f0a3-458c-97cf-2e8a3beb1100/senior_laravel_audit_report.md';
file_put_contents($artifactPath, implode("\n", $reportLines));

echo "Audit complete. Report written to $artifactPath\n";
?>