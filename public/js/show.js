$(document).ready(function () {

    // Hide or show shows based on user's click
    $('#futureShowsBtn').on('click', function (){
        $('.currentShow').hide();
        $(".pastShow").hide();
        $('.futureShow').show();

    });

    $('#currentShowsBtn').on('click', function () {
        $(".futureShow").hide();
        $(".pastShow").hide();
        $('.currentShow').show();

    });

    // Have window open on load if user has volunteered
    $(window).on('load', function () {
       $('#volunteerModal').modal('show');
        $('#passwordResetModal').modal('show');
        $('#passwordResetDoneModal').modal('show');
    });
});