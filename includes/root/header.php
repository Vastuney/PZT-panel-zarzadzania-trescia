

  <body>
    <div class="site">
      <nav>
        <div class="logo"><i class="fas fa-crow"></i></div>
        <ul>
          <li><a href="/index.php">Pliki</a></li>
          <li><a href="/settings.php">Ustawienia</a></li>
        </ul>
        <div class="usage">Zużycie: <?php echo uploadSize(usedSpace($_SESSION['id'])); ?> / <?php echo uploadSize($config['maxSpace']); ?></div>
      </nav>
      <header>
        <div class="top">
          <div class="search"><i class="fas fa-search"></i><form method="Post"><input type="text" name="value" placeholder="Szukaj..." required></form></div>
          <div class="panel">
            <li class="dropdown">
              <i class="fas fa-bell"></i>
              <ul class="dropdown">
                <li><a href="#">Dawid udostępnił Ci plik</a></li>
                <li><a href="#">Zaaktualizuj hasło</a></li>
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
