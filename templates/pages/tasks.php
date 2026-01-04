<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Todo\Services\TemplateData;

TemplateData::setTemplateData([
    'pageTitle' => 'Tasks',
]);

?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-xl-8 col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Todo List</h3>
            </div>

            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="taskTitle" class="form-label">Title</label>
                        <input
                            type="text"
                            class="form-control"
                            id="taskTitle"
                            placeholder="Task title"
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="taskDescription" class="form-label">Description</label>
                        <input
                            type="text"
                            class="form-control"
                            id="taskDescription"
                            placeholder="Short description"
                        >
                    </div>
                    <div class="col-md-2">
                        <label for="taskStatus" class="form-label">Status</label>
                        <select id="taskStatus" class="form-select">
                            <option value="New">New</option>
                            <option value="In progress">In progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="taskPriority" class="form-label">Priority</label>
                        <select id="taskPriority" class="form-select">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3" selected>3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button id="addTaskBtn" type="button" class="btn btn-primary w-100">
                            Add task
                        </button>
                    </div>
                </div>

                <div id="tasksBlock" class="table-responsive mt-4" style="display: none;">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="width: 20%;">Title</th>
                            <th>Description</th>
                            <th style="width: 140px;">Status</th>
                            <th style="width: 100px;">Priority</th>
                            <th style="width: 80px;" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="todoTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        let taskCounter = 0;

        function updateTaskNumbers() {
            $('#todoTableBody tr').each(function (index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        $('#addTaskBtn').on('click', function () {
            const title = $('#taskTitle').val().trim();
            const description = $('#taskDescription').val().trim();
            const status = $('#taskStatus').val();
            const priority = $('#taskPriority').val();

            if (!title) {
                alert('Please enter a task title.');
                return;
            }

            taskCounter++;

            if (!$('#tasksBlock').is(':visible')) {
                $('#tasksBlock').show();
            }

            const rowHtml = `
                <tr data-task-id="${taskCounter}">
                    <td></td>
                    <td>${$('<div>').text(title).html()}</td>
                    <td>${$('<div>').text(description).html()}</td>
                    <td>${$('<div>').text(status).html()}</td>
                    <td>${$('<div>').text(priority).html()}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-task">
                            Delete
                        </button>
                    </td>
                </tr>
            `;

            $('#todoTableBody').append(rowHtml);
            updateTaskNumbers();

            $('#taskTitle').val('');
            $('#taskDescription').val('');
            $('#taskStatus').val('New');
            $('#taskPriority').val('3');
        });

        $(document).on('click', '.btn-delete-task', function () {
            $(this).closest('tr').remove();
            updateTaskNumbers();

            if (!$('#todoTableBody tr').length) {
                $('#tasksBlock').hide();
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
