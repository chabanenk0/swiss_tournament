$(document).ready(function () {

    $('.round-result-show-button').on('click', function (e) {
        // Show modal
        $('#set-round-result-modal').modal('show');
        // AJAX request
       var url = $(e.target).data('modal-target');
        $.ajax({
            url: url,
            type: 'get',
            success: function (response) {
                // Add response in Modal body
                $('#round-result-modal-body').html(response);
            }
        });
    });
});