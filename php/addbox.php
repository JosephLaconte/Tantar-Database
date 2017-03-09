<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/addbox.css">
</head>
<body>
	<?php
		include "addword.php";
		if(isset($_POST['AddSubmitButton'])){
			$errors = submit_word($_POST);
			if($errors != ""){
				echo '<script>alert("' . str_replace("\n", ": ", $errors) . '");</script>';
			} else {
				$_POST = array();
			}
		}
	?>
	<div id="addModal" class="modal">
		<div class="addBox" id="addBox">
			<div class="addBoxContent">	
				<form method="post">
					<div class="addBoxHeader">
						<span class="closeX">X</span>
						<span class="help">?</span>
						<h1>Add Word</h1>
					</div>
					<div class="addBoxBody">
						<div class="mainInfo">
							<div class="infoItem">
								<p>Tantar<span class='star'>*</span></p><p id="testText"></p>
								<input class="text" type="text"  placeholder="New Tantar Word" name="tantar" required>
							</div>
							<div class="infoItem">
								<p>English<span class='star'>*</span></p>
								<input class="text" type="text"  placeholder="Best Single Translation" name="english" required>
							</div>
							<div class="infoItem">
								<p>Vandrelle</p>
								<div class="sideBySide">
									<input type="checkbox" name="isvan" onclick="document.getElementById('vanText').disabled=this.checked;">
									<input id="vanText" class="text" name="syl" placeholder="syl-la-bles">
								</div>
							</div>
							<div class="infoItem" style="width:100%;">
								<p>Description</p>
								<textarea style="width:98%;" maxlength="1000" class="text" name="desc"></textarea>
							</div>
							<div class="infoItem">
								<p>Translations</p>
								<div class="sideBySide">
									<input class="text" type="text" id="addtrans" placeholder="list,of,translations"><select id="wordType" name="ttype">
										<?php
											echo "\n";
											include("wordtypes.php");
											for($i = 0; $i < count($word_types);$i++){
												echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="' . $word_types[$i] . '">' . $word_type_names[$i] . (($word_types[$i]!="prefix" and $word_types[$i]!="ending") ? "s" : "es") . '</option>'  . "\n";
											}
										?>
									</select></br>
									<button type="button" name="addt" onclick="addWords()" >Add</button>
								</div>
							</div>
						</div>
						<div class="translations">
							<?php
								echo "\n";
								include("wordtypes.php");
								for($i = 0; $i < count($word_types);$i++){
									echo '<div id="' . $word_types[$i] . '">' . "\n" . '<p>' . $word_type_names[$i] . (($word_types[$i]!="prefix" and $word_types[$i]!="ending") ? "s" : "es") . ':</p>' . "\n" . '<textarea class="text" name="' . $word_types[$i] . '"></textarea>' . "\n" . '</div>' . "\n";
								}
							?>
						</div>
						<div class="imageOptions">
							<center>
								<label class="fileUploadLabel"><input type="file" name="imageFile"/></label>
								<label class="drawImageButtonLabel"><input type="button" id="openDraw" value="Draw Instead"/></label>
							</center>
						</div>
					</div>
					<div class="addBoxFooter">
						<center><button name="AddSubmitButton">Submit</button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Javascripts -->
	<script src="js/addbox.js">
	</script>
</body>
</html>