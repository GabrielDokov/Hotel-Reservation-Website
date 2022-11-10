    <?php
    // стартираме
    session_start();
    
    // освобождаваме променливите
    $_SESSION = array();
    
    // премахваме
    session_destroy();
    
    // изпращаме на welcome page
    header("location: login.php");
    exit;
    ?>