<?php
session_start();

require '../include/database_config.php';

header('Access-Control-Allow-Origin: *');

?>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel = "stylesheet" href="../../frontend/css/style.css">
   <script src="https://kit.fontawesome.com/d0ce752c6a.js"
        crossorigin="anonymous">
   </script>
   <title>Product Search</title>
</head>
<header>
    <div class = "heading">
       <div class="left-heading">
            <div class="logo">
                <a href ="../../frontend/pages/homepage.php">
                   <img id="logo" src="../../frontend/images/DataDash.png" alt=""/>
                </a>
            </div>
            <div class="search-bar">
                <form class="search" action="http://localhost:8081/backend/utils/search_catalog.php" method="POST">
                    <label>
                        <input type="search" name ="search" placeholder="search...">
                    </label>
                    <input type="submit" name="submit-search">                        
                </form>
            </div>
        </div>
        <div class="right-heading">
            <div class="login-status">
                <div class="login" title="login"><i class="fas fa-sign-in-alt fa-2x"></i></div>
                    <div class="register" title="register"><i class="fas fa-user-times fa-2x"></i></div>
                    <div class="logout" title = "log out"><i class="fas fa-sign-out-alt fa-2x"></i></div>
                        <div class="logged-user">
                            <i class="fas fa-user-check fa-2x"></i>
                            <span class="username">username</span>
                        </div>
                    </div>
                <div class="cart"><i class="fas fa-shopping-cart fa-2x"></i></div>
        </div>
    </div>
</header>
<main>
    <div class="catalog">
        <?php
        if(isset($_POST['submit-search'])){
            $search = mysqli_escape_string($conn,$_POST['search']);
            $sql="SELECT * FROM product join category on product.category_id = category.id WHERE product.name LIKE '%$search%'" ;
            $result = mysqli_query($conn,$sql);    
            $queryResult = mysqli_num_rows($result);
            if($queryResult > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo "<div class = card >
                    <div class = card-img>
                    <img src = http://localhost:8081".$row['image'].">
                    </div>
                    <div class = card-desc>
                    <p>Name: ".$row['name']."</p>
                    <p>Price: ".$row['price']."</p>
                    <p>Description ".$row['description']."</p>
                    <p>Category: ".$row['Name']."</p>
                    </div>
                    </div>   ";
                }
            }
            else{
                echo "nothing matches";
            }
        }
        $conn->close();

        ?>
    </div>
</main>   
<footer>
    <div class="social-media">
        <ul>
        <li><i class="fab fa-facebook fa-1.5x"></i></i></li>
        <li><i class="fab fa-instagram fa-1.5x"></i></li>
        <li><i class="fab fa-youtube fa-1.5x"></i></li>
        <li><i class="fab fa-twitter fa-1.5x"></i></li>
        <li><i class="fab fa-pinterest fa-1.5x"></i></li>
        </ul>
    </div>
    <div class="general-info">
        <div class="help">
            <h3>Help</h3>
        <ul>
            <li>Frequently asked Questions</li>
            <li>Delivery Information</li>
            <li>Returns</li>
            <li>Customer Service</li>
        </ul>
        </div>
        <div class="location"></div>
        <div class="legal">
            <h3>Privacy & legal</h3>
            <ul>
                <li>Cookies & Privacy</li>
                <li>Terms & Conditions</li>
            </ul>
        </div>
    </div>
    </footer>
</html>
