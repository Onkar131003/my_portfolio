document.addEventListener("DOMContentLoaded", () => {
    // 0. Initialize Lenis Smooth Scroll (Disabled to restore native browser scrolling)
    let lenis;
    let cinematicIntroComplete = false;

    // Initialize GSAP and ScrollTrigger
    if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
        gsap.registerPlugin(ScrollTrigger);
    }

    // 0.1. Initialize Cinematic Intro Animation if elements exist
    const cinematicWrapper = document.querySelector(".cinematicIntro-module-scss-module__hcIKfW__cinematicWrapper");
    if (cinematicWrapper) {
        const pinnedViewport = cinematicWrapper.querySelector(".cinematicIntro-module-scss-module__hcIKfW__pinnedViewport");
        const filmGrain = cinematicWrapper.querySelector(".cinematicIntro-module-scss-module__hcIKfW__filmGrain");
        const vignette = cinematicWrapper.querySelector(".cinematicIntro-module-scss-module__hcIKfW__vignette");
        const ambientLight = cinematicWrapper.querySelector(".cinematicIntro-module-scss-module__hcIKfW__ambientLight");
        const heroText = cinematicWrapper.querySelector(".cinematicIntro-module-scss-module__hcIKfW__heroText");
        const heroTextOutline = cinematicWrapper.querySelector(".cinematicIntro-module-scss-module__hcIKfW__heroTextOutline");
        const heroFrame = cinematicWrapper.querySelector(".cinematicIntro-module-scss-module__hcIKfW__heroFrame");
        const frameGlow = cinematicWrapper.querySelector(".cinematicIntro-module-scss-module__hcIKfW__frameGlow");

        if (pinnedViewport && heroText && heroFrame) {
            // Replaces custom scroll-locking with native ScrollTrigger pin and scrub (fluid like original site)
            const cinematicTimeline = gsap.timeline({
                scrollTrigger: {
                    trigger: cinematicWrapper,
                    start: "top top",
                    end: "+=120%",
                    scrub: true,
                    pin: true,
                    anticipatePin: 1,
                    pinSpacing: true,
                    onToggle: self => {
                        document.body.style.overflow = self.isActive ? "" : "";
                    }
                }
            });
            
            // Step 1: Scale text slightly
            cinematicTimeline.to(heroText, { scale: 1.02, opacity: 0.95, duration: 0.2, ease: "none" }, 0);
            
            // Step 2: Zoom text large
            cinematicTimeline.to(heroText, { scale: 9, opacity: 0, rotateX: -3, y: "-5%", duration: 0.4, ease: "power2.in" }, 0.2);
            
            // Step 3: Fade in and zoom outline text
            cinematicTimeline.fromTo(heroTextOutline, 
                { scale: 1.02, opacity: 0 }, 
                { scale: 3, opacity: 0.5, rotateX: -2, y: "-3%", duration: 0.15, ease: "power1.in" }, 
                0.3
            );
            
            // Step 4: Zoom outline text extremely large
            cinematicTimeline.to(heroTextOutline, { scale: 9, opacity: 0, rotateX: -4, y: "-8%", duration: 0.25, ease: "power2.in" }, 0.45);
            
            // Step 5: Expand clip-path of frame
            cinematicTimeline.to(heroFrame, { clipPath: "inset(0% 0% round 0px)", filter: "brightness(1)", duration: 0.35, ease: "power2.out" }, 0.55);
            
            // Step 6: Fade out glow
            if (frameGlow) {
                cinematicTimeline.to(frameGlow, { opacity: 0, duration: 0.3, ease: "power1.out" }, 0.55);
            }
            
            // Mark cinematic intro complete and release pointer events
            cinematicTimeline.call(() => {
                cinematicIntroComplete = true;
                cinematicWrapper.style.pointerEvents = "none";
                pinnedViewport.style.pointerEvents = "none";
            });

            // Step 7: Fade overlays and make pinned viewport transparent
            cinematicTimeline.to(pinnedViewport, { backgroundColor: "rgba(10, 10, 12, 0)", duration: 0.15, ease: "none" }, 0.8);
            if (filmGrain) {
                cinematicTimeline.to(filmGrain, { opacity: 0, duration: 0.15, ease: "none" }, 0.8);
            }
            if (vignette) {
                cinematicTimeline.to(vignette, { opacity: 0, duration: 0.15, ease: "none" }, 0.8);
            }
            if (ambientLight) {
                cinematicTimeline.to(ambientLight, { opacity: 0, duration: 0.15, ease: "none" }, 0.8);
            }
        }
    }
    
    // 1. Loading Screen Animation (Introduction Loader)
    const loader = document.querySelector(".style-module-scss-module__u-Ka2a__introduction");
    const greetings = ["Hello", "Namaste", "Bonjour", "Ciao", "Hola", "Onkar Padman"];
    
    if (loader) {
        // Ensure loader is styled correctly
        loader.style.zIndex = "999"; // Stay on top
        loader.style.position = "fixed";
        loader.style.inset = "0";
        loader.style.backgroundColor = "#141516";
        loader.style.display = "flex";
        loader.style.justifyContent = "center";
        loader.style.alignItems = "center";
        loader.style.overflow = "hidden";
        
        // Add a text element inside if none exists
        let textEl = loader.querySelector("p");
        if (!textEl) {
            textEl = document.createElement("p");
            loader.appendChild(textEl);
        }
        
        // Style the greeting paragraph
        textEl.style.color = "#ffffff";
        textEl.style.fontSize = "clamp(2rem, 6vw, 3.5rem)";
        textEl.style.fontFamily = "var(--font-inter), sans-serif";
        textEl.style.fontWeight = "500";
        textEl.style.margin = "0";
        textEl.style.zIndex = "1000";
        
        let currentWordIndex = 0;
        
        function cycleWords() {
            if (currentWordIndex < greetings.length) {
                textEl.textContent = greetings[currentWordIndex];
                textEl.style.opacity = "1";
                textEl.style.transform = "translateY(0)";
                textEl.style.transition = "transform 0.2s ease, opacity 0.2s ease";
                
                setTimeout(() => {
                    // Start fading out before switching word
                    textEl.style.opacity = "0";
                    textEl.style.transform = "translateY(-15px)";
                    textEl.style.transition = "transform 0.15s ease, opacity 0.15s ease";
                    
                    setTimeout(() => {
                        currentWordIndex++;
                        cycleWords();
                    }, 150);
                }, 200);
            } else {
                // End of greetings - slide up the loading screen
                loader.style.transform = "translateY(-100%)";
                loader.style.transition = "transform 0.85s cubic-bezier(0.76, 0, 0.24, 1)";
                
                // Animate header and content entrance once loader slides up
                setTimeout(() => {
                    animateHeaderEntrance();
                    triggerImmediateScrollIntersection();
                }, 100);
                
                // Remove loader from DOM after transition completes
                setTimeout(() => {
                    loader.style.display = "none";
                }, 900);
            }
        }
        
        // Start the greeting cycle
        cycleWords();
    } else {
        // No loader on this page - animate entrance immediately
        animateHeaderEntrance();
        triggerImmediateScrollIntersection();
    }

    // Header Entrance Animation function
    function animateHeaderEntrance() {
        const header = document.getElementById("global-header");
        const burgerContainer = document.getElementById("global-burger-container");
        
        if (header) {
            header.style.opacity = "1";
            header.style.transform = "translateY(0)";
            header.style.transition = "transform 0.8s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.8s ease";
        }
        
        if (burgerContainer) {
            burgerContainer.style.transform = "scale(1)";
            burgerContainer.style.transition = "transform 0.6s cubic-bezier(0.22, 1, 0.36, 1)";
        }
    }

    // Scroll Header Style switch (Using native browser scroll listener)
    window.addEventListener("scroll", () => {
        const header = document.getElementById("global-header");
        if (!header) return;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > 50) {
            header.classList.add("styleheader-module-scss-module__WEgd9a__headerFloating");
        } else {
            header.classList.remove("styleheader-module-scss-module__WEgd9a__headerFloating");
        }
    });

    // 2. Mobile Menu Burger Click
    const burgerBtn = document.getElementById("global-burger-btn");
    const burgerIcon = document.getElementById("global-burger-icon");
    const nav = document.getElementById("global-nav");

    const isIntroActive = () => {
        const loaderEl = document.querySelector(".style-module-scss-module__u-Ka2a__introduction");
        return !cinematicIntroComplete || (loaderEl && loaderEl.style.display !== "none");
    };

    if (burgerBtn && nav) {
        burgerBtn.addEventListener("click", (e) => {
            if (isIntroActive()) return;
            e.stopPropagation();
            const isExpanded = burgerIcon.classList.contains("styleheader-module-scss-module__WEgd9a__burgerActive");
            
            if (isExpanded) {
                burgerIcon.classList.remove("styleheader-module-scss-module__WEgd9a__burgerActive");
                nav.classList.remove("styleheader-module-scss-module__WEgd9a__shownav");
            } else {
                burgerIcon.classList.add("styleheader-module-scss-module__WEgd9a__burgerActive");
                nav.classList.add("styleheader-module-scss-module__WEgd9a__shownav");
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener("click", (e) => {
            if (isIntroActive()) return;
            if (!nav.contains(e.target) && !burgerBtn.contains(e.target)) {
                burgerIcon.classList.remove("styleheader-module-scss-module__WEgd9a__burgerActive");
                nav.classList.remove("styleheader-module-scss-module__WEgd9a__shownav");
            }
        });
    }

    // 3. Active Nav Link Styling
    const path = window.location.pathname;
    const navLinks = document.querySelectorAll("#global-nav a");
    navLinks.forEach(link => {
        const dataNav = link.getAttribute("data-nav");
        let isActive = false;
        
        if (dataNav === "home" && (path === "/" || path === "/index.html")) {
            isActive = true;
        } else if (path.includes("/" + dataNav)) {
            isActive = true;
        }
        
        if (isActive) {
            link.classList.add("styleheader-module-scss-module__WEgd9a__elActive");
        } else {
            link.classList.remove("styleheader-module-scss-module__WEgd9a__elActive");
        }
    });

    // 3.1 Smooth Anchor Scrolling with Lenis
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener("click", function(e) {
            const targetId = this.getAttribute("href");
            if (targetId === "#" || targetId.length <= 1) return;
            const target = document.querySelector(targetId);
            if (!target) return;
            e.preventDefault();
            if (window.lenis) {
                window.lenis.scrollTo(target, { offset: 0, duration: 1.2, easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)) });
            } else {
                target.scrollIntoView({ behavior: "smooth" });
            }
        });
    });

    // 4. Staggered Word Slide-Up (Mask text)
    const animatedWords = document.querySelectorAll(".styledesc-module-scss-module__Uf2pWa__mask span");
    if (animatedWords.length > 0) {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animatedWords.forEach((wordSpan, index) => {
                        setTimeout(() => {
                            wordSpan.style.transform = "translateY(0)";
                            wordSpan.style.transition = "transform 0.8s cubic-bezier(0.22, 1, 0.36, 1)";
                        }, index * 25);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        const container = document.querySelector(".styledesc-module-scss-module__Uf2pWa__body");
        if (container) {
            observer.observe(container);
        }
    }

    // 5. Project Accordion Case Studies
    const projects = document.querySelectorAll(".style-module-scss-module__g_CNKG__project");
    projects.forEach(project => {
        project.addEventListener("click", () => {
            const panelId = project.getAttribute("aria-controls");
            const panel = document.getElementById(panelId);
            const indicator = project.querySelector(".style-module-scss-module__g_CNKG__indicator");
            
            if (!panel) return;
            
            const isExpanded = project.getAttribute("aria-expanded") === "true";
            
            // Collapse all other projects first
            projects.forEach(otherProj => {
                if (otherProj !== project) {
                    otherProj.setAttribute("aria-expanded", "false");
                    const otherPanelId = otherProj.getAttribute("aria-controls");
                    const otherPanel = document.getElementById(otherPanelId);
                    if (otherPanel) {
                        otherPanel.setAttribute("aria-hidden", "true");
                        otherPanel.style.maxHeight = null;
                        otherPanel.classList.remove("style-module-scss-module__g_CNKG__panelExpanded");
                    }
                    const otherIndicator = otherProj.querySelector(".style-module-scss-module__g_CNKG__indicator");
                    if (otherIndicator) {
                        otherIndicator.textContent = "+";
                        otherIndicator.style.transform = "rotate(0)";
                    }
                }
            });
            
            // Toggle current project
            if (isExpanded) {
                project.setAttribute("aria-expanded", "false");
                panel.setAttribute("aria-hidden", "true");
                panel.style.maxHeight = null;
                panel.classList.remove("style-module-scss-module__g_CNKG__panelExpanded");
                if (indicator) {
                    indicator.textContent = "+";
                    indicator.style.transform = "rotate(0)";
                }
            } else {
                project.setAttribute("aria-expanded", "true");
                panel.setAttribute("aria-hidden", "false");
                panel.style.maxHeight = panel.scrollHeight + "px";
                panel.classList.add("style-module-scss-module__g_CNKG__panelExpanded");
                if (indicator) {
                    indicator.textContent = "−";
                    indicator.style.transform = "rotate(180deg)";
                    indicator.style.transition = "transform 0.4s ease";
                }
            }
        });
    });

    // 6. Certifications 3D Stacked Card Deck Swap
    const cards = document.querySelectorAll(".card-swap-container .card");
    if (cards.length > 0) {
        let cardArray = Array.from(cards);
        
        function updateDeck() {
            cardArray.forEach((card, index) => {
                card.style.zIndex = 10 - index;
                const scale = 1 - (index * 0.05);
                const translateY = index * 12;
                const rotateY = -index * 3;
                const translateZ = -index * 20;
                
                card.style.transform = `translate(-50%, -50%) translateZ(${translateZ}px) translateY(${translateY}px) rotateY(${rotateY}deg) scale(${scale})`;
                card.style.transition = "transform 0.6s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.6s ease, z-index 0.6s ease";
                
                if (index === 0) {
                    card.style.opacity = "1";
                    card.style.cursor = "pointer";
                    card.style.pointerEvents = "auto";
                } else {
                    card.style.opacity = (1 - index * 0.15).toString();
                    card.style.cursor = "default";
                    card.style.pointerEvents = "none";
                }
            });
        }
        
        updateDeck();
        
        const container = document.querySelector(".card-swap-container");
        if (container) {
            container.addEventListener("click", () => {
                const topCard = cardArray.shift();
                topCard.style.transform = "translate(50%, -50%) rotate(15deg) scale(0.9)";
                topCard.style.opacity = "0";
                cardArray.push(topCard);
                
                setTimeout(() => {
                    updateDeck();
                }, 150);
            });
        }
    }

    // 7. General Scroll Intersection Observer for pre-hidden elements (style="opacity:0")
    function triggerImmediateScrollIntersection() {
        const preHiddenElements = document.querySelectorAll('[style*="opacity:0"], [style*="opacity: 0"]');
        
        const observerOptions = {
            threshold: 0.05,
            rootMargin: "0px 0px -50px 0px"
        };
        
        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    
                    // Set transition first to ensure animation works
                    el.style.transition = "transform 1.2s cubic-bezier(0.22, 1, 0.36, 1), opacity 1.2s ease";
                    
                    // Force reflow
                    void el.offsetHeight;
                    
                    // Trigger animation
                    el.style.opacity = "1";
                    
                    const inlineStyle = el.getAttribute("style") || "";
                    if (inlineStyle.includes("transform")) {
                        el.style.transform = "translateX(0) translateY(0) scale(1)";
                    }
                    scrollObserver.unobserve(el);
                }
            });
        }, observerOptions);
        
        preHiddenElements.forEach(el => {
            if (el.id !== "global-header") {
                scrollObserver.observe(el);
            }
        });

        // Contact Section (Footer) Observer
        const contactSec = document.getElementById("contact");
        if (contactSec) {
            const contactObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        contactSec.classList.add("contact-module-scss-module__CgwxEq__isVisible");
                        contactObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            contactObserver.observe(contactSec);
        }
    }
});