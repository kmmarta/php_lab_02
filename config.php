<?php
define('_SERVER_NAME', 'localhost:80');
define('_SERVER_URL', 'http://'._SERVER_NAME);
define('_APP_ROOT', '/php_02_ochrona_dostepu');
define('_APP_URL', _SERVER_URL._APP_ROOT);
define("_ROOT_PATH", dirname(__FILE__));


//funkcja w php moze zwracac wartosc
//parametr jest przekazywany przez referencje,sprawdza czy zmienna prama jest ustawiona jak jest tp wysyla do przegladarki

function out(&$param){
	if (isset($param)){
		echo $param;
	}
}
?>