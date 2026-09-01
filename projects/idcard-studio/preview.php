<?php
require_once 'includes/db.php';

$page_title = "Preview & Generate Card";
$active_tab = "create";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM cards WHERE id = ?");
    $stmt->execute([$id]);
    $card = $stmt->fetch();
    
    if (!$card) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

require_once 'includes/header.php';
?>

<!-- Stepper Container (Step 5 Completed) -->
<div class="stepper-container mb-5 d-none d-md-flex">
    <div class="stepper-line"></div>
    <div class="stepper-line-active" style="width: 100%;"></div>
    
    <div class="step-item completed">
        <div class="step-circle">1</div>
        <span class="step-label">Info</span>
    </div>
    <div class="step-item completed">
        <div class="step-circle">2</div>
        <span class="step-label">Template</span>
    </div>
    <div class="step-item completed">
        <div class="step-circle">3</div>
        <span class="step-label">Front Design</span>
    </div>
    <div class="step-item completed">
        <div class="step-circle">4</div>
        <span class="step-label">Back Design</span>
    </div>
</div>

<!-- Success Hero Title -->
<div class="text-center mb-5 mt-3">
    <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
        <span class="material-symbols-outlined fs-1" style="font-variation-settings: 'FILL' 1;">check_circle</span>
    </div>
    <h2 class="fw-bold text-dark mb-2">Card Generated Successfully</h2>
    <p class="text-muted mx-auto" style="max-width: 600px;">The ID card for <?php echo htmlspecialchars($card['name']); ?> is ready. You can now download, print, or create another card using the templates.</p>
</div>

