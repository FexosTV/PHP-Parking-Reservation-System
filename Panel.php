<?php
session_start();
include_once "Assets/Config/databaze.php";

if (!isset($_SESSION['Logged_in'])){
	header('Location: ./');
}
$Pocet_parkovacich_miest = 18;
/*if(date('w') == 6){ // day 5 = Piatok
  if (date('G') == 10) {
    $query_rst_rez = "UPDATE `Users-parkovanie` SET `Pondelok`='0',`Utorok`='0',`Streda`='0',`Štvrtok`='0',`Piatok`='0'";
    $runQuery_Rst_rez = mysqli_query($auth,$query_rst_rez);
  } 

  pouizete v databaze - 
  CREATE EVENT Reset_parkovanie
    ON SCHEDULE
    EVERY 1 MINUTE
      STARTS '2021-09-25 00:00:00' ON COMPLETION PRESERVE ENABLE
      DO
      UPDATE `Users-parkovanie` SET `Pondelok`='0',`Utorok`='0',`Streda`='0',`Štvrtok`='0',`Piatok`='0';
}*/

# <-- Edit User Script Start-->
if(isset($_POST['edit-usr-btn'])){
    $edit_usr_id = $_POST['edit-usr-id'];
    $query = "SELECT * FROM `Users-parkovanie` WHERE ID = '$edit_usr_id'";
    $runQuery = mysqli_query($auth, $query);
    if(mysqli_num_rows($runQuery) > 0){
        $row = mysqli_fetch_array($runQuery);
        $User_Meno = $row['Meno'];
        $User_Priezvisko = $row['Priezvisko'];
        $User_Heslo = $row['Heslo'];
        $User_Spz = $row['ŠPZ'];
        $User_Trieda = $row['Trieda'];
        $User_Tel_c = $row['Tel.c'];
        $User_Povolenie = $row['Povolenie'];
        $User_Rola = $row['Rola'];
        $User_Pondelok = $row['Pondelok'];
        $User_Utorok = $row['Utorok'];
        $User_Streda = $row['Streda'];
        $User_Štvrtok = $row['Štvrtok'];
        $User_Piatok = $row['Piatok'];
    }
	$edit_usr_meno = $_POST['edit-usr-meno'];
	$edit_usr_priezvisko = $_POST['edit-usr-priezvisko'];
  $edit_usr_tel_c = $_POST['edit-usr-tel_c'];
  $edit_usr_spz = $spz = strtoupper($_POST['edit-usr-spz']);
  $edit_usr_trieda = $_POST['edit-usr-trieda'];
  $edit_usr_rola = $_POST['edit-usr-rola'];
	$rst_heslo = $_POST['rst-pass'];
  $edit_usr_povolenie = $_POST['edit-usr-povolenie'];
  $delete = $_POST['delete'];
  if(empty($edit_usr_meno)){
    $edit_usr_meno = $User_Meno;
  }
  if(empty($edit_usr_priezvisko)){
    $edit_usr_priezvisko = $User_Priezvisko;
  }
  if(empty($edit_usr_povolenie)){
    $edit_usr_povolenie = $User_Povolenie;
  }
  if ($edit_usr_povolenie == 'Nie') {
    $edit_usr_povolenie = "0";
  }
  if ($edit_usr_povolenie == 'Ano') {
    $edit_usr_povolenie = "1";
  }
  if(empty($edit_usr_trieda)){
    $edit_usr_trieda = $User_Trieda;
  }
  if(empty($edit_usr_rola)){
    $edit_usr_rola = $User_Rola;
  }
if($edit_usr_rola == 'A'){
    $edit_usr_rola = "1";
}
if($edit_usr_rola == 'U'){
    $edit_usr_rola = "0";
}
  if($delete=='Ano'){
    $query = "DELETE FROM `Users-parkovanie` WHERE `ID`='$edit_usr_id'";
    $runQuery = mysqli_query($auth,$query);
    $success_delete = "Používateľ úspešne zmazaný";
  }
  if($rst_heslo == 'Nie' || empty($rst_heslo)){
    $rst_heslo = $User_Heslo;
  }else{
    $array1 = [];
    $array2 = [];
    for ($i = 0; $i < 3; $i++){
    $letter = chr(rand(65,90));
    $number = rand(0,9);
    array_push($array1, $letter);
    array_push($array2, $number);
    }
    $result = array_merge($array1, $array2);
    for ($i = 0; $i <20; $i++){
    shuffle($result);
    }
    $random = join("", $result);
    $rst_heslo = hash('sha256',$random);
    $message_Pass = "Heslo úspešne vygenerované! <br> Nové-Heslo: $random";
  }
  if(!empty($edit_usr_tel_c)){
    if (!preg_match('/[+]{1}[0-9]{12}/', $edit_usr_tel_c)){
      $error = "Zadal si zlé telefónne číslo!!";
    }else{
      $edit_usr_tel_c = $edit_usr_tel_c; 
    }
  }else{
    $edit_usr_tel_c = $User_Tel_c;
  }
  if(!empty($edit_usr_spz)){
    if (!preg_match('/[A-Za-z]{2}[0-9A-Za-z]{3}[0-9A-Za-z]{2}/', $edit_usr_spz)){
      $error = "Zadal si zlú ŠPZ značku!!";
    }else{
      $edit_usr_spz = $edit_usr_spz;
    }
  }else{
    $edit_usr_spz = $User_Spz;
  }
$query_Pondelok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Pondelok` = '1'";
  $runQuery_Pondelok = mysqli_query($auth,$query_Pondelok);
  if(mysqli_num_rows($runQuery_Pondelok) > 0){
    $row = mysqli_fetch_array($runQuery_Pondelok);
    $Pocet_Miest_Pondelok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Pondelok > 0){
      $Rez_Count_Edit_Pondelok = "1";
    }else{
      $error_Pondelok = "Pondelok je už plný. Rezervácia neprešla!!";
    }
  }
  $query_Utorok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Utorok` = '1'";
  $runQuery_Utorok = mysqli_query($auth,$query_Utorok);
  if(mysqli_num_rows($runQuery_Utorok) > 0){
    $row = mysqli_fetch_array($runQuery_Utorok);
    $Pocet_Miest_Utorok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Utorok > 0){
      $Rez_Count_Edit_Utorok = "1";
    }else{
      $error_Utorok = "Utorok je už plný. Rezervácia neprešla!!";
    }
  }
  $query_Streda = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Streda` = '1'";
  $runQuery_Streda = mysqli_query($auth,$query_Streda);
  if(mysqli_num_rows($runQuery_Streda) > 0){
    $row = mysqli_fetch_array($runQuery_Streda);
    $Pocet_Miest_Streda = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Streda > 0){
      $Rez_Count_Edit_Streda = "1";
    }else{
      $error_Streda = "Streda je už plná. Rezervácia neprešla!!";
    }
  }
  $query_Štvrtok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Štvrtok` = '1'";
  $runQuery_Štvrtok = mysqli_query($auth,$query_Štvrtok);
  if(mysqli_num_rows($runQuery_Štvrtok) > 0){
    $row = mysqli_fetch_array($runQuery_Štvrtok);
    $Pocet_Miest_Štvrtok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Štvrtok > 0){
      $Rez_Count_Edit_Štvrtok = "1";
    }else{
      $error_Štvrtok = "Štvrtok je už plný. Rezervácia neprešla!!";
    }
  }
  $query_Piatok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Piatok` = '1'";
  $runQuery_Piatok = mysqli_query($auth,$query_Piatok);
  if(mysqli_num_rows($runQuery_Piatok) > 0){
    $row = mysqli_fetch_array($runQuery_Piatok);
    $Pocet_Miest_Piatok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Piatok > 0){
      $Rez_Count_Edit_Piatok = "1";
    }else{
      $error_Piatok = "Piatok je už plný. Rezervácia neprešla!!";
    }
  }
  if(!empty($_POST['rez-admin-edit-pondelok']) && $Rez_Count_Edit_Pondelok == "1" && $_POST['rez-admin-edit-pondelok'] == "Ano"){
    $Rez_Admin_Edit_Pondelok = "1";
  }elseif($_POST['rez-admin-edit-pondelok'] == "Nie"){
    $Rez_Admin_Edit_Pondelok = "0";
  }else{
    $Rez_Admin_Edit_Pondelok = $User_Pondelok;
  }
  if(!empty($_POST['rez-admin-edit-utorok']) && $Rez_Count_Edit_Utorok == "1" && $_POST['rez-admin-edit-utorok'] == "Ano"){
    $Rez_Admin_Edit_Utorok = "1";
  }elseif($_POST['rez-admin-edit-utorok'] == "Nie"){
    $Rez_Admin_Edit_Utorok = "0";
  }else{
    $Rez_Admin_Edit_Utorok = $User_Utorok;
  }
  if(!empty($_POST['rez-admin-edit-streda']) && $Rez_Count_Edit_Streda == "1" && $_POST['rez-admin-edit-streda'] == "Ano"){
    $Rez_Admin_Edit_Streda = "1";
  }elseif($_POST['rez-admin-edit-streda'] == "Nie"){
    $Rez_Admin_Edit_Streda = "0";
  }else{
    $Rez_Admin_Edit_Streda = $User_Streda;
  }
  if(!empty($_POST['rez-admin-edit-štvrtok']) && $Rez_Count_Edit_Štvrtok == "1" && $_POST['rez-admin-edit-štvrtok'] == "Ano"){
    $Rez_Admin_Edit_Štvrtok = "1";
  }elseif($_POST['rez-admin-edit-štvrtok'] == "Nie"){
    $Rez_Admin_Edit_Štvrtok = "0";
  }else{
    $Rez_Admin_Edit_Štvrtok = $User_Štvrtok;
  }
  if(!empty($_POST['rez-admin-edit-piatok']) && $Rez_Count_Edit_Piatok == "1" && $_POST['rez-admin-edit-piatok'] == "Ano"){
    $Rez_Admin_Edit_Piatok = "1";
  }elseif($_POST['rez-admin-edit-piatok'] == "Nie"){
    $Rez_Admin_Edit_Piatok = "0";
  }else{
    $Rez_Admin_Edit_Piatok = $User_Piatok;
  }
  if($_POST['rez-admin-edit-delete'] == "All" || $edit_usr_povolenie == "0"){
    $Rez_Admin_Edit_Pondelok = "0";
    $Rez_Admin_Edit_Utorok = "0";
    $Rez_Admin_Edit_Streda = "0";
    $Rez_Admin_Edit_Štvrtok = "0";
    $Rez_Admin_Edit_Piatok = "0";
  }elseif($_POST['rez-admin-edit-delete'] == "Pondelok"){
    $Rez_Admin_Edit_Pondelok = "0";
  }elseif($_POST['rez-admin-edit-delete'] == "Utorok"){
    $Rez_Admin_Edit_Utorok = "0";
  }elseif($_POST['rez-admin-edit-delete'] == "Streda"){
    $Rez_Admin_Edit_Streda = "0";
  }elseif($_POST['rez-admin-edit-delete'] == "Štvrtok"){
    $Rez_Admin_Edit_Štvrtok = "0";
  }elseif($_POST['rez-admin-edit-delete'] == "Piatok"){
    $Rez_Admin_Edit_Piatok = "0";
  }
  
$query = "UPDATE `Users-parkovanie` SET `Meno`='$edit_usr_meno',`Priezvisko`='$edit_usr_priezvisko',`Heslo`='$rst_heslo', `ŠPZ`='$edit_usr_spz', `Trieda`='$edit_usr_trieda', `Tel.c`='$edit_usr_tel_c', `Povolenie`='$edit_usr_povolenie',`Rola`='$edit_usr_rola',`Pondelok`='$Rez_Admin_Edit_Pondelok',`Utorok`='$Rez_Admin_Edit_Utorok',`Streda`='$Rez_Admin_Edit_Streda',`Štvrtok`='$Rez_Admin_Edit_Štvrtok',`Piatok`='$Rez_Admin_Edit_Piatok'  WHERE `ID`='$edit_usr_id'";
$runQuery = mysqli_query($auth, $query);
	if($runQuery){
	    if(isset($message_Pass)){
	    $success = "Používateľ úspeľne upravený";
	    }
	    elseif(isset($success_delete)){
	    $success = $success_delete;
	    header('Refresh:2; url= ?users');
	    }else{
	    $success = "Používateľ úspeľne upravený. Presmerovanie za 3..2..1..";
	    header('Refresh:2; url=?users');
	    }
  mysqli_close($auth);
	}else{
				$error = "Rezervácia sa nepodarila vytvoriť/upraviť";
			}
}
# <-- Edit User Script End-->

# <-- Rezervation Script Start-->
if(isset($_POST['rez-user-edit-btn'])){
  $query_Pondelok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Pondelok` = '1'";
  $runQuery_Pondelok = mysqli_query($auth,$query_Pondelok);
  if(mysqli_num_rows($runQuery_Pondelok) > 0){
    $row = mysqli_fetch_array($runQuery_Pondelok);
    $Pocet_Miest_Pondelok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Pondelok > 0){
      $Rez_Count_Edit_Pondelok = "1";
    }else{
      $error_Pondelok = "Pondelok je už plný. Rezervácia neprešla!!";
    }
  }
  $query_Utorok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Utorok` = '1'";
  $runQuery_Utorok = mysqli_query($auth,$query_Utorok);
  if(mysqli_num_rows($runQuery_Utorok) > 0){
    $row = mysqli_fetch_array($runQuery_Utorok);
    $Pocet_Miest_Utorok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Utorok > 0){
      $Rez_Count_Edit_Utorok = "1";
    }else{
      $error_Utorok = "Utorok je už plný. Rezervácia neprešla!!";
    }
  }
  $query_Streda = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Streda` = '1'";
  $runQuery_Streda = mysqli_query($auth,$query_Streda);
  if(mysqli_num_rows($runQuery_Streda) > 0){
    $row = mysqli_fetch_array($runQuery_Streda);
    $Pocet_Miest_Streda = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Streda > 0){
      $Rez_Count_Edit_Streda = "1";
    }else{
      $error_Streda = "Streda je už plná. Rezervácia neprešla!!";
    }
  }
  $query_Štvrtok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Štvrtok` = '1'";
  $runQuery_Štvrtok = mysqli_query($auth,$query_Štvrtok);
  if(mysqli_num_rows($runQuery_Štvrtok) > 0){
    $row = mysqli_fetch_array($runQuery_Štvrtok);
    $Pocet_Miest_Štvrtok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Štvrtok > 0){
      $Rez_Count_Edit_Štvrtok = "1";
    }else{
      $error_Štvrtok = "Štvrtok je už plný. Rezervácia neprešla!!";
    }
  }
  $query_Piatok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Piatok` = '1'";
  $runQuery_Piatok = mysqli_query($auth,$query_Piatok);
  if(mysqli_num_rows($runQuery_Piatok) > 0){
    $row = mysqli_fetch_array($runQuery_Piatok);
    $Pocet_Miest_Piatok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
    if($Pocet_Miest_Piatok > 0){
      $Rez_Count_Edit_Piatok = "1";
    }else{
      $error_Piatok = "Piatok je už plný. Rezervácia neprešla!!";
    }
  }
  $query = "SELECT `Pondelok`, `Utorok`, `Streda`, `Štvrtok`, `Piatok` FROM `Users-parkovanie` WHERE ID = '{$_SESSION['ID']}'";
  $runQuery = mysqli_query($auth, $query);
  if(mysqli_num_rows($runQuery) > 0){
      $row = mysqli_fetch_array($runQuery);
      $Rez_Edit_Pondelok = $row['Pondelok'];
      $Rez_Edit_Utorok = $row['Utorok'];
      $Rez_Edit_Streda = $row['Streda'];
      $Rez_Edit_Štvrtok = $row['Štvrtok'];
      $Rez_Edit_Piatok = $row['Piatok'];
  }
  if(!empty($_POST['rez-user-edit-pondelok']) && $Rez_Count_Edit_Pondelok == "1" && $_POST['rez-user-edit-pondelok'] == "Ano"){
    $Rez_User_Edit_Pondelok = "1";
  }elseif($_POST['rez-user-edit-pondelok'] == "Nie"){
    $Rez_User_Edit_Pondelok = "0";
  }else{
    $Rez_User_Edit_Pondelok = $Rez_Edit_Pondelok;
  }
  if(!empty($_POST['rez-user-edit-utorok']) && $Rez_Count_Edit_Utorok == "1" && $_POST['rez-user-edit-utorok'] == "Ano"){
    $Rez_User_Edit_Utorok = "1";
  }elseif($_POST['rez-user-edit-utorok'] == "Nie"){
    $Rez_User_Edit_Utorok = "0";
  }else{
    $Rez_User_Edit_Utorok = $Rez_Edit_Utorok;
  }
  if(!empty($_POST['rez-user-edit-streda']) && $Rez_Count_Edit_Streda == "1" && $_POST['rez-user-edit-streda'] == "Ano"){
    $Rez_User_Edit_Streda = "1";
  }elseif($_POST['rez-user-edit-streda'] == "Nie"){
    $Rez_User_Edit_Streda = "0";
  }else{
    $Rez_User_Edit_Streda = $Rez_Edit_Streda;
  }
  if(!empty($_POST['rez-user-edit-štvrtok']) && $Rez_Count_Edit_Štvrtok == "1" && $_POST['rez-user-edit-štvrtok'] == "Ano"){
    $Rez_User_Edit_Štvrtok = "1";
  }elseif($_POST['rez-user-edit-štvrtok'] == "Nie"){
    $Rez_User_Edit_Štvrtok = "0";
  }else{
    $Rez_User_Edit_Štvrtok = $Rez_Edit_Štvrtok;
  }
  if(!empty($_POST['rez-user-edit-piatok']) && $Rez_Count_Edit_Piatok == "1" && $_POST['rez-user-edit-piatok'] == "Ano"){
    $Rez_User_Edit_Piatok = "1";
  }elseif($_POST['rez-user-edit-piatok'] == "Nie"){
    $Rez_User_Edit_Piatok = "0";
  }else{
    $Rez_User_Edit_Piatok = $Rez_Edit_Piatok;
  }
  if($_POST['rez-user-edit-delete'] == "All"){
    $Rez_User_Edit_Pondelok = "0";
    $Rez_User_Edit_Utorok = "0";
    $Rez_User_Edit_Streda = "0";
    $Rez_User_Edit_Štvrtok = "0";
    $Rez_User_Edit_Piatok = "0";
  }elseif($_POST['rez-user-edit-delete'] == "Pondelok"){
    $Rez_User_Edit_Pondelok = "0";
  }elseif($_POST['rez-user-edit-delete'] == "Utorok"){
    $Rez_User_Edit_Utorok = "0";
  }elseif($_POST['rez-user-edit-delete'] == "Streda"){
    $Rez_User_Edit_Streda = "0";
  }elseif($_POST['rez-user-edit-delete'] == "Štvrtok"){
    $Rez_User_Edit_Štvrtok = "0";
  }elseif($_POST['rez-user-edit-delete'] == "Piatok"){
    $Rez_User_Edit_Piatok = "0";
  }
  $query_rez = "UPDATE `Users-parkovanie` SET `Pondelok`='$Rez_User_Edit_Pondelok',`Utorok`='$Rez_User_Edit_Utorok',`Streda`='$Rez_User_Edit_Streda', `Štvrtok`='$Rez_User_Edit_Štvrtok', `Piatok`='$Rez_User_Edit_Piatok' WHERE `ID`='{$_SESSION['ID']}'";
  $runQuery_rez = mysqli_query($auth, $query_rez);
	if($runQuery_rez){
	$success = "Rezervacia bola úspešne vytvorená/upravená. Presmerovanie 3..";
  header('Refresh:2; url= ./');
  mysqli_close($auth);
	}else{
				$error = "Rezervácia sa nepodarila vytvoriť/upraviť!!";
			}
}
# <-- Rezervation Script End-->

