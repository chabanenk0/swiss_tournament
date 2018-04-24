$(document).ready(function () {

    $('.roundResultShowButton').on('click', function (e) {
        // Show modal
        $('#setRoundResultModal').modal('show');
        // AJAX request
       var url = $(e.target).data('modal-target');
        $.ajax({
            url: url,
            type: 'get',
            success: function (response) {
                // Add response in Modal body
                $('#modalBody').html(response);
            }
        });
    });
});