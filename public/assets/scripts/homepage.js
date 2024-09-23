document.addEventListener('DOMContentLoaded', function() {
    var resetBtn = document.getElementById('resetBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
    }
});
document.querySelectorAll('.showEventsBtn').forEach(button => {
    button.addEventListener('click', function() {
        const nrInv = this.getAttribute('data-nrInv');
        const eventsRow = document.getElementById('events-row-' + nrInv);
        if (eventsRow.classList.contains('hidden')) {
            eventsRow.classList.remove('hidden');
        } else {
            eventsRow.classList.add('hidden');
        }
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
document.querySelectorAll('.showFilesBtn').forEach(button => {
button.addEventListener('click', function() {
const nrInv = this.getAttribute('data-nrInv');
const photosRow = document.getElementById(`photos-row-${nrInv}`);

// Ukrywanie innych sekcji zdjęć
document.querySelectorAll('.photos-row').forEach(row => {
    if (row !== photosRow) row.classList.add('hidden');
});

// Przełączanie widoczności sekcji zdjęć
photosRow.classList.toggle('hidden');
});
});
document.addEventListener('DOMContentLoaded', function() {
// Pobierz wszystkie przyciski podglądu
const buttons = document.querySelectorAll('#eventPhotoButton');

buttons.forEach(button => {
    button.addEventListener('click', function() {
        // Pobranie eventId z atrybutu data-id
        const eventId = this.getAttribute('data-id');
        const photoRow = document.getElementById('photos-row-' + eventId);
        
        // Przełączanie widoczności
        if (photoRow.classList.contains('hidden')) {
            photoRow.classList.remove('hidden');
        } else {
            photoRow.classList.add('hidden');
        }
    });
});
});