<?php
session_start();
include_once "Assets/Config/databaze.php";

if (isset($_SESSION['Logged_in'])){
	header('Location: Panel.php');
}

/* Vypisanie Errorov na servery
ini_set('display_errors',  '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/

# <-- Login Script Start-->
if(isset($_POST['Submit'])){
	$spz_login = $_POST['spz_login'];
	$heslo_login = hash('sha256',$_POST['heslo_login']);
	$spz_login = strtoupper($spz_login);
	if (!preg_match('/[A-Za-z]{2}[0-9A-Za-z]{3}[0-9A-Za-z]{2}/', $spz_login)) {
		$error = "Zadal si nesprávnu ŠPZ značku!!!";
		unset($spz_login);
	}else{
		$query = "SELECT * FROM `Users-parkovanie` WHERE `ŠPZ`='$spz_login'";
		$runQuery = mysqli_query($auth,$query);
		$countResultsLogin = mysqli_num_rows($runQuery);
    	if($countResultsLogin > 0){
    	    $row = mysqli_fetch_array($runQuery);
			if($row['Heslo'] == $heslo_login){
				$_SESSION['Logged_in'] = 1;
				$_SESSION['ID'] = $row['ID'];
				$_SESSION['Meno'] = $row['Meno'];
				$_SESSION['Priezvisko'] = $row['Priezvisko'];
				$_SESSION['Heslo'] = $row['Heslo'];
				$_SESSION['ŠPZ'] = $row['ŠPZ'];
				$_SESSION['Trieda'] = $row['Trieda'];
				$_SESSION['Tel.c'] = $row['Tel.c'];
				$_SESSION['Rola'] = $row['Rola'];
				header('Location: Panel.php');
			}else{
				$error = "Nesprávne heslo!!";
			}
			}else{
				unset($spz_login);
				$error = "Používateľ neexistuje!!";
			}
			}
		}
# <-- Login Script End-->

# <-- Register Script Start-->
if(isset($_POST['Register'])){
	$meno = htmlspecialchars($_POST['meno'],ENT_QUOTES);
	$priezvisko = htmlspecialchars($_POST['priezvisko'],ENT_QUOTES);
	$heslo = hash('sha256',$_POST['heslo']);
	$spz = strtoupper($_POST['spz']);
	$query_contol = "SELECT * FROM `Users-parkovanie` WHERE `ŠPZ`=$spz";
	$runQuery_control = mysqli_query($auth,$query_contol);
	if(mysqli_num_rows($runQuery_control) == 0){
		$tel_c = $_POST['tel_c'];
		$trieda = $_POST['trieda'];
		if (!preg_match('/[+]{1}[0-9]{12}/', $tel_c)){
			$error = "Zadal si zlé telefónne číslo!!";
		}else{
			if(!preg_match('/[A-Za-z]{2}[0-9A-Za-z]{3}[0-9A-Za-z]{2}/', $spz)) {
				$error = "Zadal si nesprávnu ŠPZ značku!!";
			}else{
				if(isset($_POST['rules-acc'])){
					$query = "INSERT INTO `Users-parkovanie`(`ID`, `Meno`, `Priezvisko`, `Heslo`, `ŠPZ`, `Trieda`, `Tel.c`, `Povolenie`, `Rola`) VALUES (NULL, '$meno', '$priezvisko', '$heslo', '$spz','$trieda', '$tel_c', '1', '0')";
					$runQuery = mysqli_query($auth, $query);
					if($runQuery){
						$success = "Bol si úspešne zaregistrovaný!! <br> Presmerovanie za 3..2..1..";
						header('Refresh:3; url= ./');
						mysqli_close($auth);
					}else{
						$error = "Niečo sa pokazilo!! Kontaktuj riaditeľa školy!!";
					}
				}else{
					$error = "Musíš súhlasiť s pravidlami parkoviska!!";
				}
			}
		}
	}else{
		$error = "Zadal si ŠPZ, ktorá už je zpísaná!!";
	}
}
# <-- Register Script End-->
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>

<!-- Login Start-->
<?php if(!isset($_GET['register'])) { ?>
	<div class="container-fluid">
		<div class="row main-content bg-success text-center">
			<div class="col-md-4 text-center company__info">
				<span class="company__logo"><img src="skola.png" alt=""></span>
			</div>
			<div class="col-md-8 col-xs-12 col-sm-12 login_form ">
				<div class="container-fluid">
					<div class="row">
						<h2>Prihlásiť sa</h2>
					</div>
					<?php
					if(isset($error)){
                        echo '<div class="alert alert-danger" role="alert">
                        '.$error.'
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                    }
					?>
					<div class="row">
						<form control="" class="form-group" method="post">
							<div class="row">
								<input type="spz" title="Zadaj ŠPZ: LM123AA" class="form__input" style="text-transform: uppercase;" pattern="[A-Za-z]{2}[0-9A-Za-z]{3}[0-9A-Za-z]{2}" placeholder="LM123AA" <?php if(isset($spz_login)){ echo "value='$spz_login'";}?> name="spz_login" id="spz_login" required minlength="7" maxlength="7" autocomplete="off">
						</div>
							<div class="row">
								<!-- <span class="fa fa-lock"></span> -->
								<input type="password" name="heslo_login" id="heslo_login" class="form__input" placeholder="Heslo">
							</div>
							<div class="row">
								<input type="submit" value="Prihlasiť sa" name="Submit" class="btn__form">
							</div>
						</form>
					</div>
					<div class="row">
						<p><a href="?register">Registruj sa tu!</a></p>
					</div>
				</div>
			</div>
	</div>
<!-- Login End -->

<!-- Register Start -->
<?php }else { ?>
	<div class="container-fluid">
		<div class="row main-content bg-success text-center">
			<div class="col-md-4 text-center company__info">
				<span class="company__logo"><img src="skola.png" alt=""></span>
			</div>
			<div class="col-md-8 col-xs-12 col-sm-12 login_form ">
				<div class="container-fluid">
					<div class="row">
						<h2>Registrovať sa</h2>
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
					<div class="row">
						<form control="index.php" class="form-group" method="post">
							<div class="row">
								<input type="text" name="meno" class="form__input" placeholder="Meno" maxlength="255" required>
							</div>
							<div class="row">
								<input type="text" name="priezvisko" class="form__input" placeholder="Priezvisko" maxlength="255" required>
							</div>
							<div class="row">
								<input type="password" name="heslo" class="form__input" placeholder="Heslo" required>
							</div>
							<div class="row">
								<input type="tel" class="form__input" title="Zadaj telefónne číslo: +421900123456" pattern="[+]{1}[0-9]{12}" placeholder="+421900123456" name="tel_c" id="tel_c" required minlength="13" maxlength="13" autocomplete="off">
							</div>
							<div class="row">
								<input type="spz" class="form__input" style="text-transform: uppercase;" title="Zadaj ŠPZ: LM123AA" pattern="[A-Za-z]{2}[0-9A-Za-z]{3}[0-9A-Za-z]{2}" placeholder="LM123AA" name="spz" id="spz" required minlength="7" maxlength="7" autocomplete="off">
							</div>
							<div class="row">
								<select type="text" name="trieda" class="form__input" placeholder="Trieda" required>
									<option value="" disabled selected>Vyber triedu</option>	
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
								<input type="checkbox" id="rules-acc" name="rules-acc" value="rules-acc" required>
  								<label for="rules-acc"> Súhlasím s pravidlami parkoviska</label>
							</div><br>
							<div class="row">
								<input type="submit" value="Register" name="Register" class="btn__form">
							</div>
						</form>
					</div>
					<div class="row">
						<p><a href="./">Prihlás sa tu!</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- Register End -->

<!-- Pravidlá Start -->
  <?php } ?>
		<div class="row main-content text-center pravidla">
		<h1>Pravidlá parkoviska</h1>
		<p><span>1. </span>Musíš mať spravenú rezerváciu na parkovanie vopred. Rezervácie sa resetujú každú sobotu o 00:00.</p>
		<p><span>2. </span>Musíš mať zložité cestovanie z domu do školy.</p>
		<p><span>3. </span>Musíš udržiavať poriadok na parkovisku a v jeho okolí.</p>
		<p><span>4. </span>Musíš parkovať ohľaduplne a len osobné vozidlo.</p>
		<p><span>5. </span>Nesmieš chodiť sám v aute. (Predsa je lepšie zobrať kamaráta a prísť na jednom aute ako zbytočne zaplniť parkovisko)</p>
		<p><span>6. </span>Nesmieš fajčiť v celom areáli školy. Platí aj pre parkovisko.</p>
		<p><span>7. </span>Nesmieš robiť hlúposti. Žiadne pretáčanie kolies, žiadne drifty, žiadne naháňačky...</p>
		</div>
<!-- Pravidlá End -->


<!-- Copyright/Footer Start -->
	<div class="container-fluid text-center footer">
  &copy; Copyright <strong><span>Samuel Fábry</span></strong>. All Rights Reserved
	</div>
<!-- Copyright/Footer End -->
</body>
</html>
