<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-people me-2 text-primary"></i>Klanten</h2>
    <a href="<?= $base ?>/klanten/aanmaken" class="btn btn-success">
        <i class="bi bi-person-plus me-1"></i>Klant toevoegen
    </a>
</div>

<?php if (empty($klanten)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>Nog geen klanten gevonden.
    </div>
<?php else: ?>
    <div class="mb-3">
        <input type="search" id="zoekBalk" class="form-control"
               placeholder="Zoeken op naam of e-mail..." aria-label="Klanten doorzoeken">
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
                    <td><?= (int)$klant['id'] ?></td>
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
                        <a href="<?= $base ?>/klanten/detail?id=<?= (int)$klant['id'] ?>"
                           class="btn btn-sm btn-outline-info me-1" title="Details">
                            <i class="bi bi-person-lines-fill"></i>
                        </a>
                        <a href="<?= $base ?>/klanten/wijzigen?id=<?= (int)$klant['id'] ?>"
                           class="btn btn-sm btn-outline-primary me-1" title="Wijzigen">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="<?= $base ?>/klanten/verwijderen"
                              onsubmit="return confirm('Klant <?= htmlspecialchars(addslashes($klant['naam'] ?? ''), ENT_QUOTES, 'UTF-8') ?> verwijderen? Dit kan niet ongedaan worden gemaakt.')">
                            <input type="hidden" name="csrf_token"
                                   value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int)$klant['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Verwijderen">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>


<script>
document.getElementById('zoekBalk')?.addEventListener('input', function () {
    const term = this.value.toLowerCase();
    document.querySelectorAll('#klantenTabel tbody tr').forEach(function (rij) {
        rij.style.display = rij.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});
</script>
