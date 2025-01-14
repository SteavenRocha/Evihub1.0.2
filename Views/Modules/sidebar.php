 <!--=============== SIDEBAR ===============-->
 <nav class="sidebar" id="sidebar">
     <div class="sidebar__container">
         <div class="sidebar__user">
             <div class="sidebar__img">
                 <img src="<?php echo BASE_URL; ?>assets/img/perfil.png" alt="image">
             </div>

             <div class="sidebar__info">
                 <h3><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario'; ?></h3>
                 <span><?php echo isset($_SESSION['nombre_rol']) ? $_SESSION['nombre_rol'] : 'Rol'; ?></span>
             </div>

         </div>

         <div class="sidebar__content">
             <div>
                 <h3 class="sidebar__title">MANAGE</h3>

                 <div class="sidebar__list">
                     <a href="<?php echo BASE_URL; ?>Evidence"
                         class="sidebar__link <?php echo (basename($_SERVER['REQUEST_URI']) == 'Evidence') ? 'active-link' : ''; ?>">
                         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-caret-up-square-fill" viewBox="0 0 16 16">
                             <path
                                 d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 9h8a.5.5 0 0 0 .374-.832l-4-4.5a.5.5 0 0 0-.748 0l-4 4.5A.5.5 0 0 0 4 11" />
                         </svg>
                         <span>Archivos</span>
                     </a>
                 </div>
             </div>

             <div>
                 <h3 class="sidebar__title">SETTINGS</h3>

                 <div class="sidebar__list">
                     <a href="<?php echo BASE_URL; ?>Branch"
                         class="sidebar__link <?php echo (basename($_SERVER['REQUEST_URI']) == 'Branch') ? 'active-link' : ''; ?>">
                         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-shop-window" viewBox="0 0 16 16">
                             <path
                                 d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5" />
                         </svg>
                         <span>Sucursales</span>
                     </a>

                     <a href="<?php echo BASE_URL; ?>Employee"
                         class="sidebar__link <?php echo (basename($_SERVER['REQUEST_URI']) == 'Employee') ? 'active-link' : ''; ?>">
                         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                             <path
                                 d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                         </svg>
                         <span>Empleados</span>
                     </a>

                     <a href="<?php echo BASE_URL; ?>User"
                         class="sidebar__link <?php echo (basename($_SERVER['REQUEST_URI']) == 'User') ? 'active-link' : ''; ?>">
                         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-person-fill" viewBox="0 0 16 16">
                             <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                         </svg>
                         <span>Usuarios</span>
                     </a>
                 </div>
             </div>
         </div>

         <div class="sidebar__actions">

             <a class="sidebar__link" id="btn-cerrar-sesion" href="<?php echo BASE_URL; ?>Login/salir">
                 <i class="ri-logout-box-r-fill"></i>
                 <span>Log Out</span>
             </a>
         </div>
     </div>
 </nav>