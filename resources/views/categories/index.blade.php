<!DOCTYPE html>
<html>

<head>
    <title>Laravel AJAX CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <h2>School CRUD using AJAX</h2>
    <div class="container">

        <div class="card p-4">
            <form id="addCategoryForm">
                <input type="text" class="form-control mb-2" name="name" placeholder="School Name">
                <input type="text" name="description" class="form-control  mb-2" placeholder="Address">
                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>

        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="schoolTable"></tbody>
        </table>

    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Fetch all schools
        function fetchSchools() {
            $.get('/category/fetch-data', function(data) {
                let rows = '';
                data.forEach(category => {
                    rows += `
                        <tr>
                            <td>${category.id}</td>
                            <td><input type="text" value="${category.name}" data-id="${category.id}" class="edit-name"></td>
                            <td><input type="text" value="${category.description}" data-id="${category.id}" class="edit-description"></td>
                            <td>
                                <button onclick="updatecategory(${category.id})">Update</button>
                                <button onclick="deletecategory(${category.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $('#schoolTable').html(rows);
            });
        }

        fetchSchools();

        // Add school
        $('#addCategoryForm').submit(function(e) {
            e.preventDefault();
            $.post('/category', $(this).serialize(), function() {
                fetchSchools();
                $('#addCategoryForm')[0].reset();
            });
        });

        // Update school
        function updatecategory(id) {
            let name = $(`.edit-name[data-id="${id}"]`).val();
            let description = $(`.edit-description[data-id="${id}"]`).val();

            $.ajax({
                url: '/category/' + id,
                type: 'PUT',
                data: {
                    name,
                    description
                },
                success: function() {
                    fetchSchools();
                }
            });
        }

        // Delete school
        function deletecategory(id) {
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: '/category/' + id,
                    type: 'DELETE',
                    success: function() {
                        fetchSchools();
                    }
                });
            }
        }
    </script>
</body>

</html>
