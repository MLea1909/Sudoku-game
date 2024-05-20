<?php 

class Sudoku
{
	protected $imeIgraca, $brojPokusaja, $pocetnaPloca, $trenutnaPloca, $gameOver;
	protected $errorMsg;

	function __construct()
	{
		$this->imeIgraca = false;
		$this->brojPokusaja = 0;
		$this->pocetnaPloca = array(array(0, 0, 4, 0, 0, 0), array(0, 0, 0, 2, 3, 0), array(3, 0, 0, 0, 6, 0),
											array(0, 6, 0, 0, 0, 2), array(0, 2, 1, 0, 0, 0), array(0, 0, 0, 5, 0, 0));
		$this->trenutnaPloca = array(array(0, 0, 4, 0, 0, 0), array(0, 0, 0, 2, 3, 0), array(3, 0, 0, 0, 6, 0),
											array(0, 6, 0, 0, 0, 2), array(0, 2, 1, 0, 0, 0), array(0, 0, 0, 5, 0, 0));
		$this->gameOver = false;
		$this->errorMsg = false;
	}

	function ispisiFormuZaIme()
	{
		// Ispisi formu koja ucitava ime igraca
		?>

		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<title>Sudoku</title>
			<style>
				body { font-family: Arial, sans-serif; }
			</style>
		</head>
		<body>
            <h1>Sudoku 6x6!</h1>

			<form method="post" action="<?php echo htmlentities( $_SERVER['PHP_SELF']); ?>">
				Unesi svoje ime: <input type="text" name="imeIgraca" />
				<button type="submit">Započni igru!</button>
			</form>

			<?php if( $this->errorMsg !== false ) echo '<p>Greška: ' . htmlentities( $this->errorMsg ) . '</p>'; ?>
		</body>
		</html>

		<?php
	}


	function ispisiFormuZaSudoku()
	{
		++$this->brojPokusaja; // Povećaj brojač pokušaja -- broji i neuspješne pokušaje.

		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<title>Sudoku - igra</title>
			<style>
				body { font-family: Arial, sans-serif; }
				table, th, td {	border: 1px solid black;
  								border-collapse: collapse;
								text-align: center;
								font-size: 20px;
								}
				colgroup, tbody { border: solid medium; }
			</style>
		</head>
		<body>
			<h1>Sudoku 6x6!</h1>
			<p>
				Igrač: <?php echo htmlentities( $this->imeIgraca ); ?>
				<br />
				Pokušaj broj: <?php echo $this->brojPokusaja; ?>
			</p>

			<table style="width: 250px; height: 200px;">
			<colgroup><col><col><col>
  			<colgroup><col><col><col>
			<?php
				for($i = 0; $i < 6; $i++){
					if($i%2 == 0){
						echo '<tbody>';
					}
					echo '<tr>';
					for($j = 0; $j < 6; $j++){
						echo '<td>';
						$this->upisiBrojNaPlocu($i,$j);
						echo '</td>';
					}
					echo '</tr>';
				}
			?>
			</table>

			<br>

			<form method="post" action="<?php echo htmlentities( $_SERVER['PHP_SELF']); ?>">
				<input type="radio" name="odabir_akcije" value="unesi">
					Unesi broj <input type="text" name="broj">
					u redak <select name="unesi_redak" >
						<option value="0">1</option>
						<option value="1">2</option>
						<option value="2">3</option>
						<option value="3">4</option>
						<option value="4">5</option>
						<option value="5">6</option>
					</select>
					i stupac <select name="unesi_stupac" >
						<option value="0">1</option>
						<option value="1">2</option>
						<option value="2">3</option>
						<option value="3">4</option>
						<option value="4">5</option>
						<option value="5">6</option>
					</select>
				<br>
				<br>
				<input type="radio" name="odabir_akcije" value="obrisi">
					Obriši broj iz retka
					<select name="obrisi_redak" >
						<option value="0">1</option>
						<option value="1">2</option>
						<option value="2">3</option>
						<option value="3">4</option>
						<option value="4">5</option>
						<option value="5">6</option>
					</select>
					i stupca <select name="obrisi_stupac" >
						<option value="0">1</option>
						<option value="1">2</option>
						<option value="2">3</option>
						<option value="3">4</option>
						<option value="4">5</option>
						<option value="5">6</option>
					</select>
				<br>
				<br>
				<input type="radio" name="odabir_akcije" value="novo"> Želim sve ispočetka!
				<br>
				<br>
				<button type="submit">Izvrši akciju!</button>
			</form>

			<?php if( $this->errorMsg !== false ) echo '<p>Greška: ' . htmlentities( $this->errorMsg ) . '</p>'; ?>
		</body>
		</html>

		<?php
	}

