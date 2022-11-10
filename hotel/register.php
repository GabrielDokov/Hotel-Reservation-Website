<?php
// добаваме файла
require_once "connect.php";
 
// правим променливите да са празни
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

 
// правим ПОСТ заявка
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = trim($_POST["name"]);
    
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{

        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($connection, $sql)){    

            $param_email = trim($_POST["email"]);
            mysqli_stmt_bind_param($stmt, "s", $param_email);           
            
            if(mysqli_stmt_execute($stmt)){

                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already registered.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    //проверявяма паролата
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Проверяваме пак паролата
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // проверяваме за грешки преди да ги сложим в базата
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){

        $sql_role_id = "SELECT id FROM roles WHERE name = 'Customer'";
        
        if($stmt = mysqli_prepare($connection, $sql_role_id)){              
            
            if(mysqli_stmt_execute($stmt)){
               $result = mysqli_stmt_get_result($stmt);
               $row = mysqli_fetch_assoc($result);
               $role_id = $row['id'];
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }

        $sql_register = "INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $sql_register);

        if($stmt){
            
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            $param_role_id = $role_id;

            mysqli_stmt_bind_param($stmt, 'ssss', $param_name, $param_email, $param_password, $param_role_id);
            
            if(mysqli_stmt_execute($stmt)){
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.\n";
                print_r($stmt->error_list);
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($connection);
}

?>
 
<?php require('templates/header.php') ?>

    <div class="wrapper mx-auto">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="">
            </div>    
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" required>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" required>
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    

<?php require('templates/footer.php') ?>
