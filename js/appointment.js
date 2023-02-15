let arr = [];

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
  // console.log(user_id)
  let dutyTime = $('#dutyTime');
  dutyTime.html(`<option value='0' selected hidden>Choose Time</option>`);

  // filter way

  let filter_arr = arr.filter(ele => {
    return ele.user_id == user_id;
  });

  // console.table(filter_arr);

  filter_arr.forEach(ele => {
    const shift_start = new Date(ele.shift_day + 'T' + ele.shift_start + 'Z')
      .toLocaleTimeString('en-US',
        { timeZone: 'UTC', hour12: true, hour: 'numeric', minute: 'numeric' }
      );

    const shift_end = new Date(ele.shift_day + 'T' + ele.shift_end + 'Z')
      .toLocaleTimeString('en-US',
        { timeZone: 'UTC', hour12: true, hour: 'numeric', minute: 'numeric' }
      );
      let val = shift_start +" - "+ shift_end;
    dutyTime.append(`<option value="${val}">${ val }</option>`)
  });
  
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

});