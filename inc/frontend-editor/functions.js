jQuery(document).ready(function($){

    //Show settings handler
    $(".top3jps-show-settings").click(function(){
        $("#top3jps-settings-editor").addClass("top3jps-visible");
    });

    //Hide settigns handler
    $("#top3jps-hide-editor").click(function(){
        $("#top3jps-settings-editor").removeClass("top3jps-visible");
    });

    //Make settings box draggable
    $("#top3jps-settings-editor").draggable();

    //Preview custom styles handler
    $("#top3jps-preview-editor-css").click(function(){
        $("#top3jps-custom-css").html($("#top3jps-custom-css-input").val());
        var newUserID_ = $("#top3jps-user-id-input").val();
        var newUserID = 0;
        try{ newUserID = parseInt(newUserID_); }
        catch(err){ newUserID = 0; console.log(err.message); }
        if(newUserID > 0){
            $(".top3jps-redirect-link").attr("data-id", newUserID);
        } else {
            alert("User ID can not be less than 1");
        }
    });

    //Save custom styles handler
    $("#top3jps-save-settings").click(function(){
        dataSubmit("save_settings", {
            customcss: encodeURI($("#top3jps-custom-css-input").val()),
            userid: $("#top3jps-user-id-input").val(),
            ispublic: $("#top3jps-is-public").val()
        });
    });

    //Switch tab toggles
    $(".top3jps-settings-tab-toggle").click(function(){

        //Do nothing for currently active toggle
        if($(this).hasClass("top3jps-toggle-active")){
            return;
        }

        $(".top3jps-settings-tab-toggle").removeClass("top3jps-toggle-active");
        $(this).addClass("top3jps-toggle-active");
        $(".top3jps-settings-tab-target").removeClass("top3jps-tab-active");
        $("#" + $(this).attr("data-target")).addClass("top3jps-tab-active");
    });

    //Submit data via AJAX request
	function dataSubmit(action, json_obj){
		$("#top3jps_ajax_frontend_editor_action").val(action);
		$("#top3jps_ajax_frontend_editor_jsondata").val(JSON.stringify(json_obj));
        $("#top3jps-ajax-form").ajaxSubmit({
            beforeSend: function(){
                $("#top3jps-save-settings").attr("data-disabled", "yes");
			},
            uploadProgress: function(event, position, total, percentComplete){},
            success: function (response___) {
                var response__ = response___.split("###");
                var response_ = response__[0];
                var targetLength = response__.length - 1;
                for(var i = 1; i < targetLength; i++){ response_ += '###' + response__[i]; }
                var response;
                try { response = JSON.parse(response_); }
                catch(err){ response = {}; console.log(err.message); }
                if(response.status){
                    if(response.status === true){
                        $("#top3jps-save-settings").attr("data-disabled", "no");
						jsonSuccessMessenger();
                    } else {
						jsonErrorMessenger();
					}
                } else {
					jsonErrorMessenger();
				}
                $("#top3jps-save-settings").attr("data-disabled", "no");
            },
            error: function(){
                $("#top3jps-save-settings").attr("data-disabled", "no");
                jsonErrorMessenger();
            }
        });
	}

	function jsonErrorMessenger(){
		switch($("#top3jps_ajax_frontend_editor_action").val()){
			case "save_settings": alert("Looks like something went wrong..."); break;
			default: break;
		}
	}

	function jsonSuccessMessenger(){
		switch($("#top3jps_ajax_frontend_editor_action").val()){
            case "save_settings": alert("Saved!"); break;
			default: break;
		}
    }
});