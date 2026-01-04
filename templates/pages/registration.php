<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Todo\Services\TemplateData;

TemplateData::setTemplateData([
    'pageTitle' => 'Registration',
]);

?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-xl-4 col-lg-5 col-md-6" style="min-width: 400px;">
        <form id="registrationFrom" class="card p-4 shadow">
            <h4 class="text-center mb-4">User Registration</h4>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nickname</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                >
            </div>
            <div class="text-center mt-4">
                <button id="registerBtn" type="submit" class="btn btn-success px-5">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#registrationFrom').submit(function (e) {
            e.preventDefault();

            const password = $('#password').val();
            const passwordConfirmation = $('#password_confirmation').val();

            if (!password || !passwordConfirmation || password !== passwordConfirmation) {
                alert('Wrong Password confirmation');
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

