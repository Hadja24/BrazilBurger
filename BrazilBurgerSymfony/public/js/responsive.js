// responsive.js
class ResponsiveHelper {
    constructor() {
        this.breakpoints = {
            xs: 0,
            sm: 576,
            md: 768,
            lg: 992,
            xl: 1200,
            xxl: 1400
        };
        
        this.currentBreakpoint = this.getCurrentBreakpoint();
        this.init();
    }
    
    init() {
        window.addEventListener('resize', () => {
            this.onResize();
        });
        
        // Ajouter des classes CSS au body pour les breakpoints
        this.updateBodyClasses();
    }
    
    getCurrentBreakpoint() {
        const width = window.innerWidth;
        
        if (width >= this.breakpoints.xxl) return 'xxl';
        if (width >= this.breakpoints.xl) return 'xl';
        if (width >= this.breakpoints.lg) return 'lg';
        if (width >= this.breakpoints.md) return 'md';
        if (width >= this.breakpoints.sm) return 'sm';
        return 'xs';
    }
    
    updateBodyClasses() {
        const body = document.body;
        
        // Retirer les anciennes classes
        Object.keys(this.breakpoints).forEach(bp => {
            body.classList.remove(`breakpoint-${bp}`);
        });
        
        // Ajouter la classe actuelle
        body.classList.add(`breakpoint-${this.currentBreakpoint}`);
        
        // Ajouter des classes utilitaires
        body.classList.toggle('is-mobile', this.currentBreakpoint === 'xs' || this.currentBreakpoint === 'sm');
        body.classList.toggle('is-tablet', this.currentBreakpoint === 'md');
        body.classList.toggle('is-desktop', this.currentBreakpoint === 'lg' || this.currentBreakpoint === 'xl' || this.currentBreakpoint === 'xxl');
    }
    
    onResize() {
        const newBreakpoint = this.getCurrentBreakpoint();
        
        if (newBreakpoint !== this.currentBreakpoint) {
            this.currentBreakpoint = newBreakpoint;
            this.updateBodyClasses();
            
            // Émettre un événement personnalisé
            window.dispatchEvent(new CustomEvent('breakpointChange', {
                detail: { breakpoint: newBreakpoint }
            }));
        }
    }
    
    isMobile() {
        return this.currentBreakpoint === 'xs' || this.currentBreakpoint === 'sm';
    }
    
    isTablet() {
        return this.currentBreakpoint === 'md';
    }
    
    isDesktop() {
        return this.currentBreakpoint === 'lg' || this.currentBreakpoint === 'xl' || this.currentBreakpoint === 'xxl';
    }
}

// Initialiser
document.addEventListener('DOMContentLoaded', () => {
    window.responsiveHelper = new ResponsiveHelper();
});