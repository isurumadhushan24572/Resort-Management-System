<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

   <div class="box">
      <h3>welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">update profile</a>
   </div>

   <div class="box">
   <?php
      // Assuming $conn is your database connection

      // Prepare the SQL query to call the function
      $query = "SELECT get_total_pending_orders() AS total_pendings";
      $result = $conn->query($query);

      if ($result) {
         $row = $result->fetch_assoc();
         $total_pendings = $row['total_pendings'];
         
      } else {
         $total_pendings = 0; // Default value in case of error
         echo "Query Error: " . $conn->error; // Debugging line
      }
   ?>
      <h3><span>$</span><?= number_format($total_pendings, 2); ?><span>/-</span></h3>
      <p>Amount pendings</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
   <?php
      // Assuming $conn is your database connection

      // Prepare the SQL query to call the function
      $query = "SELECT get_total_completed_orders() AS total_completes";
      $result = $conn->query($query);

      if ($result) {
         $row = $result->fetch_assoc();
         $total_completes = $row['total_completes'];
      } else {
         $total_completes = 0; // Default value in case of error
         echo "Query Error: " . $conn->error; // Debugging line
      }
   ?>
      <h3><span>$</span><?= number_format($total_completes, 2); ?><span>/-</span></h3>
      <p>Amount completes</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
   <?php
      // Assuming $conn is your database connection

      // Prepare the SQL query to call the view
      $query = "SELECT total_completes FROM view_total_completed_orders";
      $result = $conn->query($query);

      if ($result) {
         $row = $result->fetch_assoc();
         $total_completes = $row['total_completes'];
      } else {
         $total_completes = 0; // Default value in case of error
      }
   ?>
      <h3><span></span><?=$total_completes; ?><span></span></h3>
      <p>total completes</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
   <?php
      // Assuming $conn is your database connection

      // Prepare the SQL query to call the view
      $query = "SELECT total_pending_orders FROM view_pending_orders_count";
      $result = $conn->query($query);

      if ($result) {
         $row = $result->fetch_assoc();
         $total_pending_orders = $row['total_pending_orders'];
      } else {
         $total_pending_orders = 0; // Default value in case of error
      }
   ?>
   <h3><span></span><?= $total_pending_orders; ?><span></span></h3>
   <p>total pending orders</p>
   <a href="placed_orders.php" class="btn">see orders</a>
</div>

   <div class="box">
      <?php
         $select_orders = $conn->query("SELECT * FROM orders");
         $numbers_of_orders = $select_orders->num_rows;
      ?>
      <h3><?= $numbers_of_orders; ?></h3>
      <p>total orders</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
      <?php
         $select_products = $conn->query("SELECT * FROM products");
         $numbers_of_products = $select_products->num_rows;
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>products added</p>
      <a href="products.php" class="btn">see products</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->query("SELECT * FROM users");
         $numbers_of_users = $select_users->num_rows;
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>users accounts</p>
      <a href="users_accounts.php" class="btn">see users</a>
   </div>

   <div class="box">
      <?php
         $select_admins = $conn->query("SELECT * FROM admin");
         $numbers_of_admins = $select_admins->num_rows;
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>admins</p>
      <a href="admin_accounts.php" class="btn">see admins</a>
   </div>

   <div class="box">
      <?php
         $select_messages = $conn->query("SELECT * FROM messages");
         $numbers_of_messages = $select_messages->num_rows;
      ?>
      <h3><?= $numbers_of_messages; ?></h3>
      <p>new messages</p>
      <a href="messages.php" class="btn">see messages</a>
   </div>

   </div>

</section>

<!-- admin dashboard section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
