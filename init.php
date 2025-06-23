<?php
header('Content-Type: text/plain');

$scriptDir = __DIR__ . '/scripts';
$dataDir   = __DIR__ . '/data';
$logDir    = __DIR__ . '/logs';
$logFile   = $logDir . '/init.log';

// Папки
foreach ([$dataDir, $logDir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Функція логування
function logMessage(string $message): void {
    global $logFile;
    $timestamp = date('[Y-m-d H:i:s]');
    file_put_contents($logFile, "$timestamp $message\n", FILE_APPEND);
}

// Обробка скриптів
$files = glob($scriptDir . '/*.php');

foreach ($files as $file) {
    require_once $file;

    $baseName     = basename($file, '.php');
    $functionName = 'get' . ucfirst($baseName);
    $outputFile   = "$dataDir/{$baseName}.txt";

    if (function_exists($functionName)) {
        try {
            $output = $functionName();

            if (is_string($output) && strlen($output) > 0) {
                file_put_contents($outputFile, $output);
                logMessage("✅ $baseName: saved to $outputFile");
            } else {
                logMessage("⚠️ $baseName: returned empty result");
            }

        } catch (Throwable $e) {
            logMessage("❌ $baseName: error - " . $e->getMessage());
        }
    } else {
        logMessage("❌ $baseName: function '$functionName' not found");
    }
}
