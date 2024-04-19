<?php 
   require("connection.php");
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\Exception;

   function sendMail($email,$reset_token)
   {
      require("PHPMailer/PHPMailer.php");
      require("PHPMailer/SMTP.php");
      require("PHPMailer/Exception.php");

      $mail = new PHPMailer(true);

      try {
         $mail->isSMTP();                                            //Send using SMTP
         $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
         $mail->Username   = 'ft.budget.buddy@gmail.com';                     //SMTP username
         $mail->Password   = 'krlk rkhy wzyu prjl';                               //SMTP password
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
         $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
     
         $mail->setFrom('ft.budget.buddy@gmail.com', 'BudgetBuddy');
         $mail->addAddress($email);     //Add a recipient
         

         $mail->isHTML(true);                                  //Set email format to HTML
         $mail->Subject = 'Password Reset Link from Budget Buddy';
         $mail->Body    = "We received a reset password request from you! <br>
            Click the link below: <br>
            <a href='http://localhost/login-pass/updatepassword.php?email=$email&reset_token=$reset_token'>
                Reset Password
            </a>";
     
         $mail->send();
         return true;
      } 
      catch (Exception $e) {
         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }

   }

   if(isset($_POST['send-reset-link'])){
      $query="SELECT * FROM `registered_users` WHERE `email`='$_POST[email]'";
      $result=mysqli_query($con,$query);
      if($result){

         if(mysqli_num_rows($result)==1){
            $reset_token=bin2hex(random_bytes(8));
            date_default_timezone_set('America/New_York');
            $date=date("Y-m-d");
            $query="UPDATE `registered_users` SET `resettoken`='$reset_token',`resettokenexpire`='$date' WHERE `email`='$_POST[email]'";
            if(mysqli_query($con,$query) && sendMail($_POST['email'],$reset_token)){
               echo "<script>alert('Password Reset Link sent to Email.'); window.location.href='index.php';</script>";   
            }
            else{
               echo "<script>alert('Server Down Try Again Later.'); window.location.href='index.php';</script>";

            }

         }
         else{
            echo "<script>alert('Email Not Found.'); window.location.href='index.php';</script>";
         }

      }
      else
      {
         echo "<script>alert('Cannot run Query.'); window.location.href='index.php';</script>";
      }
   }

?>