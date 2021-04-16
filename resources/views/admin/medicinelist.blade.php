@include('admin/header')
<style>
.dropdown-menu{
    right: 0;
    left:auto;
}
.js-mand {
  color:red;
}


.loader {
  animation: spinningColor 1.5s ease-in-out infinite;
  margin: 50px auto;
  border: 5px double #f0eff5;
  border-radius: 50%;
  width: 50px;
  height: 50px;
}

@keyframes spinningColor {
  0% {
    transform: rotate(360deg);
    border-top:5px dashed #f56682;
    border-bottom:5px dashed #387eff;
  }
  25% {
    border-top:5px dashed #f591a6;
    border-bottom:5px dashed #6da7f7;
  }
  50% {
    border-top:5px dashed #fd878e;
    border-bottom:5px dashed #4ba3ff;
  }
  75% {
    border-top:5px dashed #f57f8f;
    border-bottom:5px dashed #569dff;
  }
  100% {
    border-top:5px dashed #f56682;
    border-bottom:5px dashed #387eff;
  }
}

</style>
<script>
$(function()
{
    $('#dash').removeClass('active');
    $('#medicine').addClass('active');
});
</script>
<section id="content">
<section class="vbox">
  <section class="scrollable padder">
  <div class="m-b-md">
                <h3 class="m-b-none" style="margin-bottom: 10px;">{{ __('Medicine')}}</h3>
                <div class="row" style="
                    text-align: right;
                    padding-right: 20px;
                ">
                  <a class="btn btn-s-md btn-success btn-rounded" href="add-med"><i class="fa fa-fw fa-plus"></i> {{ __('Add Medicine')}}</a>
                  <a class="btn btn-s-md btn-info btn-rounded" href="#" id="upload-med"><i class="fa fa-fw fa-plus"></i> {{ __('Upload Medicine')}}</a>
                </div>

  </div>
  {{ $medicines->links() }}
<div class="col-lg-3 col-md-3 pull-right" style="text-align: center;padding: 10px;"><input class="form-control input-md" type="text" name="medine_search" id="medicine_search" placeholder="Search medicine by name" onkeyup="filter_medine(this.value,'ASC')"></div>
  <section class="panel panel-default">

   <table class="table table-striped m-b-none dataTable" id="myTable">
	<thead>
	  <tr>
	    <th>{{ __('No.')}}</th>
	    <th>{{ __('Item Name')}}</th>
	    <th>{{ __('Item Code')}}</th>
	    <!-- <th>{{ __('Expiry Date')}}</th> -->
	    <!-- <th>{{ __('Batch No.')}}</th> -->
	    <th>{{ __('MFG')}}</th>
	    <th>{{ __('Nature')}}</th>
	    <th>{{ __('MRP')}}</th>
	    <th id="sort" style="width:250px">{{ __('Composition')}}</th>
	    <th id="sort">{{ __('Is Prescription Required')}}</th>
      <th> Photo </th>
	    <th>{{ __('Actions')}}</th>
	  </tr>
	</thead>
	<tbody id="medicine_content">
	<?php
	if(count($medicines)>0)
	{
	$pageNumber= Request::get('page');
	for($i=0;$i<count($medicines);$i++)
	{?>
	   <tr>
	   <td><?php echo(isset($pageNumber)?$i+1+((Request::get('page')-1)*30):$i+1)?></td>
	   <td><?php echo $medicines[$i]['name']?></td>
	   <td><?php echo $medicines[$i]['item_code']?></td>
	   <!-- loa<td><?php echo $medicines[$i]['exp']?></td> -->
	   <!-- <td><?php echo $medicines[$i]['batch_no']?></td> -->
	   <td><?php echo $medicines[$i]['mfg']?></td>
	   <td><?php echo $medicines[$i]['group']?></td>
	   <td nowrap style="text-align: right"><?php echo Setting::currencyFormat($medicines[$i]['mrp']);?></td>
	   <td><?php echo $medicines[$i]['composition']; ?></td>
	   <td><?= ($medicines[$i]['is_pres_required'] == 1) ? __('Yes') : __('No') ; ?> </td>
     <?php
      $medImagen = isset($medicines[$i]['item_code']) ? $medicines[$i]['item_code'].'.jpg' : 'default.png';
      $medPath = "/images/products/" . $medImagen;
      $medPath = (is_file($medPath)) ? $medPath : "/images/products/default.png";
     ?>

    <td>
        <img src={{$medPath}} alt="{{$medicines[$i]['item_code']}}" width="120px">
    </td>
	   <td><div class="btn-group">
	   <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
           Actions <span class="caret"></span></button>
           <ul class="dropdown-menu" role="menu">
             <li><a target="_blank" href="medicine-edit/<?php echo $medicines[$i]['id']; ?>" >Edit</a></li>
             <li><a href="medicine-delete/<?php echo $medicines[$i]['id']; ?>">Delete</a></li>
             <li><a href="medicine-prescription/<?php echo $medicines[$i]['id']; ?>">{{ __('Toggle Prescription Status')}}</a></li>
           </ul>

	    </div>
	    </td>
	   </tr>
	   <?php } } else {?>
	   <tr><td colspan="7">{{ __('No Medicines Found.')}}</td></tr>
	   <?php }?>


	</tbody>

   </table>

 </section>