# <-- Change Pass Script Start-->
if(isset($_POST['chg-pass-btn'])){
  $old_pass = hash('sha256',$_POST['chg-pass-old']);
  $new_pass = $_POST['chg-pass-new'];
  $confirm_pass = $_POST['chg-pass-confirm'];
  if($_SESSION['Heslo'] == $old_pass){
    if($new_pass == $confirm_pass){
      $new_pass = hash('sha256',$new_pass);
      $query_change_pass = "UPDATE `Users-parkovanie` SET `Heslo`='$new_pass' WHERE `ID` = '{$_SESSION['ID']}'";
      $runQuery_change_pass = mysqli_query($auth,$query_change_pass);
      if($runQuery_change_pass){
        $success = "Heslo úspešne zmenené";
        $_SESSION['Heslo'] = $new_pass;
        header('Refresh:2; url= ./');
        mysqli_close($auth);
        }else{
              $error = "Niečo sa pokazilo. Kontaktuj riaditeľa školy!!";
        }
    }else{
      $error = "Heslá sa nezhodujú";
    }
  }else{
    $error = "Staré heslo je zlé";
  }
}
# <-- Change Pass Script End-->
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<title>Parkovanie SOSELH</title>
<link href="skola.png" rel="icon">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<link rel="stylesheet" href="Assets/Css/index.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body class="main" id="body-pd">

