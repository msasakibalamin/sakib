<?php 
require_once("../../includes/config.php");
$url = $_SERVER['REQUEST_URI'];
$urlarray=explode("=",$url);
$id=$urlarray[count($urlarray)-1];
session_start();  
if(!isset($_SESSION["username"]))  
{  
     header("location:../index.php?error");  
}  


// checking if exist

$query = mysqli_query($conn, "SELECT  * FROM saving_deposit WHERE id = '$id'");
if(mysqli_num_rows($query) > 0){

    $sqll = "SELECT * FROM saving_deposit where id='$id'";
    $sth = $conn->query($sqll);
    $result=mysqli_fetch_array($sth);


    $acc_no = $result['acc_no'];

    $client_info = $conn->query("SELECT * FROM client where acc_no='$acc_no'");
    $client_infomation=mysqli_fetch_array($client_info);

    $capital = $conn->query("SELECT * FROM information where id=1");
    $capital_info=mysqli_fetch_array($capital);

}else{
    header("location: ../history/saving_deposit.php?record_not_found");
}


        if(isset($_POST["delete"])){
            $new_saving = $client_infomation['total_saving'] - $result['amount'];
            $new_capital = $capital_info['capital'] - $result['amount'];
            $date = Date("Y-m-d");

            $deleted_amount = $result['amount'];
        
            $sql = "UPDATE client set total_saving = '$new_saving' WHERE acc_no = $acc_no";
            if ($conn->query($sql) === TRUE) {
                $update_capital = "UPDATE information set capital = '$new_capital' where id = 1";
                if ($conn->query($update_capital) === TRUE) {
                $delete_record = "DELETE FROM saving_deposit WHERE id='$id'";
                if ($conn->query($delete_record) === TRUE) {
                    $insert_daily_history = "INSERT into daily_history(date,in_,out_,description) 
                    VALUES ('$date',0,'$deleted_amount','Deleted Savings Deposit Record')";
                     if ($conn->query($insert_daily_history) === TRUE) {
                        header("location: ../history/saving_deposit.php?successfully_deleted");
                     }else{
                        header("location: saving_deposit_delete.php?error");
                     }
                }else{
                    header("location: saving_deposit_delete.php?error");
                }
                }
                else{
                    header("location: saving_deposit_delete.php?error");
                }
            }else{
                header("location: saving_deposit_delete.php?error");
            }
            
        }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete Record - DKBSS</title>
    <link rel="shortcut icon" href="../../image/favicon.png" />
    <link href="../../css/bootstrap.css" rel="stylesheet">
    <link href="../../css/fontawesome.css" rel="stylesheet">
    <script src="../../js/sweetalert.js"></script>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/sweetalert.css">
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">🇩​🇰​🇧​🇸​🇸​</div>
            <div class="list-group list-group-flush">
                <a href="../index" class="unclickable nav-active list-group-item list-group-item-action bg-light">Dashboard</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light ">
                <button class="btn " id="menu-toggle">
                    <i class="fa fa-bars"></i>
                </button>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown" style="padding-right:10px">
                            <button class="btn" onclick="FullScreeen()">
                                <i class="fa fa-arrows-alt fa-lg" style="color:gray"></i>
                            </button>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user fa-lg"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <center>
                                    <p style="color:green;">Welcome Admin</p>
                                </center>
                                <a class="dropdown-item" href="setting">Setting</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="../">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="../history/saving_deposit">Deposit History</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Savings Deposit</li>
  </ol>
</nav>

            <div class="container-fluid">
            <br>
<center><h4 style="color:red">Delete Record</h4></center>
            <br>
            <table class="table table-sm table-bordered table-striped">
  <thead>
    <tr align="center">
      <th scope="col">Id</th>
      <th scope="col">Acc No</th>
      <th scope="col">Name</th>
      <th scope="col">Date</th>
      <th scope="col">Amount</th>
      <th scope="col">Type</th>
    </tr>
  </thead>
  <tbody>
    <tr align="center">
      <th><?php echo $id?></th>
      <td><a href="../client/check_client.php?acc_no=<?php echo $result['acc_no'];?>"><?php echo $result['acc_no'];?></a></td>
      <td><?php echo $client_infomation['name']?></td>
      <td><?php echo $result['date']?></td>
      <td><?php echo $result['amount']?> ৳</td>
      <td><b class="badge badge-secondary">Savings Deposit</b></td>
    </tr>
    </tbody>
    </table>

    <center>
    
        <form method="post">
        <input type="submit" class="btn btn-primary" value="Delete Now" name="delete">
        </form>

    </center>


            </div>

<!-- hidden items -->

</body>
<script src="../../js/jquery.js"></script>
<script src="../../js/bootstrap.js"></script>
<script src="../../js/fontawesome.js"></script>
<script src="../../js/sweetalert.js"></script>
<script src="../../js/main.js"></script>
<script>

window.onload = function() {
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    var url = window.location.toString();
    if (url.includes("error") {
        var name = url.split("=")[1].substring(0, 11);
        swal("Error", "", "error");
    }

}
</script>

</html>
