$ErrorActionPreference = "Stop"
$nodeModulesPath = Join-Path $PSScriptRoot "node_modules"
$tailwindPath = Join-Path $nodeModulesPath ".bin\tailwindcss.cmd"

if (Test-Path $tailwindPath) {
    & $tailwindPath -i "./public/css/input.css" -o "./public/css/styles.css" --minify
} else {
    Write-Error "Tailwind executable not found. Please ensure it's installed correctly."
}
