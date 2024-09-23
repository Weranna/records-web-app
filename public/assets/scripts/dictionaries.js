document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll('.table-form');

    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            forms.forEach(f => {
                const button = f.querySelector('.table-button');
                button.classList.remove('active');
            });

            const button = this.querySelector('.table-button');
            button.classList.add('active');

            this.submit();
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var accountButton = document.getElementById('accountButton');
    var accountPopup = document.getElementById('accountPopup');
    
    accountButton.addEventListener('click', function() {
        console.log('Popup before toggle:', accountPopup.classList);
        accountPopup.classList.toggle('hidden');
        console.log('Popup after toggle:', accountPopup.classList);
    });

    // Zamknij okienko, gdy użytkownik kliknie poza nim
    document.addEventListener('click', function(event) {
        if (!accountPopup.contains(event.target) && !accountButton.contains(event.target)) {
            accountPopup.classList.add('hidden');
        }
    });
});