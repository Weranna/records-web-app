document.addEventListener('DOMContentLoaded', function() {
    const popupDel = document.getElementById('popupDel');
    const yesBtn = document.getElementById('yesBtn');
    const noBtn = document.getElementById('noBtn');
    const popupMessageDel = document.getElementById('popupMessageDel');
    const closeBtnDel = document.getElementById('closeBtnDel');

    const popup = document.getElementById('popup');
    const popupMessage = document.getElementById('popupMessage');
    const closeBtn = document.getElementById('closeBtn');

    let itemIdToDelete = null;
    let tableToDeleteFrom = '';

    function showDeletePopup(message, id, table) {
        // Ukryj poprzedni pop-up, jeśli jest otwarty
        popup.style.display = 'none';

        popupMessageDel.textContent = message;
        itemIdToDelete = id;
        tableToDeleteFrom = table;
        popupDel.style.display = 'flex';
    }

    function closeDeletePopup() {
        popupDel.style.display = 'none';
    }

    function showErrorPopup(message) {
        // Ukryj poprzedni pop-up, jeśli jest otwarty
        popupDel.style.display = 'none';

        popupMessage.textContent = message;
        popup.style.display = 'flex';
    }

    function closeErrorPopup() {
        popup.style.display = 'none';
    }

    document.querySelectorAll('.delPopupBtn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const table = this.getAttribute('data-table');
            showDeletePopup('Czy na pewno chcesz usunąć ten element?', id, table);
        });
    });

    noBtn.addEventListener('click', closeDeletePopup);
    closeBtnDel.addEventListener('click', closeDeletePopup);

    popupDel.addEventListener('click', function(e) {
        if (e.target === popupDel) {
            closeDeletePopup();
        }
    });

    closeBtn.addEventListener('click', closeErrorPopup);

    popup.addEventListener('click', function(e) {
        if (e.target === popup) {
            closeErrorPopup();
        }
    });

    yesBtn.addEventListener('click', function() {
        if (itemIdToDelete !== null) {
            fetch(`../includes/deldicth.inc.php?table=${tableToDeleteFrom}&id=${itemIdToDelete}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = `?table=${data.table}`;
                    } else {
                        showErrorPopup(data.message || 'Wystąpił błąd. Spróbuj ponownie.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorPopup('Wystąpił błąd. Spróbuj ponownie.');
                });
        }
    });
});
