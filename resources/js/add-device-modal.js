document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('addDroneModal');
    const closeBtn = modal.querySelector('.close');
    const addBtn = document.querySelector('.add-button');

    // Atidaryti modalą paspaudus add button
    addBtn.addEventListener('click', (e) => {
        // Patikriname, kad nepaspausta ant form ar X
        if (!e.target.closest('form') && !e.target.classList.contains('close') && !e.target.closest('.modal-content')) {
            modal.style.display = 'block';
        }
    });

    // Uždaryti paspaudus X
    closeBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // svarbu, kad click nepereitų į addBtn
        modal.style.display = 'none';
    });

    // Uždaryti paspaudus už modal content
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});