<!DOCTYPE html>
<html>
<head>
    <title>Users</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h2 class="mb-4">User List</h2>
<form action="/add-user" method="POST" class="mb-3">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <input type="text" name="name" class="form-control" placeholder="Nhập tên" required>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">
                Add User
            </button>
        </div>
    </div>
</form>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
    </thead>

    <tbody>
       @foreach ($users as $user)
    <tr>
        <td>{{ $user['uid'] }}</td>

        <td>{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
    </tr>
@endforeach
    </tbody>
</table>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div style="position: fixed; bottom: 10px; right: 10px; background: #ffeb3b; color: #000; padding: 5px 10px; font-weight: bold; border-radius: 5px; z-index: 9999; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
    Base API: https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api/test-users
</div>
</body>
</html>