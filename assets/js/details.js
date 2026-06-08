'use strict';


document.addEventListener('DOMContentLoaded', loadDetails);


let activePrises = [];
let activePaiements = [];


async function loadDetails() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    const isAdmin = !!document.getElementById('station-select');

    // Création
    if (!id) {
        if (!isAdmin) {
            alert("Accès refusé. Mode administrateur requis pour créer une borne.");
            window.location.href = 'index.php';
            return;
        }

        await loadFilters({
            station: 'station-select',
            amenageur: 'amenageur-select',
            operateur: 'operateur-select'
        });

        renderAdminBadges('prises', activePrises, 'add-prise');
        renderAdminBadges('paiements', activePaiements, 'add-paiement');

        initAdminActions(null);     //null <=> POST
        return;
    }

    // Edition / Lecture seule
    try {
        const response = await fetch(`api/pdc.php?id=${id}`);
        const data = await response.json();
        if (!data.success) {
            console.error("Erreur API :", result.error);
            return;
        }
        const pdc = data.data;
        const id_station = pdc.id_station_local ?? pdc.id_station_itinerance;
        const adresse = getAdresseFromPdc(pdc);

        setTextAtId('station-name', pdc.nom_station);
        setTextAtId('station-id',   id_station, parseIdStation);

        activePrises = getPrisesFromPdc(pdc);
        activePaiements = getPaiementsFromPdc(pdc);

        
        if (isAdmin) {
            // Remplissage du formulaire
            setValueAtId('puissance-input',     pdc.puissance_nominale);
            setValueAtId('cable-t2-input',      pdc.cable_t2_attache);
            setValueAtId('tarification-input',  pdc.tarification || "");

            await loadFilters({
                station: 'station-select',
                amenageur: 'amenageur-select',
                operateur: 'operateur-select'
            });
            setValueAtId('station-select',      pdc.id_station);
            setValueAtId('amenageur-select',    pdc.nom_entreprise_AMENAGER);
            setValueAtId('operateur-select',    pdc.nom_entreprise)

            renderAdminBadges('prises', activePrises, 'add-prise');
            renderAdminBadges('paiements', activePaiements, 'add-paiement');

            initAdminActions(id);   // id <=> PUT

        } else {
            // Mode client
            setTextAtId('adresse',      adresse);
            setTextAtId('horaires',     pdc.horaires);
            setTextAtId('raccordement', pdc.raccordement);
            setTextAtId('cable-t2',     pdc.cable_t2_attache, parseCableT2);
            setTextAtId('puissance',    pdc.puissance_nominale, parsePuissance);
            setTextAtId('amenageur',    pdc.nom_entreprise_AMENAGER);
            setTextAtId('operateur',    pdc.nom_entreprise);
            setTextAtId('tarification', pdc.tarification);

            loadPrises(activePrises);
            loadPaiements(activePaiements);
        }
    
    } catch (error) {
        console.error("Impossible de charger les détails :", error);
        alert("Le chargement de la page a échoué. \nVoir la console pour plus de détails.");
    }
}


function renderAdminBadges(containerId, dataArray, selectId) {
    console.log(dataArray);
    const container = document.getElementById(containerId);
    const select = document.getElementById(selectId);
    container.innerHTML = '';
    
    if (dataArray.length === 0) {
        container.innerHTML = '<span class="text-secondary small">Aucun</span>';
    } else {
        dataArray.forEach(item => {
            // Création badge prise/paiement
            const span = document.createElement('span');
            span.className = 'badge-custom d-inline-flex align-items-center gap-1';
            span.style.cursor = 'pointer';
            span.title = 'Cliquer pour retirer';
            span.innerHTML = `${item} <i class="bi bi-x text-danger fs-5 lh-1"></i>`;
            
            // Suppression au clic
            span.onclick = () => {
                const index = dataArray.indexOf(item);
                if (index > -1) dataArray.splice(index, 1);
                renderAdminBadges(containerId, dataArray, selectId);
                window.markAsChanged(); // Active le bouton Valider
            };
            container.appendChild(span);
        });
    }

    // Écoute les ajouts depuis le select
    select.onchange = (e) => {
        const val = e.target.value;
        if (val && !dataArray.includes(val)) {
            dataArray.push(val);
            renderAdminBadges(containerId, dataArray, selectId);
            window.markAsChanged(); // Active le bouton Valider
        }
        e.target.value = '';
    };
}


