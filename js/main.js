let cms_card = document.querySelectorAll("#cms_card");


document.querySelector('#grid1').addEventListener('click', function () {

    document.cookie = 'class=col-3 mb-4';
    cms_card.forEach(ele => ele.setAttribute('class', 'col-3 mb-4'));
});

document.querySelector('#grid2').addEventListener('click', function () {
    document.cookie = 'class=col-6 mb-4';
    cms_card.forEach(ele => ele.setAttribute('class', 'col-6 mb-4'));
});

function getCookie() {
    return document.cookie.split(";")
        .map(cookie => cookie.split('='))
        .reduce((accumulator, [key, value]) =>
        ({
            ...accumulator, [key.trim()]: decodeURIComponent(value)
        }), {});
}

// upload img
function file_changed(){
    var selectedFile = document.getElementById('input').files[0];
    var img = document.getElementById('img')
  
    var reader = new FileReader();
    reader.onload = function(){
       img.src = this.result
    }
    reader.readAsDataURL(selectedFile);
   }