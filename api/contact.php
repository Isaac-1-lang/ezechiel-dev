<?php
/**
 * API Endpoint: Contact Message Processor
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$name    = isset($_POST['name']) ? sanitize($_POST['name']) : '';
$email   = isset($_POST['email']) ? sanitize($_POST['email']) : '';
$subject = isset($_POST['subject']) ? sanitize($_POST['subject']) : '';
$message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields (Name, Email, Message).']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO contact_messages (sender_name, sender_email, subject, message, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
    $stmt->execute([$name, $email, $subject, $message]);

    echo json_encode([
        'success' => true,
        'message' => 'Thank you, ' . htmlspecialchars($name) . '! Your message has been sent successfully. I will get back to you soon.'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: Unable to save message. Please try again.']);
}
