 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title><?php echo TITLE . ' - ' . $data['title'] ?></title>
     <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/Libraries/bootstrap.min.css">
     <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/Libraries/notyf.min.css">
     <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/Libraries/sweetalert2.min.css">
     <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/Libraries/dataTables.css">
     <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
 </head>

 <body>

     <div class="wrapper">

         <!--=============== HEADER ===============-->
         <header class="header" id="header">
             <div class="header__container">
                 <a href="#" class="header__logo">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mouse-fill" viewBox="0 0 16 16">
                         <path d="M3 5a5 5 0 0 1 10 0v6a5 5 0 0 1-10 0zm5.5-1.5a.5.5 0 0 0-1 0v2a.5.5 0 0 0 1 0z" />
                     </svg>
                     <span>EviHub</span>
                 </a>

                 <button class="header__toggle button__header" id="header-toggle">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                         <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                     </svg>
                 </button>
             </div>
         </header>