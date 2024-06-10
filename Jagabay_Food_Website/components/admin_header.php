<?php
include '../components/connect.php';

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="dashboard.php">home</a>
         <a href="products.php">products</a>
         <a href="placed_orders.php">orders</a>
         <a href="admin_accounts.php">admins</a>
         <a href="users_accounts.php">users</a>
         <a href="messages.php">messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>
      

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->bind_param("i", $admin_id);
            $select_profile->execute();
            $result = $select_profile->get_result();
            $fetch_profile = $result->fetch_assoc();
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
         <?php
   
         if(is_null($admin_id)){
            echo "<div class='flex-btn'>
                  <a href='admin_login.php' class='option-btn'>login</a>
                  <a href='register_admin.php' class='option-btn'>register</a>
                  </div>";
         }
         ?>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
      </div>

   </section>

</header>
