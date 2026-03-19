class ModernDropdown {
    constructor(element, options = {}) {
        this.wrapper = element;
        this.trigger = element.querySelector('.dropdown-trigger');
        this.menu = element.querySelector('.dropdown-menu');
        this.options = {
            openOnHover: false,
            closeOnClickOutside: true,
            animation: true,
            ...options
        };

        this.init();
    }

    init() {
        if (this.options.openOnHover) {
            this.wrapper.addEventListener('mouseenter', () => this.open());
            this.wrapper.addEventListener('mouseleave', () => this.close());
        }
        this.trigger.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggle();
        });
        if (this.options.closeOnClickOutside) {
            document.addEventListener('click', (e) => {
                if (!this.wrapper.contains(e.target)) {
                    this.close();
                }
            });
        }

        this.handleSubmenuPostition();

        this.initKeyboardNav();
    }

    open() {
        this.wrapper.classList.add('active');
        this.menu.setAttribute('aria-expanded', 'true');
        
    }

    close() {
        this.wrapper.classList.remove('active');
        this.menu.setAttribute('aria-expanded', 'false');
    }

    toggle() {
        this.wrapper.classList.contains('active') ? this.close() : this.open();
    }

    handleSubmenuPostition() {
        const submenus = this.wrapper.querySelectorAll('.submenu');
        submenus.forEach(submenu => {
            const rect = submenu.getBoundingClientRect();
            if (rect.right > window.innerWidth) {
                submenu.style.left = 'auto';
                submenu.style.right = '100%';
            }

        });
    }
    initKeyboardNav() {
        const items = this.menu.querySelectorAll('.dropdown-item');
        items.forEach((item, index) => {
            item.setAttribute('tabindex', 0);
            item.addEventListener('keydown', (e) => {
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        items[index + 1]?.focus();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        items[index - 1]?.focus();
                        break;
                    case 'Enter':
                    case ' ':
                        e.preventDefault();
                        item.click();
                        break;
                }
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('.dropdown-wrapper');
    dropdowns.forEach(dropdown => {
        new ModernDropdown(dropdown);
    });

    window.addEventListener('resize', () => {
        dropdowns.forEach(dropdown => {
            const isDesktop = window.innerWidth > 768;
            dropdown.classList.toggle('hover-mode', isDesktop);
        });
    });
});

document.addEventListener('click', function(e) {
    console.log('Clicked element:', e.target);
}, true);