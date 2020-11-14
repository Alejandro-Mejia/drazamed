<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<div class="social-buttons">
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
       target="_blank">
       <i class="fa fa-3x fa-comment" style="color:white"></i>
    </a>

    <a href="https://www.facebook.com/pages/category/Pharmacy---Drugstore/Drazamed-100465195081694/"
       target="_blank">
       <i class="fa fa-3x fa-facebook" style="color:white"></i>
    </a>

    <a href="https://www.instagram.com/drazamed_colombia/"
       target="_blank">
        <i class="fa fa-3x fa-instagram" style="color:white"></i>
    </a>


</div>



<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
<script>

    var popupSize = {
        width: 780,
        height: 550
    };

    $(document).on('click', '.social-buttons > a', function(e){

        var
            verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
            horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

        var popup = window.open($(this).prop('href'), 'social',
            'width='+popupSize.width+',height='+popupSize.height+
            ',left='+verticalPos+',top='+horisontalPos+
            ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

        if (popup) {
            popup.focus();
            e.preventDefault();
        }

    });
</script>
