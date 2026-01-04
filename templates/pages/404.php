<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Todo\Services\TemplateData;

TemplateData::setTemplateData([
    'pageTitle' => '404',
]);

?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="d-flex justify-content-center align-items-center">
    <div class="card shadow border-0 text-center p-4" style="max-width: 400px; width: 100%;">
        <div class="mb-3">
            <div class="fw-bold text-primary" style="font-size: 72px; line-height: 1">
                404
            </div>
            <div class="fw-semibold fs-5 mt-1">
                Page Not Found
            </div>
        </div>
        <p class="text-muted small mb-3">
            The page you are looking for does not exist.
        </p>
        <div class="d-flex justify-content-center gap-2">
            <a href="/todo" class="btn btn-sm btn-primary px-3">
                Home
            </a>
            <button onclick="history.back()" class="btn btn-sm btn-light px-3">
                Back
            </button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
