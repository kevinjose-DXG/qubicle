<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>
         Meet Your Celebrity
      </title>
      <meta name="description" content="">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
      <!-- jquery -->
      <!-- CSS only -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
      <link rel="stylesheet" href="css/style.css">
      <!-- bootstrap icons -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
      <!-- favicon -->
      <link rel="icon" type="image/x-icon" href="{{ asset('/') }}images/favicon.png">
      <link rel="stylesheet" href="{{ asset('/') }}css/toastr.min.css">
        <style>
            .error{
                color: red;
            }
        </style>
    </head>
    <body style="background-color:#f52828 ;">
      <script>
         //toggle password button register
         
         function togglebutton(id) {
             var x = document.getElementById(id);
             if (x.type === "password") 
             {
               x.type = "text";
               document.getElementById(id+"_passButton").className = "btn bi bi-eye-fill";
             } else 
             {
               x.type = "password";
               document.getElementById(id+"_passButton").className = "btn bi bi-eye-slash-fill";
             }
           }
      </script>
      <!-- Navigation bar -->
        <!-- <nav class="navbar navbar-expand-lg bg-light ">
            <div class="container-fluid">
                <a class="navbar-brand" href="">
                    <img src="images/logo.png" alt="img" width="50px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                
            </div>
        </nav> -->
        @yield('content')
        
                <meta name="viewport" content="width=device-width, initial-scale=1">
               
            
                
         
        <!-- JavaScript Bundle with Popper -->
        <script src="{{ asset('/') }}js/jquery.min.js"></script>
        <script src="{{ asset('/')}}js/jquery.validate.js"></script>
        <script src="{{ asset('/') }}js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
        
        
        
        @yield('script')
       
        
    </body>
</html>