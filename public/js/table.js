// edit file before update
const dt = new DataTransfer(); 

$("#attachment").on('change', function(e){
    for(var i = 0; i < this.files.length; i++){
        let fileBloc = $('<span/>', {class: 'file-block'}),
            fileName = $('<span/>', {class: 'name', text: this.files.item(i).name});
        fileBloc.append('<span class="file-delete"><span> x|</span></span>')
            .append(fileName);
        $("#filesList > #files-names").append(fileBloc);
    };
    
    for (let file of this.files) {
        dt.items.add(file);
    }
    
    this.files = dt.files;

    $('span.file-delete').click(function(){
        let name = $(this).next('span.name').text();
        $(this).parent().remove();
        for(let i = 0; i < dt.items.length; i++){
            if(name === dt.items[i].getAsFile().name){
                dt.items.remove(i);
                continue;
            }
        }
        
        document.getElementById('attachment').files = dt.files;
    });
});

//show receiving unit selection on click event
function get_unit() {
    var checkBox = document.getElementById("right2");
    var text = document.getElementById("unit_master");

    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
        text.style.display = "block";
    } else {
        text.style.display = "none";
    }
}

//show receiving unit selection on load event
window.onload = function myFunction() {
    var checkBox = document.getElementById("right2");
    var text = document.getElementById("unit_master");

    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
        text.style.display = "block";
    } else {
        text.style.display = "none";
    }
}

//prevent submit request when press enter while form not complete
$(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
    if (event.key == "Enter") {
        event.preventDefault();
    }
});

//setup data table
$(document).ready(function() {
    $('#history_approve').DataTable({
        language: {
            //customize pagination prev and next buttons: use arrows instead of words
            'paginate': {
            'previous': '<span class="fa fa-chevron-left"></span>',
            'next': '<span class="fa fa-chevron-right"></span>'
            },
            //customize number of elements to be displayed
            "lengthMenu": '<label style="margin-left: 1rem">Display <select class="form-control input-sm" style="width: 10ch; display: inline-block;">'+
                '<option value="10">10</option>'+
                '<option value="20">20</option>'+
                '<option value="30">30</option>'+
                '<option value="40">40</option>'+
                '<option value="50">50</option>'+
                '<option value="-1">All</option>'+
                '</select> results </label>',
        },
        dom: 'ilfrtp',
    })  
} );