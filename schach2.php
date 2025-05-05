<?php
// Autor: Wölfel
// Version: 2.1
// Datum: 07.01.

//BauerW = 1
//TurmW = 2
//SpringerW = 3
//LäuferW = 4
//KönigW = 5
//DameW = 6
//BauerS = 7
//TurmS = 8
//SpringerS = 9
//LäuferS = 10
//KönigS = 11
//DameS = 12

//Session Start
session_start();
$sname = isset($_SESSION['sname']) ? $_SESSION['sname']: '';
$sid = isset($_SESSION['sid']) ? $_SESSION['sid']: '';
$koeniggezogen = isset($_SESSION['koeniggezogen']) ? $_SESSION['koeniggezogen']: '';

//Post Datenuebergabe
$aktion = isset($_POST['aktion']) ? $_POST['aktion'] : '';
$uebergabeAusgewaehltesFeld = isset($_POST['uebergabeAusgewaehltesFeld']) ? $_POST['uebergabeAusgewaehltesFeld'] : '';
$uebergabeAusgewaehlteFigur = isset($_POST['uebergabeAusgewaehlteFigur']) ? $_POST['uebergabeAusgewaehlteFigur'] : '';
$uebergabeZugFeld = isset($_POST['uebergabeZugFeld']) ? $_POST['uebergabeZugFeld'] : '';
$wert = isset($_POST['wert']) ? $_POST['wert'] : '';

if($aktion == '' && $sname != '' && $sid != ''){
	$aktion = 'start';
}
	
//Datenbankverbindung
$meinedb = new mysqli("localhost", "root", "", "schach");
if ($meinedb->connect_errno) {
	die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}
	
//Spielstand auslesen
$brettID = 1;
$ergebnis = $meinedb->query("Select * from brett3 Where bid = ".$brettID);
if ($ergebnis) {
	$row = $ergebnis->fetch_assoc();
	if ($row) {
		$spielStand = array_values($row);
		$brett = array_slice($spielStand, -64);
	} else {
		echo "Keine Daten gefunden für Brett ID " . $brettID;
	}
} else {
	die("Fehler bei der Abfrage: " . $meinedb->error);
}
$dran = $spielStand[3];
$s1id = $spielStand[1];
$s2id = $spielStand[2];
	
