@include('admin/header')
<script>
$(function()
{
    $('#dash').removeClass('active');
    $('#presc').addClass('active');
});
</script>

<section id="content">
<section class="vbox">
  <section class="scrollable padder">

  <div class="row">
  <div class="col-lg-9">  <h3 class="m-b-none">{{ __('Shipped Prescriptions')}}</h3></div>
  <div class="col-lg-3" style="padding-top: 7px">  <input class="form-control" type="text" name="pres_search" id="pres_search" placeholder="{{ __('Search prescription by email')}}" onkeyup="filter_pres(this.value,'shipped')" />
</div>
  </div>
  <?php
  echo ($pres->links());
  ?>
  <section class="panel panel-default">
   <table class="table table-striped m-b-none dataTable">
	<thead>
	  <tr>
      <th>{{ __('No.')}}</th>
      <th>{{ __('From')}}</th>
      <th>{{ __('Date')}}</th>
      <th>{{ __('Invoice Payment Status')}}</th>
      <th>{{ __('Shipping Status')}}</th>
      <th>{{ __('Actions')}}</th>
      <th>{{ __('Invoice')}}</th>
	  </tr>
	</thead>
	<tbody id="pres_content">
<?php
	if(count($pres)>0)
	{
	$pageNumber= Request::get('page');
	for($i=0;$i<count($pres);$i++)
	{
	 if ($pres[$i]->in_status =='pending') {
        $paid = "<i class='fa fa-times'  style='color:#DF0101'></i>";
     } else {
        $paid = "<i class='fa fa-check'  style='color:#01DF01'></i>";
     }
	?>
	   <tr>
	   <td>{{(isset($pageNumber)?$i+1+((Request::get('page')-1)*30):$i+1)}}</td>
	   <td>{{$pres[$i]->email}}</td>
	   <td>{{date('d-M-Y',strtotime($pres[$i]->date_created))}}</td>
	   <td style="text-align: center">{!!$paid!!}</td>

	   <td>
      @if($pres[$i]->shipping_status != ShippingStatus::DELIVERED())
          <a class='btn btn-s-md btn-info btn-rounded' href='{{url("/")}}/admin/deliver-order/{{$pres[$i]->pres_id}}'  onclick="return confirm('Do you really want to make this order as delivered?');">{{ __('Delivery Order')}}</a>
        @else
            {{ __('Enviado')}}
        @endif
     </td>
	   <td><a class='btn btn-s-md btn-info btn-rounded' href='{{url("/")}}/admin/pres-edit/{{$pres[$i]->pres_id}}/0' >{{ __('Details')}}</a>&nbsp;&nbsp;&nbsp;
	   {{--<a class='btn btn-s-md btn-danger btn-rounded' href='{{url("/")}}/admin/pres-delete/{{$pres[$i]->pres_id}}/shipped' onclick="return confirm('Do you really want to delete this order?');">{{ __('Delete')}}</a></td>--}}
	   <td><a class='text-info' href='{{url("/")}}/admin/load-invoice/{{$pres[$i]->id}}'>{{ $pres[$i]->invoice}}</a></td>
	   </tr>
	   <?php } } else {?>
	   <tr><td colspan="7">{{ __('No Prescriptions Found.')}}</td></tr>
	   <?php }?>


	</tbody>
	</tbody>
	</table>
 </section>
 </section></section></section>
  <script>
   function filter_pres(email,status)
       {
           $.ajax({
               url: '../admin/load-pres-email',
               data: 'email='+email+'&status='+status,
               type: 'GET',
               datatype: 'JSON',
               beforeSend: function () {

               },
               success: function (data) {
                   var table_con="";
                   var i=1;
                   if(data.length>0)
                   {
                       $.each(data, function ($key, $med) {
                            table_con+="<tr><td>"+i+"</td><td>"+$med.email+"</td><td>"+$med.created_date+"</td><td style='text-align: center'> Pagada </td><td>Shipped</td>" +
                             "<td><a class='btn btn-s-md btn-info btn-rounded' href='{{url("/")}}/admin/pres-edit/"+$med.pres_id+"/0' >Details</a></td>" +
                               "<td><a class='text-info' href='{{url("/")}}/admin/load-invoice/"+$med.pres_id+"'>EZ" +(1000000+$med.pres_id)+"</a></td></tr>";
                            i++;
                       });
                   }else{
                       table_con+="<tr><td colspan='8'><h4>No prescriptions found!!</h4></td></tr>";
                   }
                   $('#pres_content').html(table_con);
               }
           });
       }
  </script>
@include('admin/footer')
