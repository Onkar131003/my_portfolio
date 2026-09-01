<?php
require_once 'includes/db.php';

$page_title = "Dashboard";
$active_tab = "dashboard";

// 1. Fetch dashboard statistics
try {
    // Total cards
    $stmt = $pdo->query("SELECT COUNT(*) FROM cards");
    $total_cards = $stmt->fetchColumn();
    
    // Templates count
    $stmt = $pdo->query("SELECT COUNT(*) FROM templates");
    $total_templates = $stmt->fetchColumn();
    
    // Active companies
    $stmt = $pdo->query("SELECT COUNT(DISTINCT company) FROM cards");
    $active_companies = $stmt->fetchColumn();
    
    // Generated this month
    $start_of_month = date('Y-m-01 00:00:00');
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cards WHERE created_at >= ?");
    $stmt->execute([$start_of_month]);
    $generated_this_month = $stmt->fetchColumn();

    // 2. Fetch recent cards (limit 7 for layout balance, plus 1 card creator placeholder)
    $stmt = $pdo->query("SELECT * FROM cards ORDER BY id DESC LIMIT 7");
    $recent_cards = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fallbacks if tables are not initialized
    $total_cards = 0;
    $total_templates = 0;
    $active_companies = 0;
    $generated_this_month = 0;
    $recent_cards = [];
}

require_once 'includes/header.php';
?>

<!-- Header Title Block & Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Welcome back, Admin</h2>
        <p class="text-muted mb-0">Here's a summary of your ID Studio activity.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="create.php#step-template" class="btn btn-white border-secondary border-opacity-25 text-dark fw-semibold px-3 py-2 btn-sm bg-white" onclick="if(window.goToStep) { window.goToStep(1); }">
            Browse Templates
        </a>
        <a href="create.php" class="btn btn-indigo text-white fw-semibold px-3 py-2 btn-sm shadow-sm d-flex align-items-center gap-1" style="background-color: #6366F1; border-color: #6366F1;">
            <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
            Create New ID
        </a>
    </div>
</div>

<!-- Statistics Bento Grid -->
<div class="row g-4 mb-5">
    <!-- Total ID Cards -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="bento-card d-flex flex-column justify-content-between" style="height: 140px;">
            <div class="d-flex justify-content-between align-items-start">
                <span class="text-muted small fw-semibold">Total ID Cards</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(99, 102, 241, 0.1); color: #6366F1;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">badge</span>
                </div>
            </div>
            <h3 class="fs-1 fw-bold mb-0 text-dark"><?php echo $total_cards; ?></h3>
        </div>
    </div>
    
    <!-- Templates Used -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="bento-card d-flex flex-column justify-content-between" style="height: 140px;">
            <div class="d-flex justify-content-between align-items-start">
                <span class="text-muted small fw-semibold">Templates Used</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(70, 72, 212, 0.1); color: #4648d4;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">style</span>
                </div>
            </div>
            <h3 class="fs-1 fw-bold mb-0 text-dark"><?php echo $total_templates; ?></h3>
        </div>
    </div>
    
    <!-- Active Companies -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="bento-card d-flex flex-column justify-content-between" style="height: 140px;">
            <div class="d-flex justify-content-between align-items-start">
                <span class="text-muted small fw-semibold">Active Companies</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(20, 184, 166, 0.1); color: #14B8A6;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">corporate_fare</span>
                </div>
            </div>
            <h3 class="fs-1 fw-bold mb-0 text-dark"><?php echo $active_companies; ?></h3>
        </div>
    </div>
    
    <!-- Generated This Month -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="bento-card d-flex flex-column justify-content-between position-relative overflow-hidden" style="height: 140px;">
            <div class="position-absolute top-0 end-0 bottom-0 start-0 opacity-10 pointer-events-none" style="background: linear-gradient(135deg, #6366F1 0%, transparent 100%);"></div>
            <div class="d-flex justify-content-between align-items-start position-relative">
                <span class="text-muted small fw-semibold">Generated This Month</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(20, 184, 166, 0.1); color: #14B8A6;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">trending_up</span>
                </div>
            </div>
            <h3 class="fs-1 fw-bold mb-0 text-dark position-relative"><?php echo $generated_this_month; ?></h3>
        </div>
    </div>
</div>