function initAdminActions(pdcId) {
    const btnSave = document.getElementById('btn-save');
    const btnReset = document.getElementById('btn-reset');
    const btnDelete = document.getElementById('btn-delete');
    const isCreation = (pdcId === null);        // null <=> création

    window.markAsChanged = () => {
        btnSave.disabled = false;
        btnReset.disabled = false;
    };

    ['station-select', 'amenageur-select', 'operateur-select', 'puissance-input', 'cable-t2-input', 'tarification-input'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', window.markAsChanged);
            el.addEventListener('change', window.markAsChanged);
        }
    });

    btnReset.addEventListener('click', () => {
        window.location.reload();
    });

    // Valider
    btnSave.addEventListener('click', async () => {

        const stationId = parseInt(document.getElementById('station-select').value);
        const puissance = parseFloat(document.getElementById('puissance-input').value);
        if (isNaN(stationId) || isNaN(puissance)) {
            alert("Veuillez sélectionner une station et renseigner la puissance.");
            return;
        }
        
        const payload = {
            id_station :                stationId,
            puissance_nominale :        puissance,
            cable_t2_attache :          parseInt(document.getElementById('cable-t2-input').value),
            tarification :              document.getElementById('tarification-input').value,

            nom_entreprise :            document.getElementById('operateur-select').value,
            nom_entreprise_AMENAGER :   document.getElementById('amenageur-select').value,

            prise_type_ef :             activePrises.includes('EF') ? 1 : 0,
            prise_type_2 :              activePrises.includes('Type 2') ? 1 : 0,
            prise_type_combo_ccs :      activePrises.includes('Combo CCS') ? 1 : 0,
            prise_type_chademo :        activePrises.includes('CHAdeMO') ? 1 : 0,
            prise_type_autre :          activePrises.includes('Autre') ? 1 : 0,

            gratuit :                   activePaiements.includes('Gratuit') ? 1 : 0,
            paiement_cb :               activePaiements.includes('CB') ? 1 : 0,
            paiement_acte :             activePaiements.includes('Acte') ? 1 : 0,
            paiement_autre :            activePaiements.includes('Autre') ? 1 : 0,
        };
        if (!isCreation) {
            payload.id = parseInt(pdcId);
        }
        console.log(payload);
        //exit(); //crash pour ne pas recharger la page

        try {
            const url = isCreation ? `api/pdc.php` : `api/pdc.php?id=${pdcId}`;
            const method = isCreation ? 'POST' : 'PUT';

            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(isCreation ? "Le point a été créé avec succès !" : "Les modifications ont été enregistrées !");
                if (isCreation) {
                    window.location.href = 'index.php';
                } else {
                    window.location.reload();
                }
            } else {
                alert("Erreur serveur : " + result.error);
            }
        } catch (err) {
            console.error("Erreur :", err);
            alert("Une erreur réseau est survenue.");
        }
    });

    // Supprimer
    if (btnDelete) {
        btnDelete.addEventListener('click', async () => {
            if (!confirm("Supprimer définitivement ce point ?")) return;
            try {
                const response = await fetch(`api/pdc.php?id=${pdcId}`, { method: 'DELETE' });
                const result = await response.json();
                if (result.success) {
                    alert("Point supprimé.");
                    window.location.href = 'index.php';
                } else {
                    alert("Erreur : " + result.error);
                }
            } catch (err) {
                alert("Erreur réseau.");
            }
        });
    }
}



function loadPrises(prisesData) {
    const container = document.getElementById('prises');
    if(container) insertElementsInContainer(container, prisesData);
}


function loadPaiements(paiementsData) {
    const container = document.getElementById('paiements');
    if(container) insertElementsInContainer(container, paiementsData);
}

