document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('popup');
    const popupMessage = document.getElementById('popupMessage');
    const popupFormContainer = document.getElementById('popupFormContainer');
    const closeBtn = document.getElementById('closeBtn');

    const columnHeaders = {
        'devices': { 'name': 'Nazwa' },
        'manufacturers': { 'name': 'Nazwa' },
        'suppliers': { 'name': 'Nazwa', 'address': 'Adres', 'phone': 'Telefon', 'email': 'Email' },
        'locations': { 'name': 'Nazwa' },
        'statuses': { 'name': 'Nazwa' },
        'users': { 'login': 'Login', 'user_group': 'Grupa użytkowników', 'email': 'Email', 'location': 'Lokalizacja' },
        'events': { 'name': 'Nazwa' }
    };

    async function fetchLocations() {
        try {
            const response = await fetch('../includes/getlocationsh.inc.php');
            const data = await response.json();
            if (data.error) {
                showPopup('locations', {}, [data.message]);
                return [];
            }
            return data.locations;
        } catch (error) {
            console.error('Fetch error:', error);
            return [];
        }
    }

    function createLocationDropdown(selectedValue = '') {
        return fetchLocations().then(locations => {
            let optionsHtml = '<option value="">Wybierz lokalizację</option>';
            locations.forEach(location => {
                optionsHtml += `<option value="${location.name}" ${selectedValue === location.name ? 'selected' : ''}>${location.name}</option>`;
            });
            return optionsHtml;
        });
    }

    function showPopup(table, id = null, formData, errors) {

        const successMessage = document.querySelector('.message.success');
        if (successMessage) {
            successMessage.classList.remove('show');
        }

        formData = formData || {};
        errors = errors || [];

        const headers = columnHeaders[table] || {};
        let formHtml = `<form id="editForm" method="post" class="popup-form" autocomplete="off">`;

        // Generowanie pól formularza
        for (const [key, value] of Object.entries(headers)) {
            const formValue = formData[key] || ''; // Użyj wartości z formData, jeśli istnieje

            if (table === 'users' && key === 'location') {
                createLocationDropdown(formValue).then(locationDropdownHtml => {
                    formHtml += `
                        <label for="${key}">${value}:</label>
                        <select name="${key}" id="${key}">
                            ${locationDropdownHtml}
                        </select>
                        <br>
                    `;
                    renderFormHtml();
                });
            } else if (table === 'users' && key === 'user_group') {
                formHtml += `
                    <label for="${key}">${value}:</label>
                    <select name="${key}" id="${key}">
                        <option value="" ${formValue === 'none' || formValue === '' ? 'selected' : ''}>Wybierz grupę</option>
                        <option value="admin" ${formValue === 'admin' ? 'selected' : ''}>admin</option>
                        <option value="user" ${formValue === 'user' ? 'selected' : ''}>user</option>
                    </select>
                    <br>
                `;
            }
            
            else {
            formHtml += `
                    <label for="${key}">${value}:</label>
                    <input type="text" id="${key}" name="${key}" value="${formValue}">
                    <br>
                `;
            }
        }

        function renderFormHtml() {
            formHtml += `<input type="hidden" name="table" value="${table}">`;
            formHtml += `<input type="hidden" name="id" value="${id}">`;
            formHtml += '<input type="submit" value="Zapisz zmiany">';
            formHtml += '</form>';

            let errorHtml = '';
            if (errors.length > 0) {
                errorHtml = '<div class="message error show">';
                errors.forEach(error => {
                    errorHtml += `<p>${error}</p>`;
                });
                errorHtml += '</div>';
            }

            popupMessage.textContent = `Edytuj element w bazie danych`;
            popupFormContainer.innerHTML = formHtml + errorHtml;
            popup.style.display = 'flex';

            // Obsługa wysłania formularza
            document.getElementById('editForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch('../includes/editdicth.inc.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = `?table=${data.table}`;
                    } else {
                        // Przekazanie danych i błędów do showPopup
                        showPopup(table, id, Object.fromEntries(formData), data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        if (!(table === 'users' && 'location' in headers)) {
            renderFormHtml();
        }
    }

    closeBtn.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    popup.addEventListener('click', function(e) {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });

    document.querySelectorAll('.editPopupBtn').forEach(button => {
        button.addEventListener('click', function() {
            const table = this.getAttribute('data-table');
            const id = this.getAttribute('data-id');

            fetch(`../includes/getdicth.inc.php?table=${table}&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showPopup(table, id, data.formData);
                    } else {
                        alert('Wystąpił błąd podczas pobierania danych.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
});
