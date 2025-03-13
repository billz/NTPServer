$(document).on("click", ".js-add-ntp-server", function(e) {
    e.preventDefault();

    var field = $("#add-ntp-server-field")
    var row = $("#ntp-server").html().replace("{{ server }}", field.val())
    if (field.val().trim() == "") { return }
    $(".js-ntp-servers").append(row)
    field.val("")
});

$(document).on("click", ".js-remove-ntp-server", function(e) {
    e.preventDefault();
    $(this).parents(".js-ntp-server").remove();
});

$('#chxntpedit').change(function() {
    if ($(this).is(':checked')) {
        $('#txtntpconfigraw').prop('disabled', false);
    } else {
        $('#txtntpconfigraw').prop('disabled', true);
    }
});

