jQuery(function () {
    FindATrainer.Load();
});

var FindATrainer = {
    Load: function () {
		

        var $findatrainer = $(".findatrainer");

        $findatrainer.keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.KeyCode == 13)) {
                $findatrainer.find(".btnPostCodeSearch").click();
                e.preventDefault();
            }
        });

        $findatrainer.find(".btnPostCodeSearch").click(function (e) {
            e.preventDefault();
            FindATrainer.PostCodeSearch();
        });

        $findatrainer.find(".contact input[type='submit']").click(function (e) {

            var $contact = $findatrainer.find(".contact");

            //validate
            $contact.find("input.required").each(function () {
                var $this = $(this);
                $this.css("border-color", "#000");
                if ($this.val() == "") {
                    $this.css("border-color", "#f00");
                }
            });


            var $txtTrainerids = $contact.find(".txtTrainerids");
            $txtTrainerids.val("");
            $contact.find(".ddlTrainerids option").each(function () {
                $txtTrainerids.val($txtTrainerids.val() + $(this).val() + ",")
            });
        });

    }
    , PostCodeSearch: function () {

        var $findatrainer = $(".findatrainer");
        var postcode = $findatrainer.find("input.postcode").val();
        

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': postcode }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                map.setZoom(21);
				
			
				
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });


        var $nearesttrainers = $findatrainer.find(".nearesttrainers");
        $nearesttrainers.hide();
        $findatrainer.find(".nearesttrainers").load("_NearestTrainers.aspx?postcode=" + encodeURIComponent(postcode) + " .ajax", function () {
            $(this).fadeIn();
            FindATrainer.NearestTrainers.Load();
        });

        //new search made so clear selected trainers
        $findatrainer.find(".contact .ddlTrainerids option").remove();
    }
    , NearestTrainers: {
        Load: function () {

            var $findatrainer = $(".findatrainer");
            $nearesttrainers = $findatrainer.find(".nearesttrainers");

            $nearesttrainers.find(".trainer input[type='checkbox']").change(function () {

                var trainerId = $(this).parents("span").attr("data-trainerid");

                var $trainerids = $findatrainer.find(".contact .ddlTrainerids");
                if ($(this).is(":checked")) {

                    if ($trainerids.find("option").length >= 3) {
                        alert("You can only select a maximum of 3 trainers");
                    }

                    $trainerids.append($("<option>" + trainerId + "</option>").val(trainerId));
                }
                else {
                    $trainerids.find("option[value='" + trainerId + "']").remove();
                }

            });

        }
    }
	
	
}