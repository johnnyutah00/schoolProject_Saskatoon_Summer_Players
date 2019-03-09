$(document).ready(function () {
   $('#searchMemberButton').on('click', function (e) {
        var text = $('#searchName').val();
        if (text === "") {
            e.preventDefault();
            $('.memberSearchInfo').remove();
            $('#notFound').remove();
            $('#nothingEntered').html("You must enter a name to search");
        }
   });
});