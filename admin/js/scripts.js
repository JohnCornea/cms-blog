$(document).ready(function() {
    $('#summernote').summernote({
        height: 200
    });

    // select all post checkboxes by clicking the default one
    $('#selectAllBoxes').click(function (event) {
        if (this.checked) {
            $('.checkBoxes').each(function () {
                this.checked = true;
            });
        } else {
            $('.checkBoxes').each(function () {
                this.checked = false;
            });
        }
    });

    // prepend the div with the loader to the body
    var div_box = "<div id='load-screen'><div id='loading'></div></div>";

    $("body").prepend(div_box);

    $('#load-screen').delay(700).fadeOut(600, function (){
        $(this).remove();
    });
});

// Ajax for show #no of users online
function loadUsersOnline() {
    $.get("functions.php?onlineusers=result", function(data) {
        $(".usersonline").text(data);
    });
}

setInterval(function (){
    loadUsersOnline();
}, 500);
