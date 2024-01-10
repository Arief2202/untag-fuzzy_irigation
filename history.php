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
                    <a class="nav-link me-3" href="/">Add Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="/lahan.php">Lahan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3 active" href="/history.php">History</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-5 mt-2">
            <div class="col">
                <h2 style="color:rgba(135,103,78,1);"><b>History</b></h2>
            </div>
        </div>
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lahan</th>
                    <th>Luas Lahan</th>
                    <th>Debit Air</th>
                    <th>Fuzzy Lahan</th>
                    <th>Fuzzy Air</th>
                    <th>Fuzzy Output (Lama Pengairan)</th>
                    <th>µ Lahan</th>
                    <th>µ Air</th>
                    <th>Output</th>
                    <th>Created At</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $result = mysqli_query($koneksi, "SELECT * FROM history");
                $index = 1;
                while($data = mysqli_fetch_object($result)){
                    $resultLahan = mysqli_query($koneksi, "SELECT * FROM lahan");
                    while($lahans = mysqli_fetch_object($resultLahan)){
                    $u_lahan = 0;
                    $lahan_kecil_c = 25;
                    $lahan_kecil_d = 58;
                    $lahan_sedang_a = 30;
                    $lahan_sedang_b = 58;
                    $lahan_sedang_c = 90;

                    $u_air = 0;
                    $air_kecil_c = 25;
                    $air_kecil_d = 58;
                    $air_sedang_a = 30;
                    $air_sedang_b = 58;
                    $air_sedang_c = 90;

                    $fuzzy_lahan = "";
                    $fuzzy_air = "";
                    $fuzzy_lama_pengairan = "";
                    $nilai_pengairan = 0; //L/dtk
                    $an_air = 0;
                    $an_lahan = 0;
                    $output = 0;

                    $rulebase = array(
                        array(0, 0),
                        array(0, 0),
                        array(0, 0),
                        array(0, 0),
                        array(0, 0),
                        array(0, 0),
                        array(0, 0),
                        array(0, 0),
                        array(0, 0)
                    );
                    $output_rulebase = array(0, 0, 0, 0, 0, 0, 0, 0, 0);

                    if($lahans->luas_lahan <= 30){
                        $fuzzy_lahan = "Kecil";
                        $u_lahan = ($lahan_kecil_d - $lahans->luas_lahan)/($lahan_kecil_d - $lahan_kecil_c);
                        $rulebase[0][0] = $u_lahan;
                        $rulebase[1][0] = $u_lahan;
                        $rulebase[2][0] = $u_lahan;
                    }
                    else if($lahans->luas_lahan > 30 && $lahans->luas_lahan <= 90 ){
                        $fuzzy_lahan = "Normal";
                        $u_lahan = ($lahans->luas_lahan - $lahan_sedang_a)/($lahan_sedang_b - $lahan_sedang_a);
                        $rulebase[3][0] = $u_lahan;
                        $rulebase[4][0] = $u_lahan;
                        $rulebase[5][0] = $u_lahan;
                    }
                    else if($lahans->luas_lahan > 90){
                        $fuzzy_lahan = "Luas";
                        $u_lahan = 0;
                        $rulebase[6][0] = $u_lahan;
                        $rulebase[7][0] = $u_lahan;
                        $rulebase[8][0] = $u_lahan;
                    }

                    if($data->debit_air <= 30){
                        $fuzzy_air = "Kecil";
                        $u_air = ($air_kecil_d - $data->debit_air)/($air_kecil_d - $air_kecil_c);
                        $rulebase[0][1] = $u_air;
                        $rulebase[3][1] = $u_air;
                        $rulebase[6][1] = $u_air;
                    }
                    else if($data->debit_air > 30 && $data->debit_air <= 90 ){
                        $fuzzy_air = "Normal";
                        $u_air = ($data->debit_air - $air_sedang_a)/($air_sedang_b - $air_sedang_a);
                        $rulebase[1][1] = $u_air;
                        $rulebase[4][1] = $u_air;
                        $rulebase[7][1] = $u_air;
                    }
                    else if($data->debit_air > 90){
                        $fuzzy_air = "Besar";
                        $u_air = 0;
                        $rulebase[2][1] = $u_air;
                        $rulebase[5][1] = $u_air;
                        $rulebase[8][1] = $u_air;
                    }

                    if($fuzzy_lahan == "Kecil" && $fuzzy_air == "Kecil") $fuzzy_lama_pengairan = "Normal";
                    else if($fuzzy_lahan == "Kecil" && $fuzzy_air == "Normal") $fuzzy_lama_pengairan = "Sebentar";
                    else if($fuzzy_lahan == "Kecil" && $fuzzy_air == "Besar") $fuzzy_lama_pengairan = "Sebentar";
                    else if($fuzzy_lahan == "Normal" && $fuzzy_air == "Kecil") $fuzzy_lama_pengairan = "Lama";
                    else if($fuzzy_lahan == "Normal" && $fuzzy_air == "Normal") $fuzzy_lama_pengairan = "Normal";
                    else if($fuzzy_lahan == "Normal" && $fuzzy_air == "Besar") $fuzzy_lama_pengairan = "Sebentar";
                    else if($fuzzy_lahan == "Luas" && $fuzzy_air == "Kecil") $fuzzy_lama_pengairan = "Lama";
                    else if($fuzzy_lahan == "Luas" && $fuzzy_air == "Normal") $fuzzy_lama_pengairan = "Normal";
                    else if($fuzzy_lahan == "Luas" && $fuzzy_air == "Besar") $fuzzy_lama_pengairan = "Sebentar";
                    $output_pengairan =  array(60, 35, 35, 100, 60, 35, 100, 60, 35);

                    if($fuzzy_lama_pengairan == "Sebentar") $nilai_pengairan = 35;
                    else if($fuzzy_lama_pengairan == "Normal") $nilai_pengairan = 60;
                    else if($fuzzy_lama_pengairan == "Lama") $nilai_pengairan = 100;
                    
                    for($a=0; $a<9; $a++){
                        if($rulebase[$a][0] == 0 || $rulebase[$a][1] == 0) $output_rulebase[$a] = 0;
                        else {
                            if($rulebase[$a][0] < $rulebase[$a][1]) $output_rulebase[$a] = $rulebase[$a][0];
                            else $output_rulebase[$a] = $rulebase[$a][1]; 
                        }
                    }

                    $WA_UP = 0;
                    $WA_DOWN = 0;
                    for($a=0; $a<9; $a++){
                        $WA_UP = $WA_UP + ($output_rulebase[$a]*$output_pengairan[$a]);
                        $WA_DOWN = $WA_DOWN + ($output_rulebase[$a]);
                    }
                    $WA = $WA_UP / $WA_DOWN;
                ?>
                <tr>
                    <td><?=$index++?></td>
                    <td><?=$lahans->nama_lahan?></td>
                    <td><?=$lahans->luas_lahan?></td>
                    <td><?=$data->debit_air?></td>
                    <td><?=$fuzzy_lahan?></td>
                    <td><?=$fuzzy_air?></td>
                    <td><?=$fuzzy_lama_pengairan?></td>
                    <td><?=$u_lahan?></td>
                    <td><?=$u_air?></td>
                    <td><?=$WA?></td>
                    <td><?=$data->created_at?></td>
                    <td><a class="btn btn-danger" href="/index.php?delete=<?=$data->id?>">Delete</a></td>
                </tr>
                <?php }} ?>
            </tbody>
        </table>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <div class="mb-3">
                        <label class="form-label">Debit Air (L/detik)</label>
                        <input type="number" step="0.01"  class="form-control" name="debit_air" required>
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
            });
        } );
    </script>
  </body>
</html>