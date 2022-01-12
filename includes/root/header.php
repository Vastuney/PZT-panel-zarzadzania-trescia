

  <body>
    <div class="site">
      <nav>
        <div class="logo"><a href="/index.php"><i class="fas fa-crow"></i></a></div>
        <ul>
          <li><a href="/index.php">Pliki</a></li>
          <li><a href="/settings.php">Ustawienia</a></li>
        </ul>
        <div class="usage">Zużycie: <?php echo uploadSize(usedSpace($_SESSION['id'])); ?> / <?php echo uploadSize($config['maxSpace']); ?></div>
      </nav>
      <header>
        <div class="top">
          <div class="search"><i class="fas fa-search"></i><form><input id="search" type="text" name="value" placeholder="Szukaj..." required></form></div>
          <div class="panel">
            <li class="dropdown">
              <i class="fas fa-bell"></i>
              <ul class="dropdown">
                <?php
                  $class = new Notifications("1");
                  $class->showNotifications();
                ?>
              </ul>
            </li>
            <li class="dropdown">
            <img src="/users/avatars/default.jpg">
            <ul class="dropdown">
              <li><span><?php echo $user['name']." ".$user['surname'] ?></span></li>
              <li><a href="#">Ustawienia</a></li>
              <li><a href="/logout.php">Wyloguj</a></li>
            </ul>
          </li>
          </div>
        </div>
        <div class="content">
          <main>
