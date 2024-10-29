
jQuery( document ).ready(function() {
	jQuery(".sunarcwpdb_delete").on("click",function(e){
		if (!confirm('Are you sure you want to delete this backup?')){
			return false;
		}
		else{
			return true;
		}
		
	});
});

/*jQuery( document ).ready(function() {
	jQuery("form#sunarcwpdb_backup_form").on("submit",function(e){
		e.preventDefault();
		var dbtype = jQuery("#sunarcwpdb_backup").val();
		var postdata = "action=sunarcwpdbcreatebackup&dbtype="+dbtype;
		jQuery.post(sunarcwpdbajaxurl,postdata,function(response){
			alert(response);
		    return false;

		});
		
	});
});*/