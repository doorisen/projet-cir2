'use strict'


function cleanString(str, limit) {
    return str.length <= limit ? str : (str.substring(0, limit - 3) + '...');
}


function getPrisesFromPdc(pdc) {
    const prises = [];

    if (pdc.prise_type_ef == 1) prises.push('EF');
    if (pdc.prise_type_2 == 1) prises.push('Type 2');
    if (pdc.prise_type_combo_ccs == 1) prises.push('Combo CCS');
    if (pdc.prise_type_chademo == 1) prises.push('CHAdeMO');
    if (pdc.prise_type_autre == 1) prises.push('Autre');

    return prises;
}


function getPaiementsFromPdc(pdc) {
    const paiements = [];

    if (pdc.gratuit == 1) paiements.push('Gratuit');
    else {
        if (pdc.paiement_cb == 1) paiements.push('CB');
        if (pdc.paiement_acte == 1) paiements.push('Acte');
        if (pdc.paiement_autre == 1) paiements.push('Autre');
    }

    return paiements;
}

function getAdresseFromPdc(pdc) {
    if (!pdc.adresse_station && !pdc.code_postal && !pdc.commune) {
        return;
    }
    return `${pdc.adresse_station || ''}, ${pdc.code_postal || ''} ${pdc.commune || ''}`.trim();
}

function getCommuneFromPdc(pdc) {
    if (!pdc.commune && !pdc.dep_code) {
        return;
    }
    return `${pdc.commune} (${pdc.dep_code})`;
}

function parseCableT2(t2) {
    return t2 == 1 ? 'Oui' : 'Non';
}

function parseIdStation(id) {
    return id ? `Station ${id}` : "-";
}

function parsePuissance(puissance) {
    return `${parseFloat(puissance)} kW`;
}

function parseDefault(data) {
    return data ?? "-";
}

function setTextAtId(elementId, text, parsing=parseDefault) {
    const el = document.getElementById(elementId);
    if (el) {
        el.textContent = parsing(text);
    } else {
        console.warn(`id '${elementId}' est introuvable.`);
    }
}

function setValueAtId(elemendId, value) {
    document.getElementById(elemendId).value = value;
}

function insertElementsInContainer(container, elements) {
    if (!elements.length) {
        container.innerHTML = "-";
    } else {
        elements.forEach(label => {
            container.insertAdjacentHTML('beforeend', `<span class="badge-custom">${label}</span>`);
        });
    }
}

function createTdCell(text='', classes=[], parsing=parseDefault) {
    const td = document.createElement('td');

    td.textContent = cleanString(parsing(text), 30);
    td.classList.add(...classes);
    td.title = parsing(text);

    return td;
}

function createLink(href, text='', classes=[]) {
    const link = document.createElement('a');

    link.textContent = text;
    link.href = href;
    link.classList.add(...classes);

    return link;
}

