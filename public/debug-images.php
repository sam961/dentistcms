<?php
/**
 * Debug helper to check feature images on production
 * Access this file via: https://yourdomain.com/debug-images.php
 * DELETE THIS FILE after debugging!
 */

echo "<h1>Feature Images Debug</h1>";
echo "<p><strong>Base Path:</strong> " . __DIR__ . "/images/features/</p>";

$features = [
    'patients',
    'appointments',
    'dentists',
    'treatment-plans',
    'dental-chart',
    'invoices',
    'medical-records',
    'dashboard',
    'notifications'
];

function getFeatureImages($featureName) {
    $images = [];
    $basePath = __DIR__ . '/images/features/';

    // Check for single image
    if (file_exists($basePath . $featureName . '.png')) {
        $images[] = '/images/features/' . $featureName . '.png';
    }

    // Check for numbered images (feature-name-1.png, feature-name-2.png, etc.)
    $index = 1;
    while (file_exists($basePath . $featureName . '-' . $index . '.png')) {
        $images[] = '/images/features/' . $featureName . '-' . $index . '.png';
        $index++;
    }

    return $images;
}

echo "<h2>Scanning for images:</h2>";

foreach ($features as $feature) {
    $images = getFeatureImages($feature);
    echo "<div style='margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9;'>";
    echo "<h3>" . ucwords(str_replace('-', ' ', $feature)) . "</h3>";

    if (empty($images)) {
        echo "<p style='color: red;'><strong>No images found!</strong></p>";
    } else {
        echo "<p style='color: green;'><strong>Found " . count($images) . " image(s):</strong></p>";
        echo "<ul>";
        foreach ($images as $img) {
            $fullPath = __DIR__ . $img;
            $exists = file_exists($fullPath) ? '✓ EXISTS' : '✗ MISSING';
            $color = file_exists($fullPath) ? 'green' : 'red';
            echo "<li style='color: $color;'>$img - $exists</li>";

            if (file_exists($fullPath)) {
                echo "<img src='$img' style='max-width: 200px; margin: 10px 0; border: 1px solid #ccc;' />";
            }
        }
        echo "</ul>";
    }
    echo "</div>";
}

echo "<hr>";
echo "<h2>All files in /images/features/:</h2>";
$allFiles = glob(__DIR__ . '/images/features/*');
if ($allFiles) {
    echo "<ul>";
    foreach ($allFiles as $file) {
        echo "<li>" . basename($file) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'><strong>Directory is empty or doesn't exist!</strong></p>";
}

echo "<hr>";
echo "<p><strong>Remember to DELETE this file after debugging!</strong></p>";