<!-- Recent ID Cards Section -->
<div id="recent-cards">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fs-4 fw-bold text-dark mb-0">Recent ID Cards</h3>
        <span class="text-indigo small fw-bold cursor-pointer hover-underline" style="color: #6366F1;">View All</span>
    </div>
    
    <!-- ID Cards Grid -->
    <div class="row g-4">
        <?php foreach ($recent_cards as $card): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="bento-card p-0 overflow-hidden d-flex flex-column cursor-pointer" onclick="location.href='preview.php?id=<?php echo $card['id']; ?>'" style="min-height: 380px;">
                    <!-- Static preview representation of front of the card -->
                    <div class="p-3 bg-light d-flex align-items-center justify-content-center position-relative" style="height: 250px;">
                        <!-- CR80 Scaled mockup (approx 0.6 of size) -->
                        <div class="card shadow-sm border border-light overflow-hidden position-relative rounded-3" style="width: 140px; height: 220px; font-size: 8px;">
                            <!-- Top Color Band -->
                            <div class="w-100 position-absolute top-0 start-0" style="height: 45px; background-color: <?php echo $card['primary_color']; ?>; z-index: 1;"></div>
                            
                            <!-- Logo Mock -->
                            <div class="position-relative d-flex justify-content-between align-items-center px-2 mt-2 text-white" style="z-index: 10;">
                                <span class="material-symbols-outlined" style="font-size: 10px;">hexagon</span>
                                <span style="font-size: 6px; letter-spacing: 0.5px;" class="fw-bold text-uppercase">TECHNOVA</span>
                            </div>
                            
                            <!-- Photo -->
                            <div class="position-relative text-center mt-3" style="z-index: 10;">
                                <img src="<?php echo htmlspecialchars($card['photo_path']) ?: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&q=80'; ?>" 
                                     class="border border-white border-2 object-fit-cover <?php echo $card['photo_shape'] == 'circle' ? 'rounded-circle' : 'rounded-2'; ?>" 
                                     style="width: 55px; height: 55px; object-fit: cover;">
                            </div>
                            
                            <!-- Details -->
                            <div class="text-center px-1 mt-2">
                                <h4 class="mb-0 fw-bold" style="font-size: 9px; color: #0f172a;"><?php echo htmlspecialchars($card['name']); ?></h4>
                                <p class="mb-0 text-muted" style="font-size: 7px;"><?php echo htmlspecialchars($card['role'] ?: 'Developer'); ?></p>
                                
                                <div class="bg-light border border-light py-1 px-2 rounded-1 mt-2 mx-2 text-start" style="font-size: 6px;">
                                    <div class="d-flex justify-content-between"><span class="text-muted">ID:</span><span class="fw-bold"><?php echo htmlspecialchars($card['employee_id']); ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer of the card item -->
                    <div class="p-3 border-top border-light d-flex justify-content-between align-items-center bg-white">
                        <div class="overflow-hidden">
                            <h4 class="fs-6 fw-bold mb-0 text-truncate text-dark"><?php echo htmlspecialchars($card['name']); ?></h4>
                            <p class="text-muted mb-0 small text-truncate"><?php echo htmlspecialchars($card['company']); ?></p>
                        </div>
                        <?php 
                        $status_class = 'bg-success-subtle text-success';
                        if (strtolower($card['status']) == 'pending') {
                            $status_class = 'bg-warning-subtle text-warning';
                        } else if (strtolower($card['status']) == 'printed') {
                            $status_class = 'bg-info-subtle text-info';
                        }
                        ?>
                        <span class="badge rounded-pill <?php echo $status_class; ?> px-2 py-1 small"><?php echo htmlspecialchars($card['status']); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <!-- Add Card Placeholder -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="bento-card border-dashed border-2 border-secondary border-opacity-25 d-flex flex-column align-items-center justify-content-center bg-light text-center cursor-pointer hover-bg-white" onclick="location.href='create.php'" style="min-height: 338px;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background-color: rgba(99, 102, 241, 0.1); color: #6366F1;">
                    <span class="material-symbols-outlined fs-2">add</span>
                </div>
                <h4 class="fs-6 fw-bold text-dark mb-1">Create New ID</h4>
                <p class="text-muted small mb-0">Start from blank or template</p>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
