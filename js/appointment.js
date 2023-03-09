$(function () {

  let arr = [];

  let filter_arr = [];
  let days = [];
  // get value from duty_time json array
  if (!!duty_time) {
    Object.keys(duty_time).map(k => {
      arr.push(duty_time[k]);
    });
  }

  // arr.forEach(ele => {
  //   console.log(ele);
  // })


  // console.table(filter_arr);

  $('#chooseDoc').change(function () {

    let user_id = $(this).val();
    // console.log(user_id);

    // cleaning val
    $('#sch_day').val('');
    $('#sch_day').datepicker('destroy');
    $('#sch_day').datepicker({ minDate: new Date(), dateFormat: 'mm/dd/yy', beforeShowDay: enableDays });
    $('#sch_day').attr('placeholder', 'mm/dd/yy');
    $('#dutyTime').html(`<option value='0' selected hidden>No Time to show</option>`);

    // console.log(user_id)

    // filter way

    filter_arr = arr.filter(ele => {
      return ele.user_id == user_id;
    });

    // console.table(filter_arr);

    // filter way

    // Ajax Way

    // $.ajax({
    //   type: "post",
    //   url: "search_appointment",
    //   data: {id: user_id},
    //   success: function (response) {
    //       if(response != 'fail'){
    //         let filter_arr = JSON.parse(response);

    //         filter_arr.forEach(ele => {
    //           const shift_start = new Date(ele.shift_day + 'T' + ele.shift_start + 'Z')
    //             .toLocaleTimeString('en-US',
    //               { timeZone: 'UTC', hour12: true, hour: 'numeric', minute: 'numeric' }
    //             );

    //           const shift_end = new Date(ele.shift_day + 'T' + ele.shift_end + 'Z')
    //             .toLocaleTimeString('en-US',
    //               { timeZone: 'UTC', hour12: true, hour: 'numeric', minute: 'numeric' }
    //             );
    //           dutyTime.append(`<option value="${ele.user_id}">${shift_start} - ${ shift_end }</option>`)
    //         });
    //       }
    //   }
    // });
    // Ajax Way
  })
  $('#sch_day').on('focus', function () {
    

    $('#sch_day').val();
    days = filter_arr.map(ele => {
      return ele.shift_day;
    });

    $('#sch_day').datepicker({ minDate: new Date(), dateFormat: 'mm/dd/yy', beforeShowDay: enableDays });


    // console.log(filter_arr);
  });

  // for duty time
  $('#sch_day').on('change', function () {

    // styling time select
    let dutyTime = $('#dutyTime');
    dutyTime.html(`<option value='0' selected hidden>Choose Time</option>`);


    // format date mm/dd/yy to yy-mm-dd
    let pickDate = new Date($(this).val());
    let formatDate = new Date(pickDate.getTime() - (pickDate.getTimezoneOffset()) * 60000)
      .toISOString().split('T')[0];

    // console.log(filter_arr);
    filter_arr = filter_arr.filter(ele => {
      return ele.shift_day == formatDate;
    });


    // console.log(filter_arr);
    // filter for duty time
    filter_arr.forEach(ele => {
      const shift_start = new Date(ele.shift_day + 'T' + ele.shift_start + 'Z')
        .toLocaleTimeString('en-US',
          { timeZone: 'UTC', hour12: true, hour: 'numeric', minute: 'numeric' }
        );

      const shift_end = new Date(ele.shift_day + 'T' + ele.shift_end + 'Z')
        .toLocaleTimeString('en-US',
          { timeZone: 'UTC', hour12: true, hour: 'numeric', minute: 'numeric' }
        );
      let val = shift_start + " - " + shift_end;
      dutyTime.append(`<option value="${val}">${val}</option>`)
    });


  });

  function enableDays(date) {
    let sDate = $.datepicker.formatDate('yy-mm-dd', date);
    if ($.inArray(sDate, days) != -1) {
      return [true];
    }
    return [false];
  }
});
