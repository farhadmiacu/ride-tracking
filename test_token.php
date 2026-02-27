<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$user = new \App\Models\User();
echo "Testing if User model has createToken method...\n";
echo "HasApiTokens trait: " . (method_exists($user, 'createToken') ? "✓ Found" : "✗ Not Found") . "\n";

// Test the trait is applied
$traits = class_uses(\App\Models\User::class);
echo "Traits: " . implode(", ", array_keys($traits)) . "\n";
