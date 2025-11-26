           <?php
            if (true) { ?>
             <li class="nav-item">
               <a href="#" class="nav-link">
                 <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                 <i class="nav-icon fas bi bi-person-lines-fill">
                   <img src="<?= URL; ?>/public/images/roles.svg" alt="Inscripcion">

                 </i>
                 <p>
                   Roles
                   <i class="right fas fa-angle-left"></i>
                 </p>
               </a>
               <ul class="nav nav-treeview">
                 <li class="nav-item">
                   <a href="<?= URL; ?>/admin/roles/index.php" class="nav-link">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Listado de roles</p>
                   </a>
                 </li>
               </ul>
             </li>

           <?php
            }
            ?>