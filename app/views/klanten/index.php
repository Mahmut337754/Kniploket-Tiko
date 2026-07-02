<?php $baseUrl = rtrim($base ?? '', '/'); ?>

<!-- Verborgen verwijder-formulier (buiten modal, directe DOM-submit) -->
<form method="POST"
      action="<?= $baseUrl ?>/klanten/verwijderen"
      id="verwijderFormulier"
      style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="id" id="verwijderKlantId">
</form>

<!-- Verwijder-modaal -->
<div class="modal fade" id="verwijderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4" style="background:#fff5f5;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;background:#fee2e2;flex-shrink:0;">
                        <i class="bi bi-trash fs-5 text-danger"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:#991b1b;">Klant verwijderen</h5>
                        <p class="text-muted small mb-0">Deze actie is onomkeerbaar</p>
                    </div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-1 text-dark">
                    Weet u zeker dat u
                    <strong id="verwijderNaam" class="text-danger"></strong>
                    wilt verwijderen?
                </p>
                <div class="rounded-3 p-3 mt-3 d-flex gap-2 align-items-start"
                     style="background:#fffbeb;border:1px solid #fde68a;">
                    <i class="bi bi-exclamation-triangle-fill text-warning mt-1 flex-shrink-0"></i>
                    <p class="small mb-0 text-muted">
                        Alle gegevens, allergieën, afspraken en bestellingen worden permanent verwijderd.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4"
                        data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-danger rounded-3 px-4"
                        id="bevestigVerwijderen">
                    <i class="bi bi-trash me-1"></i>Ja, verwijderen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Pagina header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0" style="color:#0f172a;">Klanten</h2>
        <p class="text-muted small mb-0">
            <?= count($klanten) ?> klant<?= count($klanten) !== 1 ? 'en' : '' ?> gevonden
        </p>
    </div>
    <a href="<?= $baseUrl ?>/klanten/aanmaken" class="btn btn-primary rounded-3 px-4">
        <i class="bi bi-person-plus me-2"></i>Klant toevoegen
    </a>
</div>

<?php if (empty($klanten)): ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body text-center py-5">
        <div class="rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center"
             style="width:80px;height:80px;background:#eef2ff;">
            <i class="bi bi-people text-primary fs-2"></i>
        </div>
        <h5 class="fw-semibold mb-1">Nog geen klanten</h5>
        <p class="text-muted mb-4">Er zijn op dit moment geen klanten in het systeem.</p>
        <a href="<?= $baseUrl ?>/klanten/aanmaken" class="btn btn-primary rounded-3 px-4">
            <i class="bi bi-person-plus me-2"></i>Eerste klant toevoegen
        </a>
    </div>
</div>

<?php else: ?>
<div class="mb-3">
    <div class="input-group rounded-3 overflow-hidden border bg-white">
        <span class="input-group-text bg-white border-0 ps-3">
            <i class="bi bi-search text-muted"></i>
        </span>
        <input type="search" id="zoekBalk"
               class="form-control border-0 shadow-none"
               placeholder="Zoeken op naam, e-mail of telefoon...">
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="klantenTabel">
            <thead style="background:#f8fafc;">
                <tr>
                    <th class="ps-4 py-3 border-0 text-muted fw-semibold" style="font-size:.8rem;text-transform:uppercase;">Naam</th>
                    <th class="py-3 border-0 text-muted fw-semibold" style="font-size:.8rem;text-transform:uppercase;">E-mail</th>
                    <th class="py-3 border-0 text-muted fw-semibold d-none d-md-table-cell" style="font-size:.8rem;text-transform:uppercase;">Telefoon</th>
                    <th class="py-3 border-0 text-muted fw-semibold" style="font-size:.8rem;text-transform:uppercase;">Status</th>
                    <th class="py-3 border-0 pe-4 text-end text-muted fw-semibold" style="font-size:.8rem;text-transform:uppercase;">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($klanten as $klant): ?>
                <tr style="border-top:1px solid #f1f5f9;">
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:36px;height:36px;background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                                <span class="text-white fw-bold" style="font-size:.8rem;">
                                    <?= strtoupper(mb_substr($klant['naam'] ?? 'K', 0, 1)) ?>
                                </span>
                            </div>
                            <span class="fw-semibold text-dark">
                                <?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                    </td>
                    <td class="py-3 text-muted" style="font-size:.9rem;">
                        <?= htmlspecialchars($klant['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td class="py-3 text-muted d-none d-md-table-cell" style="font-size:.9rem;">
                        <?= htmlspecialchars($klant['telefoonnummer'] ?? '–', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td class="py-3">
                        <?php if ($klant['is_actief']): ?>
                            <span class="badge rounded-pill px-3 py-2"
                                  style="background:#dcfce7;color:#15803d;font-size:.78rem;font-weight:600;">Actief</span>
                        <?php else: ?>
                            <span class="badge rounded-pill px-3 py-2"
                                  style="background:#f1f5f9;color:#64748b;font-size:.78rem;font-weight:600;">Inactief</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 pe-4 text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="<?= $baseUrl ?>/klanten/detail?id=<?= (int)$klant['id'] ?>"
                               class="btn btn-sm btn-light border rounded-3" title="Details">
                                <i class="bi bi-person-lines-fill" style="color:#0ea5e9;"></i>
                            </a>
                            <a href="<?= $baseUrl ?>/klanten/wijzigen?id=<?= (int)$klant['id'] ?>"
                               class="btn btn-sm btn-light border rounded-3" title="Wijzigen">
                                <i class="bi bi-pencil" style="color:#6366f1;"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-light border rounded-3 js-verwijder"
                                    data-id="<?= (int)$klant['id'] ?>"
                                    data-naam="<?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    title="Verwijderen">
                                <i class="bi bi-trash" style="color:#ef4444;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="geenResultaten" class="text-center py-5 d-none">
    <i class="bi bi-search text-muted" style="font-size:2rem;"></i>
    <p class="text-muted mt-2 mb-0">Geen klanten gevonden.</p>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Verwijderknop via event delegation
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.js-verwijder');
        if (!btn) return;
        document.getElementById('verwijderKlantId').value    = btn.getAttribute('data-id');
        document.getElementById('verwijderNaam').textContent = btn.getAttribute('data-naam');
        new bootstrap.Modal(document.getElementById('verwijderModal')).show();
    });

    // Bevestig knop in modaal → submit verborgen formulier
    var bevestig = document.getElementById('bevestigVerwijderen');
    if (bevestig) {
        bevestig.addEventListener('click', function () {
            document.getElementById('verwijderFormulier').submit();
        });
    }

    // Zoekfunctie
    var zoek = document.getElementById('zoekBalk');
    if (zoek) {
        zoek.addEventListener('input', function () {
            var term  = this.value.toLowerCase();
            var rijen = document.querySelectorAll('#klantenTabel tbody tr');
            var n     = 0;
            rijen.forEach(function (r) {
                var toon = r.textContent.toLowerCase().indexOf(term) !== -1;
                r.style.display = toon ? '' : 'none';
                if (toon) n++;
            });
            var geen = document.getElementById('geenResultaten');
            if (geen) geen.classList.toggle('d-none', n > 0 || term === '');
        });
    }

});
</script>
