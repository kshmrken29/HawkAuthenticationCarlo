<?php
// Include necessary files
require_once '../config/database.php';
require_once '../utils/functions.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Authorization, X-CSRF-Token');

// Handle OPTIONS request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check if request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse('error', 'Only GET method is allowed');
}

// Get search query
if (!isset($_GET['q']) || empty($_GET['q'])) {
    sendResponse('error', 'Search query is required');
}

$search_query = '%' . $_GET['q'] . '%';

// Require Hawk authentication
$user_id = verifyHawkAuthentication();
if (!$user_id) {
    sendResponse('error', 'Invalid Hawk authentication. Please login first.');
}

// Create database connection
$database = new Database();
$conn = $database->getConnection();

// Search items
<<<<<<< HEAD
$stmt = $conn->prepare("SELECT * FROM items WHERE name LIKE ? OR description LIKE ?");
$stmt->bind_param("ss", $search_query, $search_query);
=======
$stmt = $conn->prepare("SELECT * FROM items WHERE (name LIKE ? OR description LIKE ?) AND user_id = ?");
$stmt->bind_param("ssi", $search_query, $search_query, $user_id);
>>>>>>> 348f439 (Initial commit from XAMPP htdocs)
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Close connection
$stmt->close();
$conn->close();

// Send response
sendResponse('success', 'Search results', $items);
?> 