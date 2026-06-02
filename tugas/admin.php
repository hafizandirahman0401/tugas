<?php
session_start();
include 'koneksi.php';

// Cek login admin
if (!isset($_SESSION['admin_loggedin']) && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password' AND status='approved'");
    if (mysqli_num_rows($query) == 1) {
        $_SESSION['admin_loggedin'] = true;
        $_SESSION['admin_user'] = $username;
    } else {
        $error = "Username atau password salah!";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Cek session
if (!isset($_SESSION['admin_loggedin'])) {
    // Tampilkan form login
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Login Admin HMPTM</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
        <style>
            * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
            body { background:#f0f7f0; display:flex; justify-content:center; align-items:center; min-height:100vh; }
            .login-card { background:white; padding:2rem; border-radius:1.5rem; box-shadow:0 12px 28px rgba(0,0,0,0.1); width:90%; max-width:400px; text-align:center; }
            .login-card h2 { color:#1b6b3c; margin-bottom:1.5rem; }
            .login-card input { width:100%; padding:0.8rem; margin:0.5rem 0; border:1px solid #ccc; border-radius:2rem; }
            .btn-primary { background:#1b6b3c; color:white; border:none; padding:0.8rem; border-radius:2rem; width:100%; cursor:pointer; font-weight:bold; }
            .error { color:#c0392b; margin-bottom:1rem; }
        </style>
    </head>
    <body>
        <div class="login-card">
            <h2><i class="fas fa-lock"></i> Login Admin</h2>
            <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login" class="btn-primary">Login</button>
            </form>
            <p style="margin-top:1rem; font-size:0.8rem;">Default: admin / admin123</p>
            <a href="user.php" style="display:inline-block; margin-top:1rem; color:#1b6b3c;">← Kembali ke Halaman Publik</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Proses CRUD
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_user'])) {
    $id = intval($_POST['id']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    if ($id == 0) {
        // Tambah baru
        $password = md5($_POST['password']);
        mysqli_query($conn, "INSERT INTO users (email, username, password, jurusan, fakultas, alamat, jenis_kelamin, status) 
                             VALUES ('$email', '$username', '$password', '$jurusan', '$fakultas', '$alamat', '$jenis_kelamin', '$status')");
    } else {
        // Update
        $query = "UPDATE users SET email='$email', username='$username', jurusan='$jurusan', fakultas='$fakultas', alamat='$alamat', jenis_kelamin='$jenis_kelamin', status='$status'";
        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']);
            $query .= ", password='$password'";
        }
        $query .= " WHERE id=$id";
        mysqli_query($conn, $query);
    }
    header("Location: admin.php");
    exit;
}

// Ambil data user
$users = mysqli_query($conn, "SELECT * FROM users WHERE username != 'admin' ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin HMPTM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
        body { background:#f0f7f0; }
        :root { --primary:#1b6b3c; --primary-dark:#0f532e; --primary-light:#d9f0e2; }
        .admin-header { background:white; padding:1rem 2rem; display:flex; justify-content:space-between; align-items:center; border-bottom:3px solid var(--primary); flex-wrap:wrap; }
        .logo h2 { color:var(--primary); }
        .btn-primary { background:var(--primary); border:none; padding:0.6rem 1.2rem; border-radius:40px; font-weight:bold; color:white; cursor:pointer; }
        .btn-outline { background:transparent; border:2px solid var(--primary); color:var(--primary); padding:0.5rem 1rem; border-radius:30px; cursor:pointer; }
        .btn-danger { background:#c0392b; color:white; border:none; padding:0.4rem 0.8rem; border-radius:20px; cursor:pointer; }
        .card { background:white; border-radius:1.5rem; box-shadow:0 12px 28px rgba(0,0,0,0.08); padding:1.8rem; margin-bottom:2rem; }
        .container { max-width:1400px; margin:2rem auto; padding:0 1.5rem; }
        table { width:100%; border-collapse:collapse; overflow-x:auto; display:block; }
        th, td { text-align:left; padding:12px 8px; border-bottom:1px solid #ddd; }
        th { background:var(--primary-light); color:var(--primary-dark); }
        .action-icons i { margin:0 5px; cursor:pointer; font-size:1.1rem; }
        .edit-icon { color:#e68a2e; }
        .delete-icon { color:#c0392b; }
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000; }
        .modal-content { background:white; width:90%; max-width:500px; border-radius:1.5rem; padding:1.8rem; position:relative; }
        .close-modal { position:absolute; right:1.2rem; top:0.8rem; font-size:1.8rem; cursor:pointer; }
        .form-group { margin-bottom:1rem; }
        .form-group label { display:block; font-weight:600; margin-bottom:0.3rem; }
        .form-group input, .form-group select, .form-group textarea { width:100%; padding:0.6rem; border-radius:0.8rem; border:1px solid #ccc; }
        .badge { padding:0.2rem 0.6rem; border-radius:20px; font-size:0.75rem; font-weight:bold; }
        .badge-pending { background:#f39c12; color:white; }
        .badge-approved { background:#27ae60; color:white; }
        .badge-rejected { background:#e74c3c; color:white; }
        @media (max-width:768px) { th,td { font-size:0.75rem; } .admin-header { flex-direction:column; gap:10px; } }
    </style>
</head>
<body>
<div class="admin-header">
    <div class="logo"><h2>⚙️ Dashboard Admin HMPTM</h2><small>Kelola Pendaftar</small></div>
    <div><a href="user.php" class="btn-outline" style="margin-right:10px;"><i class="fas fa-globe"></i> Halaman Publik</a><a href="?logout=1" class="btn-outline"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
</div>
<div class="container">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:1rem;">
            <h2><i class="fas fa-users"></i> Data Pendaftar</h2>
            <button id="openAddModal" class="btn-primary"><i class="fas fa-plus"></i> Tambah User</button>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead><tr><th>ID</th><th>Email</th><th>Username</th><th>Jurusan</th><th>Fakultas</th><th>Alamat</th><th>JK</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($users)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['jurusan']) ?></td>
                        <td><?= htmlspecialchars($row['fakultas']) ?></td>
                        <td><?= htmlspecialchars(substr($row['alamat'],0,30)) ?></td>
                        <td><?= $row['jenis_kelamin'] ?></td>
                        <td><span class="badge badge-<?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                        <td class="action-icons">
                            <i class="fas fa-edit edit-icon" onclick="editUser(<?= htmlspecialchars(json_encode($row)) ?>)"></i>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash-alt delete-icon"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal CRUD -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3 id="modalTitle">Tambah User</h3>
        <form method="POST">
            <input type="hidden" name="id" id="userId">
            <div class="form-group"><label>Email</label><input type="email" name="email" id="userEmail" required></div>
            <div class="form-group"><label>Username</label><input type="text" name="username" id="userUsername" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" id="userPassword" placeholder="(isi jika baru/ganti)"></div>
            <div class="form-group"><label>Jurusan</label><input type="text" name="jurusan" id="userJurusan" value="Teknik Mesin" required></div>
            <div class="form-group"><label>Fakultas</label><input type="text" name="fakultas" id="userFakultas" value="Fakultas Sains dan Teknologi" required></div>
            <div class="form-group"><label>Alamat</label><textarea name="alamat" id="userAlamat" rows="2"></textarea></div>
            <div class="form-group"><label>Jenis Kelamin</label>
                <select name="jenis_kelamin" id="userGender">
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="form-group"><label>Status</label>
                <select name="status" id="userStatus">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <button type="submit" name="save_user" class="btn-primary">Simpan</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('userModal');
    const closeModal = document.querySelector('.close-modal');
    const openAddBtn = document.getElementById('openAddModal');
    
    function editUser(user) {
        document.getElementById('modalTitle').innerText = 'Edit User';
        document.getElementById('userId').value = user.id;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userUsername').value = user.username;
        document.getElementById('userPassword').value = '';
        document.getElementById('userJurusan').value = user.jurusan;
        document.getElementById('userFakultas').value = user.fakultas;
        document.getElementById('userAlamat').value = user.alamat;
        document.getElementById('userGender').value = user.jenis_kelamin;
        document.getElementById('userStatus').value = user.status;
        modal.style.display = 'flex';
    }
    
    openAddBtn.onclick = () => {
        document.getElementById('modalTitle').innerText = 'Tambah User';
        document.getElementById('userId').value = '0';
        document.getElementById('userEmail').value = '';
        document.getElementById('userUsername').value = '';
        document.getElementById('userPassword').value = '';
        document.getElementById('userJurusan').value = 'Teknik Mesin';
        document.getElementById('userFakultas').value = 'Fakultas Sains dan Teknologi';
        document.getElementById('userAlamat').value = '';
        document.getElementById('userGender').value = 'Laki-laki';
        document.getElementById('userStatus').value = 'pending';
        modal.style.display = 'flex';
    };
    
    closeModal.onclick = () => modal.style.display = 'none';
    window.onclick = (e) => { if(e.target === modal) modal.style.display = 'none'; };
</script>
</body>
</html>