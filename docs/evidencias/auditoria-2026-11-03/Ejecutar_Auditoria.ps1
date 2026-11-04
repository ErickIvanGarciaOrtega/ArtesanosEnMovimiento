param([string]$Filter = '')

$ErrorActionPreference = 'Stop'
$repoPath = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..\..\..'))
$testPath = Join-Path $PSScriptRoot 'AuditSecurityTest.php'
if (!(Test-Path -LiteralPath (Join-Path $repoPath 'vendor\bin\phpunit'))) {
    throw 'No se encontro PHPUnit. Instala las dependencias del repositorio antes de ejecutar la auditoria.'
}
$phpPath = (Get-Command php -ErrorAction Stop).Source
$testEnvironment = @{
    APP_ENV = 'testing'
    DB_CONNECTION = 'sqlite'
    DB_DATABASE = ':memory:'
    DB_URL = ''
    SESSION_DRIVER = 'array'
    CACHE_STORE = 'array'
    MAIL_MAILER = 'array'
    QUEUE_CONNECTION = 'sync'
}
$previousEnvironment = @{}
foreach ($key in $testEnvironment.Keys) {
    $previousEnvironment[$key] = [Environment]::GetEnvironmentVariable($key, 'Process')
    [Environment]::SetEnvironmentVariable($key, $testEnvironment[$key], 'Process')
}
Push-Location -LiteralPath $repoPath
try {
    $phpunitArguments = @('vendor/bin/phpunit', '--configuration', 'phpunit.xml', '--do-not-cache-result', '--colors=never')
    if ($Filter) {
        $phpunitArguments += @('--filter', $Filter)
    }
    $phpunitArguments += $testPath
    & $phpPath @phpunitArguments
    $auditExitCode = $LASTEXITCODE
} finally {
    Pop-Location
    foreach ($key in $testEnvironment.Keys) {
        [Environment]::SetEnvironmentVariable($key, $previousEnvironment[$key], 'Process')
    }
}
exit $auditExitCode