<!-- Main Split Bento Layout -->
<div class="row g-4 mb-5">
    <!-- Left Column: Front and Back Live Preview Cards -->
    <div class="col-12 col-lg-7 col-xl-8">
        <div class="bento-card bg-white p-4 d-flex flex-column align-items-center position-relative overflow-hidden" style="min-height: 580px;">
            <!-- Decorative shimmers -->
            <div class="position-absolute bg-primary bg-opacity-5 rounded-circle blur-3xl" style="width: 250px; height: 250px; top: -100px; right: -100px; filter: blur(60px);"></div>
            <div class="position-absolute bg-teal bg-opacity-5 rounded-circle blur-3xl" style="width: 250px; height: 250px; bottom: -100px; left: -100px; filter: blur(60px);"></div>

            <!-- Header tabs: Front / Back selector -->
            <div class="btn-group mb-4 position-relative z-1" role="group">
                <button type="button" id="tab-front" class="btn btn-primary px-4 py-2 small">Front Preview</button>
                <button type="button" id="tab-back" class="btn btn-outline-primary px-4 py-2 small">Back Preview</button>
            </div>

            <!-- Perspective container for 3D flip card -->
            <div class="d-flex justify-content-center align-items-center perspective-1000 z-1 mb-2">
                <div class="card-flip-container shadow-lg <?php echo $card['hologram_enabled'] ? 'hologram-active' : ''; ?>">
                    
                    <!-- FRONT FACE -->
                    <div class="card-face card-face-front text-start" style="font-family: <?php echo $card['font_family'] == 'roboto' ? "'Roboto', sans-serif" : ($card['font_family'] == 'merriweather' ? "'Playfair Display', serif" : "'Inter', sans-serif"); ?>;">
                        <!-- Colored Top Strip Header -->
                        <div class="w-100 position-absolute top-0 start-0" style="height: 100px; background-color: <?php echo $card['primary_color']; ?>;"></div>
                        
                        <!-- Logo & Company Name -->
                        <div class="z-10 mt-4 px-4 w-full d-flex align-items-center <?php 
                            if ($card['logo_placement'] == 'top-center') echo 'justify-content-center flex-column'; 
                            else if ($card['logo_placement'] == 'top-right') echo 'justify-content-between flex-row-reverse';
                            else if ($card['logo_placement'] == 'hidden') echo 'd-none';
                            else echo 'justify-content-between'; // top-left
                        ?>">
                            <div class="rounded bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                <span class="material-symbols-outlined font-bold" style="color: <?php echo $card['primary_color']; ?>;">corporate_fare</span>
                            </div>
                            <span class="fw-bold text-uppercase text-white tracking-wider text-truncate" style="font-size: 11px; max-width: 180px;"><?php echo htmlspecialchars($card['company']); ?></span>
                        </div>
                        
                        <!-- Profile Photo Frame -->
                        <div class="z-10 text-center" style="margin-top: 30px;">
                            <img alt="Employee Photo" 
                                 class="border border-white border-4 shadow object-fit-cover <?php echo $card['photo_shape'] == 'circle' ? 'rounded-circle' : 'rounded-3'; ?>" 
                                 style="width: 130px; height: 130px;"
                                 src="<?php echo htmlspecialchars($card['photo_path']); ?>">
                        </div>
                        
                        <!-- Details Panel -->
                        <div class="z-10 text-center px-4 mt-3 d-flex flex-column align-items-center">
                            <h3 class="fw-bold text-dark mb-1 fs-4 text-truncate" style="max-width: 260px;"><?php echo htmlspecialchars($card['name']); ?></h3>
                            <p class="fw-bold mb-0 text-truncate" style="font-size: 14px; color: <?php echo $card['primary_color']; ?>; max-width: 260px;"><?php echo htmlspecialchars($card['role'] ?: 'Developer'); ?></p>
                            
                            <!-- Custom Details Frame -->
                            <div class="w-100 bg-light border border-light p-3 rounded-3 mt-4 text-start" style="font-size: 11px;">
                                <div class="d-flex justify-content-between mb-2 pb-1 border-bottom border-light">
                                    <span class="text-muted fw-semibold">EMPLOYEE ID</span>
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($card['employee_id']); ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted fw-semibold">COMPANY</span>
                                    <span class="fw-bold text-dark text-truncate" style="max-width: 140px;"><?php echo htmlspecialchars($card['company']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QR Mockup element -->
                        <?php if ($card['qr_code_enabled']): ?>
                            <div class="position-absolute bg-white border border-light p-1 shadow-sm rounded" style="bottom: 16px; right: 16px;">
                                <span class="material-symbols-outlined fs-2 text-dark">qr_code_2</span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Hologram shimmer overlay -->
                        <div class="hologram-overlay"></div>
                    </div>
                    
                    <!-- BACK FACE -->
                    <div class="card-face card-face-back text-center p-3" style="font-family: <?php echo $card['font_family'] == 'roboto' ? "'Roboto', sans-serif" : ($card['font_family'] == 'merriweather' ? "'Playfair Display', serif" : "'Inter', sans-serif"); ?>;">
                        <!-- Simulated magnetic stripe -->
                        <div class="w-100 bg-dark position-absolute start-0" style="height: 48px; top: 40px;"></div>
                        
                        <!-- Content Details Wrapper -->
                        <div class="mt-5 pt-4 text-<?php echo htmlspecialchars($card['text_alignment']); ?>">
                            <span class="text-muted small fw-bold text-uppercase tracking-wider d-block mb-1">Mailing Address</span>
                            <p class="text-dark small px-3 mb-4 lh-sm">
                                <?php echo nl2br(htmlspecialchars($card['address'])); ?>
                            </p>
                            
                            <span class="text-muted small fw-bold text-uppercase tracking-wider d-block mb-1">Terms & Policy</span>
                            <p class="text-muted px-4 mb-4 lh-xs" style="font-size: 9px;">
                                <?php echo nl2br(htmlspecialchars($card['terms'])); ?>
                            </p>
                        </div>
                        
                        <!-- Emergency Contacts Block -->
                        <?php if ($card['include_emergency']): ?>
                            <div class="mt-auto border-top border-light pt-2">
                                <span class="text-muted uppercase fw-bold" style="font-size: 8px; tracking-wider: 1px;">Emergency Contact</span>
                                <p class="fw-bold text-danger mb-2 small"><?php echo htmlspecialchars($card['emergency_contact']); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Barcode element simulation -->
                        <?php if ($card['include_back_barcode']): ?>
                            <div class="mb-2">
                                <div class="barcode-sim mx-auto mb-1" style="width: 220px;"></div>
                                <span class="text-muted small" style="font-size: 10px;"><?php echo htmlspecialchars($card['employee_id']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
            
            <p class="text-muted small mb-0 mt-3 position-relative z-1">Click card to spin 3D view.</p>
        </div>
    </div>

    <!-- Right Column: Record Summary & Action downloads -->
    <div class="col-12 col-lg-5 col-xl-4">
        <div class="d-flex flex-column gap-4">
            
            <!-- Summary Details -->
            <div class="bento-card border-0 shadow-sm p-4">
                <h3 class="fs-5 fw-bold text-dark border-bottom border-light pb-2 mb-4 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-indigo" style="color: #6366F1;">data_object</span>
                    Record Summary
                </h3>
                
                <div class="d-flex flex-column gap-3 small">
                    <div class="d-flex justify-content-between border-bottom border-light pb-2">
                        <span class="text-muted">Holder Name</span>
                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($card['name']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom border-light pb-2">
                        <span class="text-muted">Company</span>
                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($card['company']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom border-light pb-2">
                        <span class="text-muted">Employee ID</span>
                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($card['employee_id']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom border-light pb-2">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2"><?php echo htmlspecialchars($card['status']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Generated Time</span>
                        <span class="fw-semibold text-dark"><?php echo date('M d, Y H:i', strtotime($card['created_at'])); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Downloads Panel Actions -->
            <div class="bento-card border-0 shadow-sm p-4">
                <h3 class="fs-5 fw-bold text-dark border-bottom border-light pb-2 mb-4 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-indigo" style="color: #6366F1;">download</span>
                    Export Actions
                </h3>
                
                <div class="d-flex flex-column gap-3">
                    <!-- Download Front PNG -->
                    <button onclick="downloadCardAsPNG('front', currentCardData)" class="btn btn-indigo text-white py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #6366F1; border-color: #6366F1;">
                        <span class="material-symbols-outlined">image</span>
                        Download Front PNG
                    </button>
                    <!-- Download Back PNG -->
                    <button onclick="downloadCardAsPNG('back', currentCardData)" class="btn btn-light border py-3 fw-semibold text-dark d-flex align-items-center justify-content-center gap-2">
                        <span class="material-symbols-outlined text-muted">image</span>
                        Download Back PNG
                    </button>
                    <!-- Print card trigger -->
                    <button onclick="window.print()" class="btn btn-outline-secondary py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">print</span>
                        Print Physical Card
                    </button>
                    
                    <div class="border-top border-light my-3"></div>
                    
                    <!-- Create another redirect -->
                    <a href="create.php" class="btn btn-outline-primary py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">person_add</span>
                        Create Another Card
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Print Styles for printing actual CR80 cards -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .card-flip-container, .card-flip-container * {
        visibility: visible;
    }
    .card-flip-container {
        position: absolute;
        left: 0;
        top: 0;
        transform: none !important;
        box-shadow: none !important;
        border: none !important;
    }
    .card-face-back {
        display: none !important; /* Standard print does front side first */
    }
    /* Hide top nav, sidebar, footer, buttons */
    header, aside, footer, .btn-group, h2, p, button, .col-lg-5 {
        display: none !important;
    }
}
</style>

<!-- JSON Data Injector for JS Canvas downloads -->
<script>
const currentCardData = {
    name: <?php echo json_encode($card['name']); ?>,
    role: <?php echo json_encode($card['role'] ?: 'Developer'); ?>,
    dob: <?php echo json_encode($card['dob'] ?: 'YYYY-MM-DD'); ?>,
    empId: <?php echo json_encode($card['employee_id']); ?>,
    company: <?php echo json_encode($card['company']); ?>,
    photoUrl: <?php echo json_encode($card['photo_path']); ?>,
    primaryColor: <?php echo json_encode($card['primary_color']); ?>,
    photoShape: <?php echo json_encode($card['photo_shape']); ?>,
    qrCodeEnabled: <?php echo $card['qr_code_enabled'] ? 'true' : 'false'; ?>,
    hologramEnabled: <?php echo $card['hologram_enabled'] ? 'true' : 'false'; ?>,
    address: <?php echo json_encode($card['address']); ?>,
    terms: <?php echo json_encode($card['terms']); ?>,
    includeEmergency: <?php echo $card['include_emergency'] ? 'true' : 'false'; ?>,
    emergencyContact: <?php echo json_encode($card['emergency_contact']); ?>,
    includeBarcode: <?php echo $card['include_back_barcode'] ? 'true' : 'false'; ?>
};
</script>

<?php
require_once 'includes/footer.php';
?>
