var events_arr = [];
document.addEventListener('DOMContentLoaded', function () {
  /* initialize the calendar
  -----------------------------------------------------------------*/
  if (!!schedules) {
    Object.keys(schedules).map(k => {
      let row = schedules[k];
      events_arr.push({ id: row.id, title: `Dr.${row.name}`, start: `${row.shift_day}T${row.shift_start}`, end: `${row.shift_day}T${row.shift_end}`, allDay: false });
    });
  }
  console.log(schedules);

  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,list',
    },
    //defaultDate: schedules[0].shift_start,
    themeSystem: 'bootstrap',
    editable: true,
    selectable: true,
    events: events_arr,
    eventClick: function (info) {
      var _details = $('#event_details_modal');
      var id = info.event.id;
      console.log(id);

      // Change 24 hrs to 12 hrs format for display
      const shift_start = new Date(schedules[id].shift_day +'T'+schedules[id].shift_start+ 'Z')
        .toLocaleTimeString('en-US',
          { timeZone: 'UTC', hour12: true, hour: 'numeric', minute: 'numeric' }
        );
      const shift_end = new Date(schedules[id].shift_day +'T'+schedules[id].shift_end+ 'Z')
      .toLocaleTimeString('en-US',
        { timeZone: 'UTC', hour12: true, hour: 'numeric', minute: 'numeric' }
      );

      if (!!schedules[id]) {
        _details.find('#name').text(`Dr.${schedules[id].name}`);
        _details.find('#date').text(schedules[id].shift_day);
        _details.find('#start').text(shift_start)
        _details.find('#end').text(shift_end);
        _details.find('#edit,#delete').attr('data-id', id);
        _details.modal('show');
      } else {
        alert("Event is undefined");
      }
    },
  });
  calendar.render();

  // Edit Button
  $('#edit').click(function() {
    var id = $(this).attr('data-id');
    if (!!schedules[id]) {
        var _form = $('#schedule_form')
        _form.find('[name="id"]').val(id);
        _form.find('[name="user_id"]').val(schedules[id].user_id);
        _form.find('[name="shift_day"]').val(schedules[id].shift_day);
        _form.find('[name="shift_start"]').val(schedules[id].shift_start);
        _form.find('[name="shift_end"]').val(schedules[id].shift_end);
        let btns = '<button class="btn btn-info mx-3" name="update">Update</button><button class="btn btn-secondary" name="cancel">Cancel</button>';
        $('#btn-group').html(btns);
        // for page refresh
        $.ajax({
          type: "post",
          url: "saveBtnSession",
          data: {btns: btns},
          success: function (response) {
            
          }
        });

        $('#event_details_modal').modal('hide');
    } else {
        alert("Schedule is undefined");
    }
});

  // close model
  $('#event_details_modal #close').click(function(){
      $('#event_details_modal').modal('hide');
  });

  // delete schedule
  $('#event_details_modal #delete').click(function(){
      $("#event_details_modal").modal('hide');
      let id = $(this).attr("data-id");

      if(!!schedules[id]){
        let message = confirm('Are you sure?');

        if(message){
            $.ajax({
              type: "post",
              url: "delete_schedule",
              data: {id: id},
              success: function (response) {
                  if(response == "success"){
                      alert('Success');
                      window.location.href="http://localhost/CMS/schedule";
                  }
                  else{
                      alert("You can't delete!There is an appointment.");
                  }
              }
            });
        }
      }else{
        alert('Schedule is undefined');
      }
  })
});
