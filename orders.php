<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   // Atur user_id, misalnya dari database atau sesuai dengan pengguna saat ini
   $_SESSION['user_id'] = generateUniqueUserId(); // fungsi ini untuk menghasilkan ID unik
}

$user_id = $_SESSION['user_id'];

function generateUniqueUserId() {
   // Tambahkan prefix atau manipulasi sesuai kebutuhan
   $prefix = 'user_';
   $unique_id = $prefix . uniqid();

   return $unique_id;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesanan</title> <!-- Favicons -->
 <link href="uploaded_img/logo.jpeg" rel="icon" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Pesanan</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <p> Tanggal : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Nama : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Nomer HP : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Metode Pengambilan : <span><?= $fetch_orders['method']; ?></span> </p>
      <p> Pesanan Kamu : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total Harga : <span>Rp<?= $fetch_orders['total_price']; ?>,-</span> </p>
      <p> Status Pesanan : <span style="color:<?php if($fetch_orders['payment_status'] == 'Tertunda'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">Belum Ada Pesanan Yg Dilakukan!</p>';
   }
   ?>

   </div>

</section>









<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>