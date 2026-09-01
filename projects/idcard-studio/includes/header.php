<?php
if (!isset($active_tab)) {
    $active_tab = 'dashboard';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ID Card Studio' : 'ID Card Studio'; ?></title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <!-- Custom Style -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Responsive Sidebar Drawer -->
    <aside class="sidebar">
        <!-- Brand Name & Logo -->
        <div class="p-4 border-bottom border-secondary border-opacity-25 d-flex align-items-center gap-3">
            <div class="rounded bg-indigo d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #6366F1;">
                <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">badge</span>
            </div>
            <span class="fs-5 fw-bold text-white">ID Card Studio</span>
        </div>
        
        <!-- Navigation Menu Links -->
        <nav class="flex-grow-1 py-3 overflow-y-auto">
            <a class="nav-link <?php echo $active_tab == 'dashboard' ? 'active' : ''; ?>" href="index.php">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a class="nav-link <?php echo $active_tab == 'create' ? 'active' : ''; ?>" href="create.php">
                <span class="material-symbols-outlined">add_box</span>
                Create Card
            </a>
            <a class="nav-link <?php echo $active_tab == 'templates' ? 'active' : ''; ?>" href="create.php#step-template" onclick="if(window.goToStep) { window.goToStep(1); }">
                <span class="material-symbols-outlined">style</span>
                Templates
            </a>
            <a class="nav-link <?php echo $active_tab == 'cards' ? 'active' : ''; ?>" href="index.php#recent-cards">
                <span class="material-symbols-outlined">contacts</span>
                My Cards
            </a>
            <a class="nav-link <?php echo $active_tab == 'settings' ? 'active' : ''; ?>" href="#">
                <span class="material-symbols-outlined">settings</span>
                Settings
            </a>
        </nav>
        
        <!-- Profile Panel (Fixed Bottom of Sidebar) -->
        <div class="p-3 border-top border-secondary border-opacity-10">
            <div class="d-flex align-items-center gap-3 p-2 rounded hover-bg-dark cursor-pointer text-white">
                <img alt="Admin Portrait" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCyitX7AwIRCWYr0rFN3YFoBnkXPc39ypMzjY5FGfXtKK25Oy6NDuJZ80udnw_DqdTljz0GhNtpTg_3N3X0Yhtus_L7q4rgkiJheossskNECKL-GftP66asaNTSdRINHueV5jL0ddD4KT4y4Ht3D0nGi49W2QEGcN5PD7yA7PENFm4CayH_sYExmBC3ukACCC60Kc59s9X04b-PbcM_n1zAWUpTXDeVTi4wlYUaTgjnu-nsS-S65n7AUw">
                <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                    <p class="mb-0 fw-semibold text-truncate small">ID Master Admin</p>
                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.75rem;">Professional Plan</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navigation Bar Header -->
        <header class="d-flex justify-content-between align-items-center bg-white px-4 border-bottom border-light sticky-top" style="height: 64px; z-index: 90;">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-dark p-0 d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="fs-5 fw-bold text-indigo mb-0" style="color: #6366F1;">ID Platform</h1>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="setup.php" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size: 16px;">database</span> Reset DB
                </a>
                <button class="btn btn-link text-dark p-0">
                    <span class="material-symbols-outlined">account_circle</span>
                </button>
            </div>
        </header>
        
        <!-- Viewport Area -->
        <main class="flex-grow-1 p-4">