	function upisiBrojNaPlocu($redak, $stupac){
		if ($this->trenutnaPloca[$redak][$stupac] === 0)
			echo ' ';
		elseif ($this->trenutnaPloca[$redak][$stupac] === $this->pocetnaPloca[$redak][$stupac])
			echo '<b>' . $this->trenutnaPloca[$redak][$stupac]  . '</b>';
		else {
			$ok = $this->provjeriKorektnost($redak, $stupac);
			if($ok === true){
				echo '<font color="blue">' . $this->trenutnaPloca[$redak][$stupac]  . '</font>';
				//boji broj u plavo
			}else{
				echo '<font color="red">' . $this->trenutnaPloca[$redak][$stupac]  . '</font>';
				//boji broj u crveno
			}
		}

	}


	function provjeriKorektnost($redak, $stupac){
		//provjerava je li unos korektan
		
		//provjera retka
		for($i = 0; $i < 6; $i++){
			if($stupac !== $i && (int)$this->trenutnaPloca[$redak][$stupac] === (int)$this->trenutnaPloca[$redak][$i] ){
				return false;
			}
		}
				
		//provjera stupca
		for($i = 0; $i < 6; $i++){
			if($redak !== $i && (int)$this->trenutnaPloca[$redak][$stupac] === (int)$this->trenutnaPloca[$i][$stupac] ){
				return false;
			}
		}
		
		//provjera pravokutnika
		$r = $redak - $redak % 2;
    	$s = $stupac - $stupac % 3;
    	for ($i = $r; $i < $r + 2; $i++){
        	for ($j = $s; $j < $s + 3; $j++){
            	if ($stupac !== $i && $redak !== $i && (int)$this->trenutnaPloca[$i][$j] === (int)$this->trenutnaPloca[$redak][$stupac]){
                	return false;
            	}
        	}
    	}

		return true;
	}

	function ispisiCestitku()
	{
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<title>Sudoku - Bravo!</title>
			<style>
				body { font-family: Arial, sans-serif; }
			</style>
		</head>
		<body>
			<p>
				Bravo, <?php echo htmlentities( $this->imeIgraca ); ?>!
				<br />
				Sudoku igra je riješena u <?php echo $this->brojPokusaja; ?> pokušaja!
			</p>
		</body>
		</html>

		<?php
	}


	function get_imeIgraca()
	{
		// Je li već definirano ime igrača?
		if( $this->imeIgraca !== false )
			return $this->imeIgraca;

		// Možda nam se upravo sad šalje ime igrača?
		if( isset( $_POST['imeIgraca'] ) )
		{
			// Šalje nam se ime igrača. Provjeri da li se sastoji samo od slova.
			if( !preg_match( '/^[a-zA-Z]{1,20}$/', $_POST['imeIgraca'] ) )
			{
				// Nije dobro ime. Dakle nemamo ime igrača.
				$this->errorMsg = 'Ime igrača treba imati između 1 i 20 slova.';
				return false;
			}
			else
			{
				// Dobro je ime. Spremi ga u objekt.
				$this->imeIgraca = $_POST['imeIgraca'];
				return $this->imeIgraca;
			}
		}

		// Ne šalje nam se sad ime. Dakle nemamo ga uopće.
		return false;
	}