function felderZumRand($feld){
	$felderNachOben = floor($feld/8-0.1);
	$felderNachUnten = 7 - $felderNachOben;
	$felderNachLinks = ($feld % 8) - 1;
	if($feld % 8 == 0){
		$felderNachLinks = 7;
	}
	$felderNachRechts = 7 - $felderNachLinks;
	$felderNachObenRechts = ($felderNachRechts < $felderNachOben) ? $felderNachRechts : $felderNachOben;
	$felderNachObenLinks = ($felderNachLinks < $felderNachOben) ? $felderNachLinks : $felderNachOben;
	$felderNachUntenRechts = ($felderNachRechts < $felderNachUnten) ? $felderNachRechts : $felderNachUnten;
	$felderNachUntenLinks = ($felderNachLinks < $felderNachUnten) ? $felderNachLinks : $felderNachUnten;
	$felderZRand = array($felderNachOben, $felderNachUnten, $felderNachRechts, $felderNachLinks, $felderNachObenRechts, $felderNachObenLinks, $felderNachUntenRechts, $felderNachUntenLinks);
	return $felderZRand;
}
$moeglicheZugfelder = array();
function moeglicheZugfelder($brett, $feld, $koeniggezogen, $nurSchlagzuege){
	$moeglicheZugfelder = array();
	$figur = $brett[$feld-1];
	$fzr = felderZumRand($feld);
	switch($figur){
		case 1:
		//BauerW
			if($brett[$feld-9] == 0 && $nurSchlagzuege == 0){
				array_push($moeglicheZugfelder, $feld-8);
			}
			if($feld > 48 && $feld < 57 && $brett[$feld-17] == 0 && $brett[$feld-9] == 0 && $nurSchlagzuege == 0){
				array_push($moeglicheZugfelder, $feld-16);
			}
			if($brett[$feld-8] > 6){
				array_push($moeglicheZugfelder, $feld-7);
			}
			if($brett[$feld-10] > 6){
				array_push($moeglicheZugfelder, $feld-9);
			}
		break;
		case 2:
		//TurmW
			$a = 1;
			while($a<=$fzr[2]){
				if($feld+$a <= 64){
					if($brett[$feld-1+$a] == 0){
						array_push($moeglicheZugfelder, $feld+$a);
						$a++;
					}else if($brett[$feld-1+$a] > 6){
						array_push($moeglicheZugfelder, $feld+$a);
						$a = 10;
					}else{
						$a = 10;
					}
				}else{
					$a = 10;
				}
			}
			$b = 1;
			while($b<=$fzr[3]){
				if($feld-$b >= 1){
					if($brett[$feld-1-$b] == 0){
						array_push($moeglicheZugfelder, $feld-$b);
						$b++;
					}else if($brett[$feld-1-$b] > 6){
						array_push($moeglicheZugfelder, $feld-$b);
						$b = 10;
					}else{
						$b = 10;
					}
				}else{
					$b = 10;
				}
			}
			$c = 1;
			while($c<=$fzr[0]){
				if($feld-8*$c >= 1){
					if($brett[$feld-1-8*$c] == 0){
						array_push($moeglicheZugfelder, $feld-8*$c);
						$c++;
					}else if($brett[$feld-1-8*$c] > 6){
						array_push($moeglicheZugfelder, $feld-8*$c);
						$c = 10;
					}else{
						$c = 10;
					}
				}else{
					$c = 10;
				}
			}
			$d = 1;
			while($d<=$fzr[1]){
				if($feld+8*$d <= 64){
					if($brett[$feld-1+8*$d] == 0){
						array_push($moeglicheZugfelder, $feld+8*$d);
						$d++;
					}else if($brett[$feld-1+8*$d] > 6){
						array_push($moeglicheZugfelder, $feld+8*$d);
						$d = 10;
					}else{
						$d = 10;
					}
				}else{
					$d = 10;
				}
			}
		break;
		case 3:
		//SpringerW
			if($fzr[0]>1 && $fzr[2]>=1 && ($brett[$feld-16] == 0 || $brett[$feld-16] > 6)){
				array_push($moeglicheZugfelder, $feld-15);
			}
			if($fzr[0] >= 1 && $fzr[2] > 1 && ($brett[$feld-7] == 0 || $brett[$feld-7] > 6)){
				array_push($moeglicheZugfelder, $feld-6);
			}
			if($fzr[0]>1 && $fzr[3]>=1 && ($brett[$feld-18] == 0 || $brett[$feld-18] > 6)){
				array_push($moeglicheZugfelder, $feld-17);
			}
			if($fzr[0] >= 1 && $fzr[3] > 1 && ($brett[$feld-11] == 0 || $brett[$feld-11] > 6)){
				array_push($moeglicheZugfelder, $feld-10);
			}
			if($fzr[1]>1 && $fzr[2]>=1 && ($brett[$feld+16] == 0 || $brett[$feld+16] > 6)){
				array_push($moeglicheZugfelder, $feld+17);
			}
			if($fzr[1] >= 1 && $fzr[2] > 1 && ($brett[$feld+9] == 0 || $brett[$feld+9] > 6)){
				array_push($moeglicheZugfelder, $feld+10);
			}
			if($fzr[1]>1 && $fzr[3]>=1 && ($brett[$feld+14] == 0 || $brett[$feld+14] > 6)){
				array_push($moeglicheZugfelder, $feld+15);
			}
			if($fzr[1] >= 1 && $fzr[3] > 1 && ($brett[$feld+5] == 0 || $brett[$feld+5] > 6)){
				array_push($moeglicheZugfelder, $feld+6);
			}
		break;
		case 4:
		//LäuferW
			$a = 1;
			while($a<=$fzr[4]){
				if($feld-7*$a >= 0){
					if($brett[$feld-1-7*$a] == 0){
						array_push($moeglicheZugfelder, $feld-7*$a);
						$a++;
					}else if($brett[$feld-1-7*$a] > 6){
						array_push($moeglicheZugfelder, $feld-7*$a);
						$a = 10;
					}else{
						$a = 10;
					}
				}else{
					$a = 10;
				}
			}
			$b = 1;
			while($b<=$fzr[5]){
				if($feld-9*$b >= 0){
					if($brett[$feld-1-9*$b] == 0){
						array_push($moeglicheZugfelder, $feld-9*$b);
						$b++;
					}else if($brett[$feld-1-9*$b] > 6){
						array_push($moeglicheZugfelder, $feld-9*$b);
						$b = 10;
					}else{
						$b = 10;
					}
				}else{
					$b = 10;
				}
			}
			$c = 1;
			while($c<=$fzr[6]){
				if($feld+9*$c <= 64){
					if($brett[$feld-1+9*$c] == 0){
						array_push($moeglicheZugfelder, $feld+9*$c);
						$c++;
					}else if($brett[$feld-1+9*$c] > 6){
						array_push($moeglicheZugfelder, $feld+9*$c);
						$c = 10;
					}else{
						$c = 10;
					}
				}else{
					$c = 10;
				}
			}
			$d = 1;
			while($d<=$fzr[7]){
				if($feld+7*$d <= 64){
					if($brett[$feld-1+7*$d] == 0){
						array_push($moeglicheZugfelder, $feld+7*$d);
						$d++;
					}else if($brett[$feld-1+7*$d] > 6){
						array_push($moeglicheZugfelder, $feld+7*$d);
						$d = 10;
					}else{
						$d = 10;
					}
				}else{
					$d = 10;
				}
			}
		break;
		case 5:
		//KönigW
			$moeglicheZugfelderSchwarz = array();
			for($i=0;$i<64;$i++){
				if($brett[$i] > 6){
					array_push($moeglicheZugfelderSchwarz, moeglicheZugfelder($brett, $i+1, $koeniggezogen, 1));
				}
			}
			$moeglicheZugfelderSchwarz = array_merge(...$moeglicheZugfelderSchwarz);
			if($fzr[0] > 0 && ($brett[$feld-1-8] == 0 || $brett[$feld-1-8] > 6) && !(in_array($feld-8, $moeglicheZugfelderSchwarz))){
				array_push($moeglicheZugfelder, $feld-8);
			}
			if($fzr[1] > 0 && ($brett[$feld-1+8] == 0 || $brett[$feld-1+8] > 6) && !(in_array($feld+8, $moeglicheZugfelderSchwarz))){
				array_push($moeglicheZugfelder, $feld+8);
			}
			if($fzr[2] > 0 && ($brett[$feld-1+1] == 0 || $brett[$feld-1+1] > 6) && !(in_array($feld+1, $moeglicheZugfelderSchwarz))){
				array_push($moeglicheZugfelder, $feld+1);
			}
			if($fzr[3] > 0 && ($brett[$feld-1-1] == 0 || $brett[$feld-1-1] > 6) && !(in_array($feld-1, $moeglicheZugfelderSchwarz))){
				array_push($moeglicheZugfelder, $feld-1);
			}
			if($fzr[4] > 0 && ($brett[$feld-1-7] == 0 || $brett[$feld-1-7] > 6) && !(in_array($feld-7, $moeglicheZugfelderSchwarz))){
				array_push($moeglicheZugfelder, $feld-7);
			}
			if($fzr[5] > 0 && ($brett[$feld-1-9] == 0 || $brett[$feld-1-9] > 6) && !(in_array($feld-9, $moeglicheZugfelderSchwarz))){
				array_push($moeglicheZugfelder, $feld-9);
			}
			if($fzr[6] > 0 && ($brett[$feld-1+9] == 0 || $brett[$feld-1+9] > 6) && !(in_array($feld+9, $moeglicheZugfelderSchwarz))){
				array_push($moeglicheZugfelder, $feld+9);
			}
			if($fzr[7] > 0 && ($brett[$feld-1+7] == 0 || $brett[$feld-1+7] > 6) && !(in_array($feld+7, $moeglicheZugfelderSchwarz))){
				array_push($moeglicheZugfelder, $feld+7);
			}
			//Rochade
			if($koeniggezogen == '' && $brett[$feld] == 0 && $brett[$feld+1] == 0 && $brett[$feld+2] == 2){
				array_push($moeglicheZugfelder, $feld+2);
			}
			if($koeniggezogen == '' && $brett[$feld-2] == 0 && $brett[$feld-3] == 0 && $brett[$feld-4] == 0 && $brett[$feld-5] == 2){
				array_push($moeglicheZugfelder, $feld-2);
			}
		break;
		case 6:
		//DameW
			$a = 1;
			while($a<=$fzr[2]){
				if($feld+$a <= 64){
					if($brett[$feld-1+$a] == 0){
						array_push($moeglicheZugfelder, $feld+$a);
						$a++;
					}else if($brett[$feld-1+$a] > 6){
						array_push($moeglicheZugfelder, $feld+$a);
						$a = 10;
					}else{
						$a = 10;
					}
				}else{
					$a = 10;
				}
			}
			$b = 1;
			while($b<=$fzr[3]){
				if($feld-$b >= 1){
					if($brett[$feld-1-$b] == 0){
						array_push($moeglicheZugfelder, $feld-$b);
						$b++;
					}else if($brett[$feld-1-$b] > 6){
						array_push($moeglicheZugfelder, $feld-$b);
						$b = 10;
					}else{
						$b = 10;
					}
				}else{
					$b = 10;
				}
			}
			$c = 1;
			while($c<=$fzr[0]){
				if($feld-8*$c >= 1){
					if($brett[$feld-1-8*$c] == 0){
						array_push($moeglicheZugfelder, $feld-8*$c);
						$c++;
					}else if($brett[$feld-1-8*$c] > 6){
						array_push($moeglicheZugfelder, $feld-8*$c);
						$c = 10;
					}else{
						$c = 10;
					}
				}else{
					$c = 10;
				}
			}
			$d = 1;
			while($d<=$fzr[1]){
				if($feld+8*$d <= 64){
					if($brett[$feld-1+8*$d] == 0){
						array_push($moeglicheZugfelder, $feld+8*$d);
						$d++;
					}else if($brett[$feld-1+8*$d] > 6){
						array_push($moeglicheZugfelder, $feld+8*$d);
						$d = 10;
					}else{
						$d = 10;
					}
				}else{
					$d = 10;
				}
			}
			$a = 1;
			while($a<=$fzr[4]){
				if($feld-7*$a >= 0){
					if($brett[$feld-1-7*$a] == 0){
						array_push($moeglicheZugfelder, $feld-7*$a);
						$a++;
					}else if($brett[$feld-1-7*$a] > 6){
						array_push($moeglicheZugfelder, $feld-7*$a);
						$a = 10;
					}else{
						$a = 10;
					}
				}else{
					$a = 10;
				}
			}
			$b = 1;
			while($b<=$fzr[5]){
				if($feld-9*$b >= 0){
					if($brett[$feld-1-9*$b] == 0){
						array_push($moeglicheZugfelder, $feld-9*$b);
						$b++;
					}else if($brett[$feld-1-9*$b] > 6){
						array_push($moeglicheZugfelder, $feld-9*$b);
						$b = 10;
					}else{
						$b = 10;
					}
				}else{
					$b = 10;
				}
			}
			$c = 1;
			while($c<=$fzr[6]){
				if($feld+9*$c <= 64){
					if($brett[$feld-1+9*$c] == 0){
						array_push($moeglicheZugfelder, $feld+9*$c);
						$c++;
					}else if($brett[$feld-1+9*$c] > 6){
						array_push($moeglicheZugfelder, $feld+9*$c);
						$c = 10;
					}else{
						$c = 10;
					}
				}else{
					$c = 10;
				}
			}
			$d = 1;
			while($d<=$fzr[7]){
				if($feld+7*$d <= 64){
					if($brett[$feld-1+7*$d] == 0){
						array_push($moeglicheZugfelder, $feld+7*$d);
						$d++;
					}else if($brett[$feld-1+7*$d] > 6){
						array_push($moeglicheZugfelder, $feld+7*$d);
						$d = 10;
					}else{
						$d = 10;
					}
				}else{
					$d = 10;
				}
			}
		break;
		case 7:
		//BauerS
			if($brett[$feld+7] == 0 && $nurSchlagzuege == 0){
				array_push($moeglicheZugfelder, $feld+8);
			}
			if($feld > 8 && $feld < 17 && $brett[$feld+15] == 0 && $brett[$feld+7] == 0 && $nurSchlagzuege == 0){
				array_push($moeglicheZugfelder, $feld+16);
			}
			if($brett[$feld+6] < 7 && $brett[$feld+6] != 0){
				array_push($moeglicheZugfelder, $feld+7);
			}
			if($brett[$feld+8] < 7 && $brett[$feld+8] != 0){
				array_push($moeglicheZugfelder, $feld+9);
			}
		break;
		case 8:
			//TurmS
			$a = 1;
			while($a<=$fzr[2]){
				if($feld+$a <= 64){
					if($brett[$feld-1+$a] == 0){
						array_push($moeglicheZugfelder, $feld+$a);
						$a++;
					}else if($brett[$feld-1+$a] < 7){
						array_push($moeglicheZugfelder, $feld+$a);
						$a = 10;
					}else{
						$a = 10;
					}
				}else{
					$a = 10;
				}
			}
			$b = 1;
			while($b<=$fzr[3]){
				if($feld-$b >= 1){
					if($brett[$feld-1-$b] == 0){
						array_push($moeglicheZugfelder, $feld-$b);
						$b++;
					}else if($brett[$feld-1-$b] < 7){
						array_push($moeglicheZugfelder, $feld-$b);
						$b = 10;
					}else{
						$b = 10;
					}
				}else{
					$b = 10;
				}
			}
			$c = 1;
			while($c<=$fzr[0]){
				if($feld-8*$c >= 1){
					if($brett[$feld-1-8*$c] == 0){
						array_push($moeglicheZugfelder, $feld-8*$c);
						$c++;
					}else if($brett[$feld-1-8*$c] < 7){
						array_push($moeglicheZugfelder, $feld-8*$c);
						$c = 10;
					}else{
						$c = 10;
					}
				}else{
					$c = 10;
				}
			}
			$d = 1;
			while($d<=$fzr[1]){
				if($feld+8*$d <= 64){
					if($brett[$feld-1+8*$d] == 0){
						array_push($moeglicheZugfelder, $feld+8*$d);
						$d++;
					}else if($brett[$feld-1+8*$d] < 7){
						array_push($moeglicheZugfelder, $feld+8*$d);
						$d = 10;
					}else{
						$d = 10;
					}
				}else{
					$d = 10;
				}
			}
		break;
		case 9:
		//SpringerS
			if($fzr[0]>1 && $fzr[2]>=1 && ($brett[$feld-16] == 0 || $brett[$feld-16] < 7)){
				array_push($moeglicheZugfelder, $feld-15);
			}
			if($fzr[0] >= 1 && $fzr[2] > 1 && ($brett[$feld-7] == 0 || $brett[$feld-7] < 7)){
				array_push($moeglicheZugfelder, $feld-6);
			}
			if($fzr[0]>1 && $fzr[3]>=1 && ($brett[$feld-18] == 0 || $brett[$feld-18] < 7)){
				array_push($moeglicheZugfelder, $feld-17);
			}
			if($fzr[0] >= 1 && $fzr[3] > 1 && ($brett[$feld-11] == 0 || $brett[$feld-11] < 7)){
				array_push($moeglicheZugfelder, $feld-10);
			}
			if($fzr[1]>1 && $fzr[2]>=1 && ($brett[$feld+16] == 0 || $brett[$feld+16] < 7)){
				array_push($moeglicheZugfelder, $feld+17);
			}
			if($fzr[1] >= 1 && $fzr[2] > 1 && ($brett[$feld+9] == 0 || $brett[$feld+9] < 7)){
				array_push($moeglicheZugfelder, $feld+10);
			}
			if($fzr[1]>1 && $fzr[3]>=1 && ($brett[$feld+14] == 0 || $brett[$feld+14] < 7)){
				array_push($moeglicheZugfelder, $feld+15);
			}
			if($fzr[1] >= 1 && $fzr[3] > 1 && ($brett[$feld+5] == 0 || $brett[$feld+5] < 7)){
				array_push($moeglicheZugfelder, $feld+6);
			}
		break;
		case 10:
		//LäuferS
			$a = 1;
			while($a<=$fzr[4]){
				if($feld-7*$a >= 0){
					if($brett[$feld-1-7*$a] == 0){
						array_push($moeglicheZugfelder, $feld-7*$a);
						$a++;
					}else if($brett[$feld-1-7*$a] < 7){
						array_push($moeglicheZugfelder, $feld-7*$a);
						$a = 10;
					}else{
						$a = 10;
					}
				}else{
					$a = 10;
				}
			}
			$b = 1;
			while($b<=$fzr[5]){
				if($feld-9*$b >= 0){
					if($brett[$feld-1-9*$b] == 0){
						array_push($moeglicheZugfelder, $feld-9*$b);
						$b++;
					}else if($brett[$feld-1-9*$b] < 7){
						array_push($moeglicheZugfelder, $feld-9*$b);
						$b = 10;
					}else{
						$b = 10;
					}
				}else{
					$b = 10;
				}
			}
			$c = 1;
			while($c<=$fzr[6]){
				if($feld+9*$c <= 64){
					if($brett[$feld-1+9*$c] == 0){
						array_push($moeglicheZugfelder, $feld+9*$c);
						$c++;
					}else if($brett[$feld-1+9*$c] < 7){
						array_push($moeglicheZugfelder, $feld+9*$c);
						$c = 10;
					}else{
						$c = 10;
					}
				}else{
					$c = 10;
				}
			}
			$d = 1;
			while($d<=$fzr[7]){
				if($feld+7*$d <= 64){
					if($brett[$feld-1+7*$d] == 0){
						array_push($moeglicheZugfelder, $feld+7*$d);
						$d++;
					}else if($brett[$feld-1+7*$d] < 7){
						array_push($moeglicheZugfelder, $feld+7*$d);
						$d = 10;
					}else{
						$d = 10;
					}
				}else{
					$d = 10;
				}
			}
		break;
		case 11:
		//KönigS
			if($nurSchlagzuege == 0){
				$moeglicheZugfelderWeiß = array();
				for($i=0;$i<64;$i++){
					if($brett[$i] < 7 && $brett[$i] != 0){
						array_push($moeglicheZugfelderWeiß, moeglicheZugfelder($brett, $i+1, $koeniggezogen, 1));
					}
				}
				$moeglicheZugfelderWeiß = array_merge(...$moeglicheZugfelderWeiß);
				if($fzr[0] > 0 && ($brett[$feld-1-8] == 0 || $brett[$feld-1-8] < 7) && !(in_array($feld-8, $moeglicheZugfelderWeiß))){
					array_push($moeglicheZugfelder, $feld-8);
				}
				if($fzr[1] > 0 && ($brett[$feld-1+8] == 0 || $brett[$feld-1+8] < 7) && !(in_array($feld+8, $moeglicheZugfelderWeiß))){
					array_push($moeglicheZugfelder, $feld+8);
				}
				if($fzr[2] > 0 && ($brett[$feld-1+1] == 0 || $brett[$feld-1+1] < 7) && !(in_array($feld+1, $moeglicheZugfelderWeiß))){
					array_push($moeglicheZugfelder, $feld+1);
				}
				if($fzr[3] > 0 && ($brett[$feld-1-1] == 0 || $brett[$feld-1-1] < 7) && !(in_array($feld-1, $moeglicheZugfelderWeiß))){
					array_push($moeglicheZugfelder, $feld-1);
				}
				if($fzr[4] > 0 && ($brett[$feld-1-7] == 0 || $brett[$feld-1-7] < 7) && !(in_array($feld-7, $moeglicheZugfelderWeiß))){
					array_push($moeglicheZugfelder, $feld-7);
				}
				if($fzr[5] > 0 && ($brett[$feld-1-9] == 0 || $brett[$feld-1-9] < 7) && !(in_array($feld-9, $moeglicheZugfelderWeiß))){
					array_push($moeglicheZugfelder, $feld-9);
				}
				if($fzr[6] > 0 && ($brett[$feld-1+9] == 0 || $brett[$feld-1+9] < 7) && !(in_array($feld+9, $moeglicheZugfelderWeiß))){
					array_push($moeglicheZugfelder, $feld+9);
				}
				if($fzr[7] > 0 && ($brett[$feld-1+7] == 0 || $brett[$feld-1+7] < 7) && !(in_array($feld+7, $moeglicheZugfelderWeiß))){
					array_push($moeglicheZugfelder, $feld+7);
				}
				//Rochade
				if($koeniggezogen == '' && $brett[$feld] == 0 && $brett[$feld+1] == 0 && $brett[$feld+2] == 8){
					array_push($moeglicheZugfelder, $feld+2);
				}
				if($koeniggezogen == '' && $brett[$feld-2] == 0 && $brett[$feld-3] == 0 && $brett[$feld-4] == 0 && $brett[$feld-5] == 8){
					array_push($moeglicheZugfelder, $feld-2);
				}
			}
		break;
		case 12:
		//DameS
			$a = 1;
			while($a<=$fzr[2]){
				if($feld+$a <= 64){
					if($brett[$feld-1+$a] == 0){
						array_push($moeglicheZugfelder, $feld+$a);
						$a++;
					}else if($brett[$feld-1+$a] < 7){
						array_push($moeglicheZugfelder, $feld+$a);
						$a = 10;
					}else{
						$a = 10;
					}
				}else{
					$a = 10;
				}
			}
			$b = 1;
			while($b<=$fzr[3]){
				if($feld-$b >= 1){
					if($brett[$feld-1-$b] == 0){
						array_push($moeglicheZugfelder, $feld-$b);
						$b++;
					}else if($brett[$feld-1-$b] < 7){
						array_push($moeglicheZugfelder, $feld-$b);
						$b = 10;
					}else{
						$b = 10;
					}
				}else{
					$b = 10;
				}
			}
			$c = 1;
			while($c<=$fzr[0]){
				if($feld-8*$c >= 1){
					if($brett[$feld-1-8*$c] == 0){
						array_push($moeglicheZugfelder, $feld-8*$c);
						$c++;
					}else if($brett[$feld-1-8*$c] < 7){
						array_push($moeglicheZugfelder, $feld-8*$c);
						$c = 10;
					}else{
						$c = 10;
					}
				}else{
					$c = 10;
				}
			}
			$d = 1;
			while($d<=$fzr[1]){
				if($feld+8*$d <= 64){
					if($brett[$feld-1+8*$d] == 0){
						array_push($moeglicheZugfelder, $feld+8*$d);
						$d++;
					}else if($brett[$feld-1+8*$d] < 7){
						array_push($moeglicheZugfelder, $feld+8*$d);
						$d = 10;
					}else{
						$d = 10;
					}
				}else{
					$d = 10;
				}
			}
			$a = 1;
			while($a<=$fzr[4]){
				if($feld-7*$a >= 0){
					if($brett[$feld-1-7*$a] == 0){
						array_push($moeglicheZugfelder, $feld-7*$a);
						$a++;
					}else if($brett[$feld-1-7*$a] < 7){
						array_push($moeglicheZugfelder, $feld-7*$a);
						$a = 10;
					}else{
						$a = 10;
					}
				}else{
					$a = 10;
				}
			}
			$b = 1;
			while($b<=$fzr[5]){
				if($feld-9*$b >= 0){
					if($brett[$feld-1-9*$b] == 0){
						array_push($moeglicheZugfelder, $feld-9*$b);
						$b++;
					}else if($brett[$feld-1-9*$b] < 7){
						array_push($moeglicheZugfelder, $feld-9*$b);
						$b = 10;
					}else{
						$b = 10;
					}
				}else{
					$b = 10;
				}
			}
			$c = 1;
			while($c<=$fzr[6]){
				if($feld+9*$c <= 64){
					if($brett[$feld-1+9*$c] == 0){
						array_push($moeglicheZugfelder, $feld+9*$c);
						$c++;
					}else if($brett[$feld-1+9*$c] < 7){
						array_push($moeglicheZugfelder, $feld+9*$c);
						$c = 10;
					}else{
						$c = 10;
					}
				}else{
					$c = 10;
				}
			}
			$d = 1;
			while($d<=$fzr[7]){
				if($feld+7*$d <= 64){
					if($brett[$feld-1+7*$d] == 0){
						array_push($moeglicheZugfelder, $feld+7*$d);
						$d++;
					}else if($brett[$feld-1+7*$d] < 7){
						array_push($moeglicheZugfelder, $feld+7*$d);
						$d = 10;
					}else{
						$d = 10;
					}
				}else{
					$d = 10;
				}
			}
		break;
		default:
		break;
	}
	
	return $moeglicheZugfelder;
}

