<?php
  include "koneksi.php";
  date_default_timezone_set("Asia/Jakarta");
  if(isset($_POST['create'])){
    mysqli_query($koneksi, "INSERT INTO `history` (`id`, `nama_lahan`, `luas_lahan`, `debit_air`, `created_at`) VALUES (NULL, '".$_POST['nama_lahan']."', '".$_POST['luas_lahan']."', '".$_POST['debit_air']."', current_timestamp());");
    header("Location: /");
  }
  if(isset($_GET['delete'])){
    mysqli_query($koneksi, "DELETE FROM `history` WHERE `history`.`id` = ".$_GET['delete']);
    header("Location: /");
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
                    <a class="nav-link me-3 active" href="/">Add Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="/history.php">History</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-5 mt-2">
            <div class="col">
                <h2 style="color:rgba(135,103,78,1);"><b>Add Data</b></h2>
            </div>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lahan</label>
                <input type="text" class="form-control" name="nama_lahan" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Luas Lahan (Ha)</label>
                <input type="number" step="0.01" class="form-control" name="luas_lahan" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Debit Air (L/detik)</label>
                <input type="number" step="0.01"  class="form-control" name="debit_air" required>
            </div>
            <button type="submit" class="btn btn-primary" name="create" value="submit">Submit</button>
        </form>

    </div>
    
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {  
            $('#myTable').DataTable({
                // scrollX:true,
            });
        } );
    </script>
  </body>
</html>