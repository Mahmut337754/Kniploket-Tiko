<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="/klanten" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="mb-0">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                Klant wijzigen:
                <?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            </h2>
        </div>

        <!-- Actieknoppen -->
        <div class="d-flex gap-2 mb-4">
            <a href="/klanten/detail?id=<?= (int)$klant['id'] ?>"
               class="btn btn-outline-info">
                <i class="bi bi-person-lines-fill me-1"></i>Details
            </a>
            <a href="/klanten/wijzigen?id=<?= (int)$klant['id'] ?>"
               class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Wijzigen
            </a>
            <button
                type="button"
                class="btn btn-outline-danger"
                id="verwijderBtn"
                data-id="<?= (int)$klant['id'] ?>"
                data-naam="<?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <i class="bi bi-trash me-1"></i>Verwijderen
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="/klanten/wijzigen" novalidate id="klantForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="id" value="<?= (int) $klant['id'] ?>">

                    <div class="row g-3">
                        <!-- Naam -->
                        <div class="col-md-6">
                            <label for="naam" class="form-label">Naam <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                id="naam"
                                name="naam"
                                required
                                maxlength="100"
                                value="<?= htmlspecialchars($formData['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <div class="invalid-feedback">Naam is verplicht.</div>
                        </div>

                        <!-- E-mail -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mailadres <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required
                                maxlength="255"
                                value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <div class="invalid-feedback">Voer een geldig e-mailadres in.</div>
                        </div>

                        <!-- Nieuw wachtwoord (optioneel) -->
                        <div class="col-md-6">
                            <label for="wachtwoord" class="form-label">Nieuw wachtwoord</label>
                            <input
                                type="password"
                                class="form-control"
                                id="wachtwoord"
                                name="wachtwoord"
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Laat leeg om niet te wijzigen">
                            <div class="form-text">Laat leeg om het huidige wachtwoord te bewaren.</div>
                            <div class="invalid-feedback">Minimaal 8 tekens vereist.</div>
                        </div>

                        <!-- Telefoonnummer -->
                        <div class="col-md-6">
                            <label for="telefoonnummer" class="form-label">Telefoonnummer</label>
                            <input
                                type="tel"
                                class="form-control"
                                id="telefoonnummer"
                                name="telefoonnummer"
                                maxlength="20"
                                value="<?= htmlspecialchars($formData['telefoonnummer'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <!-- Adres -->
                        <div class="col-12">
                            <label for="adres" class="form-label">Adres</label>
                            <input
                                type="text"
                                class="form-control"
                                id="adres"
                                name="adres"
                                maxlength="255"
                                value="<?= htmlspecialchars($formData['adres'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <!-- Allergieën -->
                        <div class="col-md-6">
                            <label for="allergieen" class="form-label">Allergieën</label>
                            <textarea
                                class="form-control"
                                id="allergieen"
                                name="allergieen"
                                rows="3"><?= htmlspecialchars($formData['allergieen'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <!-- Wensen -->
                        <div class="col-md-6">
                            <label for="wensen" class="form-label">Wensen</label>
                            <textarea
                                class="form-control"
                                id="wensen"
                                name="wensen"
                                rows="3"><?= htmlspecialchars($formData['wensen'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div><!-- /row -->

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Wijzigingen opslaan
                        </button>
                        <a href="/klanten" class="btn btn-outline-secondary">Annuleren</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                Weet u zeker dat u klant <strong><?= htmlspecialchars($klant['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong> wilt verwijderen?
                <br><span class="text-danger small">Deze actie kan niet ongedaan worden gemaakt.</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <form method="POST" action="/klanten/verwijderen">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="id" value="<?= (int)$klant['id'] ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Verwijderen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('verwijderBtn')?.addEventListener('click', function () {
    new bootstrap.Modal(document.getElementById('verwijderModal')).show();
});
</script>
