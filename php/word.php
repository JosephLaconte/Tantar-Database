<?php
include_once "wordtypes.php";

class Word{

	/**
	  * @var string
	  */
	public $tantar;
	public $english;
	public $description;
	public $syllables;
	public $tran_list;
	public $raw_tantar;
	public $raw_description;
	public $raw_syllables;
	public $raw_translations;

	/**
	  * @var int
	  */
	public $id;

	/**
	  * @var bool
	  */
	public $is_van;

	/**
	  * An an associative array of arrays, one for each word type.
	  *
	  * @var array
	  */
	public $translations;


	/**
	  * Constructs a word from two arrays of associated data.
	  *
	  * @param array $word_data - the information from the word table, a single row
	  * 	of associated data
	  * @param array $translation_list - an array of associtive arrays from the
	  *		translations table
	  */
	function __construct($word_data, $translation_list) {
		include ("wordtypes.php");
		$this->translations = array();
		foreach ($word_types as $type) {
			$this->translations[$type] = array(); 
		}
		foreach ($translation_list as $translation) {
			array_push($this->translations[$translation['wordtype']], $translation['translation']);
		}
		$this->id = $word_data['id'];
		$this->is_van = $word_data['isvan'];
		$this->description = to_upper_tantar($word_data['description']);
		$this->icon = (bool)$word_data['icon'];
		$this->raw_description = $word_data['description'];
		$this->raw_tantar = $word_data['tantar'];
		$this->tantar = $word_data['tantar'];
		if ($this->tantar[0] != "^") {
			$this->tantar = ucfirst($this->tantar);
		} else {
			$this->tantar[1] = ucfirst($this->Tantar[1]);
		}
		$this->tantar = to_upper_tantar($this->tantar);
		$this->english = ucfirst($word_data['english']);
		$this->syllables = to_upper_tantar($word_data['syllables']);
		$this->raw_syllables = $word_data['syllables'];
		$this->raw_translations = $translation_list;
		$this->tran_list = $this->get_tran_list();
	}


	/**
	  * @return string - a list of translations for display purposes
	  */
	function get_tran_list() {
		include ("wordtypes.php");
		$return = "";
		foreach ($word_types as $type) {
			if(($type != 'naa') && ($type != 'simmilar') && ($type != 'compound')) {
				if (count($this->translations[$type])>0) {
					$return .= ', ' . $this->translations[$type][0];
				}
				if (count($this->translations[$type])>1) {
					$return .= ', ' . $this->translations[$type][1];
				}
			}
		}
		return trim($return, ' ,');
	}

	/**
	  * @return string - this word in json format.
	  */
	function JSON() {
		return json_encode($this);
	}
}

function to_upper_tantar($words){
if(strpos($words, '^')) {
		$parts = explode("^", $words);
		for($i = 1; $i < count($parts); $i++) {
			$parts[$i] = substr_replace($parts[$i], '&#772;', 1, 0);
		}
		return implode($parts);
	} else {
		return $words;
	}
}

function get_upper_char($char){
	switch ($char){
		case 'A':
			return 'Ā';
		case 'E':
			return 'Ē';
		case 'I':
			return 'Ī';
		case 'O':
			return 'Ō';
		case 'U':
			return 'Ū';
		case 'a':
			return 'ā';
		case 'e':
			return 'ē';
		case 'i':
			return 'ī';
		case 'o':
			return 'ō';
		case 'u':
			return 'ū';
		case '~':
			return '≂';
		default:
			return $char;
	}
}

function get_word_by_id($word_id) {
	require_once("db.php");
	$DB = new DBC();
	$result_word = $DB->query_to_array('SELECT * FROM words WHERE id=?', 'i', [$word_id]);
	$result_tran = $DB->query_to_array('SELECT translation, wordtype FROM translations WHERE wordid=?', 'i', [$word_id]);
	return new Word($result_word[0], $result_tran);
	$DB->quit();
}

function get_all_words($lang) {
	$all_words = array();
	require_once("db.php");
	$DB = new DBC();
	$words = $DB->query('SELECT * FROM words ORDER BY ' . $lang);
	foreach ($words as $word_data) {
		$translations = $DB->query('SELECT translation, wordtype FROM translations WHERE wordid=' . $word_data['id']);
		array_push($all_words, new Word($word_data, $translations));
	}
	$DB->quit();
	return $all_words;
}