//Spieler zuordnen
if($aktion == 'start'){
	if($s1id == '0'){
		$s1id = $sid;
		$meinedb->query("UPDATE brett3 SET sp1 = ".$sid." WHERE bid = ".$brettID);
	}else if ($s2id == '0' && $sid != $s1id){
		$s2id = $sid;
		$meinedb->query("UPDATE brett3 SET sp2 = ".$sid." WHERE bid = ".$brettID);
	}else{
		if($sid != $s1id && $sid != $s2id){
			$meinedb->query("UPDATE brett3 SET sp1 = ".$sid.", sp2 = 0, dran = 1, a1 = 8, a2 = 9, a3 = 10, a4 = 12, a5 = 11, a6 = 10, a7 = 9, a8 = 8, a9 = 7, a10 = 7, a11 = 7, a12 = 7, a13 = 7, a14 = 7, a15 = 7, a16 = 7, a17 = 0, a18 = 0, a19 = 0, a20 = 0, a21 = 0, a22 = 0, a23 = 0, a24 = 0, a25 = 0, a26 = 0, a27 = 0, a28 = 0, a29 = 0, a30 = 0, a31 = 0, a32 = 0, a33 = 0, a34 = 0, a35 = 0, a36 = 0, a37 = 0, a38 = 0, a39 = 0, a40 = 0, a41 = 0, a42 = 0, a43 = 0, a44 = 0, a45 = 0, a46 = 0, a47 = 0, a48 = 0, a49 = 1, a50 = 1, a51 = 1, a52 = 1, a53 = 1, a54 = 1, a55 = 1, a56 = 1, a57 = 2, a58 = 3, a59 = 4, a60 = 6, a61 = 5, a62 = 4, a63 = 3, a64 = 2 WHERE bid = 1");
			$s1id = $sid;
			$brett = array(8, 9, 10, 12, 11, 10, 9, 8, 7, 7, 7, 7, 7, 7, 7, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 2, 3, 4, 6, 5, 4, 3, 2);
		}
	}
}

