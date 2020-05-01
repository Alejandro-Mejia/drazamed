@include('admin/header')
<script>
$(function()
{
    $('#dash').removeClass('active');
    $('#requested').addClass('active');
});
</script>

<section id="content">
<section class="vbox">
  <section class="scrollable padder">
  <div class="m-b-md">
                <h3 class="m-b-none">{{ __('Requested Medicines List')}}</h3>
  </div>
  <?php
  echo ($pres->links());
  ?>
  <section class="panel panel-default">
   <table class="table table-striped m-b-none dataTable">
	<thead>
	  <tr>
	    <th>{{ __('No.')}}</th>
	    <th>{{ __('Medicine Name')}}</th>
	    <th>{{ __('Requested Count')}}</th>
	    <th>{{ __('Date')}}</th>
	    <th width="390px">{{ __('Actions')}}</th>
	  </tr>
	</thead>
	<tbody>
<?php
	if(count($pres)>0)
	{
	$pageNumber= Request::get('page');
	for($i=0;$i<count($pres);$i++)
	{
	?>
	   <tr>
	   <td>{{(isset($pageNumber)?$i+1+((Request::get('page')-1)*30):$i+1)}}</td>
	   <td>{{$pres[$i]->name}}</td>
	   <td>{{$pres[$i]->count}}</td>
	   <td>{{date('d-M-Y',strtotime($pres[$i]->created_at))}}</td>
	   <td>
	   <a class="btn btn-s-md btn-info btn-rounded"  data-toggle="modal" data-target="#myModal" onclick="show_email({{$pres[$i]->id}})">{{ __('View Requested Emails')}}</a> &nbsp;&nbsp;&nbsp;&nbsp;
	   <a class="btn btn-s-md btn-danger btn-rounded" href="{{url('/')}}/admin/delete-new-medicine/{{$pres[$i]->id}}" onclick="return confirm('Do you really want to delete this requested medicine?');">{{ __('Delete')}}</a>
	   </td>
	   </tr>
	   <?php } } else {?>
	   <tr><td colspan="7">{{ __('No Medicines Found.')}}</td></tr>
	   <?php }?>
	</tbody>
	</tbody>
	</table>
 </section>
 </section></section></section>

@include('admin/footer')

<div id="myModal" class="modal fade" role="dialog">
   <div class="modal-dialog">

     <!-- Modal content-->
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title">{{ __('Requested Emails')}}</h4>
       </div>
       <div class="modal-body" id="emails">
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close')}}</button>
       </div>
     </div>

   </div>
 </div>
<script>
function show_email(med)
{
    $.ajax({
            url:'{{ URL::to('admin/new-medicine-email' )}}',
            type:'GET',
            data:'med='+med,
            datatype: 'JSON',
            success: function(data){
            var email="";
                $.each(data,function($key,$d) {
                  email+="<p>"+$d.email+"</p>";
                });
               $('#emails').html(email);
            }
            });
}
</script>