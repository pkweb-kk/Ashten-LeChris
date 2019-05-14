// Initialize Firebase
var config = {
	apiKey: "AIzaSyBEjfIoZjJ5hatyOjiD7kpeTbROP7JsDfE",
	authDomain: "aslechris.firebaseapp.com",
	databaseURL: "https://aslechris.firebaseio.com",
	projectId: "aslechris",
	storageBucket: "aslechris.appspot.com",
	messagingSenderId: "882502787969"
};
firebase.initializeApp(config);

/*var form = document.querySelector("form");
form.addEventListener("submit", function(event) { */
jQuery(document).ready(function() {
	jQuery(".pmpro_form").submit(function(event) {	
		event.preventDefault();
		var timestamp = Number(new Date());
		var storageRef = firebase.storage().ref(timestamp.toString());
		var $ = jQuery;
		var file_data = $("#upload_id_copy").prop("files")[0];		
		if( document.getElementById("upload_id_copy").files.length == 0 ){
		    alert("No files selected");
		    return false;
		}else{
			var returndata = storageRef.put(file_data); 
		}
		console.log('file_data-'+file_data);
	});
});