$ausgewaehltesFeld = 0;
$ausgewaehlteFigur = 0;
//Figur ausgewaehlt?
if($aktion == 'figurAusgewaehlt'){
	$ausgewaehltesFeld = $uebergabeAusgewaehltesFeld;
	$ausgewaehlteFigur = $brett[$ausgewaehltesFeld - 1];
	if(($sid == $s1id && $ausgewaehlteFigur < 7) || ($sid == $s2id && $ausgewaehlteFigur > 6)){
		$moeglicheZugfelder = moeglicheZugfelder($brett, $ausgewaehltesFeld, $koeniggezogen, 0);
	}
}
	
//Figur gezogen
if($aktion == 'zug'){
	if(($sid == $s1id && $dran == 1) || ($sid == $s2id && $dran == 2)){
		$geschlageneFigur = $brett[$uebergabeZugFeld-1];
		$meinedb->query("UPDATE brett3 SET a".$uebergabeAusgewaehltesFeld." = 0 WHERE bid = ".$brettID);
		$meinedb->query("UPDATE brett3 SET a".$uebergabeZugFeld." = ".$uebergabeAusgewaehlteFigur." WHERE bid = ".$brettID);
		$brett[$uebergabeAusgewaehltesFeld-1] = 0;
		$brett[$uebergabeZugFeld-1] = $uebergabeAusgewaehlteFigur;
		if($dran == 1){
			$dran = 2;
			$meinedb->query("UPDATE brett3 SET dran = 2 WHERE bid = ".$brettID);
		}else if ($dran == 2){
			$dran = 1;
			$meinedb->query("UPDATE brett3 SET dran = 1 WHERE bid = ".$brettID);
		}
		//Rochade
		if($uebergabeAusgewaehlteFigur == 11 || $uebergabeAusgewaehlteFigur == 5){
			$_SESSION['koeniggezogen'] = 1;
		}
		if($uebergabeAusgewaehlteFigur == 5 && $uebergabeAusgewaehltesFeld == 61 && $uebergabeZugFeld == 63){
			$meinedb->query("UPDATE brett3 SET a64 = 0 WHERE bid = ".$brettID);
			$meinedb->query("UPDATE brett3 SET a62 = 2 WHERE bid = ".$brettID);
			$brett[63] = 0;
			$brett[61] = 2;
		}
		if($uebergabeAusgewaehlteFigur == 5 && $uebergabeAusgewaehltesFeld == 61 && $uebergabeZugFeld == 59){
			$meinedb->query("UPDATE brett3 SET a57 = 0 WHERE bid = ".$brettID);
			$meinedb->query("UPDATE brett3 SET a60 = 2 WHERE bid = ".$brettID);
			$brett[57] = 0;
			$brett[59] = 2;
		}
		if($uebergabeAusgewaehlteFigur == 11 && $uebergabeAusgewaehltesFeld == 5 && $uebergabeZugFeld == 7){
			$meinedb->query("UPDATE brett3 SET a8 = 0 WHERE bid = ".$brettID);
			$meinedb->query("UPDATE brett3 SET a6 = 8 WHERE bid = ".$brettID);
			$brett[7] = 0;
			$brett[5] = 8;
		}
		if($uebergabeAusgewaehlteFigur == 11 && $uebergabeAusgewaehltesFeld == 5 && $uebergabeZugFeld == 3){
			$meinedb->query("UPDATE brett3 SET a1 = 0 WHERE bid = ".$brettID);
			$meinedb->query("UPDATE brett3 SET a4 = 8 WHERE bid = ".$brettID);
			$brett[0] = 0;
			$brett[3] = 8;
		}
		//Schachmatt?
		if($geschlageneFigur == 5 || $geschlageneFigur == 11){
			$dran = 0;
			$meinedb->query("UPDATE brett3 SET dran = 0 WHERE bid = ".$brettID);
			$_SESSION['koeniggezogen'] = '';
		}
		//Remis? 
		$moeglicheZugfelderWeiß = array();
		for($i=0;$i<64;$i++){
			if($brett[$i] < 7){
				array_push($moeglicheZugfelderWeiß, moeglicheZugfelder($brett, $i+1, $koeniggezogen, 0));
			}
		}
		$moeglicheZugfelderWeiß = array_merge(...$moeglicheZugfelderWeiß);
		if(empty($moeglicheZugfelderWeiß)){
			$dran = 0;
			$meinedb->query("UPDATE brett3 SET dran = 0 WHERE bid = ".$brettID);
			$_SESSION['koeniggezogen'] = '';
		}
		$moeglicheZugfelderSchwarz = array();
		for($i=0;$i<64;$i++){
			if($brett[$i] > 6){
				array_push($moeglicheZugfelderSchwarz, moeglicheZugfelder($brett, $i+1, $koeniggezogen, 0));
			}
		}
		$moeglicheZugfelderSchwarz = array_merge(...$moeglicheZugfelderSchwarz);
		if(empty($moeglicheZugfelderSchwarz)){
			$dran = 0;
			$meinedb->query("UPDATE brett3 SET dran = 0 WHERE bid = ".$brettID);
			$_SESSION['koeniggezogen'] = '';
		}
		//Bauernumwandlung?
		if($uebergabeAusgewaehlteFigur == 1 && $uebergabeZugFeld < 9){
			$meinedb->query("UPDATE brett3 SET a".$uebergabeZugFeld." = 6 WHERE bid = ".$brettID);
			$brett[$uebergabeZugFeld-1] = 6;
		}
		if($uebergabeAusgewaehlteFigur == 7 && $uebergabeZugFeld > 56){
			$meinedb->query("UPDATE brett3 SET a".$uebergabeZugFeld." = 12 WHERE bid = ".$brettID);
			$brett[$uebergabeZugFeld-1] = 12;
		}
	}
}

