document.addEventListener('DOMContentLoaded', function() {

    const popup = document.getElementById('popup');
    const popupMessage = document.getElementById('popupMessage');
    const yesBtn = document.getElementById('yesBtn');
    const noBtn = document.getElementById('noBtn');

    let itemIdToDelete = null;
    let deleteScript = null;

    function showPopup(message, id, script) {
        popupMessage.textContent = message;
        itemIdToDelete = id;
        deleteScript = script;
        popup.style.display = 'flex';
    }

    function closePopup() {
        popup.style.display = 'none';
    }

    function handleClick(event) {
        const button = event.currentTarget;
        const nrInv = button.getAttribute('data-nrInv');
        const id = button.getAttribute('data-id');
        const message = 'Czy na pewno chcesz usunąć ten element?';

        // Sprawdzanie, który atrybut jest ustawiony
        if (nrInv !== null && nrInv !== '') {
            showPopup(message, nrInv, 'delequiph.inc.php');
        } else if (id !== null && id !== '') {
            showPopup(message, id, 'deleventh.inc.php');
        }
    }

    document.querySelectorAll('.showPopupBtn').forEach(button => {
        button.addEventListener('click', handleClick);
    });

    noBtn.addEventListener('click', closePopup);

    popup.addEventListener('click', function(e) {
        if (e.target === popup) {
            closePopup();
        }
    });

    yesBtn.addEventListener('click', function() {
        if (itemIdToDelete !== null && deleteScript !== null) {
            // Używamy idType do określenia odpowiedniego parametru
            const queryParam = deleteScript === 'delequiph.inc.php' ? `nrInv=${itemIdToDelete}` : `id=${itemIdToDelete}`;
            window.location.href = `../includes/${deleteScript}?${queryParam}`;
        }
    });

});
