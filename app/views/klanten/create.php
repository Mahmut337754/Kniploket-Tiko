<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="/klanten" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="mb-0"><i class="bi bi-person-plus me-2 text-success"></i>Klant toevoegen</h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="/klanten/aanmaken" novalidate id="klantForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

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
                                value="<?= htmlspecialchars($oud['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
                                value="<?= htmlspecialchars($oud['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <div class="invalid-feedback">Voer een geldig e-mailadres in.</div>
                        </div>

                        <!-- Wachtwoord -->
                        <div class="col-md-6">
                            <label for="wachtwoord" class="form-label">Wachtwoord <span class="text-danger">*</span></label>
                            <input
                                type="password"
                                class="form-control"
                                id="wachtwoord"
                                name="wachtwoord"
                                required
                                minlength="8"
                                autocomplete="new-password">
                            <div class="form-text">Minimaal 8 tekens.</div>
                            <div class="invalid-feedback">Wachtwoord is verplicht (minimaal 8 tekens).</div>
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
                                value="<?= htmlspecialchars($oud['telefoonnummer'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
                                value="<?= htmlspecialchars($oud['adres'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <!-- Allergieën -->
                        <div class="col-md-6">
                            <label for="allergieen" class="form-label">Allergieën</label>
                            <textarea
                                class="form-control"
                                id="allergieen"
                                name="allergieen"
                                rows="3"
                                placeholder="Bijv. ammoniak, parfum..."><?= htmlspecialchars($oud['allergieen'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <!-- Wensen -->
                        <div class="col-md-6">
                            <label for="wensen" class="form-label">Wensen</label>
                            <textarea
                                class="form-control"
                                id="wensen"
                                name="wensen"
                                rows="3"
                                placeholder="Bijv. natuurlijke producten..."><?= htmlspecialchars($oud['wensen'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div><!-- /row -->

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>Klant opslaan
                        </button>
                        <a href="/klanten" class="btn btn-outline-secondary">Annuleren</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
