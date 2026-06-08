'use strict';


async function populateDatabase() {
    try {
        const response = await fetch('../config/populate.php');
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();
        if (!data.populated) {
            throw new Error(
                data.error ?? 'Erreur inconnue'
            );
        }
        window.location.href = RETURN_PAGE;

    } catch (error) {
        console.error(error);
        document.getElementById('status').textContent = 'Erreur lors du chargement de la base de données';
    }
}


populateDatabase();


