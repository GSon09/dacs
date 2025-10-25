<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản trị</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
                body { background: #f8f9fa; }
                .admin-header { background: #4B2067; color: #fff; padding: 1rem; }
                .admin-sidebar { background: #FFD6E0; min-height: 100vh; padding-top: 2rem; }
                .admin-content { padding: 2rem; }
                .admin-logo { font-family: Georgia, serif; font-size: 2rem; font-weight: bold; color: #4B2067; }
        </style>
</head>
<body>
        <div class="container-fluid">
                <div class="row">
                        <nav class="col-md-2 admin-sidebar d-flex flex-column align-items-center">
                                <a href="/admin" class="admin-logo mb-4 text-decoration-none">
                                        <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle; margin-right:10px;">
                                                <rect x="6" y="8" width="36" height="32" rx="8" fill="#FFD6E0" stroke="#4B2067" stroke-width="2"/>
                                                <rect x="12" y="14" width="24" height="20" rx="4" fill="#fff" stroke="#4B2067" stroke-width="1.5"/>
                                                <ellipse cx="24" cy="24" rx="8" ry="7" fill="#FFD6E0"/>
                                                <circle cx="21" cy="23" r="1.5" fill="#4B2067"/>
                                                <circle cx="27" cy="23" r="1.5" fill="#4B2067"/>
                                                <path d="M21 27c1.5 1.5 4.5 1.5 6 0" stroke="#4B2067" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                        Admin
                                </a>
                                <ul class="nav flex-column w-100">
                                        <li class="nav-item mb-2"><a class="nav-link" href="/admin">Dashboard</a></li>
                                        <li class="nav-item mb-2"><a class="nav-link" href="/admin/users">Users</a></li>
                                        <li class="nav-item mb-2"><a class="nav-link" href="/admin/books">Books</a></li>
                                        <li class="nav-item mb-2"><a class="nav-link" href="/admin/orders">Orders</a></li>
                                        <li class="nav-item mb-2">
                                                <form action="/logout" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="nav-link text-danger" style="background:none; border:none; padding:0; cursor:pointer;">
                                                                Đăng xuất
                                                        </button>
                                                </form>
                                        </li>
                                </ul>
                        </nav>
                        <main class="col-md-10 admin-content">
                                @yield('content')
                        </main>
                </div>
        </div>
</body>
</html>
