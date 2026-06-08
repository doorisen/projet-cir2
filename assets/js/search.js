'use strict';


document.addEventListener('DOMContentLoaded', async () => {

    await loadFilters({
        amenageur: 'amenageur',
        prise: 'prise',
        departement: 'departement',
        station: 'station'
    });

    const params = new URLSearchParams(window.location.search);
    const station = params.get('station');
    if (station) {
        document.getElementById('station').value = station;
    }

    loadResults();

    const form = document.getElementById('search-form');

    form.addEventListener('submit', event => {
        event.preventDefault();
        loadResults();
    });

    form.addEventListener('reset', () => {
        setTimeout(loadResults, 0);
    });

});



async function loadResults() {
    const amenageur     = document.getElementById('amenageur').value;
    const prise         = document.getElementById('prise').value;
    const departement   = document.getElementById('departement').value;
    const station       = document.getElementById('station').value;
    const params        = new URLSearchParams({amenageur, prise, departement, station});

    const response = await fetch(`api/pdcs.php?${params}`);
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }
    const data = await response.json();

    const tbody = document.getElementById('results-body');
    tbody.innerHTML = '';
    
    data.data.forEach(pdc => {
        const tr = document.createElement('tr');

        const tdNom = createTdCell(pdc.nom_station, ['ps-4']);
        const tdEnseigne = createTdCell(pdc.enseigne);
        const tdPrises = createTdCell();
        const tdPuissance = createTdCell(pdc.puissance_nominale, [], parsePuissance);
        const tdCommune = createTdCell(getCommuneFromPdc(pdc));

        const link = createLink(`details.php?id=${pdc.id}`, 'Détails →', ['text-decoration-none', 'text-light']);
        const tdDetails = createTdCell('', ['pe-4', 'text-end']);
        tdDetails.appendChild(link);

        tr.append(tdNom, tdEnseigne, tdPrises, tdPuissance, tdCommune, tdDetails);
        tbody.appendChild(tr);

        const prises = getPrisesFromPdc(pdc);
        insertElementsInContainer(tdPrises, prises);
    });
}


