<!-- Verwijder-modaal (Bootstrap) -->
<div class="modal fade" id="verwijderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1rem;overflow:hidden;">
            <div class="modal-header border-0 pb-0" style="background:#fff2f2;">
                <div class="d-flex align-items-center gap-2">
                    <div style="background:#dc3545;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-trash text-white"></i>
                    </div>
                    <h5 class="modal-title fw-bold mb-0 text-danger">Klant verwijderen</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="mb-1">Weet u zeker dat u <strong id="verwijderNaam" class="text-dark"></strong> wilt verwijderen?</p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-exclamation-circle me-1 text-warning"></i>
                    Alle gegevens, afspraken en bestellingen worden permanent verwijderd.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    Annuleren
                </button>
                <form method="POST" action="<?= $base ?>/klanten/verwijderen" id="verwijderForm" class="d-inline">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="id" id="verwijderIdInput">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-trash me-1"></i>Ja, verwijderen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Klanten</h2>
        <p class="text-muted small mb-0"><?= count($klanten) ?> klant<?= count($klanten) !== 1 ? 'en' : '' ?> gevonden</p>
    </div>
    <a href="<?= $base ?>/klanten/aanmaken" class="btn btn-primary px-4">
        <i class="bi bi-person-plus me-2"></i>Klant toevoegen
    </a>
</div>

<?php if (empty($klanten)): ?>
<!-- Lege staat -->
<div class="card border-0 shadow-sm" style="border-radius:1rem;">
    <div class="card-body text-center py-5">
        <div style="background:#f0f4ff;border-radius:50%;width:80px;height:80px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <i class="bi bi-people text-primary" style="font-size:2rem;"></i>
        </div>
        <h5 class="fw-semibold mb-1">Nog geen klanten</h5>
        <p class="text-muted mb-4">Er zijn op dit moment geen klanten in het systeem.</p>
        <a href="<?= $base ?>/klanten/aanmaken" class="btn btn-primary px-4">
            <i class="bi bi-person-plus me-2"></i>Eerste klant toevoegen
        </a>
    </div>
</div>

<?php else: ?>
<!-- Zoekbalk -->
<div class="mb-3">
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search text-muted"></i>
        </span>
        <input type="search" id="zoekBalk" class="form-control border-start-0 ps-0"
               placeholder="Zoeken op naam, e-mail of telefoon...">
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm" style="border-radius:1rem;overflow:hidden;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="klantenTabel">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4 py-3 fw-semibold text-muted border-0">Naam</th>
                    <th class="py-3 fw-semibold text-muted border-0">E-mail</th>
                    <th class="py-3 fw-semibold text-muted border-0 d-none d-md-table-cell">Telefoon</th>
                    <th class="py-3 fw-semibold text-muted border-0">Status</th>
                    <th class="py-3 fw-semibold text-muted border-0 text-end pe-4">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($klanten as $klant): ?>
                <tr style="border-top:1px solid #f1f3f5;">
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <span class="text-white fw-bold" style="font-size:.85rem;">
                                    <?= strtoupper(substr($klant['naam'] ?? 'K', 0, 1)) ?>
                                </span>
                            </div>
                            <span class="fw-semibold"><?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </td>
                    <td class="py-3 text-muted"><?= htmlspecialchars($klant['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="py-3 text-muted d-none d-md-table-cell"><?= htmlspecialchars($klant['telefoonnummer'] ?? '–', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="py-3">
                        <?php if ($klant['is_actief']): ?>
                            <span class="badge rounded-pill px-3" style="background:#e8f5e9;color:#2e7d32;font-weight:600;">Actief</span>
                        <?php else: ?>
                            <span class="badge rounded-pill px-3" style="background:#f5f5f5;color:#757575;font-weight:600;">Inactief</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 pe-4 text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="<?= $base ?>/klanten/detail?id=<?= (int)$klant['id'] ?>"
                               class="btn btn-sm btn-light border" title="Details">
                                <i class="bi bi-person-lines-fill text-info"></i>
                            </a>
                            <a href="<?= $base ?>/klanten/wijzigen?id=<?= (int)$klant['id'] ?>"
                               class="btn btn-sm btn-light border" title="Wijzigen">
                                <i class="bi bi-pencil text-primary"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-light border verwijderBtn"
                                    data-id="<?= (int)$klant['id'] ?>"
                                    data-naam="<?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    title="Verwijderen">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Geen zoekresultaten melding -->
<div id="geenResultaten" class="text-center py-4 d-none">
    <i class="bi bi-search text-muted" style="font-size:2rem;"></i>
    <p class="text-muted mt-2 mb-0">Geen klanten gevonden voor uw zoekopdracht.</p>
</div>
<?php endif; ?>

<script>
// Koppel verwijderknop aan modaal
document.querySelectorAll('.verwijderBtn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('verwijderIdInput').value = this.dataset.id;
        document.getElementById('verwijderNaam').textContent = this.dataset.naam;
        new bootstrap.Modal(document.getElementById('verwijderModal')).show();
    });
});

// Zoekfunctie
const zoekBalk = document.getElementById('zoekBalk');
if (zoekBalk) {
    zoekBalk.addEventListener('input', function () {
        const term  = this.value.toLowerCase();
        const rijen = document.querySelectorAll('#klantenTabel tbody tr');
        let zichtbaar = 0;
        rijen.forEach(function (rij) {
            const match = rij.textContent.toLowerCase().includes(term);
            rij.style.display = match ? '' : 'none';
            if (match) zichtbaar++;
        });
        document.getElementById('geenResultaten').classList.toggle('d-none', zichtbaar > 0);
    });
}
</script>
