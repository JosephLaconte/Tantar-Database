//Modal Functions
//////////////////////////////////////////////

// Get the modal
var modal = document.getElementById('addModal');

// Get the button that opens the modal
var btn = document.getElementById("addBoxBut");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("closeX")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
	modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
	modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
}



//Word Functions
///////////////////////////////////////////////

//Add a word to the list of translations:
function addWords(){
	if(document.getElementById("addtrans").value != ""){
		var wordType = document.getElementById("wordType").value;
		var wordBox = document.getElementById(wordType);
		wordBox.style.display = "block";
		var textBox = wordBox.getElementsByTagName("textarea")[0];
		if(textBox.value != ""){
			textBox.value = textBox.value + ', ';
		}
		textBox.value = textBox.value + document.getElementById("addtrans").value;
		document.getElementById("addtrans").value = '';
	}
}