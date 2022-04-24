    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg"style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small">
                <?php
                $sqluser='select id, username, firstname, lastname, time from userinfo where id="'.$_SESSION["UserIDPortal"].'"';
                $resultuser = $conn->query($sqluser);
                  if ($resultuser->num_rows > 0) {
                    while($row = $resultuser->fetch_assoc()) {
                        echo $row['firstname']." ".$row['lastname'];
                    }
                  }
                ?>
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="changepw">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  เปลี่ยนรหัสผ่าน
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  ออกจากระบบ
                </a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- Topbar -->