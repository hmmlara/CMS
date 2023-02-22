$('#check').click(function () {

    $.ajax({
        type: "POST",
        url: "create_invoice",
        data: {
            data: {
                'treatment_id': $('#treatment_id').text(),
                'amount': $('#total').text()
            }
        },
        success: function (response) {
            if(response == 'success'){
                window.print();

                window.location.href = `add_appointment`
            }
            else{
                alert('There is an erro!');
            }
        }
    });
});