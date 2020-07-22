


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