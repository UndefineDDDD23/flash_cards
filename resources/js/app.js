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

document.querySelectorAll('.flip-btn').forEach(button => {
  button.addEventListener('click', (e) => {
    e.stopPropagation();
    const card = button.closest('.flash-card');
    card.classList.toggle('flipped');
  });
});