if($aktion == 'aufgegeben'){
	$dran = 0;
	$meinedb->query("UPDATE brett3 SET dran = 0 WHERE bid = ".$brettID);
	$_SESSION['koeniggezogen'] = '';
}

if($aktion == 'neuesSpiel'){
	$meinedb->query("UPDATE brett3 SET sp1 = 0, sp2 = 0, dran = 1, a1 = 8, a2 = 9, a3 = 10, a4 = 12, a5 = 11, a6 = 10, a7 = 9, a8 = 8, a9 = 7, a10 = 7, a11 = 7, a12 = 7, a13 = 7, a14 = 7, a15 = 7, a16 = 7, a17 = 0, a18 = 0, a19 = 0, a20 = 0, a21 = 0, a22 = 0, a23 = 0, a24 = 0, a25 = 0, a26 = 0, a27 = 0, a28 = 0, a29 = 0, a30 = 0, a31 = 0, a32 = 0, a33 = 0, a34 = 0, a35 = 0, a36 = 0, a37 = 0, a38 = 0, a39 = 0, a40 = 0, a41 = 0, a42 = 0, a43 = 0, a44 = 0, a45 = 0, a46 = 0, a47 = 0, a48 = 0, a49 = 1, a50 = 1, a51 = 1, a52 = 1, a53 = 1, a54 = 1, a55 = 1, a56 = 1, a57 = 2, a58 = 3, a59 = 4, a60 = 6, a61 = 5, a62 = 4, a63 = 3, a64 = 2 WHERE bid = ".$brettID);
	$brett = array(8, 9, 10, 12, 11, 10, 9, 8, 7, 7, 7, 7, 7, 7, 7, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 2, 3, 4, 6, 5, 4, 3, 2);
}

