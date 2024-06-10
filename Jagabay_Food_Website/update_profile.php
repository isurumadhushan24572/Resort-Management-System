<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);

   // -- Create stored procedure for updating user name
   // CREATE PROCEDURE update_user_name
   //     @p_name NVARCHAR(255),
   //     @p_user_id INT
   // AS
   // BEGIN
   //     UPDATE users SET name = @p_name WHERE id = @p_user_id;
   // END
   // GO
   
   // -- Create stored procedure for updating user email
   // CREATE PROCEDURE update_user_email
   //     @p_email NVARCHAR(255),
   //     @p_user_id INT,
   //     @email_exists BIT OUTPUT
   // AS
   // BEGIN
   //     DECLARE @count INT;
   //     SELECT @count = COUNT(*) FROM users WHERE email = @p_email;
   //     IF @count > 0
   //     BEGIN



   // Placeholder for the stored procedure creation (it should be created in the database directly)
   $sql_trig = "
   CREATE PROCEDURE update_user_email
      @p_email NVARCHAR(255),
      @p_user_id INT,
      @email_exists BIT OUTPUT
   AS
   BEGIN
      DECLARE @count INT;
      SELECT @count = COUNT(*) FROM users WHERE email = @p_email;
      IF @count > 0
      BEGIN";

   // Update name
   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->bind_param('si', $name, $user_id);
      $update_name->execute();
      $update_name->close();
   }

   // Update email
   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
      $select_email->bind_param('s', $email);
      $select_email->execute();
      $select_email->store_result();
      
      if($select_email->num_rows > 0){
         $message[] = 'email already taken!';
      }else{
         // Assuming $sql_trig is already created correctly in the database
         mysqli_query($conn, $sql_trig);
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->bind_param('si', $email, $user_id);
         $update_email->execute();
         $update_email->close();
      }
      $select_email->close();
   }

   // Update number
   if(!empty($number)){
      $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ?");
      $select_number->bind_param('i', $number);
      $select_number->execute();
      $select_number->store_result();
      
      if($select_number->num_rows > 0){
         $message[] = 'number already taken!';
      }else{
         $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
         $update_number->bind_param('ii', $number, $user_id);
         $update_number->execute();
         $update_number->close();
      }
      $select_number->close();
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->bind_param('i', $user_id);
   $select_prev_pass->execute();
   $result = $select_prev_pass->get_result();
   $fetch_prev_pass = $result->fetch_assoc();
   $prev_pass = $fetch_prev_pass['password'];
   $select_prev_pass->close();

   $old_pass = ($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = ($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = ($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->bind_param('si', $confirm_pass, $user_id);
            $update_pass->execute();
            $update_pass->close();
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container update-form">

   <form action="" method="post">
      <h3>update profile</h3>
      <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box" maxlength="50">
      <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" placeholder="<?= $fetch_profile['number']; ?>" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="old_pass" placeholder="enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" placeholder="confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="update now" name="submit" class="btn">
   </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
