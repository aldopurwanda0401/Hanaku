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

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'already sent message!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `message`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'Testimoni berhasil dikirim, Terima Kasih :)';

   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Testimoni</title> <!-- Favicons -->
 <link href="uploaded_img/logo.jpeg" rel="icon" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="contact">

   <h1 class="title">Berikan Testimoni Ke Kami</h1>

   <form action="" method="POST">
      <input type="text" name="name" class="box" required placeholder="Masukkan Nama Kamu">
      <input type="email" name="email" class="box" required placeholder="Masukkan Email Kamu">
      <input type="number" name="number" min="0" class="box" required placeholder="Masukkan Nomer Telpon">
      <textarea name="msg" class="box" required placeholder="Masukkan Testimoni Kamu" cols="30" rows="10"></textarea>
      <input type="submit" value="Kirim Testimoni" class="btn" name="send">
   </form>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>