<!-- Sidebar Start-->
<header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> <a href="http://www.soselh.sk" class="nav_logo"><i class='bx bx-info-circle nav_logo-icon'></i> <span class="nav_logo-name">Parkovanie Soselh</span> </a>
                <div class="nav_list"> <a href="./" class="nav_link <?php if(empty($_GET) || isset($_GET['rez'])){ echo "active";}?>"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Hlavná stránka</span> </a>
                 <?php if($_SESSION['Rola'] == '1') {
                   ?>
                    <a href='?users' class='nav_link <?php if(!empty($_GET) && !isset($_GET['rez']) && !isset($_GET['chg-pass'])){ echo "active";} ?>'> <i class='bx bx-user nav_icon'></i> <span class='nav_name'>Používatelia</span> </a>
                 <?php }
                 ?>
                 <a href="?chg-pass" class='nav_link <?php if(!empty($_GET) && isset($_GET['chg-pass'])){ echo "active";} ?>'> <i class='bx bx-edit-alt nav_icon'></i> <span class="nav_name">Zmeniť heslo</span> </a>
                 <a href="Logout.php" class="nav_link logout-mobile"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">Odhlásiť sa</span> </a>
                </div>
            </div><a href="Logout.php" class="nav_link logout"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">Odhlásiť sa</span> </a>
        </nav>
    </div>
