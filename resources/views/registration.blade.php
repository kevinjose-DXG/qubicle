@extends('layouts.webapp')
@section('content')

   <!-- regsitration form section -->
   <!-- main div for center align -->
   <div class="d-flex justify-content-center">
   <div class="container p-5 col-12  col-xl-4 m-5" style="background-color: white; ">
      <form id="UserRegForm" action="javascript:;">
         @csrf
         <div class="row text-center mb-3">
            <h3 class="heading_font mb-3">Register !</h3>
         </div>
         <!--username -->
         <div class="row ">
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text " style="background: none; border:none;"><i class="bi bi-person-fill fs-4"></i></span>
               </div>
               <div class="form-group col-9">
                  <input type="text" name="username" id="username" class="form-control" placeholder="Name" >
               </div>
            </div>
         </div>
          <div class="row ">
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text " style="background: none; border:none;"><i class="bi  bi-envelope-fill fs-4"></i></span>
               </div>
               <div class="form-group col-9">
                  <input type="text" name="email" id="email" class="form-control" placeholder="Email ..." >
               </div>
            </div>
         </div>
        
         <!-- Email adress -->
        
         <!-- Password -->
         <div class="row ">
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text " style="background: none; border:none;"><i class="bi bi-box-fill fs-4"></i></span>
               </div>
               <div class="form-group col-9">
                  <input type="text" name="refferal_code" id="refferal_code" class="form-control" placeholder="Refferal Code" >
               </div>
            </div>
         </div>
         <div class="d-flex justify-content-center mt-2">
            <div class="row col-12">
               <button type="submit" class="btn p-3 addBtn" style=" background-color:black; color:#ffffff; font-variant: all-petite-caps;
                  font-size: medium;">Register Now</button>
                  
            </div>
         </div>
      </form>
      
   </div>
</div>
<div style="height: 150px; width:100%"></div>
@endsection
@section('script')
<script type="text/javascript">
   $.validator.addMethod("patterntest", function(value) {
      var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
      return pattern.test(value) // lower case
   });
   $("#UserRegForm").validate({
         normalizer : function (value) {
         return $.trim(value);
      },
      ignore: [],
      rules: {
         'username': {
            required: true,
         },
        
         'email' :{
            required: true,
            patterntest:true
         },
        
      },
      messages: {
         username: "Please enter Username",
         email: {
            required:"please enter email",
            patterntest:"Please Enter a Valid Email Address"
         },
        
       
      },
      submitHandler: function (form) {
         var form        = document.getElementById("UserRegForm");
         var formData    = new FormData(form);
         $(".addBtn").prop("disabled", true);
         $.ajax({
               data: formData,
               type: "post",
               url: "{{route('save.user.registration')}}",
               processData: false,
               dataType: "json",
               contentType: false, // The content type used when sending data to the server.
               cache: false, // To unable request pages to be cached
               async: true,
               headers: {
                  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
               },
               beforeSend: function () {
                  $(".addBtn").text('Registering..');
               },
               success: function (data) {
                  if(data.status==true){
                     $(".addBtn").text('Submit');
                     $(".addBtn").prop("disabled", false);
                           toastr.success(data.message);
                           setTimeout(function(){location.href=""} , 3000);
                  }else{
                     toastr.error(data.message);
                     $(".addBtn").text('Submit');
                     $(".addBtn").prop("disabled", false);
                  }
               },
         });
      },
   });
</script>
@endsection