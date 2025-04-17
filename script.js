document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('header');
    let lastScrollPosition = 0;

    window.addEventListener('scroll', () => {
        const currentScrollPosition = window.scrollY;
        
        if (currentScrollPosition > lastScrollPosition && currentScrollPosition > 0) {
            // Прокручиваемся вниз
            header.classList.add('hidden'); // Скрыть шапку
        } else {
            // Прокручиваемся вверх
            header.classList.remove('hidden'); // Показать шапку
        }

        lastScrollPosition = currentScrollPosition;
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Обработка кнопок +/-
    document.querySelectorAll('.quantity_btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity_input');
            let value = parseInt(input.value);
            
            if(this.classList.contains('plus')) {
                value++;
            } else {
                value = value > 1 ? value - 1 : 1;
            }
            
            input.value = value;
        });
    });
});