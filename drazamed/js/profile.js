$(".presDelete").on("click", function() {
    console.log($(this).data('id'));
    id = $(this).data('id');
    url = '/user/pres-delete/' + $(this).data('id')
    var r = ConfirmDialog("Esta seguro de querer borrar esta ordennnnnn?");
    if (r == true) {
        $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            // console.log(data);
            alert('Se ha borrado su orden');
            rowId = 'r' + id;
            rowInfo = 'pinfo' + id;
            console.log("Row Name: " + rowId);
            element = document.getElementById(rowId);
            rowIndex = element.rowIndex;
            document.getElementById('ordenes_pendientes').deleteRow(rowIndex);
            document.getElementById('ordenes_pendientes').deleteRow(rowIndex-1);
            document.getElementById('ordenes_pendientes').deleteRow(rowIndex-2);
            element = document.getElementById(rowInfo);
            rowIndex = element.rowIndex;
            document.getElementById('ordenes_pendientes').deleteRow(rowIndex);
        }
    });
    } else {

    }

    //window.location = "account-page/";
});


function ConfirmDialog(message) {
      $('<div></div>').appendTo('body')
        .html('<div><h6>' + message + '?</h6></div>')
        .dialog({
          modal: true,
          title: 'Borrar orden',
          zIndex: 10000,
          autoOpen: true,
          width: 'auto',
          resizable: false,
          buttons: {
            Yes: function() {
              $(this).dialog("close");
              return true ;
            },
            No: function() {
              $(this).dialog("close");
              return true ;
            }
          },
          close: function(event, ui) {
            $(this).remove();
          }
        });
    };

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