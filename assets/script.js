// Évite les redéclarations en utilisant une vérification
if (!window.portfolioInitialized) {
    window.portfolioInitialized = true;

    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    }

    const animateSkillsOnScroll = () => {
        // ✅ Sélection à l'intérieur de la fonction
        const skillBars = document.querySelectorAll('.skill-progress');

        skillBars.forEach(bar => {
            const barPosition = bar.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;

            if (barPosition < screenPosition) {
                const width = bar.getAttribute('data-width');
                bar.style.width = `${width}%`;
                bar.classList.add('animate-progress');
            }
        });
    };

    const checkFadeIn = () => {
        // ✅ Sélection à l'intérieur de la fonction
        const fadeInElements = document.querySelectorAll('.fade-in');

        fadeInElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('visible');
            }
        });
    };

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    const initAnimations = () => {
        animateSkillsOnScroll();
        checkFadeIn();
    };

    window.addEventListener('load', () => {
        initAnimations();
        setTimeout(initAnimations, 100);
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAnimations);
    } else {
        initAnimations();
    }

    window.addEventListener('scroll', () => {
        animateSkillsOnScroll();
        checkFadeIn();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const snackbars = document.querySelectorAll('.animate-slide-up');
        snackbars.forEach(snackbar => {
            setTimeout(() => {
                snackbar.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                snackbar.style.opacity = '0';
                snackbar.style.transform = 'translateY(20px)';
                setTimeout(() => snackbar.remove(), 300);
            }, 5000);
        });
    });
}
