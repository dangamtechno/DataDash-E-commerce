<?php 
require './include/db.php';
header('Access-Control-Allow-Origin: *');
?>
<link rel = "stylesheet" href="../frontend/style.css">
<h1> Search page</h1>
<div class="container">
<?php
if(isset($_POST['submit-search'])){
    echo $_POST['submit-search'];
    $search = mysqli_escape_string($conn,$_POST['search']);
    echo $search;
    $sql="SELECT * FROM product WHERE name LIKE '%$search%'" ;
    $result = mysqli_query($conn,$sql);
    $queryResult = mysqli_num_rows($result);
    if($queryResult > 0){
        while($row = mysqli_fetch_assoc($result)){
            echo "<div class = card >
             <div class = card-img>
             <img src = http://localhost:8081".$row['image'].">
             </div>
             <h3>".$row['name']."<h3>
             <p>".$row['price']."</p>
             </div>   ";
        }
    }
    else{
        echo "nothing matches";
    }
}
?>
</div>   



