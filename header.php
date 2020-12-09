<?php
  include('conexao.php');

  if(!isset($_SESSION['idUser'])){
    header("Location: login.php");
    exit;
  }

  $id_usuario = $_SESSION['idUser'];
  $consulta = $pdo->query("SELECT * FROM users where id = $id_usuario;");
  while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
      $nome = $linha['nome'];
      $tipo_usuario = $linha['tipo_user'];
      $codigo_corretor = $linha['codigo_corretor'];
  }

?>

<aside class="menu-sidebar2">
    <div class="logo">
        <a href="#">
            <img src="images/logo.png" alt="Cool Admin" />
        </a>
    </div>
    <div class="menu-sidebar2__content js-scrollbar1">
        <div class="account2">
            <div class="image img-cir img-120">
                <img src="images/icon/avatar-big-01.jpg" alt="John Doe" />
            </div>
            <h4 class="name"><?php echo''.$nome; ?></h4>
            <a href="session_destroy.php">Deslogar</a>
        </div>
        <nav class="navbar-sidebar2">
            <ul class="list-unstyled navbar__list">
                <li class="active has-sub">
                    <a class="js-arrow" href="inicio.php">
                      <i class="fas fa-tachometer-alt"></i>Ínicio
                    </a>
                </li>
                <li>
                    <a href="material.php">
                    <i class="fas fa-chart-bar"></i>Material de Venda</a>
                </li>
                <li>
                    <a href="contratospj.php">
                    <i class="fas fa-edit"></i>Contratos PME</a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
      <!-- END HEADER MOBILE-->

      <!-- MENU SIDEBAR-->
      <div class="page-container2">
          <!-- HEADER DESKTOP-->
          <header class="header-desktop2">
              <div class="section__content section__content--p30">
                  <div class="container-fluid">
                      <div class="header-wrap2">
                          <div class="logo d-block d-lg-none">
                              <a href="#">
                                <img src="images/logo.png" alt="Cool Admin" />
                              </a>
                          </div>
                          <div class="header-button2">
                              <div class="header-button-item js-item-menu">

                              </div>
                              <div class="header-button-item has-noti js-item-menu">
                                  <i class="zmdi zmdi-notifications"></i>
                                  <div class="notifi-dropdown js-dropdown">
                                      <div class="notifi__title">
                                          <p>You have 3 Notifications</p>
                                      </div>
                                      <div class="notifi__item">
                                          <div class="bg-c1 img-cir img-40">
                                              <i class="zmdi zmdi-email-open"></i>
                                          </div>
                                          <div class="content">
                                              <p>You got a email notification</p>
                                              <span class="date">April 12, 2018 06:50</span>
                                          </div>
                                      </div>
                                      <div class="notifi__item">
                                          <div class="bg-c2 img-cir img-40">
                                              <i class="zmdi zmdi-account-box"></i>
                                          </div>
                                          <div class="content">
                                              <p>Your account has been blocked</p>
                                              <span class="date">April 12, 2018 06:50</span>
                                          </div>
                                      </div>
                                      <div class="notifi__item">
                                          <div class="bg-c3 img-cir img-40">
                                              <i class="zmdi zmdi-file-text"></i>
                                          </div>
                                          <div class="content">
                                              <p>You got a new file</p>
                                              <span class="date">April 12, 2018 06:50</span>
                                          </div>
                                      </div>
                                      <div class="notifi__footer">
                                          <a href="#">All notifications</a>
                                      </div>
                                  </div>
                              </div>
                              <div class="header-button-item mr-0 js-sidebar-btn">
                                  <i class="zmdi zmdi-menu"></i>
                              </div>
                              <div class="setting-menu js-right-sidebar d-none d-lg-block">
                                  <div class="account-dropdown__body">
                                      <div class="account-dropdown__item">
                                          <a href="#">
                                              <i class="zmdi zmdi-account"></i>Account</a>
                                      </div>
                                      <div class="account-dropdown__item">
                                          <a href="#">
                                              <i class="zmdi zmdi-settings"></i>Setting</a>
                                      </div>
                                      <div class="account-dropdown__item">
                                          <a href="#">
                                              <i class="zmdi zmdi-money-box"></i>Billing</a>
                                      </div>
                                  </div>
                                  <div class="account-dropdown__body">
                                      <div class="account-dropdown__item">
                                          <a href="#">
                                              <i class="zmdi zmdi-globe"></i>Language</a>
                                      </div>
                                      <div class="account-dropdown__item">
                                          <a href="#">
                                              <i class="zmdi zmdi-pin"></i>Location</a>
                                      </div>
                                      <div class="account-dropdown__item">
                                          <a href="#">
                                              <i class="zmdi zmdi-email"></i>Email</a>
                                      </div>
                                      <div class="account-dropdown__item">
                                          <a href="#">
                                            <i class="zmdi zmdi-notifications"></i>Notifications</a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </header>
          <aside class="menu-sidebar2 js-right-sidebar d-block d-lg-none">
              <div class="logo">
                  <a href="#">
                      <img src="images/icon/logo-white.png" alt="Cool Admin" />
                  </a>
              </div>
              <div class="menu-sidebar2__content js-scrollbar2">
                  <div class="account2">
                      <div class="image img-cir img-120">
                          <img src="images/icon/avatar-big-01.jpg" alt="John Doe" />
                      </div>
                      <h4 class="name"><?php echo''.$nome; ?></h4>
                      <a href="#">Deslogar</a>
                  </div>
                  <nav class="navbar-sidebar2">
                      <ul class="list-unstyled navbar__list">
                          <li class="active has-sub">
                              <a class="js-arrow" href="#">
                                  <i class="fas fa-tachometer-alt"></i>Ínicio
                                  <span class="arrow">
                                      <i class="fas fa-angle-down"></i>
                                  </span>
                              </a>
                          </li>
                          <li>
                            <a href="material.php">
                            <i class="fas fa-chart-bar"></i>Material de Venda</a>
                          </li>
                          <li>
                              <a href="material.php">
                              <i class="fas fa-edit"></i>Contratos PME</a>
                          </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
