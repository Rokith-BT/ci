class MenuTourGuide {
    constructor() {
        this.currentStep = 0;
        this.isActive = false;
        this.tourSteps = [];
        this.createTourElements();
        this.bindEvents();
    }
    appointment() {
        this.tourSteps = [
            {
                selector: 'a[href*="admin/leavetypes"]',
                title: 'Human Resource Setup',
                description: 'Manage HR operations including employee designations, departments, leave types, and staff management.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/staff"]',
                title: 'Staff Management',
                description: 'Manage hospital staff, including doctors, nurses, and administrative personnel. View and edit staff details.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/charges"]',
                title: 'Hospital Charges',
                description: 'Set up and manage hospital charges for various services, procedures, and treatments.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/onlineappointment/"]',
                title: 'Appointment Setup',
                description: 'Configure  appointment slots, doctor shifts, and Doctor Fee Management.',
                position: 'right'
            },
            {
                selector: 'a[href*="#today"]',
                title: 'Today\'s Appointments',
                description: 'View today\'s appointments, manage patient check-ins, and update appointment statuses.',
                position: 'right'
            },
            {
                selector: 'a[href*="#upcoming"]',
                title: 'Upcoming Appointments',
                description: 'View and manage upcoming appointments, including rescheduling and cancellations.',
                position: 'right'
            },
            {
                selector: 'a[href*="#history"]',
                title: 'Appointment History',
                description: 'Access past appointment records, view patient history, and manage follow-up appointments.',
                position: 'right'

            },
            {
                selector: 'a[data-target="#myModal"]',
                title: 'Add Appointment',
                description: 'Add new appointments for patients, including selecting doctors, appointment types, and scheduling details.',
                position: 'right'
            }
        ];
        this.startTour();
    }

    setupmodule() {
        this.tourSteps = [
            {
                selector: 'a[href*="schsettings"]',
                title: 'General Settings',
                description: 'Configure hospital general settings like logo, timing, location, contact information, and other basic hospital details.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/admin/search"]',
                title: 'Patient Management',
                description: 'Manage patient information, registration, and patient records. View and search patient details.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/charges"]',
                title: 'Hospital Charges',
                description: 'Set up and manage hospital charges for various services, procedures, and treatments.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/setup/bed/status"]',
                title: 'Bed Management',
                description: 'Manage hospital beds, bed status, bed types, and bed assignments for IPD patients.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/printing"]',
                title: 'Print Settings',
                description: 'Configure print headers and footers for prescriptions, bills, payslips, and other documents.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/visitorspurpose"]',
                title: 'Front Office',
                description: 'Manage front office operations including visitor purposes, complaints, and reception activities.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/operationtheatre/index"]',
                title: 'Operations',
                description: 'Manage operation theatre, operation categories, and surgical procedures scheduling.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/medicinecategory/index"]',
                title: 'Pharmacy',
                description: 'Manage pharmacy operations including medicine categories, suppliers, dosages, and inventory.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/pathologycategory/addcategory"]',
                title: 'Pathology',
                description: 'Set up pathology categories, units, parameters, and manage lab test configurations.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/lab/addlab"]',
                title: 'Radiology',
                description: 'Configure radiology categories, units, parameters, and imaging test settings.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/bloodbank/products"]',
                title: 'Blood Bank',
                description: 'Manage blood bank products, blood groups, donors, and blood inventory.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/symptoms"]',
                title: 'Symptoms',
                description: 'Configure symptom types and symptom categories for patient diagnosis.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/finding"]',
                title: 'Findings',
                description: 'Manage clinical findings and examination results templates.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/incomehead"], a[href*="admin/expensehead"]',
                title: 'Finance',
                description: 'Manage financial operations including income heads, expense heads, and accounting categories.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/leavetypes"]',
                title: 'Human Resource',
                description: 'Manage HR operations including employee designations, departments, leave types, and staff management.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/referral/commission"]',
                title: 'Referral System',
                description: 'Configure referral commissions and manage referral partner settings.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/onlineappointment"]',
                title: 'Appointment Management',
                description: 'Set up online appointment slots, doctor shifts, and appointment scheduling system.',
                position: 'right'
            },
            {
                selector: 'a[href*="admin/itemcategory"]',
                title: 'Inventory Management',
                description: 'Manage inventory items, categories, stock levels, and procurement processes.',
                position: 'right'
            }
        ];
        this.startTour();
    }

    createTourElements() {
        this.originalClickHandlers = new Map();

        this.overlay = document.createElement('div');
        this.overlay.className = 'tour-overlay';
        this.overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            z-index: 999999;
            display: none;
        `;

        this.popup = document.createElement('div');
        this.popup.className = 'tour-popup';
        this.popup.style.cssText = `
            position: fixed;
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            z-index: 1000001;
            max-width: 380px;
            min-width: 350px;
            display: none;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            border: 3px solid #007bff;
        `;
        this.highlight = document.createElement('div');
        this.highlight.className = 'tour-highlight';
        this.highlight.style.cssText = `
            position: absolute;
            border: 4px solid #ff6b35;
            border-radius: 8px;
            background: rgba(255, 107, 53, 0.15);
            z-index: 1000000;
            display: none;
            pointer-events: none;
            box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.3), 
                        0 0 20px rgba(255, 107, 53, 0.5),
                        inset 0 0 10px rgba(255, 107, 53, 0.1);
            animation: highlightPulse 2s infinite;
        `;

        this.addEnhancedStyles();
        document.body.appendChild(this.overlay);
        document.body.appendChild(this.highlight);
        document.body.appendChild(this.popup);
        this.createStartButton();
    }

    addEnhancedStyles() {
        const style = document.createElement('style');
        style.id = 'tour-guide-styles';
        style.textContent = `
            @keyframes highlightPulse {
                0% { 
                    transform: scale(1);
                    box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.3), 
                                0 0 20px rgba(255, 107, 53, 0.5),
                                inset 0 0 10px rgba(255, 107, 53, 0.1);
                }
                50% { 
                    transform: scale(1.02);
                    box-shadow: 0 0 0 6px rgba(255, 107, 53, 0.2), 
                                0 0 30px rgba(255, 107, 53, 0.7),
                                inset 0 0 15px rgba(255, 107, 53, 0.2);
                }
                100% { 
                    transform: scale(1);
                    box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.3), 
                                0 0 20px rgba(255, 107, 53, 0.5),
                                inset 0 0 10px rgba(255, 107, 53, 0.1);
                }
            }
            
            .tour-popup {
                background: linear-gradient(135deg, #ffffff, #f8f9fa) !important;
                backdrop-filter: blur(10px);
            }
            
            .tour-popup h3 {
                margin: 0 0 12px 0 !important;
                color: #007bff !important;
                font-size: 20px !important;
                font-weight: bold !important;
                border-bottom: 2px solid #e9ecef !important;
                padding-bottom: 8px !important;
            }
            
            .tour-popup p {
                margin: 0 0 20px 0 !important;
                color: #495057 !important;
                line-height: 1.6 !important;
                font-size: 15px !important;
            }
            
            .tour-buttons {
                display: flex !important;
                flex-direction: column !important;
                gap: 15px !important;
            }
            
            .tour-progress {
                text-align: center !important;
                font-size: 14px !important;
                color: #007bff !important;
                font-weight: bold !important;
                background: #e3f2fd !important;
                padding: 8px 15px !important;
                border-radius: 20px !important;
                margin-bottom: 10px !important;
            }
            
            .tour-btn-group {
                display: flex !important;
                gap: 10px !important;
                justify-content: center !important;
                flex-wrap: wrap !important;
            }
            
            .tour-btn {
                padding: 12px 20px !important;
                border: none !important;
                border-radius: 25px !important;
                cursor: pointer !important;
                font-size: 14px !important;
                font-weight: bold !important;
                transition: all 0.3s ease !important;
                min-width: 80px !important;
            }
            
            .tour-btn-primary {
                background: linear-gradient(135deg, #007bff, #0056b3) !important;
                color: white !important;
                box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3) !important;
            }
            
            .tour-btn-primary:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4) !important;
            }
            
            .tour-btn-secondary {
                background: linear-gradient(135deg, #6c757d, #545b62) !important;
                color: white !important;
                box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3) !important;
            }
            
            .tour-btn-secondary:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4) !important;
            }
            
            .tour-btn-skip {
                background: linear-gradient(135deg, #dc3545, #c82333) !important;
                color: white !important;
                box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3) !important;
            }
            
            .tour-btn-skip:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4) !important;
            }

            .tour-overlay,
            .tour-popup,
            .tour-highlight {
                pointer-events: auto !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .tour-highlight {
                border: 4px solid #ff6b35 !important;
                background: rgba(255, 107, 53, 0.15) !important;
            }

            .tour-disabled {
                pointer-events: none !important;
                cursor: not-allowed !important;
                opacity: 0.6 !important;
            }
        `;

        const existingStyles = document.getElementById('tour-guide-styles');
        if (existingStyles) {
            existingStyles.remove();
        }

        document.head.appendChild(style);
    }

    createStartButton() {
    //     document.querySelector('.start-tour-btn')?.remove();
    //     const path = window.location.pathname.split('/').slice(window.location.pathname.split('/').indexOf('admin')).join('/');
    //     const tourpath = ['admin/appointment/index'];
    //     if (!tourpath.includes(path)) return;
    //     const startBtn = Object.assign(document.createElement('button'), {
    //         className: 'start-tour-btn',
    //         title: 'About this page',
    //         style: `
    //         position: fixed; bottom: 20px; right: 20px; background: white; border: none; padding: 10px;
    //         border-radius: 50%; cursor: pointer; z-index: 1000; transition: 0.3s ease;
    //         width: 60px; height: 60px; box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    //         display: flex; align-items: center; justify-content: center; `
    //     });
    //     const iconImg = Object.assign(document.createElement('img'), {
    //         src: 'https://assets.onecompiler.app/42vjmaxtb/43mqy7gv6/tutorial.png',
    //         alt: 'Tutorial',
    //         style: 'width:30px;height:30px;'
    //     });
    //     startBtn.appendChild(iconImg);
    //     startBtn.onmouseenter = () => startBtn.style.transform = 'scale(1.1)';
    //     startBtn.onmouseleave = () => startBtn.style.transform = 'scale(1)';
    //     startBtn.onclick = () => {
    //         startBtn.style.display = 'none';
    //         path === 'admin/appointment/index' ? this.appointment() : errormsg("This page is not supported for tour guide");
    //     };
    //     document.body.appendChild(startBtn);
    //     document.body.insertAdjacentHTML('beforeend', `
    //     <a href="https://www.flaticon.com/free-icons/tutorial" title="tutorial icons"
    //        style="position:fixed;bottom:5px;right:90px;font-size:10px;color:#888;z-index:999;
    //        text-decoration:none;pointer-events:none;">
    //        Tutorial icons by Muhammad Atif - Flaticon
    //     </a>
    // `);
    }
    bindEvents() {
        window.addEventListener('resize', () => {
            if (this.isActive) {
                setTimeout(() => this.showStep(), 100);
            }
        });

        window.addEventListener('scroll', () => {
            if (this.isActive) {
                setTimeout(() => this.repositionElements(), 50);
            }
        });
    }

    disableMenuClicks() {
        const allLinks = document.querySelectorAll('a');
        allLinks.forEach(link => {
            if (link.href && (link.href.includes('admin') || link.href.includes('setup'))) {
                const originalClick = link.onclick;
                this.originalClickHandlers.set(link, originalClick);

                link.onclick = (e) => {
                    if (this.isActive) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                    if (originalClick) {
                        return originalClick.call(link, e);
                    }
                };

                link.addEventListener('click', (e) => {
                    if (this.isActive) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }, true);

                if (this.isActive) {
                    link.classList.add('tour-disabled');
                }
            }
        });
    }

    enableMenuClicks() {
        const allLinks = document.querySelectorAll('a');
        allLinks.forEach(link => {
            link.classList.remove('tour-disabled');
            if (this.originalClickHandlers.has(link)) {
                link.onclick = this.originalClickHandlers.get(link);
            }
        });
        this.originalClickHandlers.clear();
    }

    findElement(selector) {
        let element = null;

        element = document.querySelector(selector);
        if (element && this.isElementVisible(element)) {
            return element;
        }

        if (selector.includes('href*=')) {
            const hrefValue = selector.match(/href\*="([^"]+)"/)[1];
            const links = document.querySelectorAll('a');
            for (let link of links) {
                if (link.href && link.href.includes(hrefValue) && this.isElementVisible(link)) {
                    return link;
                }
            }
        }

        const sidebarSelectors = [
            '.sidebar a',
            '.main-sidebar a',
            '.sidebar-menu a',
            '.menu a',
            'nav a',
            '.navigation a'
        ];

        for (let sidebarSelector of sidebarSelectors) {
            const sidebarLinks = document.querySelectorAll(sidebarSelector);
            for (let link of sidebarLinks) {
                if (link.href && selector.includes('href*=')) {
                    const hrefValue = selector.match(/href\*="([^"]+)"/)[1];
                    if (link.href.includes(hrefValue) && this.isElementVisible(link)) {
                        return link;
                    }
                }
            }
        }

        return null;
    }

    isElementVisible(element) {
        if (!element) return false;

        const rect = element.getBoundingClientRect();
        const style = window.getComputedStyle(element);

        return (
            rect.width > 0 &&
            rect.height > 0 &&
            style.display !== 'none' &&
            style.visibility !== 'hidden' &&
            style.opacity !== '0'
        );
    }

    startTour() {
        this.isActive = true;
        this.currentStep = 0;
        this.disableMenuClicks();
        this.showStep();
    }

    showStep() {
        if (this.currentStep >= this.tourSteps.length) {
            this.endTour();
            return;
        }
        const step = this.tourSteps[this.currentStep];
        const element = this.findElement(step.selector);
        if (!element) {
            this.expandMenus();
            setTimeout(() => {
                const retryElement = this.findElement(step.selector);
                if (retryElement) {
                    this.positionTourElements(retryElement, step);
                } else {
                    this.nextStep();
                }
            }, 500);
            return;
        }

        this.expandParentMenus(element);

        setTimeout(() => {
            this.positionTourElements(element, step);
        }, 200);
    }

    expandMenus() {
        const menuSelectors = [
            '.treeview > a',
            '.has-dropdown > a',
            '.menu-toggle',
            '[data-toggle="collapse"]',
            '.collapsed'
        ];

        menuSelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                if (element.click && !element.closest('.active, .open, .show')) {
                    element.click();
                }
            });
        });
    }

    expandParentMenus(element) {
        let parent = element.closest('.treeview, .has-dropdown, .menu-item');
        while (parent) {
            if (!parent.classList.contains('active') && !parent.classList.contains('open')) {
                const toggle = parent.querySelector('a');
                if (toggle && toggle !== element) {
                    toggle.click();
                }
            }
            parent = parent.parentElement?.closest('.treeview, .has-dropdown, .menu-item');
        }
    }

    positionTourElements(element, step) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
            inline: 'nearest'
        });

        setTimeout(() => {
            const rect = element.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

            this.overlay.style.display = 'block';

            this.highlight.style.display = 'block';
            this.highlight.style.position = 'absolute';
            this.highlight.style.left = (rect.left + scrollLeft - 8) + 'px';
            this.highlight.style.top = (rect.top + scrollTop - 8) + 'px';
            this.highlight.style.width = (rect.width + 16) + 'px';
            this.highlight.style.height = (rect.height + 16) + 'px';

            this.positionPopup(rect, step);
            this.updatePopupContent(step);

        }, 300);
    }

    repositionElements() {
        if (!this.isActive || this.currentStep >= this.tourSteps.length) return;

        const step = this.tourSteps[this.currentStep];
        const element = this.findElement(step.selector);

        if (element) {
            this.positionTourElements(element, step);
        }
    }

    positionPopup(rect, step) {
        this.popup.style.display = 'block';

        const popupWidth = 380;
        const popupHeight = 300;
        const screenWidth = window.innerWidth;
        const screenHeight = window.innerHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        let popupLeft, popupTop;

        if (rect.right + popupWidth + 30 < screenWidth) {
            popupLeft = rect.right + 20;
            popupTop = Math.max(scrollTop + 20, rect.top + scrollTop - 50);
        } else if (rect.left - popupWidth - 30 > 0) {
            popupLeft = rect.left - popupWidth - 20;
            popupTop = Math.max(scrollTop + 20, rect.top + scrollTop - 50);
        } else {
            popupLeft = Math.max(20, Math.min(rect.left, screenWidth - popupWidth - 20));
            if (rect.bottom + popupHeight + 30 < screenHeight + scrollTop) {
                popupTop = rect.bottom + scrollTop + 20;
            } else {
                popupTop = Math.max(scrollTop + 20, rect.top + scrollTop - popupHeight - 20);
            }
        }

        popupLeft = Math.max(10, Math.min(popupLeft, screenWidth - popupWidth - 10));
        popupTop = Math.max(scrollTop + 10, Math.min(popupTop, scrollTop + screenHeight - popupHeight - 10));

        this.popup.style.left = popupLeft + 'px';
        this.popup.style.top = popupTop + 'px';
    }

    updatePopupContent(step) {
        this.popup.innerHTML = `
            <h3>${step.title}</h3>
            <p>${step.description}</p>
            <div class="tour-buttons">
                <div class="tour-progress">
                    Step ${this.currentStep + 1} of ${this.tourSteps.length}
                </div>
                <div class="tour-btn-group">
                    ${this.currentStep > 0 ? '<button class="tour-btn tour-btn-secondary" onclick="window.menuTour.prevStep()">← Previous</button>' : ''}
                    <button class="tour-btn tour-btn-primary" onclick="window.menuTour.nextStep()">
                        ${this.currentStep === this.tourSteps.length - 1 ? 'Finish ✓' : 'Next →'}
                    </button>
                    <button class="tour-btn tour-btn-skip" onclick="window.menuTour.endTour()">Skip Tour</button>
                </div>
            </div>
        `;
    }

    nextStep() {
        this.currentStep++;
        this.showStep();
    }

    prevStep() {
        if (this.currentStep > 0) {
            this.currentStep--;
            this.showStep();
        }
    }


    endTour() {
        this.isActive = false;
        this.enableMenuClicks();
        this.overlay.style.display = 'none';
        this.popup.style.display = 'none';
        this.highlight.style.display = 'none';

        const startBtn = document.querySelector('.start-tour-btn');
        if (startBtn) {
            startBtn.style.display = 'block';
        }

        this.showSuccessMessage();
    }

    showSuccessMessage() {
        const successMsg = document.createElement('div');
        successMsg.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 25px 35px;
            border-radius: 15px;
            font-size: 18px;
            font-weight: bold;
            z-index: 1000001;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
            text-align: center;
            min-width: 300px;
        `;
        successMsg.innerHTML = '🎉 Tour Completed!<br><small style="font-size: 14px; font-weight: normal;">You now know how to navigate the system</small>';
        document.body.appendChild(successMsg);

        setTimeout(() => {
            successMsg.remove();
        }, 3000);
    }

    restartTour() {
        this.currentStep = 0;
        this.startTour();
    }

    debugCurrentStep() {
        const step = this.tourSteps[this.currentStep];
        console.log('Current step:', this.currentStep);
        console.log('Step config:', step);
        console.log('Element found:', this.findElement(step.selector));
        console.log('All matching elements:', document.querySelectorAll(step.selector));
    }
}

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        window.menuTour = new MenuTourGuide();
    }, 1000);
});

function initializeTour() {
    if (typeof window.menuTour === 'undefined') {
        window.menuTour = new MenuTourGuide();
    }
}

function autoStartTour() {
    setTimeout(() => {
        if (window.menuTour) {
            window.menuTour.startTour();
        }
    }, 2000);
}

if (typeof module !== 'undefined' && module.exports) {
    module.exports = MenuTourGuide;
}