<!-- Sidebar End-->

<!--Main Start-->
    <div class="container">
<!--User Table Start-->
        <?php
        if($_SESSION['Rola'] == '1' && isset($_GET['users'])){
            ?>
            <h1 style="text-align:center; color:#008080; margin-top:50px">Informacie o používateloch</h1>
<div class="form-group pull-right" style="margin-top:60px">
    <input type="text" class="search form-control" placeholder="Čo hladáte?">
</div>
<span class="counter pull-right"></span>
<table class="table table-hover table table-striped results">
  <thead>
    <tr style="color:#008080">
      <th>#</th>
      <th class="col-md-2 col-xs-1">Meno Priezvisko</th>
      <th class="col-md-1 col-xs-1">ŠPZ</th>
      <th class="col-md-1 col-xs-1">Trieda</th>
      <th class="col-md-1 col-xs-1">Tel.č</th>
      <th class="col-md-1 col-xs-1">Rola</th>
      <th class="col-md-1 col-xs-1">Povolenie</th>
      <th class="col-md-1 col-xs-1">Pondelok</th>
      <th class="col-md-1 col-xs-1">Utorok</th>
      <th class="col-md-1 col-xs-1">Streda</th>
      <th class="col-md-1 col-xs-1">Štvrtok</th>
      <th class="col-md-1 col-xs-1">Piatok</th>
      <th class="col-md-1 col-xs-1">Upraviť</th>
    </tr>
    <tr class="warning no-result">
      <td colspan="4"><i class="fa fa-warning"></i> Žiadny výsledok</td>
    </tr>
  </thead>
  <?php
    $results_per_page = 5;

    // Zistiť kolko záznamov je v databáze
    $sql='SELECT * FROM `Users-parkovanie`';
    $result = mysqli_query($auth, $sql);
    $number_of_results = mysqli_num_rows($result);

    // Zistit počet strán
    $number_of_pages = ceil($number_of_results/$results_per_page);

    // Určit na ktorej strane sa momentálne nachádzame
    if (empty($_GET['users'])) {
      $page = 1;
    } else {
      $page = $_GET['users'];
    }

    // Určiť počet záznamov na jednu stranu
    $this_page_first_result = ($page-1)*$results_per_page;

    // Vypísať záznami
    $query='SELECT * FROM `Users-parkovanie` LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
    $runQuery = mysqli_query($auth, $query);
    $count_rows = mysqli_num_rows($runQuery);
    if($count_rows > 0){
      $sr = $this_page_first_result+1;
      while($row = mysqli_fetch_array($runQuery)){
        $ID = $row['ID'];
		    $CeleMeno = $row['Meno'] .' '. $row['Priezvisko'];
		    $Spz = $row['ŠPZ'];
		    $Trieda = $row['Trieda'];
		    $Tel_c = $row['Tel.c'];
		    $Povolenie = $row['Povolenie'];
		    $Rola = $row['Rola'];
        $Rez_Pondelok = $row['Pondelok'];
        $Rez_Utorok = $row['Utorok'];
        $Rez_Streda = $row['Streda'];
        $Rez_Štvrtok = $row['Štvrtok'];
        $Rez_Piatok = $row['Piatok'];
  ?>
  <tbody>
    <tr>
      <th scope="row"><?php echo $sr;?></th>
      <td><?php echo $CeleMeno;?></td>
      <td><?php echo $Spz;?></td>
      <td><?php echo $Trieda;?></td>
      <td><?php echo $Tel_c;?></td>
      <td><?php if($Rola == '0'){ echo "<button class='btn btn-info'>Používateľ</button>";} else{ echo "<button class='btn btn-danger'>Admin</button>";}?></td>
      <td style="text-align:center; font-size:20px" ><?php if($Povolenie == '1'){ echo "✅";}else{ echo "❌";}?></td>
      <td style="text-align:center; font-size:20px" ><?php if($Rez_Pondelok == '1'){ echo "✅";}else{ echo "❌";}?></td>
      <td style="text-align:center; font-size:20px" ><?php if($Rez_Utorok == '1'){ echo "✅";}else{ echo "❌";}?></td>
      <td style="text-align:center; font-size:20px" ><?php if($Rez_Streda == '1'){ echo "✅";}else{ echo "❌";}?></td>
      <td style="text-align:center; font-size:20px" ><?php if($Rez_Štvrtok == '1'){ echo "✅";}else{ echo "❌";}?></td>
      <td style="text-align:center; font-size:20px" ><?php if($Rez_Piatok == '1'){ echo "✅";}else{ echo "❌";}?></td>
      <td style="text-align:center"><a href="?editusr=<?php echo base64_encode($ID);?>"><i class='bx bxs-pencil bx-md'></i></a></td>
    </tr>
    <?php 
    $sr++; 
}} 
?>
  </tbody>
</table>
<ul class="pagination justify-content-center">
<?php  
if($count_rows >= $results_per_page || $page > 1){
  for ($page=1;$page<=$number_of_pages;$page++) {
    echo '<li class="page-item"><a class="page-link" href="?users=' . $page . '">' . $page . '</a> </li>';
  }
  ?>
  </ul>
<!--User Table End-->

<!--Edit User By Admin Start-->
<?php }}
        elseif($_SESSION['Rola'] == '1' && isset($_GET['editusr']) && !empty($_GET['editusr'])){
            $Edit_ID = base64_decode($_GET['editusr']);
            $query = "SELECT * FROM `Users-parkovanie` WHERE ID = '$Edit_ID'";
            $runQuery = mysqli_query($auth, $query);
            if(mysqli_num_rows($runQuery) > 0){
                $row = mysqli_fetch_array($runQuery);
                $_SESSION['User_Cele_Meno'] = $row['Meno'] .' '. $row['Priezvisko'];
                $Edit_Povolenie = $row['Povolenie'];
                $Edit_Trieda = $row['Trieda'];
        }?>
				<div class="container-fluid">
					<div class="row" style="margin-top: 50px" >
						<h2 style="text-align:center" >Upraviť Používateľa (<?php echo $_SESSION['User_Cele_Meno'];?>)</h2>
					</div>
					<?php
					if(isset($error)){
                        echo '<div class="alert alert-danger" role="alert">
                        '.$error.'
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                    }
             if(isset($success)){
               echo '<div class="alert alert-success" role="alert">
               '.$success.'
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
           }
          					if(isset($message_Pass)){
                      echo '<div class="alert alert-info" role="alert">
                      '.$message_Pass.'
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
					?>
					<div>
						<form control="" class="form-group" method="post">
                        <div class="row">
								<input type="text" name="edit-usr-meno" class="form__input-edit" placeholder="Meno">
							</div>
							<div class="row">
								<input type="text" name="edit-usr-priezvisko"class="form__input-edit" placeholder="Priezvisko">
							</div>
							<div class="row">
								<input type="tel" class="form__input-edit" title="Zadaj telefónne číslo: +421900123456" pattern="[+]{1}[0-9]{12}" placeholder="+421900123456" name="edit-usr-tel_c" id="tel_c" minlength="13" maxlength="13" autocomplete="off">
							</div>
							<div class="row">
								<input type="spz" class="form__input-edit" title="Zadaj ŠPZ: LM123AA" pattern="[A-Za-z]{2}[0-9A-Za-z]{3}[0-9A-Za-z]{2}" placeholder="LM123AA" name="edit-usr-spz" id="spz" minlength="7" maxlength="7" autocomplete="off">
							</div>
              <input type="hidden" name="edit-usr-id" value="<?php echo $Edit_ID;?>">
							<div class="row">
								<select type="text" name="edit-usr-trieda" class="form__input-edit">
									<option value="None" disabled selected>Vyber triedu</option>	
									<option value="3.A">3.A</option>
  									<option value="3.B">3.B</option>
  									<option value="3.C">3.C</option>
  									<option value="3.D">3.D</option>
									<option value="4.A">4.A</option>
  									<option value="4.B">4.B</option>
  									<option value="4.C">4.C</option>
  									<option value="4.D">4.D</option>
								</select>
							</div>
                <div class="row">
								<select type="text" name="rst-pass" class="form__input-edit">
									<option value="None" disabled selected>Reset Hesla</option>	
									<option value="Nie">Nie</option>
  									<option value="Ano">Ano</option>
								</select>
							</div>
              <div class="row">
								<select type="text" name="edit-usr-povolenie" class="form__input-edit">
									<option value="None" disabled selected>Povolenie parkovať</option>	
									<option value="Nie">Nie</option>
  									<option value="Ano" >Ano</option>
								</select>
							</div>
<div class="row">
                <?php
                      $query_Pondelok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Pondelok` = '1'";
                      $runQuery_Pondelok = mysqli_query($auth,$query_Pondelok);
                      if(mysqli_num_rows($runQuery_Pondelok) > 0){
                        $row = mysqli_fetch_array($runQuery_Pondelok);
                        $Pocet_Miest_Pondelok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Pondelok > 0){?>
                        <select type="text" name="rez-admin-edit-pondelok" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v pondelok - Voľných $Pocet_Miest_Pondelok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-admin-edit-pondelok" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v pondelok - Voľných $Pocet_Miest_Pondelok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
<div class="row">
                <?php
                      $query_Utorok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Utorok` = '1'";
                      $runQuery_Utorok = mysqli_query($auth,$query_Utorok);
                      if(mysqli_num_rows($runQuery_Utorok) > 0){
                        $row = mysqli_fetch_array($runQuery_Utorok);
                        $Pocet_Miest_Utorok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Utorok > 0){?>
                        <select type="text" name="rez-admin-edit-utorok" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v utorok - Voľných $Pocet_Miest_Utorok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-admin-edit-utorok" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v utorok - Voľných $Pocet_Miest_Utorok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
<div class="row">
                <?php
                      $query_Streda = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Streda` = '1'";
                      $runQuery_Streda = mysqli_query($auth,$query_Streda);
                      if(mysqli_num_rows($runQuery_Streda) > 0){
                        $row = mysqli_fetch_array($runQuery_Streda);
                        $Pocet_Miest_Streda = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Streda > 0){?>
                        <select type="text" name="rez-admin-edit-streda" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v stredu - Voľných $Pocet_Miest_Streda miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-admin-edit-streda" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v stredu - Voľných $Pocet_Miest_Streda miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
<div class="row">
                <?php
                      $query_Štvrtok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Štvrtok` = '1'";
                      $runQuery_Štvrtok = mysqli_query($auth,$query_Štvrtok);
                      if(mysqli_num_rows($runQuery_Štvrtok) > 0){
                        $row = mysqli_fetch_array($runQuery_Štvrtok);
                        $Pocet_Miest_Štvrtok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Štvrtok > 0){?>
                        <select type="text" name="rez-admin-edit-štvrtok" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v štvrtok - Voľných $Pocet_Miest_Štvrtok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-admin-edit-štvrtok" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v štvrtok - Voľných $Pocet_Miest_Štvrtok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
<div class="row">
                <?php
                      $query_Piatok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Piatok` = '1'";
                      $runQuery_Piatok = mysqli_query($auth,$query_Piatok);
                      if(mysqli_num_rows($runQuery_Piatok) > 0){
                        $row = mysqli_fetch_array($runQuery_Piatok);
                        $Pocet_Miest_Piatok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Piatok > 0){?>
                        <select type="text" name="rez-admin-edit-piatok" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v piatok - Voľných $Pocet_Miest_Piatok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-admin-edit-piatok" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v piatok - Voľných $Pocet_Miest_Piatok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
              <div class="row">
								<select type="text" name="edit-usr-rola" class="form__input-edit">
									<option value="None" disabled selected>Vyber rolu používateľa</option>	
									<option value="A">Admin</option>
  									<option value="U">Používateľ</option>
								</select>
							</div>
                <div class="row">
                  <select type="text" name="rez-admin-edit-delete" class="form__input-edit">
                    <option value="None" disabled selected>Vymazať rezerváciu</option>
                    <option value="Nie">Nie</option>
                    <option value="All">Všetky</option>
                    <option value="Pondelok">Pondelok</option>
                    <option value="Utorok">Utorok</option>
                    <option value="Streda">Streda</option>
                    <option value="Štvrtok">Štvrtok</option>
                    <option value="Piatok">Piatok</option>
                  </select>
                </div>
              <div class="row">
								<select type="text" name="delete" class="form__input-edit">
									<option value="None" disabled selected>Vymazat používateľa</option>	
									<option value="Nie">Nie</option>
  									<option value="Ano">Ano</option>
								</select>
							</div>
							<div class="row" style="margin-bottom:50px">
								<input type="submit" value="Upraviť" name="edit-usr-btn" class="btn-edit">
							</div>
						</form>
					</div>
			</div>
            </div>
<!--Edit User By Admin End-->

<!--Rezervation Start-->
            <?php }elseif(isset($_GET['rez'])){
              $query = "SELECT `Pondelok`, `Utorok`, `Streda`, `Štvrtok`, `Piatok` FROM `Users-parkovanie` WHERE `ID` = '{$_SESSION['ID']}'";
              $runQuery = mysqli_query($auth, $query);
              if(mysqli_num_rows($runQuery) > 0){
                  $row = mysqli_fetch_array($runQuery);
                  $Rez_User_Pondelok = $row['Pondelok'];
                  $Rez_User_Utorok = $row['Utorok'];
                  $Rez_User_Streda = $row['Streda'];
                  $Rez_User_Štvrtok = $row['Štvrtok'];
                  $Rez_User_Piatok = $row['Piatok'];
          }
          ?>
          <div class="container-fluid">
            <div class="row" style="margin-top: 50px" >
              <h2 style="text-align:center" >Vytvoriť/Upraviť Rezerváciu</h2>
            </div>
            <?php
            if(isset($error)){
                          echo '<div class="alert alert-danger" role="alert">
                          '.$error.'
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      }
                      if(isset($error_Pondelok)){
                        echo '<div class="alert alert-danger" role="alert">
                        '.$error_Pondelok.'
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                    }
                    if(isset($error_Utorok)){
                      echo '<div class="alert alert-danger" role="alert">
                      '.$error_Utorok.'
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                  if(isset($error_Streda)){
                    echo '<div class="alert alert-danger" role="alert">
                    '.$error_Streda.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                }
                if(isset($error_Štvrtok)){
                  echo '<div class="alert alert-danger" role="alert">
                  '.$error_Štvrtok.'
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              }
              if(isset($error_Piatok)){
                echo '<div class="alert alert-danger" role="alert">
                '.$error_Piatok.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
                      if(isset($success)){
                        echo '<div class="alert alert-success" role="alert">
                        '.$success.'
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                    }
            ?>
            <div>
              <form control="" class="form-group" method="post">
                <div class="row">
                <?php
                      $query_Pondelok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Pondelok` = '1'";
                      $runQuery_Pondelok = mysqli_query($auth,$query_Pondelok);
                      if(mysqli_num_rows($runQuery_Pondelok) > 0){
                        $row = mysqli_fetch_array($runQuery_Pondelok);
                        $Pocet_Miest_Pondelok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Pondelok > 0){?>
                        <select type="text" name="rez-user-edit-pondelok" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v pondelok - Voľných $Pocet_Miest_Pondelok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-user-edit-pondelok" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v pondelok - Voľných $Pocet_Miest_Pondelok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
                <div class="row">
                <?php
                      $query_Utorok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Utorok` = '1'";
                      $runQuery_Utorok = mysqli_query($auth,$query_Utorok);
                      if(mysqli_num_rows($runQuery_Utorok) > 0){
                        $row = mysqli_fetch_array($runQuery_Utorok);
                        $Pocet_Miest_Utorok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Utorok > 0){?>
                        <select type="text" name="rez-user-edit-utorok" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v utorok - Voľných $Pocet_Miest_Utorok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-user-edit-utorok" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v utorok - Voľných $Pocet_Miest_Utorok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
                <div class="row">
                <?php
                      $query_Streda = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Streda` = '1'";
                      $runQuery_Streda = mysqli_query($auth,$query_Streda);
                      if(mysqli_num_rows($runQuery_Streda) > 0){
                        $row = mysqli_fetch_array($runQuery_Streda);
                        $Pocet_Miest_Streda = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Streda > 0){?>
                        <select type="text" name="rez-user-edit-streda" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v stredu - Voľných $Pocet_Miest_Streda miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-user-edit-streda" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v stredu - Voľných $Pocet_Miest_Streda miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
                <div class="row">
                <?php
                      $query_Štvrtok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Štvrtok` = '1'";
                      $runQuery_Štvrtok = mysqli_query($auth,$query_Štvrtok);
                      if(mysqli_num_rows($runQuery_Štvrtok) > 0){
                        $row = mysqli_fetch_array($runQuery_Štvrtok);
                        $Pocet_Miest_Štvrtok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Štvrtok > 0){?>
                        <select type="text" name="rez-user-edit-štvrtok" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v štvrtok - Voľných $Pocet_Miest_Štvrtok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                          
                        }else{
                        ?>
                        <select type="text" name="rez-user-edit-štvrtok" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v štvrtok - Voľných $Pocet_Miest_Štvrtok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
                <div class="row">
                <?php
                      $query_Piatok = "SELECT COUNT(`ID`) AS 'Pocet rezervaci' FROM `Users-parkovanie` WHERE `Piatok` = '1'";
                      $runQuery_Piatok = mysqli_query($auth,$query_Piatok);
                      if(mysqli_num_rows($runQuery_Piatok) > 0){
                        $row = mysqli_fetch_array($runQuery_Piatok);
                        $Pocet_Miest_Piatok = $Pocet_parkovacich_miest - $row['Pocet rezervaci'];
                        if($Pocet_Miest_Piatok > 0){?>
                        <select type="text" name="rez-user-edit-piatok" class="form__input-edit">
                          <option value="None" disabled selected>
                            <?= "Rezervácia v piatok - Voľných $Pocet_Miest_Piatok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                        }else{
                        ?>
                        <select type="text" name="rez-user-edit-piatok" class="form__input-edit" disabled>
                          <option value="None" disabled selected>
                            <?= "Rezervácia v piatok - Voľných $Pocet_Miest_Piatok miest";?>
                          </option>	
                          <option value="Nie">Nie</option>
                          <option value="Ano">Ano</option>
                  </select>
                  <?php
                      }}
                      ?>
                </div>
                <div class="row">
                  <select type="text" name="rez-user-edit-delete" class="form__input-edit">
                    <option value="None" disabled selected>Vymazať rezerváciu</option>
                    <option value="Nie">Nie</option>
                    <option value="All">Všetky</option>
                    <option value="Pondelok">Pondelok</option>
                    <option value="Utorok">Utorok</option>
                    <option value="Streda">Streda</option>
                    <option value="Štvrtok">Štvrtok</option>
                    <option value="Piatok">Piatok</option>
                  </select>
                </div>
                <div class="row" style="margin-bottom:50px">
                  <input type="submit" value="Upraviť" name="rez-user-edit-btn" class="btn-edit">
                </div>
              </form>
            </div>
        </div>
<!--Rezervation End-->

<!--Change Pass Start-->
              <?php }elseif(isset($_GET['chg-pass'])){
          ?>
          <div class="container-fluid">
            <div class="row" style="margin-top: 50px" >
              <h2 style="text-align:center" >Zmeniť Heslo</h2>
            </div>
            <?php
            if(isset($error)){
                          echo '<div class="alert alert-danger" role="alert">
                          '.$error.'
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      }
                      if(isset($success)){
                        echo '<div class="alert alert-success" role="alert">
                        '.$success.'
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                    }
            ?>
            <div>
              <form control="" class="form-group" method="post">
                <div class="row">
								<input type="password" name="chg-pass-old" class="form__input-edit" placeholder="Staré heslo" required>
							</div>
              <div class="row">
								<input type="password" name="chg-pass-new" class="form__input-edit" placeholder="Nové heslo" required>
							</div>
              <div class="row">
								<input type="password" name="chg-pass-confirm" class="form__input-edit" placeholder="Potvrdiť heslo" required>
							</div>
                <div class="row" style="margin-bottom:50px">
                  <input type="submit" value="Upraviť" name="chg-pass-btn" class="btn-edit">
                </div>
              </form>
            </div>
        </div>
<!--Change Pass End-->

<!--Info Start-->
              <?php
            }
            else{
            $query = "SELECT `Povolenie`, `Pondelok`, `Utorok`, `Streda`, `Štvrtok`, `Piatok` FROM `Users-parkovanie` WHERE `ID` = '{$_SESSION['ID']}'";
        $runQuery = mysqli_query($auth,$query);
        if(mysqli_num_rows($runQuery) > 0){
          $row = mysqli_fetch_array($runQuery);
          $Povolenie_Usr = $row['Povolenie'];
          $Pondelok = $row['Pondelok'];
          $Utorok = $row['Utorok'];
          $Streda = $row['Streda'];
          $Štvrtok = $row['Štvrtok'];
          $Piatok = $row['Piatok'];
        }
            ?>
    <div class="container table-responsive">
    <table class="table table-striped" style="text-align:center;margin-top:50px;" >
    <thead>
      <tr>
        <th colspan="2" style="text-align:center;font-size:50px; color:#008080"><?php echo "Vaše informácie";?></th>
      </tr>
    </thead>
    <tbody style="font-size:20px">
      <tr>
        <td>Meno</td>
        <td><?= $_SESSION['Meno'];?></td>
      </tr>
      <tr>
        <td>Priezvisko</td>
        <td><?= $_SESSION['Priezvisko'];?></td>
      </tr>
      <tr>
        <td>ŠPZ</td>
        <td><?= $_SESSION['ŠPZ'];?></td>
      </tr>
      <tr>
        <td>Telefónne číslo</td>
        <td><?= $_SESSION['Tel.c'];?></td>
      </tr>
      <tr>
        <td>Trieda</td>
        <td><?= $_SESSION['Trieda'];?></td>
      </tr>
      <tr>
        <td>Povolenie parkovať</td>
        <td><?php if($Povolenie_Usr == '1'){ echo "✅";}else{ echo "❌";}?></td>
      </tr>
      <tr>
        <td>Rezervácia</td>
        <?php
        if($Povolenie_Usr == '0'){
          echo "<td style='color:red;text-decoration:underline'>Nemáš povolenie parkovať!!</td>";
        }else{
          if($Pondelok == '0' && $Utorok == '0' && $Streda == '0' && $Štvrtok == '0' && $Piatok == '0'){
            echo "<td style='color:red;text-decoration:underline'>Nemáš spravenú rezerváciu!!</td>";
          }else{
            ?>
              <td style='color:green;text-decoration:underline'>Máš spravenú rezerváciu!!</td>
          </tr>
              <tr>
              <td>Pondelok</td>
              <td><?php if($Pondelok == '1'){echo "✅";}else{ echo "❌";}?></td>
              </tr>
              <tr>
              <td>Utorok</td>
              <td><?php if($Utorok == '1'){echo "✅";}else{ echo "❌";}?></td>
              </tr>
              <tr>
              <td>Streda</td>
              <td><?php if($Streda == '1'){echo "✅";}else{ echo "❌";}?></td>
              </tr>
              <tr>
              <td>Štvrtok</td>
              <td><?php if($Štvrtok == '1'){echo "✅";}else{ echo "❌";}?></td>
              </tr>
              <tr>
              <td>Piatok</td>
              <td><?php if($Piatok == '1'){echo "✅";}else{ echo "❌";}?></td>
              </tr>
              <?php
          }
        echo "<tr  style='border-bottom:1px solid #008080'><td colspan='2' style='text-decoration:underline;color: green'><a href='?rez' style='text-decoration: none; color: inherit;'><b>Vytvoriť/Upraviť rezerváciu!!</b></a></td></tr>";
        }
        ?>
      </tr>
    </tbody>
  </table>
    </div>
    <?php } ?>
<!--Info End-->

<!-- Copyright/Footer Start -->
<div class="container-fluid text-center footer">
  &copy; Copyright <strong><span>Samuel Fábry</span></strong>. All Rights Reserved
	</div>
<!-- Copyright/Footer End -->
    </div>
<!--Main End-->

</body>
<script>
document.addEventListener("DOMContentLoaded", function(event) {

const showNavbar = (toggleId, navId, bodyId, headerId) =>{
const toggle = document.getElementById(toggleId),
nav = document.getElementById(navId),
bodypd = document.getElementById(bodyId),
headerpd = document.getElementById(headerId)

// Validate that all variables exist
if(toggle && nav && bodypd && headerpd){
toggle.addEventListener('click', ()=>{
// show navbar
nav.classList.toggle('show')
// change icon
toggle.classList.toggle('bx-x')
// add padding to body
bodypd.classList.toggle('body-pd')
// add padding to header
headerpd.classList.toggle('body-pd')
})
}
}

showNavbar('header-toggle','nav-bar','body-pd','header')

/*===== LINK ACTIVE =====*/
const linkColor = document.querySelectorAll('.nav_link')

function colorLink(){
if(linkColor){
linkColor.forEach(l=> l.classList.remove('active'))
this.classList.add('active')
}
}
linkColor.forEach(l=> l.addEventListener('click', colorLink))

});

$(document).ready(function() {
        $(".search").keyup(function () {
          var searchTerm = $(".search").val();
          var listItem = $('.results tbody').children('tr');
          var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
          
        $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
              return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
          }
        });
          
        $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
          $(this).attr('visible','false');
        });
      
        $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
          $(this).attr('visible','true');
        });
      
        var jobCount = $('.results tbody tr[visible="true"]').length;
          $('.counter').text(jobCount + ' item');
      
        if(jobCount == '0') {$('.no-result').show();}
          else {$('.no-result').hide();}
                });
      });</script>
</html>