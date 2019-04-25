$(function () {
    grecaptcha.ready(function() {
        grecaptcha.execute('6LfFL6AUAAAAAAgTnRXDEeywQ3Oi-ESO2oSkJ5gS', {action: 'contact'}).then(function(token) {
            $('#contact-form .controls').append('<input id="form_re" type="hidden" data-validate="true" class="form-control" required="required" name="g-recaptcha-response" value="'+token+'" data-error="Erreur reCaptcha, merci de réessayer."><div class="help-block with-errors"></div>');
        });
    });
    $('#contact-form').validator();
    $('#contact-form').on('submit', function (e) {
        if (!e.isDefaultPrevented()) {
            var url = "contact.php";
            grecaptcha.ready(function() {
                grecaptcha.execute('6LfFL6AUAAAAAAgTnRXDEeywQ3Oi-ESO2oSkJ5gS', {action: 'contact'}).then(function(token) {
                    $('#contact-form .controls').append('<input id="form_re" type="hidden" data-validate="true" class="form-control" required="required" name="g-recaptcha-response" value="'+token+'" data-error="Erreur reCaptcha, merci de réessayer."><div class="help-block with-errors"></div>');
                    $(this).validator('update');
                });
            });
            $(this).validator('update');
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function (data)
                {
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;
                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
                    if (messageAlert && messageText) {
                        $('#contact-form').find('.messages').html(alertBox);
                        $('#contact-form')[0].reset();
                    }
                }
            });
            return false;
        }
    })
});
