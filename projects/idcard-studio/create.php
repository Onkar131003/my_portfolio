<?php
require_once 'includes/db.php';

$page_title = "Create ID Card";
$active_tab = "create";

// Fetch templates from database
try {
    $stmt = $pdo->query("SELECT * FROM templates");
    $templates = $stmt->fetchAll();
} catch (PDOException $e) {
    $templates = [];
}

require_once 'includes/header.php';
?>

<!-- Stepper Container -->
<div class="stepper-container mb-4 d-none d-md-flex">
    <div class="stepper-line"></div>
    <div class="stepper-line-active" style="width: 0%;"></div>
    
    <div class="step-item active" onclick="goToStep(0)">
        <div class="step-circle">1</div>
        <span class="step-label">Info</span>
    </div>
    <div class="step-item" onclick="goToStep(1)">
        <div class="step-circle">2</div>
        <span class="step-label">Template</span>
    </div>
    <div class="step-item" onclick="goToStep(2)">
        <div class="step-circle">3</div>
        <span class="step-label">Front Design</span>
    </div>
    <div class="step-item" onclick="goToStep(3)">
        <div class="step-circle">4</div>
        <span class="step-label">Back Design</span>
    </div>
</div>

<div class="row g-4 align-items-start">
    <!-- Left Column: Form Editor -->
    <div class="col-12 col-lg-7 col-xl-8">
        <form action="save.php" method="POST" enctype="multipart/form-data" id="card-creator-form">
            <!-- Hidden Fields for state tracking -->
            <input type="hidden" name="template_id" id="template_id" value="1">
            <input type="hidden" name="primary_color" id="card_color" value="#6366F1">
            <input type="hidden" name="photo_base64" id="photo_base64" value="">

            <!-- STEP 1: Personal Card Information -->
            <fieldset id="step-info" class="bento-card mb-4 border-0 shadow-sm p-4">
                <div class="border-bottom border-light pb-2 mb-4 d-flex justify-content-between align-items-center">
                    <h2 class="fs-5 fw-bold text-dark mb-0">Step 1: Personal Info</h2>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">Info</span>
                </div>
                
                <div class="row g-3">
                    <!-- Full Name -->
                    <div class="col-12 col-sm-6">
                        <label for="card_name" class="form-label small fw-semibold text-muted">Full Name</label>
                        <input type="text" class="form-control" id="card_name" name="name" value="Rahul Sharma" required>
                    </div>
                    <!-- Role -->
                    <div class="col-12 col-sm-6">
                        <label for="card_role" class="form-label small fw-semibold text-muted">Job Role / Position</label>
                        <input type="text" class="form-control" id="card_role" name="role" value="Senior Developer" placeholder="e.g. Design Lead">
                    </div>
                    <!-- DOB -->
                    <div class="col-12 col-sm-6">
                        <label for="card_dob" class="form-label small fw-semibold text-muted">Date of Birth</label>
                        <input type="date" class="form-control" id="card_dob" name="dob" value="2001-03-12">
                    </div>
                    <!-- Employee ID -->
                    <div class="col-12 col-sm-6">
                        <label for="card_emp_id" class="form-label small fw-semibold text-muted">Employee ID</label>
                        <input type="text" class="form-control" id="card_emp_id" name="employee_id" value="EMP-1024" required>
                    </div>
                    <!-- Company Name -->
                    <div class="col-12 col-sm-6">
                        <label for="card_company" class="form-label small fw-semibold text-muted">Company Name</label>
                        <input type="text" class="form-control" id="card_company" name="company" value="TechNova Solutions" required>
                    </div>
                    <!-- Email -->
                    <div class="col-12 col-sm-6">
                        <label for="card_email" class="form-label small fw-semibold text-muted">Email Address</label>
                        <input type="email" class="form-control" id="card_email" name="email" value="rahul.s@technova.com" required>
                    </div>
                    
                    <!-- Profile Picture Upload -->
                    <div class="col-12 mt-4 pt-2 border-top border-light">
                        <label class="form-label small fw-semibold text-muted d-block mb-3">Profile Photo</label>
                        <div class="d-flex align-items-center gap-4">
                            <!-- Image Frame Upload Trigger -->
                            <div class="upload-trigger border border-dashed rounded-3 d-flex flex-column align-items-center justify-content-center bg-light overflow-hidden position-relative group cursor-pointer" 
                                 style="width: 140px; height: 180px; transition: border-color 0.2s;">
                                <span class="material-symbols-outlined text-muted fs-1 mb-2">cloud_upload</span>
                                <span class="small fw-semibold text-muted text-center px-2">Upload Photo</span>
                                <input type="file" id="photo_file" name="photo" class="d-none" accept="image/*">
                            </div>
                            
                            <!-- Help text & guidelines -->
                            <div class="flex-grow-1">
                                <h5 class="fs-6 fw-bold mb-1 text-dark">Select a Profile Picture</h5>
                                <p class="text-muted small mb-3">Supports JPG, PNG formats. Max file size: 2MB. Use high-resolution square ratio photos for optimal cropping details.</p>
                                
                                <!-- Fake Crop Zoom Control -->
                                <div class="d-flex flex-column gap-2" style="max-width: 250px;">
                                    <div class="d-flex justify-content-between text-muted small">
                                        <span class="material-symbols-outlined fs-6">zoom_out</span>
                                        <span>Scale Image</span>
                                        <span class="material-symbols-outlined fs-6">zoom_in</span>
                                    </div>
                                    <input type="range" class="form-range" min="1" max="100" value="50">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Button -->
                <div class="mt-5 d-flex justify-content-end">
                    <button type="button" class="btn btn-indigo text-white fw-bold px-4 py-2 btn-next-step" style="background-color: #6366F1; border-color: #6366F1;">
                        Next: Select Template
                    </button>
                </div>
            </fieldset>

            <!-- STEP 2: Template Selection -->
            <fieldset id="step-template" class="bento-card mb-4 border-0 shadow-sm p-4 d-none">
                <div class="border-bottom border-light pb-2 mb-4 d-flex justify-content-between align-items-center">
                    <h2 class="fs-5 fw-bold text-dark mb-0">Step 2: Choose Template</h2>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">Templates</span>
                </div>
                
                <p class="text-muted small mb-4">Select a pre-designed standard template framework to populate design defaults.</p>
                
                <div class="row g-3">
                    <?php foreach ($templates as $tmpl): ?>
                        <div class="col-12 col-md-6">
                            <div class="card p-3 border-2 cursor-pointer template-select-card transition-all <?php echo $tmpl['id'] == 1 ? 'border-primary bg-light' : 'border-light'; ?>" 
                                 data-template-id="<?php echo $tmpl['id']; ?>"
                                 data-color="<?php echo $tmpl['primary_color']; ?>"
                                 data-shape="<?php echo $tmpl['photo_shape']; ?>"
                                 style="border-radius: 0.75rem;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h4 class="fs-6 fw-bold mb-1 text-dark"><?php echo htmlspecialchars($tmpl['name']); ?></h4>
                                        <p class="text-muted small mb-0"><?php echo htmlspecialchars($tmpl['description']); ?></p>
                                    </div>
                                    <span class="badge bg-indigo-subtle text-indigo rounded-pill px-2 py-1 small" style="color: #6366F1; background-color: rgba(99,102,241,0.1);">
                                        <?php echo htmlspecialchars($tmpl['category']); ?>
                                    </span>
                                </div>
                                <div class="d-flex gap-2 text-muted small mt-2">
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">
                                            <?php echo $tmpl['orientation'] == 'portrait' ? 'portrait' : 'crop_landscape'; ?>
                                        </span>
                                        <?php echo ucfirst($tmpl['orientation']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Nav Buttons -->
                <div class="mt-5 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4 py-2 btn-prev-step">
                        Previous Step
                    </button>
                    <button type="button" class="btn btn-indigo text-white fw-bold px-4 py-2 btn-next-step" style="background-color: #6366F1; border-color: #6366F1;">
                        Next: Front Design
                    </button>
                </div>
            </fieldset>

            <!-- STEP 3: Front Design Configurations -->
            <fieldset id="step-design-front" class="bento-card mb-4 border-0 shadow-sm p-4 d-none">
                <div class="border-bottom border-light pb-2 mb-4 d-flex justify-content-between align-items-center">
                    <h2 class="fs-5 fw-bold text-dark mb-0">Step 3: Front Layout Design</h2>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">Front</span>
                </div>
                
                <div class="row g-4">
                    <!-- Photo Shape Selector -->
                    <div class="col-12 col-sm-6">
                        <label class="form-label small fw-semibold text-muted d-block mb-3">Photo Geometry Shape</label>
                        <div class="d-flex gap-3">
                            <div class="form-check border rounded-3 p-3 flex-fill text-center bg-light cursor-pointer">
                                <input class="form-check-input d-none" type="radio" name="photo_shape" id="shape_circle" value="circle" checked>
                                <label class="form-check-label w-100 cursor-pointer" for="shape_circle">
                                    <div class="rounded-circle bg-secondary bg-opacity-25 mx-auto mb-2" style="width: 40px; height: 40px;"></div>
                                    <span class="small fw-bold">Circle Shape</span>
                                </label>
                            </div>
                            <div class="form-check border rounded-3 p-3 flex-fill text-center bg-light cursor-pointer">
                                <input class="form-check-input d-none" type="radio" name="photo_shape" id="shape_square" value="square">
                                <label class="form-check-label w-100 cursor-pointer" for="shape_square">
                                    <div class="rounded-2 bg-secondary bg-opacity-25 mx-auto mb-2" style="width: 40px; height: 40px;"></div>
                                    <span class="small fw-bold">Square Shape</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Placement Options -->
                    <div class="col-12 col-sm-6">
                        <label for="logo_placement" class="form-label small fw-semibold text-muted">Brand Logo Alignment</label>
                        <select class="form-select py-2" id="logo_placement" name="logo_placement">
                            <option value="top-left" selected>Top Left</option>
                            <option value="top-center">Top Center</option>
                            <option value="top-right">Top Right</option>
                            <option value="hidden">Hidden</option>
                        </select>
                    </div>

                    <!-- Primary Theme Color Selection -->
                    <div class="col-12 col-sm-6">
                        <label class="form-label small fw-semibold text-muted d-block mb-3">Primary Theme Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="color-picker-dot active" data-color="#6366F1" style="background-color: #6366F1;"></button>
                            <button type="button" class="color-picker-dot" data-color="#14B8A6" style="background-color: #14B8A6;"></button>
                            <button type="button" class="color-picker-dot" data-color="#4648d4" style="background-color: #4648d4;"></button>
                            <button type="button" class="color-picker-dot" data-color="#ba1a1a" style="background-color: #ba1a1a;"></button>
                            <button type="button" class="color-picker-dot" data-color="#0F172A" style="background-color: #0F172A;"></button>
                        </div>
                    </div>

                    <!-- Font Family Settings -->
                    <div class="col-12 col-sm-6">
                        <label for="font_family" class="form-label small fw-semibold text-muted">Font Family Style</label>
                        <select class="form-select py-2" id="font_family" name="font_family">
                            <option value="inter" selected>Inter (Standard Sans)</option>
                            <option value="roboto">Roboto (Rounded Clean)</option>
                            <option value="merriweather">Playfair (Premium Serif)</option>
                        </select>
                    </div>

                    <!-- Toggles/Features -->
                    <div class="col-12 border-top border-light pt-4 mt-4">
                        <h4 class="fs-6 fw-bold text-dark mb-3">Security Features & Details</h4>
                        
                        <!-- Include QR Code -->
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-0 mb-3">
                            <div class="pe-3">
                                <label class="form-check-label fw-bold small text-dark d-block" for="toggle_qr">Include Scannable Profile QR</label>
                                <span class="text-muted small">Generates interactive digital profile checks.</span>
                            </div>
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_qr" name="qr_code_enabled" value="1" checked>
                        </div>
                        
                        <!-- Security Hologram overlay -->
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-0 mb-3">
                            <div class="pe-3">
                                <label class="form-check-label fw-bold small text-dark d-block" for="toggle_hologram">Add Holographic Shimmer Overlay</label>
                                <span class="text-muted small">Applies shimmering rainbow visual overlay security layer.</span>
                            </div>
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_hologram" name="hologram_enabled" value="1">
                        </div>
                    </div>
                </div>

                <!-- Nav Buttons -->
                <div class="mt-5 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4 py-2 btn-prev-step">
                        Previous Step
                    </button>
                    <button type="button" class="btn btn-indigo text-white fw-bold px-4 py-2 btn-next-step" style="background-color: #6366F1; border-color: #6366F1;">
                        Next: Back Design
                    </button>
                </div>
            </fieldset>

            <!-- STEP 4: Back Design Configurations -->
            <fieldset id="step-design-back" class="bento-card mb-4 border-0 shadow-sm p-4 d-none">
                <div class="border-bottom border-light pb-2 mb-4 d-flex justify-content-between align-items-center">
                    <h2 class="fs-5 fw-bold text-dark mb-0">Step 4: Back Side Layout</h2>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">Back</span>
                </div>
                
                <div class="row g-3">
                    <!-- Address -->
                    <div class="col-12">
                        <label for="card_address" class="form-label small fw-semibold text-muted">Company Mailing Address</label>
                        <textarea class="form-control text-muted" id="card_address" name="address" rows="3">100 Tech Park Drive, Suite 400
San Jose, CA 95110</textarea>
                    </div>
                    <!-- Terms -->
                    <div class="col-12">
                        <label for="card_terms" class="form-label small fw-semibold text-muted">Terms and Return Policy Conditions</label>
                        <textarea class="form-control text-muted" id="card_terms" name="terms" rows="4">This card remains the property of the issuer. If found, please drop in the nearest mailbox or return to the address listed above.</textarea>
                    </div>
                    
                    <div class="col-12 border-top border-light pt-4 mt-4">
                        <h4 class="fs-6 fw-bold text-dark mb-3 font-semibold">Toggles & Features</h4>
                        
                        <!-- Emergency Info -->
                        <div class="border border-light p-3 rounded-3 bg-light mb-3">
                            <div class="form-check form-switch d-flex justify-content-between align-items-center p-0 mb-3">
                                <div>
                                    <label class="form-check-label fw-bold small text-dark d-block" for="toggle_emergency">Emergency Contact Details</label>
                                    <span class="text-muted small">Adds emergency text details section on the back side.</span>
                                </div>
                                <input class="form-check-input" type="checkbox" role="switch" id="toggle_emergency" name="include_emergency" value="1" checked>
                            </div>
                            <div id="emergency-input-block">
                                <label for="card_emergency_contact" class="form-label small fw-semibold text-muted">Emergency Helpline Phone/Text</label>
                                <input type="text" class="form-control" id="card_emergency_contact" name="emergency_contact" value="Emergency Contact: HR Dept - Ext 401">
                            </div>
                        </div>
                        
                        <!-- Barcode Toggle -->
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-0">
                            <div>
                                <label class="form-check-label fw-bold small text-dark d-block" for="toggle_barcode">Include Scannable Barcode</label>
                                <span class="text-muted small">Generates mockup linear barcode based on Employee ID.</span>
                            </div>
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_barcode" name="include_back_barcode" value="1" checked>
                        </div>
                    </div>
                </div>

                <!-- Nav Buttons & Form Submit -->
                <div class="mt-5 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4 py-2 btn-prev-step">
                        Previous Step
                    </button>
                    <button type="submit" class="btn btn-success text-white fw-bold px-4 py-2 shadow-sm d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size: 20px;">save</span>
                        Generate Card
                    </button>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- Right Column: Live Card Preview Sticky Panel -->
    <div class="col-12 col-lg-5 col-xl-4 position-sticky" style="top: 84px;">
        <div class="bg-white rounded-4 border border-light p-4 shadow-sm text-center">
            <h3 class="fs-5 fw-bold text-dark mb-3">Live Card Preview</h3>
            
            <!-- Controls: Flip Trigger Tabs -->
            <div class="btn-group w-100 mb-4" role="group">
                <button type="button" id="tab-front" class="btn btn-primary btn-sm py-2">Front Side</button>
                <button type="button" id="tab-back" class="btn btn-outline-primary btn-sm py-2">Back Side</button>
            </div>
            
            <!-- Perspective container for 3D card rotators -->
            <div class="d-flex justify-content-center align-items-center perspective-1000 mb-3">
                <div class="card-flip-container shadow-lg">
                    
                    <!-- FRONT FACE -->
                    <div class="card-face card-face-front text-start">
                        <!-- Colored Top Strip Header -->
                        <div id="preview-header-band" class="w-100 position-absolute top-0 start-0" style="height: 100px; background-color: #6366F1;"></div>
                        
                        <!-- Logo & Company Name header -->
                        <div id="preview-logo-area" class="z-10 mt-4 px-4 w-full d-flex align-items-center justify-content-between">
                            <div class="rounded bg-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                <span class="material-symbols-outlined text-indigo font-bold" style="color: #6366F1;">corporate_fare</span>
                            </div>
                            <span id="preview-company-text" class="fw-bold text-uppercase text-white text-truncate tracking-wider" style="font-size: 11px; max-width: 180px;">TechNova Solutions</span>
                        </div>
                        
                        <!-- Profile Photo Frame -->
                        <div class="z-10 text-center" style="margin-top: 30px;">
                            <img id="preview-photo" alt="Employee Photo" 
                                 class="border border-white border-4 shadow rounded-circle object-fit-cover" 
                                 style="width: 130px; height: 130px;"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuBoF9akReTpLK_Dn9nQNstUXJbd3TvhXH0WKXqQuV86vcRiRqrZOxQjNYy8rD4AVxYGO83XfX-R6MIPRNk4Kjk9hYFvQUylDB7ToNnILPK4G9UmrM3Trj61v1WCxu9WY06rhNP_CySF9SPfUx1IPkFLo0lfGoIc3aoYm7P1H9wKfKa85fWyCYtotP8hDBLFlqqUJZPq9Xo8DbDYBgcJWUsbZWjLHyWFmMb0LvZ73NnRAzoxOaMIKbb7EA">
                        </div>
                        
                        <!-- Details Panel -->
                        <div class="z-10 text-center px-4 mt-3 d-flex flex-column align-items-center">
                            <h3 id="preview-name-text" class="fw-bold text-dark mb-1 fs-4 text-truncate" style="max-width: 260px;">Rahul Sharma</h3>
                            <p id="preview-role-text" class="primary-color-text fw-bold mb-0 text-truncate" style="font-size: 14px; color: #6366F1; max-width: 260px;">Senior Developer</p>
                            
                            <!-- Custom Details Frame -->
                            <div class="w-100 bg-light border border-light p-3 rounded-3 mt-4 text-start" style="font-size: 11px;">
                                <div class="d-flex justify-content-between mb-2 pb-1 border-bottom border-light">
                                    <span class="text-muted fw-semibold">EMPLOYEE ID</span>
                                    <span id="preview-emp-id-text" class="fw-bold text-dark">EMP-1024</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted fw-semibold">COMPANY</span>
                                    <span id="preview-company-text-display" class="fw-bold text-dark text-truncate" style="max-width: 140px;">TechNova</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QR Mockup element -->
                        <div id="preview-qr" class="position-absolute bg-white border border-light p-1 shadow-sm rounded" style="bottom: 16px; right: 16px;">
                            <span class="material-symbols-outlined fs-2 text-dark">qr_code_2</span>
                        </div>
                        
                        <!-- Hologram rainbow shimmer sheet overlay -->
                        <div class="hologram-overlay"></div>
                    </div>
                    
                    <!-- BACK FACE -->
                    <div class="card-face card-face-back text-center p-3">
                        <!-- Simulated magnetic stripe -->
                        <div class="w-100 bg-dark position-absolute start-0" style="height: 48px; top: 40px;"></div>
                        
                        <!-- Content Details Wrapper -->
                        <div class="mt-5 pt-4 text-center">
                            <span class="text-muted small fw-bold text-uppercase tracking-wider d-block mb-1">Mailing Address</span>
                            <p id="preview-address-text" class="text-dark small px-3 mb-4 lh-sm">
                                100 Tech Park Drive, Suite 400<br>San Jose, CA 95110
                            </p>
                            
                            <span class="text-muted small fw-bold text-uppercase tracking-wider d-block mb-1">Terms & Policy</span>
                            <p id="preview-terms-text" class="text-muted px-4 mb-4 lh-xs" style="font-size: 9px;">
                                This card remains the property of the issuer. If found, please drop in the nearest mailbox or return to the address listed above.
                            </p>
                        </div>
                        
                        <!-- Emergency Contacts Block -->
                        <div id="preview-emergency-block" class="mt-auto border-top border-light pt-2">
                            <span class="text-muted uppercase fw-bold" style="font-size: 8px; tracking-wider: 1px;">Emergency Contact</span>
                            <p id="preview-emergency-text" class="fw-bold text-danger mb-2 small">Emergency Contact: HR Dept - Ext 401</p>
                        </div>
                        
                        <!-- Barcode element simulation -->
                        <div id="preview-back-barcode-block" class="mb-2">
                            <div class="barcode-sim mx-auto mb-1" style="width: 220px;"></div>
                            <span id="preview-back-id" class="text-muted small" style="font-size: 10px;">EMP-1024</span>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <p class="text-muted small mb-0">Click preview card or tabs to inspect other side.</p>
            
            <button type="button" id="flip-card-btn" class="btn btn-sm btn-outline-secondary mt-3 d-flex align-items-center gap-1 mx-auto">
                <span class="material-symbols-outlined" style="font-size: 16px;">flip</span> Spin Card
            </button>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
