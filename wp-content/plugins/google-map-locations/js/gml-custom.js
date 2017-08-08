//javascript file
function checkForm(){
	var name 			= jQuery('#name').val();
	var address_one		= jQuery('#address_one').val();
	var address_two		= jQuery('#address_two').val();
	var postcode 		= jQuery('#postcode').val();
	var phone 			= jQuery('#phone').val();
	var email 			= jQuery('#email').val();
	var latitude 		= jQuery('#latitude').val();
	var longitude 		= jQuery('#longitude').val();
	
	if(name==""){		
		jQuery('#name').addClass('trainerInputError');
		return false;
	}
	else if(address_one==""){		
		jQuery('#address_one').addClass('trainerInputError');
		return false;
	}
	else if(latitude==""){		
		jQuery('#latitude').addClass('trainerInputError');
		return false;
	}
	else if(longitude==""){		
		jQuery('#longitude').addClass('trainerInputError');
		return false;
	}
	else{
		return true;
	}
	
}

function checkForm_edit(){
	var name 			= jQuery('#name').val();
	var address_one		= jQuery('#address_one').val();
	var address_two		= jQuery('#address_two').val();
	var postcode 		= jQuery('#postcode').val();
	var phone 			= jQuery('#phone').val();
	var email 			= jQuery('#email').val();
	var latitude 		= jQuery('#latitude').val();
	var longitude 		= jQuery('#longitude').val();
	
	if(name==""){		
		jQuery('#name').addClass('trainerInputError');
		return false;
	}
	else if(address_one==""){		
		jQuery('#address_one').addClass('trainerInputError');
		return false;
	}
	else if(latitude==""){		
		jQuery('#latitude').addClass('trainerInputError');
		return false;
	}
	else if(longitude==""){		
		jQuery('#longitude').addClass('trainerInputError');
		return false;
	}
	else{
		
		return true;
		}
	
}

function settings_edit(){
	var name 		= jQuery('#name').val();
	var address_one	= jQuery('#address_one').val();
	var address_two	= jQuery('#address_two').val();
	var postcode 	= jQuery('#postcode').val();
	var phone 		= jQuery('#phone').val();
	var email 		= jQuery('#email').val();
	var latitude 	= jQuery('#latitude').val();
	var longitude 	= jQuery('#longitude').val();
	var km 			= jQuery('#km').val();
	var map_height 	= jQuery('#map_height').val();
	var map_width 	= jQuery('#map_width').val();
	
	if(name==""){		
		jQuery('#name').addClass('trainerInputError');
		return false;
	}
	else if(address_one==""){		
		jQuery('#address_one').addClass('trainerInputError');
		return false;
	}
	else if(latitude==""){		
		jQuery('#latitude').addClass('trainerInputError');
		return false;
	}
	else if(longitude==""){		
		jQuery('#longitude').addClass('trainerInputError');
		return false;
	}
	else if(km==""){		
		jQuery('#km').addClass('trainerInputError');
		return false;
	}
	else if(map_height==""){		
		jQuery('#map_height').addClass('trainerInputError');
		return false;
	}
	else if(map_width==""){		
		jQuery('#map_width').addClass('trainerInputError');
		return false;
	}
	else{
		
		return true;
		}
	
}
