
$(".presDelete").on("click", function() {
    console.log($(this).data('id'));
    id = $(this).data('id');
    url = '/user/pres-delete/' + $(this).data('id')
    var r = confirm("Esta seguro de querer borrar esta orden?");
    if (r == true) {
        $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            // console.log(data);
            alert('Se ha borrado su orden');
            rowId = 'r' + id;
            console.log("Row Name: " + rowId);
            element = document.getElementById(rowId);
            rowIndex = element.rowIndex;
            document.getElementById('ordenes_pendientes').deleteRow(rowIndex);
            document.getElementById('ordenes_pendientes').deleteRow(rowIndex+1);
            console.log(element);
        }
    });
    } else {

    }

    //window.location = "account-page/";
});

// Muestra el detalle de la compra seleccionada
$('.details').on('click', function(){
        $('.detail').hide();
        let detailId = "d" + $(this).data('id');
        element = document.getElementById(detailId);
        console.log($(detailId));
        console.log(element.style.display);

        show(detailId);
        // element.toggle()    //$(this).closest('div').hasClass('detail').toggle();
    })

function show(detailId) {
    if (document.getElementById(detailId).style.display = "none")
        {
            document.getElementById(detailId).style.display = "block";
            // $test="visible"
        }
    else
    {
        document.getElementById(detailId).style.display = "none";
    // $test="hidden"
    }
}