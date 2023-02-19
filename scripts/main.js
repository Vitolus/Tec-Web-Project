const VALIDATION_ERRORS_TRANSLATIONS = {
    'not-exists': 'devi selezionare un elemento',
    'empty': 'non può essere vuoto',
    'email-not-valid': 'indirizzo e-mail non valido',
    'too-long': 'troppo lungo',
    'too-short': 'troppo corto',
    'number-presence': 'non può contenere dei numeri',
    'characters-presence': 'non può contenere dei caratteri, spazi o simboli',
    'bad-character': 'non hai scelto un elemento valido fra quelli proposti',
};

window.addEventListener("load", function () {
    let isMobileMenuOpen = false;

    // Gestisce l'apertura e la chiusura del menu per la versione mobile
    document.getElementById('toggle-mobile-menu').addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector('#menu ul').style.display = isMobileMenuOpen ? 'none' : 'block';
        isMobileMenuOpen = !isMobileMenuOpen;
    });

    // Per ogni tabella della pagina
    for (const table of this.document.querySelectorAll('table')) {
        emptyTableToMessage(table.id);
    }
});

/**
 * Esamina gli errori di validazione ritornati dal backend e ne costruisce l'HTML dinamico
 * per ogni campo di testo dove si è verificato l'errore, applicando anche la conversione
 * da codice errore a testo in italiano
 */
function validationErrorsToString(
    errors,
    fieldNames
) {
    let html = '<p>Il modulo presenta i seguenti errori ai campi:</p><ul>';

    for (const [field, fieldErrors] of Object.entries(errors)) {
        html += `<li><p><strong>${fieldNames[field]}</strong>: `;
        html += fieldErrors.map(error => VALIDATION_ERRORS_TRANSLATIONS[error]).join(', ');
        html += '</p></li>';
    }

    return html;
}

/**
 * Controlla se la tabella con l'ID passato come parametro è vuota. Se lo è, visualizza il messaggio
 * che spiega perché è vuota e nasconde il thead (quindi le colonne)
 */
function emptyTableToMessage(tableId) {
    const table = document.querySelector(`table#${tableId}`);

    if (table) {
        const tableChildren = Array.from(table.children);
        const thead = tableChildren.find(c => c.nodeName.toLocaleLowerCase() === 'thead');
        const tbody = tableChildren.find(c => c.nodeName.toLocaleLowerCase() === 'tbody');

        if (tbody.children.length === 0) {
            thead.classList.add('hidden');
            tbody.remove();
            document.querySelector(`div.alert.warning#${tableId}-empty`).classList.remove('hidden');
        }
    } else {
        console.error(`Non ho trovato nessuna tabella con id ${tableId}`);
    }
}