//Spielfeld laden
function zelleAnzeigen($feld, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett){
	echo "<form action='schach2.php' method='post' style='width: 100px; display: inline; height: 100px'>";
	if($figur != 0){
		if($aktion == 'figurAusgewaehlt' && $brett[$feld-1] != 0 && in_array($feld, $moeglicheZugfelder)){
			
		}else{
			echo'
				<input type="hidden" name="uebergabeAusgewaehltesFeld" value="'.$feld.'">
				<input type="hidden" name="aktion" value="figurAusgewaehlt">
				<input type="image" src="'.$figur.'.png" width="100" height="100">
			';
		}
	}			
	if($aktion == 'figurAusgewaehlt' && in_array($feld, $moeglicheZugfelder)){
		if($brett[$feld-1] != 0){
			echo "<input type='image' src='a".$brett[$feld-1].".png' width='100' height='100'>";
		}else{
			echo "<input type='image' src='ausgewaehlt.png' width='100' height='100'>";
		}
		echo "
			<input type='hidden' name='aktion' value='zug'>
			<input type='hidden' name='uebergabeAusgewaehltesFeld' value='".$ausgewaehltesFeld."'>
			<input type='hidden' name='uebergabeZugFeld' value='".$feld."'>
			<input type='hidden' name='uebergabeAusgewaehlteFigur' value='".$ausgewaehlteFigur."'>
		";
	}
	echo "</form>";
}
function zelleAnzeigenSchwarz($feld, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett){
	echo "<td id='felds'>";
	zelleAnzeigen($feld, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett);
	echo"</td>";
}
function zelleAnzeigenWeiß($feld, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett){
	echo "<td id='feldw'>";
	zelleAnzeigen($feld, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett);
	echo"</td>";
}

