<?php
// стартираме
session_start();
 

// Проверява дали потребителя е логнат, ако да го праща към началната страница
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// вмъкваме
require_once "connect.php";
 
// Стартираме винаги с празни полета
$email = $password = "";
$email_err = $password_err = "";
 
// Правим Пост заявка
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // проверяваме дали имейла е празен
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // проверяваме дали паролата е празна
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // валидираме
    if(empty($email_err) && empty($password_err)){
        // взимаме от таблицата
        $sql = "SELECT u.id, u.email, u.password , r.name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?";
        
        if($stmt = $connection->prepare($sql)){
            
            $stmt->bind_param("s", $param_email);
            
            // сетваме параметрите
            $param_email = $email;
            
            if($stmt->execute()){
                // запазваме резултата
                $stmt->store_result();
                
                // проверяваме дали имейла съществува, ако да проверяваме паролата
                // Check if email exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    
                    $stmt->bind_result($id, $email, $hashed_password, $role);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Паролата е правилна и стартираме
                            session_start();
                            
                            // запазваме ги в променливи
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["role"] = $role; 
                                              
                            // Изпращаме потребителя на welcome page
                            header("location: index.php");
                        } else{
                            // ако паролата е грешна, дава грешка
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // ако имейла е грешен, дава грешка
                    $email_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            
            $stmt->close();
        }
    }
    

    $connection->close();
}
?>
<?php require('templates/header.php') ?>

    <div class="wrapper mx-auto">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
           
        </form>
    </div>    

<?php require('templates/footer.php') ?>