</section>
</section>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ __('Upload Medicine List')}}</h4>
      </div>
      <div class="modal-body">
          <div class="alert alert-success hide">
            <strong>{{ __('Success')}}!</strong> {{ __('Medicine successfully updated.')}}
          </div>
          <div class="alert alert-danger hide">

        </div>
        <form class="" enctype="multipart/form-data" id="frmUpload">
                <div class="form-group">
                {{ Form::token() }}
                <p>{{ __('Upload .xls .xlsx)')}}</p>
                        <input class="form-control" type="file" name="file" id="file" />
                </div>
                <!-- <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" width=0%>
                  0%
                </div> -->


        </form>
      </div>

      <div id="loading" class="hide" style="position:fixed; top:50%; left:45%; z-index:99" >
        <div class="loader" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close')}}</button>
        <button type="button" class="btn btn-info" id="file_upload">{{ __('Submit')}}</button>
      </div>
    </div>

  </div>
</div>


  <script>
    $(document).ready(function(e){

$('#upload-med').click(function(e){
    $('#myModal').modal('show');
});

$("#file_upload").click(function(e){
    if($('#file').val() == ""){
        return false;
    }

     var fd = new FormData();
     var file_data = $('#file').prop('files')[0];
     var _token = $('#frmUpload input[name="_token"]').val();
     fd.append("file", file_data);
     console.log(fd);
     fd.append("_token", _token);
    $.ajax({
        url:'../medicine/upload',
        type:'POST',
        data:fd,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        statusCode:{
            400:function(data){
            console.log(data);
                $('.alert-danger').html(data.responseJSON.msg).removeClass('hide');
            },
            403:function(data){
                $('.alert-danger').html(data.responseJSON.msg).removeClass('hide');
            }
        },
        beforeSend: function(){
          // Handle the beforeSend event
          $('#loading').removeClass('hide');
        },
        complete: function(){
          // Handle the complete event
          $('#loading').addClass('hide');
        },
        success:function(data){
            $('.alert-success').removeClass('hide');
            $('.alert-danger').addClass('hide');
            setTimeout(function(){ window.location.reload()},1000);
        }
    })

})
    document.getElementById('searchTop').style.display = 'none';
    });

    function filter_medine(medicine,order)
    {
        $.ajax({
            url: '../medicine/medicine-list-from-name',
            data: 'name='+medicine+'&ord='+order,
            type: 'GET',
            datatype: 'JSON',
            success: function (data) {
                links = data.link;
                data = data.medicines;
                var table_con="";
                var i=1;
                if(data.length>0)
                {
                    $.each(data, function ($key, $med) {

                     $status = ($med.is_pres_required == 1) ? 'Yes' : 'No';

                     table_con+="<tr><td>"+i+"</td><td>"+$med.name+"</td><td>"+$med.item_code+"</td><td>"+$med.mfg+"</td><td>"+$med.group+"</td><td style='text-align:right' nowrap> $ "+$med.mrp+"</td><td style='width:300px'>"+$med.composition+"</td>" +
                      "<td>"+$status+"</td>" +
                      "<td> <img src='"+$med.img_url +"' width='120px'> </img></td>" +
                      "<td><div class='btn-group'><button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-toggle='dropdown'>Actions <span class='caret'></span></button>" +
                      "<ul class='dropdown-menu' role='menu'>" +
                      "<li><a target='_blank' href='medicine-edit/"+$med.id+"' >Edit</a></li>" +
                      "<li><a href='medicine-delete/"+$med.id+"'>Delete</a></li>" +
                      "<li><a href='medicine-prescription/"+$med.id+"'>Toggle Prescription Status</a></li>" +
                      "</ul></div></td></tr>";
                     i++;
                    });
                }else{
                    table_con+="<tr><td colspan='8'><h4>No medicines found!!</h4></td></tr>";
                }

                $('#medicine_content').html(table_con);
//                if(table_con != ""){
//                  $("#myTable").DataTable({
//                     "paging": false,
//                     "searching": false,
//                     "columnDefs": [
//                         { "orderable": false, "targets": [0,1,2,3,4,5,6,7,9] }
//                       ]
//                     });
//                }

            }
        });


    }

    $("#loading").ajaxStart(function(){
       $(this).show();
     });

    $(".loader").ajaxStart(function(){
       $(this).show();
     });

    $("#loading").ajaxComplete(function(){
       $(this).hide();
     });

    $(".loader").ajaxComplete(function(){
       $(this).hide();
     });

  </script>
@include('admin/footer')
