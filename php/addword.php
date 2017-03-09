<?php

function submit_word($POST){
	require_once("word.php");
	require_once("wordtypes.php");

	$word_data = array();
	$word_data['id'] = -1;
	$word_data['tantar'] = $POST["tantar"];
	$word_data['english'] = $POST["english"];
	$word_data['description'] = $POST["desc"];
	$word_data['isvan'] = isset($POST["isvan"]) ? 1 : 0;
	$word_data['syllables'] = isset($POST["syl"]) ? $POST["syl"] : $POST["tantar"];

	$translations = array();

	foreach($word_types as $type) {
		if(!empty($POST[$type])) {
			$words = array_map('trim', explode(",", $POST[$type]));
			//$translations[$type] = array();
			foreach($words as $word) {
				if($word != "") {
					array_push($translations, [
						'translation' => $word,
						'wordtype' => $type,
						'wordid' => -1
					]);
				}
			}
		}
	}

	$new_word = new Word($word_data, $translations);
	add_word($new_word);
}

?>