

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo"><b>Jagabay 😋</b></a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="about.php">About</a>
         <a href="menu.php">Menu</a>
         <a href="orders.php">Orders</a>
         <a href="contact.php">Contact</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->bind_param('i', $user_id);
            $count_cart_items->execute();
            $count_cart_items->store_result();
            $total_cart_items = $count_cart_items->num_rows;
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->bind_param('i', $user_id);
            $select_profile->execute();
            $result = $select_profile->get_result();
            if($result->num_rows > 0){
               $fetch_profile = $result->fetch_assoc();
         ?>
         <p class="name"><?= htmlspecialchars($fetch_profile['name']); ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         </div>
         <p class="account">
            <a href="login.php">login</a> or
            <a href="register.php">register</a>
         </p> 
         <?php
            }else{
         ?>
            <p class="name">please login first!</p>
            <a href="login.php" class="btn">login</a>
         <?php
          }
         $select_profile->close();
         ?>
      </div>

   </section>

</header>
