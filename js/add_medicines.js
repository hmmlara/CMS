

// add medicine list
var cloned = $('#medicines .row').clone();

$('#add_new').click(function () {
    cloned.clone().appendTo('#medicines');
});

$(document).ready(function () {


    $('#medicines').on('click', '#remove', function () {
        $(this).closest('.row').remove();
    });
    // get medicine list from php

    // $('#qty').prop('disabled', true);
    let medicines_list = [];

    if (!!medicines) {
        Object.keys(medicines).map(k => {
            medicines_list.push(medicines[k]);
        });
    }
    // get medicine list from php

    // medi type choose
    $('#medicines').on('change', '#medi_type', function () {
        let type_id = $(this).val();

        // $('#qty').prop('disabled', false);
        let lists = medicines_list.filter(medi => {
            return medi.type_id == type_id;
        });

        let medi_list = $(this).closest('.row').find('#medi_list');

        medi_list.html('<option value="0" hidden selected>Choose Medicine</option>');
        // add medicine
        lists.map(medicine => {
            medi_list.append(`<option value="${medicine.id}">${medicine.name}</option>`);
        });
    });
    $('#medicines').on('blur', '#qty', function () {
        let qty = $(this).val();

        if ($.trim(qty).length == 0) {
            $(this).attr('class', 'form-control border border-danger');
            $(this).parent().append('<small class="text-danger">Enter medicine quantity</small>');
        }
    });

    $('#medicines').on('focus', '#qty', function () {
        let qty = $(this).val();
        $(this).attr('class', 'form-control');
        $(this).parent().find('.text-danger').remove();

    });
    $('#medicines').on('keyup', '#qty', function () {
        let qty = $(this).val();

        let medicine_id = $(this).closest('.row').find('#medi_list option:selected').val();
        // console.log(medicine_id);
        if ($.trim(qty).length != 0) {
            $.ajax({
                type: "post",
                url: "check_mediStocks",
                data: { medicine_id: medicine_id },
                success: function (response) {
                    if (!response != "fail") {
                        let db_qty = JSON.parse(response);
                        // alert(db_qty)
                        if(parseInt(db_qty) < 40){
                            alert('Stock is low');
                        }
                        if (!isANumber(qty)) {
                            alert('Quantity must be a number');
                        }
                        if (parseInt(qty) > parseInt(db_qty) ) {
                            alert('Insufficient in amount');
                        }
                        if (qty == db_qty) {
                            alert('Please Enter Possible amount');
                        }
                    }
                    else {
                        alert('This medicine is out of stock');
                    }
                }
            });
        }
    });
});

function isANumber(str) {
    return Number(str) ? true : false;
}