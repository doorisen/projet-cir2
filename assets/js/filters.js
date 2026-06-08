'use strict';



async function loadFilters(config) {

    const response = await fetch('api/filters.php');
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }
    const data = await response.json();

    if (config.amenageur) {
        const select = document.getElementById(config.amenageur);
        data.amenageurs.forEach(item => {
            select.appendChild(
                new Option(item, item)
            );
        });
    }

    if (config.operateur) {
        const select = document.getElementById(config.operateur);
        data.operateurs.forEach(item => {
            select.appendChild(
                new Option(item, item)
            );
        });
    }

    if (config.departement) {
        const select = document.getElementById(config.departement);
        data.departements.forEach(item => {
            select.appendChild(
                new Option(item, item)
            );
        });
    }

    if (config.annee) {
        const select = document.getElementById(config.annee);
        data.annees.forEach(item => {
            select.appendChild(
                new Option(item, item)
            );
        });
    }

    if (config.prise) {
        const select = document.getElementById(config.prise);
        data.prises.forEach(item => {
            select.appendChild(
                new Option(item.label, item.value)
            );
        });
    }

    if (config.station) {
        const select = document.getElementById(config.station);
        data.stations.forEach(station => {
            select.appendChild(
                new Option(station.nom, station.id)
            );
        });
    }


}


