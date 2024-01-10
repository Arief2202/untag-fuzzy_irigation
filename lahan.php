<?php
  include "koneksi.php";
  date_default_timezone_set("Asia/Jakarta");
  if(isset($_POST['create'])){
    mysqli_query($koneksi, "INSERT INTO `lahan` (`id`, `nama_lahan`, `luas_lahan`) VALUES (NULL, '".$_POST['nama_lahan']."', '".$_POST['luas_lahan']."');");
    header("Location: /lahan.php");
  }
  if(isset($_GET['delete'])){
    mysqli_query($koneksi, "DELETE FROM `lahan` WHERE `lahan`.`id` = ".$_GET['delete']);
    header("Location: /lahan.php");
  }
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fuzzy Irigation</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/jquery.dataTables.css" rel="stylesheet">
  
    <style>
      .center{
        text-align: center;
      }
      .nav-link .active b{
        color: white;
      }
    </style>
  </head>
  <body style="background-color: rgba(245,244,242,1);">
    <nav class="navbar navbar-expand-lg" style="background-color: rgba(245,244,242,1); font-size:26px;">
        <div class="container-xl" style="border-bottom-style: solid;border-bottom-color: rgba(135,103,78,1); width:100%;">
            <a class="navbar-brand" href="#" style="font-size:30px;">Fuzzy Irigation System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link me-3" href="/">Add Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3 active" href="/lahan.php">Lahan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="/history.php">History</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-5 mt-3">
            <div class="col">
                <h2 style="color:rgba(135,103,78,1);"><b>Lahan</b></h2>
            </div>
            <div class="col d-flex justify-content-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mymodal">Tambahkan Lahan</button>
            </div>
        </div>
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lahan</th>
                    <th>Luas Lahan</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $result = mysqli_query($koneksi, "SELECT * FROM lahan");
                $index = 1;
                while($data = mysqli_fetch_object($result)){
                ?>
                <tr>
                    <td><?=$index++?></td>
                    <td><?=$data->nama_lahan?></td>
                    <td><?=$data->luas_lahan?></td>
                    <td><a class="btn btn-danger" href="/lahan.php?delete=<?=$data->id?>">Delete</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="mymodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id = "card_area">
                    <div class="mb-3">
                        <label class="form-label">Nama Lahan</label>
                        <input type="text" class="form-control" name="nama_lahan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Luas Lahan (Ha)</label>
                        <input type="number" step="0.01" class="form-control" name="luas_lahan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="create" value="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
    </div>


    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {  
            $('#myTable').DataTable({
                // scrollX:true,
                "pageLength": 25,
            });
        } );
    </script>
  </body>
</html>