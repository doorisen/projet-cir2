'use strict';

document.addEventListener('DOMContentLoaded', loadStats);


async function loadStats() {
    try {
        const response = await fetch('api/stats.php');
        const data = await response.json();
        if (!data.success) {
            throw new Error(data.error);
        }

        displayCards(data);
        displayChart(data.years);

    } catch (error) {
        console.error('Erreur chargement stats :', error);
    }
}


function displayCards(data) {
    setTextAtId('nb-pdc',               Number(data.stats.nb_pdc).toLocaleString('fr-FR'));
    setTextAtId('nb-amenageurs',        Number(data.stats.nb_amenageurs).toLocaleString('fr-FR'));
    setTextAtId('nb-departements',      Number(data.stats.nb_departements).toLocaleString('fr-FR'));
    setTextAtId('departements-list',    data.departements.join(' • '));
}


function displayChart(years) {
    const chart = document.getElementById('year-chart');

    if (years.length === 0) {
        return;
    }

    const max = Math.max(
        ...years.map(y => Number(y.nb_pdc))
    );

    years.forEach(year => {
        const wrapper = document.createElement('div');
        wrapper.className = 'chart-wrapper';

        const bar = document.createElement('div');
        const height = (Number(year.nb_pdc) / max) * 100;
        bar.className = 'chart-bar rounded-top-1';
        bar.style.height = `${height}%`;
        bar.title = `${year.nb_pdc} en ${year.annee}`;

        const label = document.createElement('div');
        label.className = 'chart-label';
        label.textContent = year.annee;

        wrapper.appendChild(bar);
        wrapper.appendChild(label);
        chart.appendChild(wrapper);
    });
}


