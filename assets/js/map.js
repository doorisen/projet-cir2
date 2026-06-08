'use strict'


let map;
let markerLayer;

document.addEventListener('DOMContentLoaded', async () => {
    map = L.map('map').setView([48.0, -2.5], 8);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    markerLayer = L.layerGroup().addTo(map);

    await loadFilters({
        annee: 'filter-year',
        departement: 'filter-department'
    });

    await loadStations();

    document.getElementById('filter-form').addEventListener('submit', async event => {
        event.preventDefault();
        await loadStations();
    });

});


async function loadStations() {
    const annee = document.getElementById('filter-year').value;
    const departement = document.getElementById('filter-department').value;

    const params = new URLSearchParams();

    if (annee !== '' && annee !== 'Toutes') {
        params.append('filtre_annee', annee);
    }
    if (departement !== '' && departement !== 'Tous') {
        params.append('filtre_departement', departement);
    }

    try {
        const response = await fetch(
            `../api/stations.php?${params.toString()}`
        );
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const stations = await response.json();
        markerLayer.clearLayers();

        stations.forEach(station => {
            const marker = L.marker([
                parseFloat(station.latitude),
                parseFloat(station.longitude)
            ]);

            marker.bindPopup(`
                <div class="map-popup text-light">
                    <div class="fw-bold">${station.nom_station}</div>
                    <div class="small text-secondary">${station.adresse_station}</div>
                    <div class="small text-secondary">${station.nom_commune}</div>
                    <div>${station.nb_points} point(s) de recharge</div>
                    <a href="search.php?station=${station.id_station}" class="btn btn-sm btn-outline-light"> Détails </a>
                </div>
            `, {
                className: 'custom-popup',
                closeButton: false
            });
            markerLayer.addLayer(marker);
        });

    } catch(error) {
        console.error('Erreur lors du chargement des stations :', error);
    }
}











