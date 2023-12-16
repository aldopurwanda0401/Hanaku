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


if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name,$user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
      $_SESSION['cart'] = $check_cart_numbers;
   }else{

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>Hanaku Store : Cemilan Ubi Ungu</span>
         <h3>Kami Menjual Beberapa Cemilan Khas Kuningan Khususnya Keripik Gemblong Ubi Ungu</h3>
         <p>Gemblong Ubi Ungu adalah makanan ringan yang terbuat dari bahan dasar ubi jalar warna ungu, berbentuk bulat tipis kering dan renyah, rasanya manis, tampilannya menarik terutama dari sisi warna ungu yang terlihat mencolok. Produk ini home industri, sehingga rasa dan tekstur terjamin. Ubi jalar ungu ini banyak manfaatnya bagi tubuh kita, selain untuk antioksidan, ubi mempunyai kadar gizi tinggi.</p>         
         <a href="about.php" class="btn">about us</a>
      </div>

   </section>

</div>

<section class="home-category">

   <h1 class="title">shop by category</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/cat-1.png" alt="">
         <h3>Gemblong Ubi Ungu</h3>
         <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
         <a href="category.php?category=Gemblong Ubi Ungu" class="btn">Gemblong Ubi Ungu</a>
      </div>

      <div class="box">
         <img src="images/cat-2.png" alt="">
         <h3>Gemblong Ubi</h3>
         <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
         <a href="category.php?category=Gemblong Ubi" class="btn">Gemblong Ubi</a>
      </div>

      <div class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Lainnya</h3>
         <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
         <a href="category.php?category=Lainnya" class="btn">Lainnya</a>
      </div>
   </div>

</section>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 8");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <p><font size="2rem"><?= $fetch_products['details'] ?></font></p>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>







<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>