echo "
<html>
	<head>
		<title>Schach</title>
		<link href='style.css' rel='stylesheet'>
		<meta charset='utf-8'>
		<meta http-equiv='refresh' content='10'>
	</head>
	<body>
		<br>
		<div class='boxg'>
			<div class='boxl' align='left' style='background-color: #181818'>
";	
//1. Seitenbesuch
if($aktion == ''){
	echo "
		<br><br><br>
		<center>
			<p style='color:white'>
				Hallo, wie hei&szlig;t du?
			</p>
			<form action='schach2.php' method='post'>
				<input type='hidden' name='aktion' value='name'> 
				<input type='text' name='wert' size='15' value='' maxlength='15'>
				<input type='submit' value='speichern'>
			</form>
		</center>
	";
}
// $aktion ist name -> 2. Seitenbesuch, Benutzer hat Name eingegeben
if ($aktion == 'name'){
	$_SESSION['sname'] = $wert;
	$meinedb->query("INSERT INTO spieler (name) VALUES ('".$wert."')");
	$pid = $meinedb->query("SELECT MAX(sid) FROM spieler");
	$row = mysqli_fetch_array($pid);
	$sid = $row[0];
	$_SESSION['sid'] = $sid;
	echo "
		<br><br><br>
		<center>
			<p style='color:white'>
				Du hei&szlig;t ".$wert." <br>
				Bereit f&uuml;r Schach?
			</p>
			<form action='schach2.php' method='post'>
				<input type='hidden' name='aktion' value='start'> 
				<input type='submit' value='Starten'>
			</form>
		</center>
	";
}	
if($aktion != '' && $aktion != 'name'){
	echo "<font color = white>Spieler 2: </font>";
	if($s2id != 0){
		$s2Name = $meinedb->query("SELECT name FROM spieler WHERE sid = ".$s2id);
		$row = mysqli_fetch_array($s2Name);
		$s2Name = $row[0];
		echo "<font color = white>".$s2Name." (".$s2id.")</font>";
	}
}
//Brett
if(!($aktion == '' || $aktion == 'name')){
	echo"<table cellspacing='0' cellpadding='0' border-collapse='collapse' width='100%'>";
	$wechsel = 0;
	for($i=1; $i<=64; $i++)
	{
		$figur = $brett[$i-1];
		if($i%8==1)
		{
			echo "<tr>";
			if($i%2==$wechsel){
				zelleAnzeigenSchwarz($i, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett);
			}else{								
				zelleAnzeigenWeiß($i, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett);
			}
		}else if($i%8==0)
		{
			if($i%2==$wechsel){
				zelleAnzeigenSchwarz($i, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett);
			}else{
				zelleAnzeigenWeiß($i, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett);
			}
			echo "</tr>";
			if($wechsel==0){
				$wechsel = 1;
			}else{
				$wechsel = 0;
			}
		}else
		{
			if($i%2==$wechsel){
				zelleAnzeigenSchwarz($i, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett);
			}else{
				zelleAnzeigenWeiß($i, $figur, $moeglicheZugfelder, $aktion, $ausgewaehltesFeld, $ausgewaehlteFigur, $brett);
			}
		}
	}
	echo"</table>";
}
if($aktion != '' && $aktion != 'name'){
	echo "<font color = white>Spieler 1: </font>";
	if($s1id != 0){
		$s1Name = $meinedb->query("SELECT name FROM spieler WHERE sid = ".$s1id);
		$row = mysqli_fetch_array($s1Name);
		$s1Name = $row[0];
		echo "<font color = white>".$s1Name." (".$s1id.")</font>";
	}
}
echo "
		</div>
		<div class='boxm'></div>
			<div class='boxr' style='background-color:#181818' align='left'>";
				if($aktion != '' && $aktion != 'name'){
					echo "<br><br>";
					if($dran == 1){
						echo "<font color = white>weiß ist dran</font>";
					}else if($dran == 2){
						echo "<font color = white>schwarz ist dran</font>";
					}
					echo "<br><br>";
					echo "
					<form action='schach2.php' method='post'>
						<input type='hidden' name='aktion' value='aufgeben'> 
						<input type='submit' value='Aufgeben'>
					</form>
					";
					if($aktion == 'aufgeben'){
						echo "<font color = white> Bist du sicher?</font>";
						echo"
						<form action='schach2.php' method='post'>
							<input type='hidden' name='aktion' value='aufgegeben'> 
							<input type='submit' value='ja'>
						</form>
						";
					}
					if($aktion == 'aufgegeben'){
						if($sid == $s1id){
							echo "<font color = white>Schwarz hat gewonnen! </font>";
						}else if($sid == $s2id){
							echo "<font color = white>Weiß hat gewonnen! </font>";
						}
						
					}
				}
				if($dran == 0){
					echo "<br>";
					echo "<font color = white>Das Spiel ist beendet</font>";
					echo"
					<form action='schach2.php' method='post'>
							<input type='hidden' name='aktion' value='neuesSpiel'> 
							<input type='submit' value='Neues Spiel'>
					</form>
					";
				}
			
echo "
			</div>
		</div>
	</body>
</html>";
?>