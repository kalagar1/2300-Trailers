$(document).ready(function() {

    //for favorite mechanic in gallery
    $(".favoritesForm").on("submit", function (event){
        event.preventDefault();
        var params = $(this).serialize();
        console.log(params);
        $.post('index.php', params);
        console.log(params);
        if ($(this).children(".favoriteIcon").css("color") == 'rgb(255, 255, 255)'){
            $(this).children(".favoriteIcon").css("color", "gold");
            $(this).children(".updateFavorite").val(0);
        } else {
            $(this).children(".favoriteIcon").css("color", "white");
            $(this).children(".updateFavorite").val(1);
        }
    });

    //Logic for replacing the movie image with the youtube link
    // Inspiration: https://stackoverflow.com/questions/8492239/click-on-image-splashscreen-to-play-embedded-youtube-movie
    jQuery('.playImg, .bigPlayIcon').click(function () {
        video = '<iframe src="' + $('.playImg').attr('data-video') + '" height="' + $('.playImg').height() + '"  width="' + $('.playImg').width() + '" allowfullscreen frameborder="0" controls="0" rel="0" modestbranding="1"></iframe>';
        $(".bigPlayIcon").css("visibility", "hidden");

        $('.playImg').fadeOut(500, function () {
            $('.playImg').replaceWith(video);
        });

    });

    //for admin update actor tags form
    $(".updateMovieSelector").on("submit", function (event) {
        event.preventDefault();
        var params = $(this).serialize();
        console.log(params);
        $.post('admin.php', params, function (results) {
            $("#updateActorFormDiv").html(results);
        });
    });

    //for update actor tag form to add multiple existing actors
    $("#updateActorFormDiv").on("click", "#newExistingActor", function () {
        var selectedActorName = $('#selectedActor').find(":selected").text();
        var selectedActorId = $('#selectedActor').val();
        if (!jQuery.isEmptyObject(selectedActorId)) {
            $("#newExistingActors").append('<p>' + selectedActorName + '</p>')
            $("#newExistingActors").append('<input type="hidden" name="addExistingActors[]" value="' + selectedActorId + '">');
        }
        $('#selectedActor').val("Select Existing Actor");
    });

    //for brand new actor input to add multiple new actors
    $("#updateActorFormDiv").on("click", "#brandNewActor", function () {
        var newActorName = $('#newActorName').val();
        $('#newActorName').val("");
        if (!jQuery.isEmptyObject(newActorName)) {
            $("#newActors").append('<p>' + newActorName + '</p>')
            $("#newActors").append('<input type="hidden" name="addNewActors[]" value="' + newActorName + '">');
        }
    });

    //when you click the enter button when adding a new actor, it doesnt submit the form, instead prompts you for another actor
    $("#updateActorFormDiv").on("keypress", "#newActorName", function (input) {
        if (input.keyCode == 13) {
            input.preventDefault();
            $("#brandNewActor").trigger("click");
        }
    });



});


$(window).on("load", function () {
    //to stop css flickering
    $(".homeGallery").css({
        "visibility": "visible"
    });


    //to help with styling of gallery when low amount of movies are displayed
    if ($(".grid-container").children().length <= 8) {
        $(".grid-container").css({
            "column-count": 2,
            "justify-content": "center",
            "margin": "auto",
            "width": "80%"
        });
        $(".movieWrap").css({
            "width": "100%",
            "margin-bottom": "16px"
        });
        $(".movieImg").css({
            "width": "100%",
        });
    }

    if ($(".grid-container").children().length <= 3) {
        $(".grid-container").css({
            "column-count": 1,
            "width": "50%"
        });
    }


    //for placement of play icon in movies page
    $(".bigPlayIcon").css({
        "left": ($(window).width() - 96) / 2,
        "top": ($('.playImg').height() - 96) / 2,
        "visibility": "visible",
    });
});
