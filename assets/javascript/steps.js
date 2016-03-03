function switchForms(data) {
    jQuery(function($) {
        var usersPhone = data.phonenumber;

        $(".phone-number").html("+" + String(usersPhone));
        $("#second-step").html(data.secondstep);

        $("#onSubmitPhone").hide(function(){
            $("#onSubmitCaller").show(500);
        }).delay(500);
    });
}

function verifyStatus(data) {
    jQuery(function($) {
        if (data.response.status == "failed") {
            alert(data.response.status + ", only last 5 digits, please retry.");
            location.reload(true);
        }

        return false;
    });
}