	function obradiPotez()
	{
		// Vraća false ako nije bio (ispravan) unos, true ako je bilo promjena.

		// Da li je igrač uopće odabrao iduci potez?
		if( isset( $_POST['odabir_akcije'] ) )
		{
			$odabir =  $_POST['odabir_akcije'];

			if($odabir === "unesi" ){
				if( isset( $_POST['broj'] ) ){

					// Je. Da li je pokušaj broj između 1 i 6?
					$options = array( 'options' => array( 'min_range' => 1, 'max_range' => 6 ) );

					if( filter_var( $_POST['broj'], FILTER_VALIDATE_INT, $options ) === false )
					{
						// Nije unesen broj između 1 i 6.
						$this->errorMsg = 'Trebate unijeti broj između 1 i 6.';
						return false;
					}

					$b = $_POST['broj'];
					$u_redak = $_POST['unesi_redak'];
					$u_stupac = $_POST['unesi_stupac'];
					if($this->pocetnaPloca[$u_redak][$u_stupac] !== 0){
						$this->errorMsg = 'Ne smijete mijenjati početnu tablicu!';
						return -1;
					}else{
						$this->trenutnaPloca[$u_redak][$u_stupac] = $b;

						//provjera pobjede
						for($i = 0; $i < 6; $i++){
							for($j = 0; $j < 6; $j++){
								if($this->trenutnaPloca[$i][$j] === 0 || $this->provjeriKorektnost($i, $j) === false){
									return 1;
								}
							}
						}
						return 0;
					}
				}

			}elseif($odabir === "obrisi" ){
				$o_redak = $_POST['obrisi_redak'];
				$o_stupac = $_POST['obrisi_stupac'];

				if($this->pocetnaPloca[$o_redak][$o_stupac] !== 0){
					$this->errorMsg = 'Ne smijete mijenjati početnu tablicu!';
					return -1;
				}else{
					$this->trenutnaPloca[$o_redak][$o_stupac] = 0;
					return 1;
				}

			}elseif($odabir === "novo" ){
				$this->trenutnaPloca = $this->pocetnaPloca;
				$this->brojPokusaja = 0;
				return 1;
			}
		} elseif ($this->brojPokusaja >= 1) {
			$this->errorMsg = 'Odaberite jednu od tri mogućnosti za svoj idući potez!';
			return -1;
		}

		// Igrač nije pokušao pogoditi broj.
		return -1;
	}


	function isGameOver() { return $this->gameOver; }


	function run()
	{
		// Funkcija obavlja "jedan potez" u igri.
		// Prvo, resetiraj poruke o greški.
		$this->errorMsg = false;

		// Prvo provjeri jel imamo uopće ime igraca
		if( $this->get_imeIgraca() === false )
		{
			// Ako nemamo ime igrača, ispiši formu za unos imena i to je kraj.
			$this->ispisiFormuZaIme();
			return;
		}

		//Ako je igrač odabrao neku opciju, provjerimo što se dogodilo s tim pokušajem.
		$rez = $this->obradiPotez();
		

		if( $rez === 0 )
		{
			// Ako je igrač riješio sudoku, ispiši mu čestitku.
			$this->ispisiCestitku();
			$this->gameOver = true;
		}
		else
			$this->ispisiFormuZaSudoku();
	}
};


// --------------------------------------------------------------------------------------------

session_start();

if( !isset( $_SESSION['igra'] ) )
{
	// Ako igra još nije započela, stvori novi objekt tipa Sudoku i spremi ga u $_SESSION
	$igra = new Sudoku();
	$_SESSION['igra'] = $igra;
}
else
{
	// Ako je igra već ranije započela, dohvati ju iz $_SESSION-a	
	$igra = $_SESSION['igra'];
}

// Izvedi jedan korak u igri, u kojoj god fazi ona bila.
$igra->run();

if( $igra->isGameOver() )
{
	// Kraj igre -> prekini session.
	session_unset();
	session_destroy();
}
else
{
	// Igra još nije gotova -> spremi trenutno stanje u SESSION
	$_SESSION['igra'] = $igra;	
}