function add_word($word){
	require_once("db.php");
	$DB = new DBC();
	$DB->query(
		'INSERT INTO words VALUES(NULL, ?, ?, ?, ?, ?, ?)',
		'sssisi',
		[
			$word->raw_tantar,
			$word->english,
			$word->raw_description,
			$word->is_van,
			$word->raw_syllables,
			$word->icon
		]
	);
	$word_id = $DB->get_insert_id();
	if (count($word->raw_translations) > 0) {
		$translations_query = 'INSERT INTO translations(translation, wordtype, wordid) VALUES';
		$var_types = '';
		$values = array();
		foreach ($word->raw_translations as $translation) {
			array_push($values,  $translation['translation'], $translation['wordtype'], $word_id);
			$var_types .= 'ssi' ;
			$translations_query .= '(? , ? , ?), ';
		}
		$translations_query = rtrim($translations_query, ', ');
		$DB->query(
			$translations_query,
			$var_types,
			$values
		);
	}
	$DB->quit();
}

function get_exact_word($word) {
	require_once('db.php');
	$DB = new DBC();
 	$result = $DB->query_to_array('SELECT * FROM words WHERE tantar = ?', 's', [$word]);
  	if (count($result)!=1) {
   		return false;
  	}
  	$translations = $DB->query_to_array('SELECT translation, wordtype FROM words WHERE id = ?', 'i', [$result[0]['id']]);
  	$got_word = new Word($result[0], $translations);
  	var_dump($got_word);
  	return $got_word;
}

function delete_word($word) {
	require_once('db.php');
	$DB = new DBC();
	$DB->query('DELETE FROM translations WHERE wordid=?', 'i', [$word->id]);
	$DB->query('DELETE FROM words WHERE id=?', 'i', [$word->id]);
	$DB->quit();
}

function delete_word_by_id($word_id) {
	require_once('db.php');
	$DB = new DBC();
	$DB->query('DELETE FROM translations WHERE wordid=?', 'i', [$word_id]);
	$DB->query('DELETE FROM words WHERE id=?', 'i', [$word_id]);
	$DB->quit();
}

function search_words($term) {

}

function search_english_words($term, $combine = false) {

}

function search_tantar_words($term, $combine = false) {

	require_once('db.php');
	$DB = new DBC();
	$priority = array();
	$i = 6;
	while ($i--) {
		array_push($priority, array());
	}

	//var_dump($priority);


	$results = $DB->query(
		'SELECT *
	 	FROM words 
	 	WHERE tantar 
	 	LIKE ?;' ,
	 	 's', ['%' . $term . '%']);
	foreach ($results as $result) {
		if(!empty($result)) {
			//var_dump($result);
			if ($result['tantar'] == $term) {
				array_push($priority[0], $result);
			} else {
				array_push($priority[1], $result);
			}
		}
	}

	//var_dump($priority);

	$results = $DB->query(
		'SELECT DISTINCT words.*, translations.translation 
		FROM words 
		INNER JOIN translations 
		ON translations.wordid=words.id 
		WHERE (wordtype ="compound" OR wordtype="simmilar") 
		AND translation LIKE ?;' ,
		 's', ['%' . $term . '%']);
	foreach ($results as $result) {
		if(!empty($result)) {
			if ($result['translation'] == $term) {
				array_push($priority[2], $result);
			} else {
				array_push($priority[3], $result);
			}
		}
	}

	//var_dump($priority);

	$results = $DB->query(
		'SELECT *
	 	FROM words 
	 	WHERE description 
	 	LIKE ?;' ,
	 	 's', ['%' . $term . '%']);
	foreach ($results as $result) {
		if(!empty($result)) {
			array_push($priority[4], $result);
		}
	}

	if ($combine) {
		return $priority;
	}

	//var_dump($priority);


	$words = array();
	$ids = array();
	foreach($priority as $priority_rank)
		foreach ($priority_rank as $word_data) {
			//var_dump($word_data);
			if (!in_array($word_data['id'], $ids)) {
				array_push($words, get_word_by_id($word_data['id']));
				array_push($ids, $word_data['id']);
			}
		}
	return $words;

}
/*$data = get_all_words('tantar');

highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");

*/
?>
