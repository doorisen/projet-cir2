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
        displayDepChart(data.pdc_by_dep);
        displayPlugChart(data.pdc_by_plug);
        displayYearDepChart(data.pdc_by_year_dep);

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
    if (!chart || !years || years.length === 0) return;
    chart.innerHTML = '';

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


function displayDepChart(pdcByDep) {
    const chart = document.getElementById('dep-chart');
    if (!chart || !pdcByDep || pdcByDep.length === 0) return;
    chart.innerHTML = '';

    const max = Math.max(...pdcByDep.map(d => Number(d.nb_pdc)));

    pdcByDep.forEach(item => {
        const wrapper = document.createElement('div');
        wrapper.className = 'chart-wrapper';

        const bar = document.createElement('div');
        const height = (Number(item.nb_pdc) / max) * 100;

        bar.className = `chart-bar rounded-top-1 dep-${item.dep_code}`;
        bar.style.height = `${height}%`;
        bar.title = `${Number(item.nb_pdc).toLocaleString('fr-FR')} points`;

        const label = document.createElement('div');
        label.className = 'chart-label';
        label.textContent = `Dép. ${item.dep_code}`;

        wrapper.appendChild(bar);
        wrapper.appendChild(label);
        chart.appendChild(wrapper);
    });
}


function displayPlugChart(pdcByPlug) {
    const chart = document.getElementById('plug-chart');
    if (!chart || !pdcByPlug || pdcByPlug.length === 0) return;
    chart.innerHTML = '';

    const max = Math.max(...pdcByPlug.map(p => Number(p.nb_pdc)));

    pdcByPlug.forEach(item => {
        const wrapper = document.createElement('div');
        wrapper.className = 'chart-wrapper';

        const bar = document.createElement('div');
        const height = (Number(item.nb_pdc) / max) * 100;
        const plugName = item.type_prise.toLowerCase().replace(/[^a-z0-9]/g, '-');

        bar.className = `chart-bar rounded-top-1 plug-${plugName}`;
        bar.style.height = `${height}%`;
        bar.title = `${item.type_prise} : ${Number(item.nb_pdc).toLocaleString('fr-FR')} points`;

        const label = document.createElement('div');
        label.className = 'chart-label';
        label.textContent = item.type_prise; 

        wrapper.appendChild(bar);
        wrapper.appendChild(label);
        chart.appendChild(wrapper);
    });
}


function displayYearDepChart(pdcByYearDep) {
    const chart = document.getElementById('year-dep-chart');
    if (!chart || !pdcByYearDep || pdcByYearDep.length === 0) return;
    chart.innerHTML = '';

    const max = Math.max(...pdcByYearDep.map(item => Number(item.nb_pdc)));
    const categoriesAnnee = [...new Set(pdcByYearDep.map(item => item.annee))].sort((a, b) => a - b);
    
    const departements = ['22', '29', '35', '56'];

    categoriesAnnee.forEach(annee => {
        const wrapper = document.createElement('div');
        wrapper.className = 'chart-wrapper';

        const group = document.createElement('div');
        group.className = 'chart-group';

        departements.forEach(dep => {
            const match = pdcByYearDep.find(item => item.annee === annee && item.dep_code === dep);
            const count = match ? Number(match.nb_pdc) : 0;
            const height = max > 0 ? (count / max) * 100 : 0;

            const bar = document.createElement('div');
            bar.className = `chart-bar-sm rounded-top-1 dep-${dep}`;
            bar.style.height = `${height}%`;
            bar.title = `Dép. ${dep} : ${Number(count).toLocaleString('fr-FR')} points installés en ${annee}`;

            group.appendChild(bar);
        });

        const label = document.createElement('div');
        label.className = 'chart-label';
        label.textContent = annee;

        wrapper.appendChild(group);
        wrapper.appendChild(label);
        chart.appendChild(wrapper);
    });
}