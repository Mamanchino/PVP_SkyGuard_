import './bootstrap';

// ── Signup page ──
document.addEventListener('DOMContentLoaded', () => {
    function togglePassword(inputId, eyeIconEl) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            eyeIconEl.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            eyeIconEl.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    document.querySelectorAll('.input-icon.right').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const input = this.closest('.form-group').querySelector('input[type="password"], input[type="text"]');
            togglePassword(input.id, this);
        });
    });

    const checks = {
        length:    p => p.length >= 8,
        uppercase: p => /[A-Z]/.test(p),
        lowercase: p => /[a-z]/.test(p),
        digit:     p => /\d/.test(p),
        special:   p => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(p)
    };

    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const p = this.value;
            Object.keys(checks).forEach(id => updateCriteria(id, checks[id](p), false));
        });
    }

    const signupForm = document.querySelector('form');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const p = document.getElementById('password').value;
            let allValid = true;
            Object.keys(checks).forEach(id => {
                const valid = checks[id](p);
                if (!valid) allValid = false;
                updateCriteria(id, valid, !valid);
            });
            if (allValid) this.submit();
        });
    }

    function updateCriteria(id, valid, doBlink) {
        const li = document.getElementById(id);
        if (!li) return;
        li.classList.remove('blink');
        void li.offsetWidth;
        li.classList.toggle('valid', valid);
        li.classList.toggle('invalid', !valid);
        const icon = li.querySelector('i');
        icon.className = valid ? 'fa fa-check' : 'fa fa-times';
        if (doBlink) li.classList.add('blink');
    }

    window.closeModal = function() {
        const modal = document.getElementById('errorModal');
        if (modal) modal.style.display = 'none';
    }
});