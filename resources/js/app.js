document.querySelectorAll('button[data-flashcard-id]').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-flashcard-id');
        const element = document.getElementById(id);
        if (!element) return;

        const isActive = element.classList.toggle('active');

        if (isActive) {
            button.textContent = button.getAttribute('data-text-open');
        } else {
            button.textContent = button.getAttribute('data-text-close');
        }
    });
});