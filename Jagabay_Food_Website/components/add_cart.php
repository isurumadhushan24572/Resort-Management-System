<?php

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      // Prepare and execute the query to check if the item is already in the cart
      $check_cart_numbers = $conn->prepare("SELECT * FROM cart WHERE name = ? AND user_id = ?");
      $check_cart_numbers->bind_param("si", $name, $user_id);
      $check_cart_numbers->execute();
      $result = $check_cart_numbers->get_result();

      if($result->num_rows > 0){
         $message[] = 'already added to cart!';
      }else{
         // Prepare and execute the query to insert the item into the cart
         $insert_cart = $conn->prepare("INSERT INTO cart(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->bind_param("isssis", $user_id, $pid, $name, $price, $qty, $image);
         $insert_cart->execute();
         $message[] = 'added to cart!';
      }

   }

}
?>