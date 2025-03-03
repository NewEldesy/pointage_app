// Vous pouvez ajouter des fonctionnalités AJAX ici pour interagir avec le serveur sans recharger la page.
// Par exemple, pour valider les absences ou les horaires en temps réel.

document.addEventListener('DOMContentLoaded', function() {
    // Exemple de gestion des événements
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            // Ajouter une logique pour éditer un élément
        });
    });
});
