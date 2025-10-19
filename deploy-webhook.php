<?php

/**
 * GitHub Webhook Handler for Auto-Deployment
 *
 * This file receives webhooks from GitHub and triggers deployment scripts
 * when code is pushed to specific branches.
 */

// Configuration
define('SECRET_TOKEN', 'your-secret-token-change-this'); // Change this to a random string
define('DEPLOY_SCRIPT', '/home/genecdsh/dentistcms/deploy.sh');
define('LOG_FILE', '/home/genecdsh/dentistcms/storage/logs/deployment.log');

// Get the GitHub payload
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// Verify GitHub signature
if (! verifySignature($payload, $signature, SECRET_TOKEN)) {
    http_response_code(403);
    logMessage('ERROR: Invalid signature');
    exit('Invalid signature');
}

// Parse the payload
$data = json_decode($payload, true);

if (! $data) {
    http_response_code(400);
    logMessage('ERROR: Invalid JSON payload');
    exit('Invalid payload');
}

// Check if this is a push event
if (! isset($_SERVER['HTTP_X_GITHUB_EVENT']) || $_SERVER['HTTP_X_GITHUB_EVENT'] !== 'push') {
    logMessage('INFO: Received non-push event, ignoring');
    exit('Not a push event');
}

// Get the branch name
$branch = str_replace('refs/heads/', '', $data['ref'] ?? '');
logMessage("INFO: Received push to branch: {$branch}");

// Only deploy from main branch
if ($branch !== 'main') {
    logMessage("INFO: Branch '{$branch}' is not configured for deployment");
    exit('Only main branch triggers deployment');
}

$scriptToRun = DEPLOY_SCRIPT;
logMessage('INFO: Deploying to PRODUCTION');

// Execute deployment script
if (file_exists($scriptToRun)) {
    logMessage("INFO: Executing script: {$scriptToRun}");

    // Run the script in the background
    $output = [];
    $returnCode = 0;
    exec("bash {$scriptToRun} 2>&1", $output, $returnCode);

    $outputText = implode("\n", $output);
    logMessage("DEPLOYMENT OUTPUT:\n{$outputText}");

    if ($returnCode === 0) {
        logMessage('SUCCESS: Deployment completed successfully');
        echo json_encode([
            'status' => 'success',
            'message' => 'Deployment completed successfully',
            'branch' => $branch,
        ]);
    } else {
        logMessage("ERROR: Deployment failed with code {$returnCode}");
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Deployment failed',
            'branch' => $branch,
            'return_code' => $returnCode,
        ]);
    }
} else {
    logMessage("ERROR: Deployment script not found: {$scriptToRun}");
    http_response_code(500);
    exit('Deployment script not found');
}

/**
 * Verify GitHub webhook signature
 */
function verifySignature(string $payload, string $signature, string $secret): bool
{
    if (empty($signature)) {
        return false;
    }

    $expectedSignature = 'sha256='.hash_hmac('sha256', $payload, $secret);

    return hash_equals($expectedSignature, $signature);
}

/**
 * Log message to file
 */
function logMessage(string $message): void
{
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] {$message}\n";

    // Ensure log directory exists
    $logDir = dirname(LOG_FILE);
    if (! is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND);
}
