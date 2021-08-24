<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link  rel="stylesheet" href="{{asset('asset/css/toastr.min.css')}}">
    <link  rel="stylesheet" href="{{asset('asset/css/sweetalert2.min.css')}}">


    <title>Hello, world!</title>
  </head>
  <body>
   <div class="container">
       <div class="card">
           <div class="card-header">
               <button type="button" class="btn btn-primary" data-toggle="modal" id="create_record">Add Record</button>
               <button class="btn btn-danger d-none" id="deleteAll">Delete all</button>
           </div>
           <div class="card-body">
               <table class="table table-hover" id="country_table">
                   <thead>
                        <th><input type="checkbox" name="main_checkbox"></th>
                       <th>Sl</th>
                       <th>Country Name</th>
                       <th>Capital Name</th>
                       <th>Action</th>

                   </thead>

               </table>
           </div>
       </div>
       <div class="modal fade " id="formModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-lg">
               <div class="modal-content">
                   <span id="form_result"></span>
                   <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel"></h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <form id="sample_form" method="POST" autocomplete="off">
                       @csrf
                   <div class="modal-body">

                           <div class="form-group">
                               <label for="">Country name</label>
                               <input type="text" class="form-control" name="country_name" placeholder="Enter country name">

                           </div>
                           <div class="form-group">
                               <label for="">Capital city</label>
                               <input type="text" class="form-control" name="capital_city" placeholder="Enter capital city">

                           </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                       <button type="submit" class="btn btn-primary" >Save changes</button>
                   </div>
                   </form>
               </div>
           </div>
       </div>
       <div class="modal fade " id="editModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-lg">
               <div class="modal-content">
                   <span id="up_form_result"></span>
                   <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel"></h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <form id="update_form" method="POST" autocomplete="off">
                       @csrf
                       <div class="modal-body">

                           <div class="form-group">
                               <label for="">Country name</label>
                               <input type="text" class="form-control" name="country_name" id="country_name" placeholder="Enter country name">

                           </div>
                           <div class="form-group">
                               <label for="">Capital city</label>
                               <input type="text" class="form-control" name="capital_city" id="capital_city" placeholder="Enter capital city">

                           </div>
                           <input type="hidden" name="hidden_id" id="hidden_id"/>


                       </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                           <button type="submit" class="btn btn-primary" id="action_button">Save changes</button>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
    <!-- Optional JavaScript; choose one of the two! -->


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
   <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" ></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js" ></script>
    <script src="{{asset('asset/js/toastr.min.js')}}"></script>
    <script src="{{asset('asset/js/sweetalert2.min.js')}}"></script>
   <script>
       toastr.options.preventDuplicates = true;
       $.ajaxSetup({
           headers:{
               'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
           }
       });

       $(function () {
           // Add Data
           $('#sample_form').on('submit',function(e){
               e.preventDefault();
               var form=this;
               $.ajax({
                    url: "{{route('country.store')}}",
                   method:"POST",
                   data:new FormData(form),
                   processData:false,
                   dataType:'json',
                   contentType:false,
                   success: function (data) {
                       var html = '';
                       if (data.errors) {
                           for (let count = 0; count < data.errors.length; count++) {
                               toastr.error(data.errors[count],'Error');
                           }

                       }
                       if (data.success) {
                           toastr.success(data.success, 'Success!')
                           $('#sample_form')[0].reset();
                           // $('select').selectpicker('refresh');
                           $("#formModal").modal('hide');

                           $('#country_table').DataTable().ajax.reload();
                       }
                       $('#form_result').html(html).slideDown(300).delay(5000).slideUp(300);
                   }


               })

           })


           //Get result
           $('#country_table').DataTable({
               processing:true,
               info:true,
               ajax:"{{ route('country.index') }}",
               "pageLength":10,
               "aLengthMenu":[[10,25,50,-1],[10,25,50,"All"]],
               columns:[
                    // {data:'id', name:'id'},
                   {data:'checkbox', name:'checkbox', orderable:false, searchable:false},
                   {data:'DT_RowIndex', name:'DT_RowIndex'},
                   {data:'country_name', name:'country_name'},
                   {data:'capital_city', name:'capital_city'},
                   {data:'actions', name:'actions', orderable:false, searchable:false},
               ]


           });
           //edit
           $(document).on('click','#editCountryBtn',function () {
               var id=$(this).data('id');
               var target="{{url('/country/edit')}}/"+id;
               $.ajax({
                   url: target,
                   dataType: "json",
                   success: function (html) {

                       $('#country_name').val(html.data.country_name);
                       $('#capital_city').val(html.data.capital_city);
                       $('#hidden_id').val(html.data.id);
                       $('#editModal').modal('show');
                       $('#action_button').text('{{__('Update')}}');
                   }
               })


           })


           //update
           $('#update_form').on('submit',function(e){
               e.preventDefault();
               var form=this;
               $.ajax({
                   url: "{{route('country.update')}}",
                   method:"POST",
                   data:new FormData(form),
                   processData:false,
                   dataType:'json',
                   contentType:false,
                   success: function (data) {
                       var html = '';
                       if (data.errors) {
                           for (let count = 0; count < data.errors.length; count++) {
                               toastr.error(data.errors[count],'Error');
                           }

                       }
                       if (data.success) {
                           toastr.success(data.success, 'Success!')
                           $('#update_form')[0].reset();
                           // $('select').selectpicker('refresh');
                           $("#editModal").modal('hide');

                           $('#country_table').DataTable().ajax.reload();
                       }
                       $('#up_form_result').html(html).slideDown(300).delay(5000).slideUp(300);
                   }


               })

           })
           //Delete
           $(document).on('click','#deleteCountryBtn',function () {
               var id=$(this).data('id');
             //  alert(id);

               var target="{{url('/country/delete')}}/"+id;

               const swalWithBootstrapButtons = Swal.mixin({
                   customClass: {
                       confirmButton: 'btn btn-success',
                       cancelButton: 'btn btn-danger'
                   },
                   buttonsStyling: false
               })
               swalWithBootstrapButtons.fire({
                   title: 'Are you sure?',
                   text: "You won't be able to revert this!",
                   icon: 'warning',
                   showCancelButton: true,
                   confirmButtonText: 'Yes, delete it!',
                   cancelButtonText: 'No, cancel!',
                   reverseButtons: true
               }).then((result) => {
                   if (result.isConfirmed) {
                       $.ajax({
                           type:"GET",
                           dataType:"json",
                           url:target,
                           success:function (data){
                               setTimeout(function () {
                                   swalWithBootstrapButtons.fire(
                                       'Deleted!',
                                       'Your file has been deleted.',
                                       'success'
                                   )
                                   $('#country_table').DataTable().ajax.reload();
                               },100);

                           }
                       })

                   } else if (
                       /* Read more about handling dismissals below */
                       result.dismiss === Swal.DismissReason.cancel
                   ) {
                       swalWithBootstrapButtons.fire(
                           'Cancelled',
                           'Your imaginary file is safe :)',
                           'error'
                       )
                   }
               })




           })

           $(document).on('click','input[name="main_checkbox"]', function(){
               if(this.checked){
                   $('input[name="country_checkbox"]').each(function(){
                       this.checked = true;
                   });
               }else{
                   $('input[name="country_checkbox"]').each(function(){
                       this.checked = false;
                   });
               }
               toggledeleteAllBtn();
           });

           $(document).on('change','input[name="country_checkbox"]', function(){
               if( $('input[name="country_checkbox"]').length == $('input[name="country_checkbox"]:checked').length ){
                   $('input[name="main_checkbox"]').prop('checked', true);
               }else{
                   $('input[name="main_checkbox"]').prop('checked', false);
               }
               toggledeleteAllBtn();
           });

           function toggledeleteAllBtn(){
               if( $('input[name="country_checkbox"]:checked').length > 0 ){
                   $('button#deleteAll').text('Delete ('+$('input[name="country_checkbox"]:checked').length+')').removeClass('d-none');
               }else{
                   $('button#deleteAll').addClass('d-none');
               }
           }
           $(document).on('click','button#deleteAll',function () {
               var checkedCountries = [];
               $('input[name="country_checkbox"]:checked').each(function(){
                   checkedCountries.push($(this).data('id'));
               });
               // alert(checkedCountries)
               if (checkedCountries.length>0){
                   const swalWithBootstrapButtons = Swal.mixin({
                       customClass: {
                           confirmButton: 'btn btn-success',
                           cancelButton: 'btn btn-danger'
                       },
                       buttonsStyling: false
                   })
                   swalWithBootstrapButtons.fire({
                       title: 'Are you sure?'+checkedCountries.length+'Item',
                       text: "You won't be able to revert this!",
                       icon: 'warning',
                       showCancelButton: true,
                       confirmButtonText: 'Yes, delete it!',
                       cancelButtonText: 'No, cancel!',
                       reverseButtons: true
                   }).then((result) => {
                       if (result.isConfirmed) {
                           $.ajax({
                               type:"POST",
                               dataType:"json",
                               url:'{{route("bulk.delete")}}',
                               data: {
                                   CountryIdArray: checkedCountries
                               },
                               success:function (data){
                                   setTimeout(function () {
                                       swalWithBootstrapButtons.fire(
                                           'Deleted!',
                                           'Your file has been deleted.',
                                           'success'
                                       )
                                       $('#country_table').DataTable().ajax.reload();
                                       table.rows('.selected').deselect();
                                   },100);

                               }
                           })

                       } else if (
                           /* Read more about handling dismissals below */
                           result.dismiss === Swal.DismissReason.cancel
                       ) {
                           swalWithBootstrapButtons.fire(
                               'Cancelled',
                               'Your imaginary file is safe :)',
                               'error'
                           )
                       }
                   })
               } else {
                   toastr.warning('Please Select at-least One item','Warning');
               }

           })




       });






       $('#create_record').on('click', function () {

           $('.modal-title').text('{{__('Add New Company')}}');
           $('#formModal').modal('show');
       });
       $('.close').on('click', function () {
           $('#sample_form')[0].reset();
           $('#country_table').DataTable().ajax.reload();
           // $('select').selectpicker('refresh');
       });
   </script>


    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
