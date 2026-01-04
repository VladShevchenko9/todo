<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Todo\Services\TemplateData;

TemplateData::setTemplateData([
    'pageTitle' => 'Login',
]);

?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-xl-4 col-lg-5 col-md-6" style="min-width: 400px;">
        <form id="loginForm" class="card p-4 shadow">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="example@gmail.com"
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
                    placeholder="password"
                    required
                >
            </div>
            <div class="text-center mt-4">
                <button id="loginBtn" type="submit" class="btn btn-primary px-5">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#loginForm').submit(function (e) {
            e.preventDefault();

            const email = $('#email').val();
            const password = $('#password').val();

            if (email === 'example@gmail.com' && password === 'password') {
                document.location = '/todo/tasks';
                return;
            }

            alert('Error');
        });
    });
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
