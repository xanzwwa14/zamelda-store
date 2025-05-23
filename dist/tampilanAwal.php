<?php
session_start();
include "../config/database.php";
$query = mysqli_query($kon, "SELECT gambarBarang FROM varianbarang LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zamelda Store - Selamat Datang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #393E46;
        }

        .slider-container {
            padding: 60px 40px;
            background: linear-gradient(rgba(135, 123, 84, 0.6), rgba(0, 0, 0, 0.4)), url('pantai.JPEG') no-repeat center center;
            background-size: cover;
            text-align: center;
            position: relative;
            min-height: 100vh;
            color:black;
        }

        .slider-header img.judul-gambar {
            max-width: 350px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 15px;
        }

        .slider-header p {
            max-width: 800px;
            margin: 0 auto 30px;
            font-size: 22px;
            font-weight: 600;
            color: #fff;
            line-height: 1.6;
        }

        .login-button {
            position: absolute;
            top: 30px;
            right: 40px;
            background-color:rgb(66, 55, 41);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: 0.3s ease;
            font-size: 16px;
        }

        .login-button:hover {
            background-color: #e07b00;
        }

        .slider-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .gallery-item {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.05);
        }

        footer {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .social-icons {
            margin-top: 15px;
        }

        .social-icons a {
            color: white;
            font-size: 24px;
            margin: 0 15px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .social-icons a:hover {
            color: #ff8c00;
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<div class="slider-container">
    <div class="slider-header">
        <img src="aplikasi/logo/ADS.png" alt="New Collections" class="judul-gambar">
        <p>Zamelda Official Store menjadikan anda lebih menarik</p>
        <a href="login.php" class="login-button">Login</a>
    </div>
    <div class="slider-gallery">
        <?php while ($data = mysqli_fetch_assoc($query)): ?>
            <img src="barang/gambar/<?php echo htmlspecialchars($data['gambarBarang']); ?>" alt="koleksi" class="gallery-item">
        <?php endwhile; ?>
    </div>
</div>

<!-- Footer dengan ikon sosial media -->
<footer>
    <p>&copy; 2025 Zamelda Official Store. All rights reserved.</p>
    <div class="social-icons">
        <a href="https://www.facebook.com/zamelda" target="_blank" class="fab fa-facebook-f"></a>
        <a href="https://twitter.com/zamelda" target="_blank" class="fab fa-twitter"></a>
        <a href="https://www.instagram.com/zamelda" target="_blank" class="fab fa-instagram"></a>
        <a href="https://www.linkedin.com/company/zamelda" target="_blank" class="fab fa-linkedin-in"></a>
    </div>
</footer>

</body>
</html>