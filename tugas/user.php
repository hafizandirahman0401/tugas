<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['daftar'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' OR username='$username'");

    if (mysqli_num_rows($check) > 0) {
        $msg = "<div class='alert error'>Email atau Username sudah terdaftar!</div>";
    } else {
        $query = "INSERT INTO users (email, username, password, jurusan, fakultas, alamat, jenis_kelamin, status)
                  VALUES ('$email','$username','$password','$jurusan','$fakultas','$alamat','$jenis_kelamin','pending')";

        if (mysqli_query($conn, $query)) {
            $msg = "<div class='alert success'>Pendaftaran berhasil! Menunggu verifikasi admin.</div>";
        } else {
            $msg = "<div class='alert error'>Terjadi kesalahan saat menyimpan data.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HMPTM UNUGIRI</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI,sans-serif}
html{scroll-behavior:smooth}
body{background:#f5f7f6;color:#222}
header{position:fixed;top:0;width:100%;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.08);z-index:999}
.nav{max-width:1200px;margin:auto;display:flex;justify-content:space-between;align-items:center;padding:15px 20px}
.logo{font-weight:700;color:#0a6b3f}
.nav ul{display:flex;list-style:none;gap:20px}
.nav a{text-decoration:none;color:#222;font-weight:600}
.btn{background:#0a6b3f;color:#fff;padding:10px 18px;border-radius:30px;text-decoration:none}
.hero{height:100vh;background:linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?q=80&w=1600&auto=format&fit=crop') center/cover;display:flex;align-items:center;justify-content:center;text-align:center;color:#fff}
.hero h1{font-size:56px}
.hero p{max-width:700px;margin:20px auto}
section{padding:90px 20px}
.container{max-width:1200px;margin:auto}
.title{text-align:center;margin-bottom:40px;color:#0a6b3f}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px}
.card{background:#fff;padding:25px;border-radius:18px;box-shadow:0 10px 20px rgba(0,0,0,.06)}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px}
.stat{text-align:center;background:#0a6b3f;color:#fff;padding:25px;border-radius:18px}
.gallery{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:15px}
.gallery div{height:220px;background:#ddd;border-radius:16px}
form{background:#fff;padding:25px;border-radius:18px;box-shadow:0 10px 20px rgba(0,0,0,.06)}
input,textarea,select{width:100%;padding:12px;margin-top:8px;margin-bottom:15px;border:1px solid #ccc;border-radius:10px}
button{background:#0a6b3f;color:#fff;border:none;padding:12px 20px;border-radius:10px;cursor:pointer}
.alert{padding:12px;border-radius:10px;margin-bottom:15px}
.success{background:#d4edda}
.error{background:#f8d7da}
footer{background:#082f1e;color:#fff;text-align:center;padding:30px}
@media(max-width:768px){
.nav{flex-direction:column;gap:10px}
.nav ul{flex-wrap:wrap;justify-content:center}
.hero h1{font-size:36px}
}
</style>
</head>
<body>

<header>
<div class="nav">
<div class="logo">HMPTM UNUGIRI</div>
<ul>
<li><a href="#home">Beranda</a></li>
<li><a href="#tentang">Tentang</a></li>
<li><a href="#proker">Program Kerja</a></li>
<li><a href="#galeri">Galeri</a></li>
<li><a href="#daftar">Pendaftaran</a></li>
<li><a href="admin.php">Admin</a></li>
</ul>
</div>
</header>

<section class="hero" id="home">
<div>
<h1>HMPTM UNUGIRI</h1>
<p>Wadah mahasiswa Teknik Mesin untuk berkembang, berinovasi, dan berkontribusi bagi masyarakat.</p>
<a href="#daftar" class="btn">Daftar Sekarang</a>
</div>
</section>

<section id="tentang">
<div class="container">
<h2 class="title">Tentang HMPTM</h2>
<div class="cards">
<div class="card"><h3>Profil</h3><p>Organisasi mahasiswa Teknik Mesin UNUGIRI yang aktif dalam pengembangan akademik dan non akademik.</p></div>
<div class="card"><h3>Visi</h3><p>Menjadi organisasi yang unggul, inovatif, dan profesional.</p></div>
<div class="card"><h3>Misi</h3><p>Meningkatkan kualitas anggota melalui pelatihan, seminar, dan pengabdian masyarakat.</p></div>
</div>
</div>
</section>

<section>
<div class="container">
<h2 class="title">Statistik Organisasi</h2>
<div class="stats">
<div class="stat"><h2>150+</h2><p>Anggota</p></div>
<div class="stat"><h2>25+</h2><p>Kegiatan</p></div>
<div class="stat"><h2>10+</h2><p>Mitra</p></div>
<div class="stat"><h2>5</h2><p>Tahun</p></div>
</div>
</div>
</section>

<section id="proker">
<div class="container">
<h2 class="title">Program Kerja</h2>
<div class="cards">
<div class="card"><i class="fas fa-cogs"></i> Workshop CAD</div>
<div class="card"><i class="fas fa-industry"></i> Kunjungan Industri</div>
<div class="card"><i class="fas fa-trophy"></i> Kompetisi Inovasi</div>
<div class="card"><i class="fas fa-users"></i> Pengabdian Masyarakat</div>
</div>
</div>
</section>

<section id="galeri">
<div class="container">
<h2 class="title">Galeri Kegiatan</h2>
<div class="gallery">
<div></div><div></div><div></div><div></div><div></div><div></div>
</div>
</div>
</section>

<section id="daftar">
<div class="container">
<h2 class="title">Pendaftaran Anggota</h2>
<form method="POST">
<?php if(isset($msg)) echo $msg; ?>
<label>Email</label>
<input type="email" name="email" required>

<label>Username</label>
<input type="text" name="username" required>

<label>Password</label>
<input type="password" name="password" required>

<label>Jurusan</label>
<input type="text" name="jurusan" value="Teknik Mesin" required>

<label>Fakultas</label>
<input type="text" name="fakultas" value="Fakultas Sains dan Teknologi" required>

<label>Alamat</label>
<textarea name="alamat"></textarea>

<label>Jenis Kelamin</label>
<select name="jenis_kelamin">
<option>Laki-laki</option>
<option>Perempuan</option>
</select>

<button type="submit" name="daftar">Kirim Pendaftaran</button>
</form>
</div>
</section>

<footer>
<h3>HMPTM UNUGIRI</h3>
<p>Universitas Nahdlatul Ulama Sunan Giri Bojonegoro</p>
</footer>

</body>
</html>
