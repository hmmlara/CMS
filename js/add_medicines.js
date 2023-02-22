$(document).ready(function () {

    // get medicine list from php

    $('#qty').prop('disabled', true);
    let medicines_list = [];

    if (!!medicines) {
        Object.keys(medicines).map(k => {
            medicines_list.push(medicines[k]);
        });
    }
    // get medicine list from php

    // add medicine list
    var cloned = $('#medicines .row').clone();

    $('#add_new').click(function () {
        cloned.clone().appendTo('#medicines');
    });

    $('#medicines').on('click', '#remove', function () {
        $(this).closest('.row').remove();
    });

    // medi type choose
    $('#medicines').on('change', '#medi_type', function () {
        let type_id = $(this).val();

        $('#qty').prop('disabled', false);
        let lists = medicines_list.filter(medi => {
            return medi.type_id == type_id;
        });

        let medi_list = $(this).closest('.row').find('#medi_list');

        medi_list.html('<option value="0" hidden selected>Choose Medicine</option>');
        // add medicine
        lists.map(medicine => {
            medi_list.append(`<option value='${medicine.id}'>${medicine.name}</option>`);
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

        if ($.trim(qty).length != 0) {
            $.ajax({
                type: "post",
                url: "check_mediStocks",
                data: { medicine_id: $('#medi_list').val() },
                success: function (response) {
                    if (!response != "fail") {
                        let db_qty = JSON.parse(response);
                        if (qty > db_qty) {
                            alert('Insufficient in stock.Please choose other medicine with similar effect');
                            $(this).val();
                        }
                        if (qty == db_qty) {
                            alert('Please Enter Possible amount');
                            $(this).val();
                        }
                    }
                    else {
                        alert('fail to search');
                    }
                }
            });
        }
    });
});