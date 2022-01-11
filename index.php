<?php
require($_SERVER["DOCUMENT_ROOT"]."/lib/main.php");
require($_SERVER["DOCUMENT_ROOT"]."/includes/root/head.php");
require($_SERVER["DOCUMENT_ROOT"]."/includes/root/header.php");
?>

            <div class="title">
              <h1>Twoje ostatnie pliki</h1>
              <button class="button info seeModal">Wrzuć nowy plik</button>
              <div class="modal">
                <div class="modal_top">
                  <h2>Dodaj pliki</h2>
                  <div class="close"><i class="fas fa-times"></i></div>
                </div>
                <div class="modal_container">
                  <div class="form_upload">
                    <form class="ajax" method="post" enctype="multipart/form-data">
                      <label>Tytuł</label>
                      <input type="text" name="title" required>
                      <label>Pliki</label>
                      <button class="upload" id="dropzone">
                        <span>Przeciągnij plik</span>
                        <input class="upload-input" type="file" name="file" accept=".zip, .rar, .txt" required>
                      </button>
                      <span>Akceptowalne rozszerzenia: <?php echo extensions(); ?></span>
                      <span>Limit: <?php echo uploadSize($config['uploadSize']); ?></span>
                      <input type="hidden" name="method" value="upload"  required>
                      <input type="hidden" name="type" value="files"  required>
                      <input type="submit" value="Wyślij">
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="container">
              <?php
                $class = new Files($_SESSION['id']);
                $class = $class->showFiles("files");
               ?>
              <?php
              $class = new Share("files", "19", $_SESSION['id']);
              $class->labels();
              ?>
              </div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/includes/root/footer.php"); ?>
