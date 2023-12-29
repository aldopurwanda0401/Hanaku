<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

// session_start();

// if (!isset($_SESSION['user_id'])) {
//    // Atur user_id, misalnya dari database atau sesuai dengan pengguna saat ini
//    $_SESSION['user_id'] = generateUniqueUserId(); // fungsi ini untuk menghasilkan ID unik
// }

// $user_id = $_SESSION['user_id'];

// function generateUniqueUserId() {
//    // Tambahkan prefix atau manipulasi sesuai kebutuhan
//    $prefix = 'user_';
//    $unique_id = $prefix . uniqid();

//    return $unique_id;
// }

?>

<header class="header">

   <div class="flex">

      <a href="index.php" class="logo"><img src="uploaded_img/logo.jpeg" style="width: 30px;">Hanaku<span>.</span></a>

      <nav class="navbar">
         <a href="index.php">Beranda</a>
         <a href="shop.php">Belanja</a>
         <a href="orders.php">Pesanan</a>
         <a href="about.php">Tentang</a>
         <a href="contact.php">Testimoni</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php" class="fas fa-search"></a>
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
         ?>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
      </div>

   </div>

</header>