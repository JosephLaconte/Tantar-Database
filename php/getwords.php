<?php

include_once('word.php');
if (empty($_GET['term'])) {
	if (empty($_GET['lang'])) {
		$all_words = get_all_words('tantar');
		echo json_encode($all_words);
	} else {
		$all_words = get_all_words($_GET['lang']);
		echo json_encode($all_words);
	} 
} else {
	if (empty($_GET['lang'])) {
		echo json_encode(search_words($_GET['term']));
	} else {
		if ($_GET['lang'] == 'english') {
			echo json_encode(search_english_words($_GET['term']));
		} else if ($_GET['lang'] == 'tantar') {
			echo json_encode(search_tantar_words($_GET['term']), JSON_PRETTY_PRINT);
		} else {
			echo json_encode(search_words($_GET['term']));
		}
	}
}

?>