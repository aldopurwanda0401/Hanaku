<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   // Atur user_id, misalnya dari database atau sesuai dengan pengguna saat ini
   $_SESSION['user_id'] = generateUniqueUserId(); // fungsi ini untuk menghasilkan ID unik
}

$user_id = $_SESSION['cart'];

function generateUniqueUserId() {
   // Tambahkan prefix atau manipulasi sesuai kebutuhan
   $prefix = 'user_';
   $unique_id = $prefix . uniqid();

   return $unique_id;
}

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';
   $_SESSION['cart'] =$user_id;
   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'Keranjang Kamu Kosong';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'Pesanan Sudah Dilakukan!';
   }else{
      $_SESSION['cart']=$user_id;
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'Pesanan Berhasil Dilakukan!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title> <!-- Favicons -->
 <link href="uploaded_img/logo.jpeg" rel="icon" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= 'Rp'.$fetch_cart_items['price'].',- x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">Keranjang Kamu Kosong!</p>';
   }
   ?>
   <div class="grand-total">Harga Semua Produk : <span>Rp<?= $cart_grand_total; ?>,-</span></div>
</section>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>Ambil Pesanan Kamu</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Nama :</span>
            <input type="text" name="name" placeholder="Masukkan Nama Anda" class="box" required>
         </div>
         <div class="inputBox">
            <span>Nomer HP :</span>
            <input type="number" name="number" placeholder="Masukkan Nomer HP" class="box" required>
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" placeholder="Masukkan Alamat Email" class="box" required>
         </div>
         <div class="inputBox">
            <span>Metode Pengambilan :</span>
            <input type="text" name="method" value="Ambil Ke Toko" placeholder="Ambil Ke Toko" class="box" readonly>
         </div>
         <div class="inputBox">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4055919.4152055946!2d103.86372198750003!3d-6.9074819999999955!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f172ec0d2b38b%3A0x8fd2aed8de54dcf5!2sHana%20Snack!5e0!3m2!1sid!2sid!4v1703702690246!5m2!1sid!2sid" height="400" style="border:0;" class="box" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
         </div>
      </div>
      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="Pesan Sekarang">

   </form>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>