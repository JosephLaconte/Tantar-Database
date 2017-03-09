<?php
	include 'gen/word_rows.php';
?>

<html>
	<head>
		<meta name="mobile-web-app-capable" content="yes">
		<link href="css/mobile_list_view.css" type="text/css" rel="stylesheet"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/mustache.js" ></script>
		<script>
		function loadAllWords() {
			$.getJSON('php/getwords.php' , function(data) {
				var template = $('#word_row_template').html();
				var html = Mustache.to_html(template, data);
				$('#word_list_table').html(html);
			});
		}
		
		function searchWords(term, lang) {
			$.getJSON('php/getwords.php?term=' + term + '&lang=' + lang, function(data) {
				var template = $('#word_row_template').html();
				var html = Mustache.to_html(template, data);
				$('#word_list_table').html(html);
			});
		}
		
		$(document).ready(loadAllWords());
		</script>
		<script id="word_row_template" type="text/template">
			{{#.}}
			<tr>
				<td>
					<div class="img_container">
						<img src="van/{{id}}.png" class="van_img"/>
					</div>
					<div class="text_container">
						<p class="main_words">
							<span class="tantar_word">{{{tantar}}}</span>
							<span class="english_word">•{{english}}</span>
						</p>
						<p class="other_translations">
							{{tran_list}}
						</p>
					</div>
				</td>
			</tr>
			{{/.}}
		</script>
	</head>
	<body>
		<div class="main_view">
		    <div class="side_bar">
		    	<div><img src='icon/list.svg'/></center></div>
		    	<div><p class="bar_title">Tantar</p></div>
		    	<div id="search_button"><img src='icon/search.svg'/></center></div>
		    </div>
		    <div class="top_bar_buffer">
		    </div>
		    <div class="search_bar">
		    	<input type="text" placeholder="search" id="search" onkeyup="searchWords(this.value,'tantar');"/>
		    </div>
			<div class="list_view">
				<table id="word_list_table" cellspacing="10">
					<?php
						//word_rows(10);
					?>
				</table>
			</div>
			<div class="hovering_circle" id="addBoxBut">
				<span>+</span>
			</div>
			<?php include 'php/addbox.php'; ?>
		</div>
		<script type="text/javascript">
			$("#search_button").click(function(){
				$(".search_bar").slideToggle();
			});
		</script>
	</body>
</html>