<?php
// admin/header_admin.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Puerta de Oro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .sidebar { background: var(--sidebar-gradient); border-right: 1px solid rgba(255,255,255,0.05); }
        .nav-link.active { border-left: 3px solid var(--brand-lime); background: linear-gradient(90deg, rgba(0, 158, 115, 0.1) 0%, transparent 100%); color: var(--brand-teal); }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="logo-area" style="font-size: 1.2rem;">ADMIN PANEL</div>
    <div class="nav-links">
        <a href="index.php" class="nav-link">Resumen</a>
        <a href="reports.php" class="nav-link">Reportes</a>
        <a href="users.php" class="nav-link">Usuarios</a>
        <a href="../public/dashboard.php" class="nav-link" style="margin-top: 2rem; border: 1px solid #333;">&larr; Volver al Sistema</a>
    </div>
</nav>

<main class="main-content">
