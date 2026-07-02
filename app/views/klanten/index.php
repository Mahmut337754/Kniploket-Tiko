<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-people me-2 text-primary"></i>Klanten</h2>
    <a href="/klanten/aanmaken" class="btn btn-success">
        <i class="bi bi-person-plus me-1"></i>Klant toevoegen
    </a>
</div>

<?php if (empty($klanten)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>Nog geen klanten gevonden.
    </div>
<?php else: ?>
    <!-- Zoekbalk (client-side filtering) -->
    <div class="mb-3">
        <input
            type="search"
            id="zoekBalk"
            class="form-control"
            placeholder="Zoeken op naam of e-mail..."
            aria-label="Klanten doorzoeken">
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle" id="klantenTabel">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Naam</th>
                    <th>E-mail</th>
                    <th>Telefoon</th>
                    <th>Status</th>
                    <th class="text-end">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($klanten as $klant): ?>
                <tr>
                    <td><?= (int) $klant['id'] ?></td>
                    <td><?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($klant['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($klant['telefoonnummer'] ?? '–', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if ($klant['is_actief']): ?>
                            <span class="badge bg-success">Actief</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactief</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="/klanten/wijzigen?id=<?= (int) $klant['id'] ?>"
                           class="btn btn-sm btn-outline-primary me-1"
                           title="Wijzigen">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <!-- Verwijderknop opent modaal -->
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger verwijderBtn"
                            data-id="<?= (int) $klant['id'] ?>"
                            data-naam="<?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            title="Verwijderen">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Verwijder-bevestigingsmodaal -->
<div class="modal fade" id="verwijderModal" tabindex="-1" aria-labelledby="verwijderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="verwijderModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Klant verwijderen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body">
                Weet u zeker dat u klant <strong id="verwijderNaam"></strong> wilt verwijderen?
                <br><span class="text-danger small">Deze actie kan niet ongedaan worden gemaakt.</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <form method="POST" action="/klanten/verwijderen" id="verwijderForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="id" id="verwijderIdInput">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Verwijderen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Koppel verwijderknoppen aan het modaal
document.querySelectorAll('.verwijderBtn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const naam = this.dataset.naam;
        document.getElementById('verwijderIdInput').value = id;
        document.getElementById('verwijderNaam').textContent = naam;
        new bootstrap.Modal(document.getElementById('verwijderModal')).show();
    });
});

// Client-side tabelfilter
document.getElementById('zoekBalk')?.addEventListener('input', function () {
    const term = this.value.toLowerCase();
    document.querySelectorAll('#klantenTabel tbody tr').forEach(function (rij) {
        const tekst = rij.textContent.toLowerCase();
        rij.style.display = tekst.includes(term) ? '' : 'none';
    });
});
</script>
