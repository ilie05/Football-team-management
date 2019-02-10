<!DOCTYPE html>
<html lang="en">
<head>
  <title>BD PROJECT</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<style>
  .navbar-nav {
    float:none;
    margin:0 auto;
    display: block;
    text-align: center;
}
    .navbar-nav > li {
    display: inline-block;
    float:none;
}
li>a{
  width : 240px;
  font-size:  25px;
}
td,th{
  text-align:center;
}
table{
  border-bottom: 1px solid black;
}
</style>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <ul class="nav navbar-nav"> 
      <li ><a href="index.php?section=view">View Database</a></li> 
      <li ><a href="index.php?section=modify">Modify</a></li>
      <li><a href="index.php?section=add">Add record</a></li>
      <li><a href="index.php?section=delete">Delete</a></li>
    </ul>
    
  </div>
</nav>
  <div class="container">
<?php
    $conn=@oci_connect("system","oracle","db/xe");
  if(!$conn){
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
  if(isset($_GET['section'])){
      if($_GET['section']=="view")
        include 'view.php';
      elseif($_GET['section']=="add")
        include 'add.php';
      elseif($_GET['section']=="delete")
        include 'delete.php';
      else if($_GET['section']=="modify")
        include 'modify.php';
    }else{
      include 'view.php';
    }
?>

</body>
</html>
