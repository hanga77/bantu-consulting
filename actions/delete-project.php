<?php
session_start();
require_once '../config/database.php';

if (!isset(['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}
if (['REQUEST_METHOD'] !== 'POST' || !hash_equals(['csrf_token'] ?? '', ['csrf_token'] ?? '')) {
    ['error'] = 'Requête invalide.';
    header('Location: ../?page=admin-dashboard&section=');
    exit;
}
 = intval(['id'] ?? 0);
try {
     = ->prepare("DELETE FROM  WHERE id = ?");
    ->execute([]);
    ['success'] = '';
} catch (PDOException ) {
    ['error'] = safeErrorMessage();
}
header('Location: ../?page=admin-dashboard&section=');
exit;
