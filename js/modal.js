
// rep modal show
$('#rep_table .info').click(function(){
    let id = $(this).data('id');
    $('#rep_modal').modal('show');
    if(id != null){
        $.ajax({
            type: "post",
            url: "reception_info",
            data:{id: id},
            success: function (response) {
                let data = JSON.parse(response);
                
                
                $('#rep_modal #code').text(`Code: ${data.user_code}`);
                $('#rep_modal #name').text(`Name: ${data.name}`);
                $('#rep_modal #education').text(`Education: ${data.education}`);
                $('#rep_modal #phone').text(`Phone: ${data.phone}`);
                $('#rep_modal #nrc').text(`NRC: ${data.nrc}`);
                $('#rep_modal #dob').text(`Date Of Birth: ${data.age}`);
                $('#rep_modal #m_status').text(`Martial Status: ${data.martial_status}`);
                $('#rep_modal #start_date').text(`Start Date: ${data.created_at}`);
                $('#rep_modal #gender').text(`Code: ${(data.gender == 1)? 'Male' : 'Female'}`);
            }
        });
    }
});

$('#close').click(function(){
    $('#rep_modal').modal('hide');
});