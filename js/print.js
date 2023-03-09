$('#check').click(function () {

    // alert($('#service_id').text());
    let data = {
        'treatment_id': $('#treatment_id').text(),
        'amount': $('#total').text(),
        'invoice_code': $('#invoice_code').text(),
        'service_id' : $('#service_id').text()
    };
    $.ajax({
        type: "POST",
        url: "create_invoice",
        data: {
            data: data
        },
        success: function (response) {
            // console.log(JSON.parse(response));
            if(response == 'success'){
                onPrint();
            }
            else{
                console.log(response);
                alert('There is an erro!');
            }
        }
    });
});

function onPrint(){
    window.onafterprint = function(e){
        $(window).off('mousemove', window.onafterprint);
        window.location.href = 'invoices';
    };

    window.print();

    setTimeout(function(){
        $(window).one('mousemove', window.onafterprint);
    }, 1);
}