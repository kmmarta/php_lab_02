<?php
require_once dirname(__FILE__).'/../config.php';

// KONTROLER strony kalkulatora

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

//ochrona kontrolera - poniższy skrypt przerwie przetwarzanie w tym punkcie gdy użytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';

//pobranie parametrów
function getParams($a,$b,$c,$operation){
	$a = isset($_REQUEST['a']) ? $_REQUEST['a'] :null;//warunek jezeli jest x w request to zwroc request od x jesli nie to null
	$b = isset($_REQUEST['b']) ? $_REQUEST['b'] : null;
        $c = isset($_REQUEST['c']) ? $_REQUEST['c'] : null;
	$operation = isset($_REQUEST['op']) ? $_REQUEST['op'] : null;	
}

//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$a,&$b,$c,&$operation,&$messages){// funkcja ktorsa waliduje i tez otrzymuje tablice mees ktora bedzie generowac komunikaty
	// sprawdzenie, czy parametry zostały przekazane
	if ( ! (isset($a) && isset($b) &&isset($c) && isset($operation))) {
		// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
		// teraz zakładamy, ze nie jest to błąd. Po prostu nie wykonamy obliczeń
		return false;
	}

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $a == "") {
		$messages [] = 'Nie podano kwoty';
	}
	if ( $b == "") {
		$messages [] = 'Nie podano liczby lat';
	}
        if ( $c == "") {
	$messages [] = 'Nie podano oprocentowania';
}

	//nie ma sensu walidować dalej gdy brak parametrów
	if (count ( $messages ) != 0) 
            return false;
	
	// sprawdzenie, czy $x i $y są liczbami całkowitymi
	if (! is_numeric( $a )) {
		$messages [] = 'Kwota nie jest liczbą całkowitą';
	}
	
	if (! is_numeric( $b )) {
		$messages [] = 'Liczba nie jest liczbą całkowitą';
	}	
        if (! is_numeric( $c )) {
		$messages [] = 'Oprocentowanie nie jest liczbą całkowitą';
	}
	if (count ( $messages ) != 0)
            return false;
	else 
            return true;// 
}

function process(&$a,&$b,$c,&$operation,&$messages,&$result){//wykonanie op. jesli walidacja sie udala
	global $role;
	
	
        //$a = $x;//kwota
	//$b = $y;//l.lat
	//$c = $z;//oprocentowanie
        
        //konwersja parametrów na int
	$a = intval($a);
	$b = intval($b);
	$c = intval($c);
        
        $_SESSION['a']=$a;
        $_SESSION['b']=$b;
        $_SESSION['c']=$c;
        
	
	//wykonanie operacji
	$c=12*$c;
	$b=$b/100;

	$result = ($a*$b)/(12*(1-((12/(12+$b))**$c)));
  //$result= ($a*$b)/((12*(1-((12/((12+$b)**$c))))));
        $result=round($result,2);
$_SESSION['result']=$result;

}

//definicja zmiennych kontrolera
$a = null;
$b = null;
$c=null;
$operation = null;
$result = null;
$messages = array();// tablica

//pobierz parametry i wykonaj zadanie jeśli wszystko w porządku
getParams($a,$b,$c,$operation);
if ( validate($a,$b,$c,$operation,$messages) ) { // gdy brak błędów
	process($a,$b,$c,$operation,$messages,$result);
}

// Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$y,$operation,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';