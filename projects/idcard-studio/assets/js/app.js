// ID Card Studio Client Interactivity & Canvas Generation

document.addEventListener('DOMContentLoaded', () => {
    // 1. Step Navigation & Stepper Handling
    const steps = ['step-info', 'step-template', 'step-design-front', 'step-design-back'];
    let currentStepIndex = 0;

    const stepperItems = document.querySelectorAll('.step-item');
    const stepperLineActive = document.querySelector('.stepper-line-active');

    function updateStepper() {
        if (!stepperLineActive) return;
        
        // Update active line width
        const totalSteps = stepperItems.length;
        const progress = (currentStepIndex / (totalSteps - 1)) * 100;
        stepperLineActive.style.width = `${progress}%`;

        // Update step states
        stepperItems.forEach((item, index) => {
            if (index < currentStepIndex) {
                item.classList.add('completed');
                item.classList.remove('active');
            } else if (index === currentStepIndex) {
                item.classList.add('active');
                item.classList.remove('completed');
            } else {
                item.classList.remove('active', 'completed');
            }
        });
    }

    window.goToStep = function(stepIndex) {
        if (stepIndex < 0 || stepIndex >= steps.length) return;
        
        // Hide all steps
        steps.forEach(stepId => {
            const stepEl = document.getElementById(stepId);
            if (stepEl) stepEl.classList.add('d-none');
        });

        // Show target step
        const targetStepEl = document.getElementById(steps[stepIndex]);
        if (targetStepEl) targetStepEl.classList.remove('d-none');

        currentStepIndex = stepIndex;
        updateStepper();

        // Automatically flip card to front when editing front, or back when editing back
        const cardContainer = document.querySelector('.card-flip-container');
        if (cardContainer) {
            if (steps[stepIndex] === 'step-design-back') {
                cardContainer.classList.add('flipped');
            } else {
                cardContainer.classList.remove('flipped');
            }
        }
    };

    // Bind next/prev buttons
    const nextButtons = document.querySelectorAll('.btn-next-step');
    nextButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Basic validation
            if (currentStepIndex === 0) {
                const name = document.getElementById('card_name');
                const empId = document.getElementById('card_emp_id');
                const company = document.getElementById('card_company');
                
                if (name && !name.value.trim()) {
                    alert('Please enter a full name.');
                    name.focus();
                    return;
                }
                if (empId && !empId.value.trim()) {
                    alert('Please enter an employee ID.');
                    empId.focus();
                    return;
                }
                if (company && !company.value.trim()) {
                    alert('Please enter a company name.');
                    company.focus();
                    return;
                }
            }
            goToStep(currentStepIndex + 1);
        });
    });

    const prevButtons = document.querySelectorAll('.btn-prev-step');
    prevButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            goToStep(currentStepIndex - 1);
        });
    });


    // 2. Real-time Preview Bindings
    const bindings = [
        { inputId: 'card_name', outputIds: ['preview-name-text', 'preview-back-name'] },
        { inputId: 'card_role', outputIds: ['preview-role-text'] },
        { inputId: 'card_emp_id', outputIds: ['preview-emp-id-text', 'preview-back-id'] },
        { inputId: 'card_company', outputIds: ['preview-company-text', 'preview-back-company'] },
        { inputId: 'card_address', outputIds: ['preview-address-text'] },
        { inputId: 'card_terms', outputIds: ['preview-terms-text'] },
        { inputId: 'card_emergency_contact', outputIds: ['preview-emergency-text'] }
    ];

    bindings.forEach(binding => {
        const input = document.getElementById(binding.inputId);
        if (input) {
            const updateTexts = () => {
                binding.outputIds.forEach(outputId => {
                    const output = document.getElementById(outputId);
                    if (output) {
                        if (input.tagName === 'TEXTAREA') {
                            output.innerHTML = input.value.replace(/\n/g, '<br>');
                        } else {
                            output.innerText = input.value || ' ';
                        }
                    }
                });
            };
            input.addEventListener('keyup', updateTexts);
            input.addEventListener('change', updateTexts);
            // Run initially
            updateTexts();
        }
    });

    // 3. Card Flip Interactions
    const cardContainer = document.querySelector('.card-flip-container');
    const flipTrigger = document.getElementById('flip-card-btn');
    const tabFront = document.getElementById('tab-front');
    const tabBack = document.getElementById('tab-back');

    function flipCardTo(side) {
        if (!cardContainer) return;
        if (side === 'back') {
            cardContainer.classList.add('flipped');
            if (tabBack) {
                tabBack.classList.add('btn-primary');
                tabBack.classList.remove('btn-outline-primary');
            }
            if (tabFront) {
                tabFront.classList.remove('btn-primary');
                tabFront.classList.add('btn-outline-primary');
            }
        } else {
            cardContainer.classList.remove('flipped');
            if (tabFront) {
                tabFront.classList.add('btn-primary');
                tabFront.classList.remove('btn-outline-primary');
            }
            if (tabBack) {
                tabBack.classList.remove('btn-primary');
                tabBack.classList.add('btn-outline-primary');
            }
        }
    }

    if (cardContainer) {
        cardContainer.addEventListener('click', () => {
            const isFlipped = cardContainer.classList.contains('flipped');
            flipCardTo(isFlipped ? 'front' : 'back');
        });
    }

    if (flipTrigger) {
        flipTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isFlipped = cardContainer.classList.contains('flipped');
            flipCardTo(isFlipped ? 'front' : 'back');
        });
    }

    if (tabFront) {
        tabFront.addEventListener('click', (e) => {
            e.stopPropagation();
            flipCardTo('front');
        });
    }
    if (tabBack) {
        tabBack.addEventListener('click', (e) => {
            e.stopPropagation();
            flipCardTo('back');
        });
    }


    // 4. Color Dot Picker Binding
    const colorDots = document.querySelectorAll('.color-picker-dot');
    const hiddenColorInput = document.getElementById('card_color');
    const previewHeaderBand = document.getElementById('preview-header-band');
    const primaryColorTexts = document.querySelectorAll('.primary-color-text');

    colorDots.forEach(dot => {
        dot.addEventListener('click', () => {
            colorDots.forEach(d => d.classList.remove('active'));
            dot.classList.add('active');
            
            const color = dot.getAttribute('data-color');
            if (hiddenColorInput) hiddenColorInput.value = color;
            if (previewHeaderBand) previewHeaderBand.style.backgroundColor = color;
            
            primaryColorTexts.forEach(el => {
                el.style.color = color;
            });
        });
    });


    // 5. Photo Shape Handler
    const shapeInputs = document.querySelectorAll('input[name="photo_shape"]');
    const previewPhoto = document.getElementById('preview-photo');

    shapeInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            if (!previewPhoto) return;
            if (e.target.value === 'square') {
                previewPhoto.classList.remove('rounded-circle');
                previewPhoto.classList.add('rounded-3');
            } else {
                previewPhoto.classList.remove('rounded-3');
                previewPhoto.classList.add('rounded-circle');
            }
        });
    });


    // 6. Logo Placement Handler
    const logoSelect = document.getElementById('logo_placement');
    const previewLogoContainer = document.getElementById('preview-logo-area');

    if (logoSelect && previewLogoContainer) {
        logoSelect.addEventListener('change', () => {
            const placement = logoSelect.value;
            previewLogoContainer.className = 'z-10 mt-4 px-4 w-full d-flex align-items-center';
            
            if (placement === 'top-left') {
                previewLogoContainer.classList.add('justify-content-between');
                previewLogoContainer.style.flexDirection = 'row';
            } else if (placement === 'top-center') {
                previewLogoContainer.classList.add('justify-content-center');
                previewLogoContainer.style.flexDirection = 'column';
            } else if (placement === 'top-right') {
                previewLogoContainer.classList.add('justify-content-between');
                previewLogoContainer.style.flexDirection = 'row-reverse';
            } else if (placement === 'hidden') {
                previewLogoContainer.classList.add('d-none');
            }
        });
    }


    // 7. Toggle Features (QR, Hologram, Emergency, Barcode)
    const toggleQr = document.getElementById('toggle_qr');
    const previewQr = document.getElementById('preview-qr');
    if (toggleQr && previewQr) {
        toggleQr.addEventListener('change', () => {
            previewQr.classList.toggle('d-none', !toggleQr.checked);
        });
    }

    const toggleHologram = document.getElementById('toggle_hologram');
    if (toggleHologram && cardContainer) {
        toggleHologram.addEventListener('change', () => {
            cardContainer.classList.toggle('hologram-active', toggleHologram.checked);
        });
    }

    const toggleEmergency = document.getElementById('toggle_emergency');
    const previewEmergencyBlock = document.getElementById('preview-emergency-block');
    if (toggleEmergency && previewEmergencyBlock) {
        toggleEmergency.addEventListener('change', () => {
            previewEmergencyBlock.classList.toggle('d-none', !toggleEmergency.checked);
        });
    }

    const toggleBarcode = document.getElementById('toggle_barcode');
    const previewBackBarcodeBlock = document.getElementById('preview-back-barcode-block');
    if (toggleBarcode && previewBackBarcodeBlock) {
        toggleBarcode.addEventListener('change', () => {
            previewBackBarcodeBlock.classList.toggle('d-none', !toggleBarcode.checked);
        });
    }


    // 8. Photo Upload Handling (Local File Preview)
    const fileInput = document.getElementById('photo_file');
    const uploadTrigger = document.querySelector('.upload-trigger');
    const photoInputBase64 = document.getElementById('photo_base64');

    if (uploadTrigger && fileInput) {
        uploadTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.click();
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (previewPhoto) {
                        previewPhoto.src = evt.target.result;
                    }
                    if (photoInputBase64) {
                        photoInputBase64.value = evt.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }


    // 9. Template Selector logic
    const templateCards = document.querySelectorAll('.template-select-card');
    const hiddenTemplateId = document.getElementById('template_id');

    templateCards.forEach(card => {
        card.addEventListener('click', () => {
            templateCards.forEach(c => c.classList.remove('border-primary', 'bg-light'));
            card.classList.add('border-primary', 'bg-light');
            
            const templateId = card.getAttribute('data-template-id');
            const color = card.getAttribute('data-color');
            const shape = card.getAttribute('data-shape');

            if (hiddenTemplateId) hiddenTemplateId.value = templateId;

            // Apply template defaults to creator page
            if (color) {
                const targetDot = document.querySelector(`.color-picker-dot[data-color="${color}"]`);
                if (targetDot) targetDot.click();
            }
            if (shape) {
                const targetShape = document.querySelector(`input[name="photo_shape"][value="${shape}"]`);
                if (targetShape) {
                    targetShape.checked = true;
                    targetShape.dispatchEvent(new Event('change'));
                }
            }
        });
    });


    // 10. HTML Canvas Generation for PNG Download
    window.downloadCardAsPNG = function(side, cardData) {
        const canvas = document.createElement('canvas');
        canvas.width = 640; // Double scale for high res
        canvas.height = 1016; 
        const ctx = canvas.getContext('2d');

        // Setup font styles
        ctx.textBaseline = 'top';

        if (side === 'front') {
            // Background
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, 640, 1016);

            // Border subtle
            ctx.strokeStyle = '#e2e8f0';
            ctx.lineWidth = 4;
            ctx.strokeRect(2, 2, 636, 1012);

            // Header color band
            ctx.fillStyle = cardData.primaryColor || '#6366F1';
            ctx.fillRect(0, 0, 640, 200);

            // Draw Company Name text in header
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 32px sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText(cardData.company || 'Company Name', 120, 80);

            // Draw a simple logo icon (hexagonal)
            ctx.strokeStyle = '#ffffff';
            ctx.lineWidth = 4;
            ctx.beginPath();
            ctx.moveTo(70, 70);
            ctx.lineTo(90, 80);
            ctx.lineTo(90, 100);
            ctx.lineTo(70, 110);
            ctx.lineTo(50, 100);
            ctx.lineTo(50, 80);
            ctx.closePath();
            ctx.stroke();

            // Load and draw photo
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = function() {
                // Circle or Square photo clipping
                ctx.save();
                if (cardData.photoShape === 'circle') {
                    ctx.beginPath();
                    ctx.arc(320, 380, 120, 0, Math.PI * 2);
                    ctx.closePath();
                    ctx.clip();
                } else {
                    // Rounded rectangle
                    const r = 24;
                    const x = 200, y = 260, w = 240, h = 240;
                    ctx.beginPath();
                    ctx.moveTo(x+r, y);
                    ctx.lineTo(x+w-r, y);
                    ctx.quadraticCurveTo(x+w, y, x+w, y+r);
                    ctx.lineTo(x+w, y+h-r);
                    ctx.quadraticCurveTo(x+w, y+h, x+w-r, y+h);
                    ctx.lineTo(x+r, y+h);
                    ctx.quadraticCurveTo(x, y+h, x, y+h-r);
                    ctx.lineTo(x, y+r);
                    ctx.quadraticCurveTo(x, y, x+r, y);
                    ctx.closePath();
                    ctx.clip();
                }
                
                // Draw profile photo
                if (cardData.photoShape === 'circle') {
                    ctx.drawImage(img, 200, 260, 240, 240);
                } else {
                    ctx.drawImage(img, 200, 260, 240, 240);
                }
                ctx.restore();

                // Draw a nice border around photo
                ctx.strokeStyle = '#ffffff';
                ctx.lineWidth = 8;
                if (cardData.photoShape === 'circle') {
                    ctx.beginPath();
                    ctx.arc(320, 380, 120, 0, Math.PI * 2);
                    ctx.stroke();
                } else {
                    // Same rounded rect
                    const r = 24;
                    const x = 200, y = 260, w = 240, h = 240;
                    ctx.beginPath();
                    ctx.moveTo(x+r, y);
                    ctx.lineTo(x+w-r, y);
                    ctx.quadraticCurveTo(x+w, y, x+w, y+r);
                    ctx.lineTo(x+w, y+h-r);
                    ctx.quadraticCurveTo(x+w, y+h, x+w-r, y+h);
                    ctx.lineTo(x+r, y+h);
                    ctx.quadraticCurveTo(x, y+h, x, y+h-r);
                    ctx.lineTo(x, y+r);
                    ctx.quadraticCurveTo(x, y, x+r, y);
                    ctx.closePath();
                    ctx.stroke();
                }

                // Name
                ctx.fillStyle = '#0f172a';
                ctx.font = 'bold 44px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText(cardData.name || 'John Doe', 320, 560);

                // Role
                ctx.fillStyle = cardData.primaryColor || '#6366F1';
                ctx.font = '600 28px sans-serif';
                ctx.fillText(cardData.role || 'Employee', 320, 620);

                // Record details block (Department & ID)
                ctx.fillStyle = '#f8fafc';
                ctx.fillRect(80, 700, 480, 160);
                ctx.strokeStyle = '#e2e8f0';
                ctx.lineWidth = 2;
                ctx.strokeRect(80, 700, 480, 160);

                ctx.textAlign = 'left';
                ctx.fillStyle = '#64748b';
                ctx.font = 'bold 20px sans-serif';
                ctx.fillText('EMPLOYEE ID', 110, 730);
                ctx.fillText('DOB', 110, 800);

                ctx.textAlign = 'right';
                ctx.fillStyle = '#0f172a';
                ctx.font = 'bold 24px sans-serif';
                ctx.fillText(cardData.empId || 'EMP-0000', 530, 730);
                ctx.fillText(cardData.dob || 'YYYY-MM-DD', 530, 800);

                // QR Code icon mock at bottom right
                if (cardData.qrCodeEnabled) {
                    ctx.fillStyle = '#0f172a';
                    // Draw a mockup QR box
                    ctx.fillRect(490, 880, 70, 70);
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(495, 885, 60, 60);
                    ctx.fillStyle = '#0f172a';
                    // Simple nested squares
                    ctx.fillRect(500, 890, 20, 20);
                    ctx.fillRect(530, 890, 20, 20);
                    ctx.fillRect(500, 920, 20, 20);
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(505, 895, 10, 10);
                    ctx.fillRect(535, 895, 10, 10);
                    ctx.fillRect(505, 925, 10, 10);
                }

                // Trigger Download
                const dataUrl = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.download = `${cardData.name.replace(/\s+/g, '_')}_front.png`;
                link.href = dataUrl;
                link.click();
            };
            img.src = cardData.photoUrl || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80';
        } else {
            // Draw Back Side
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, 640, 1016);

            // Border subtle
            ctx.strokeStyle = '#e2e8f0';
            ctx.lineWidth = 4;
            ctx.strokeRect(2, 2, 636, 1012);

            // Magstripe mock
            ctx.fillStyle = '#1e293b';
            ctx.fillRect(0, 80, 640, 100);

            // Address title
            ctx.fillStyle = '#64748b';
            ctx.font = 'bold 22px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('COMPANY ADDRESS', 320, 240);

            // Address Details
            ctx.fillStyle = '#0f172a';
            ctx.font = '24px sans-serif';
            const addressLines = (cardData.address || 'Address not provided').split('\n');
            let addressY = 280;
            addressLines.forEach(line => {
                ctx.fillText(line, 320, addressY);
                addressY += 32;
            });

            // Terms & Conditions
            ctx.fillStyle = '#64748b';
            ctx.font = 'bold 22px sans-serif';
            ctx.fillText('TERMS & CONDITIONS', 320, 440);

            // Terms Details (Wrapped)
            ctx.fillStyle = '#334155';
            ctx.font = '20px sans-serif';
            const termsLines = wrapText(ctx, cardData.terms || 'Terms not provided', 500);
            let termsY = 480;
            termsLines.forEach(line => {
                ctx.fillText(line, 320, termsY);
                termsY += 28;
            });

            // Emergency Contact Block
            if (cardData.includeEmergency) {
                ctx.fillStyle = '#f1f5f9';
                ctx.fillRect(80, 720, 480, 90);
                ctx.fillStyle = '#ef4444';
                ctx.font = 'bold 18px sans-serif';
                ctx.fillText('EMERGENCY CONTACT', 320, 735);
                ctx.fillStyle = '#1e293b';
                ctx.font = 'bold 22px sans-serif';
                ctx.fillText(cardData.emergencyContact || '1-800-555-0199', 320, 765);
            }

            // Draw Barcode simulation
            if (cardData.includeBarcode) {
                // Barcode bars drawing
                ctx.fillStyle = '#0f172a';
                ctx.fillRect(120, 870, 400, 50);
                ctx.fillStyle = '#ffffff';
                // Draw vertical white stripes to make it look like a barcode
                const stripes = [135, 140, 155, 160, 165, 185, 200, 205, 225, 240, 255, 260, 275, 290, 310, 325, 330, 345, 360, 375, 380, 400, 415, 430, 445, 455, 470, 485, 490, 505];
                stripes.forEach(x => {
                    ctx.fillRect(x, 870, 3 + Math.random() * 6, 50);
                });

                ctx.fillStyle = '#64748b';
                ctx.font = 'bold 18px sans-serif';
                ctx.fillText(cardData.empId || 'EMP-0000', 320, 930);
            }

            // Trigger Download
            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = `${cardData.name.replace(/\s+/g, '_')}_back.png`;
            link.href = dataUrl;
            link.click();
        }
    };

    // Helper text wrapper for canvas
    function wrapText(ctx, text, maxWidth) {
        const words = text.split(' ');
        const lines = [];
        let currentLine = words[0];

        for (let i = 1; i < words.length; i++) {
            const word = words[i];
            const width = ctx.measureText(currentLine + " " + word).width;
            if (width < maxWidth) {
                currentLine += " " + word;
            } else {
                lines.push(currentLine);
                currentLine = word;
            }
        }
        lines.push(currentLine);
        return lines;
    }
});
