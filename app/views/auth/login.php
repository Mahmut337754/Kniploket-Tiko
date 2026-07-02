<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-scissors fs-1 text-primary"></i>
                    <h4 class="mt-2 fw-bold">Kniploket Tiko</h4>
                    <p class="text-muted small">Inloggen beheerpaneel</p>
                </div>

                <form method="POST" action="/login" novalidate id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mailadres</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            required
                            autocomplete="email"
                            maxlength="255"
                            placeholder="naam@voorbeeld.nl">
                        <div class="invalid-feedback">Voer een geldig e-mailadres in.</div>
                    </div>

                    <div class="mb-4">
                        <label for="wachtwoord" class="form-label">Wachtwoord</label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control"
                                id="wachtwoord"
                                name="wachtwoord"
                                required
                                autocomplete="current-password"
                                placeholder="Wachtwoord">
                            <button class="btn btn-outline-secondary" type="button" id="toggleWachtwoord" tabindex="-1">
                                <i class="bi bi-eye" id="oogIcoon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">Wachtwoord is verplicht.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Inloggen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Toon/verberg wachtwoord
document.getElementById('toggleWachtwoord').addEventListener('click', function () {
    const veld  = document.getElementById('wachtwoord');
    const icoon = document.getElementById('oogIcoon');
    if (veld.type === 'password') {
        veld.type = 'text';
        icoon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        veld.type = 'password';
        icoon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>
