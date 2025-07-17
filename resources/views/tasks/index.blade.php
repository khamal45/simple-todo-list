<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Task List</h1>

   
    <form id="taskForm">
        <div class="mb-3">
            <input type="text" class="form-control" id="title" placeholder="Task title" required>
        </div>
        <div class="mb-3">
            <textarea class="form-control" id="description" placeholder="Task description (optional)"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Task</button>
    </form>

    <hr>

  
    <table class="table table-bordered mt-3" id="taskTable">
        <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Completed</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        
        </tbody>
    </table>
</div>


<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editTaskForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-id">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" id="edit-title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="edit-description"></textarea>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="edit-completed">
                    <label class="form-check-label">Completed</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        let selectedId = null;

     
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        
        function fetchTasks() {
            $.get('/api/tasks', function (data) {
                let rows = '';
                data.forEach(task => {
                    rows += `
                        <tr data-id="${task.id}">
                            <td>${task.title}</td>
                            <td>${task.description ?? ''}</td>
                            <td><input type="checkbox" class="toggle-completed" ${task.is_completed ? 'checked' : ''}></td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-task" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                <button class="btn btn-sm btn-danger delete-task">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $('#taskTable tbody').html(rows);
            });
        }

       
        $('#taskForm').submit(function (e) {
            e.preventDefault();
            const task = {
                title: $('#title').val(),
                description: $('#description').val()
            };

            $.ajax({
                url: '/api/tasks',
                type: 'POST',
                data: task,
                success: function () {
                    $('#taskForm')[0].reset();
                    fetchTasks();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

     
    $(document).on('change', '.toggle-completed', function () {
         const row = $(this).closest('tr');
         const id = row.data('id');
         const is_completed = $(this).is(':checked') ? 1 : 0;


    $.ajax({
        url: `/api/tasks/${id}`,
        type: 'PUT',
        data: { is_completed },
        success: fetchTasks
    });
});

      
        $(document).on('click', '.delete-task', function () {
            const id = $(this).closest('tr').data('id');
            if (confirm('Are you sure you want to delete this task?')) {
                $.ajax({
                    url: `/api/tasks/${id}`,
                    type: 'DELETE',
                    success: fetchTasks
                });
            }
        });

       
        $(document).on('click', '.edit-task', function () {
            const row = $(this).closest('tr');
            selectedId = row.data('id');

            $.get(`/api/tasks/${selectedId}`, function (task) {
                $('#edit-id').val(task.id);
                $('#edit-title').val(task.title);
                $('#edit-description').val(task.description);
                $('#edit-completed').prop('checked', task.is_completed);
            });
        });

       
        $('#editTaskForm').submit(function (e) {
    e.preventDefault();

    const isChecked = $('#edit-completed').is(':checked');

    const updatedTask = {
        title: $('#edit-title').val(),
        description: $('#edit-description').val(),
        is_completed: isChecked ? 1 : 0, 
    };

    $.ajax({
        url: `/api/tasks/${selectedId}`,
        type: 'PUT',
        data: updatedTask,
        success: function () {
            $('#editModal').modal('hide');
            fetchTasks();
        },
        error: function (xhr) {
            alert(xhr.responseJSON.message);
        }
    });
});
        fetchTasks();
    });
</script>
</body>
</html>
