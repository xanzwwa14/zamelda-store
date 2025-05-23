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
            color: #b6afac;
            background-color: #b6afac;
        }

        .slider-container {
            padding: 60px 40px; 
            min-height: 100vh;
            text-align: center;
            color: #fff;
}

        .slider-header img.judul-gambar {
            max-width: 340px;
            height: auto;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        .slider-header {
            max-width: fit-content;
            margin: 0 auto;
            text-align: center:
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
            background-color:rgb(103, 101, 99);
        }

        .slider-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            padding: 0 20px;
        }

        .gallery-item {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .slider-gallery .gallery-item {
            width: 100%;
            max-width: 200px;   
            height: 180px;
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
            color:rgb(84, 83, 82);
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