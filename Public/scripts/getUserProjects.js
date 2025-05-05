$(function() {
    $.ajax({
        url: 'http://localhost/programacion/PFC/App/user/projects',
        type: 'POST',
        dataType: 'json',
        data: {
            'token': 'access_token',
        },
        success: function(data) {
            console.log(data); // Debugging line to check the response
        },
        error: function() {
            console.error('Error fetching projects');
        }
    });
});