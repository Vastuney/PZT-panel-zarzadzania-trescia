<?php
require($_SERVER["DOCUMENT_ROOT"]."/lib/main.php");
require($_SERVER["DOCUMENT_ROOT"]."/includes/root/head.php");
require($_SERVER["DOCUMENT_ROOT"]."/includes/root/header.php");
?>

            <div class="title">
              <h1>Ustawienia</h1>
              <button class="button info seeModal">Avatar</button>
              <div class="modal">
                <div class="modal_top">
                  <h2>Dodaj pliki</h2>
                  <div class="close"><i class="fas fa-times"></i></div>
                </div>
                <div class="modal_container">
                  <div class="form_upload">
                    <form class="ajax" method="post" enctype="multipart/form-data">
                      <label>Avatar</label>
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
              <div class="multi-items">
                <div class="multi-items-navigation">
                  <ul>
                    <li class="multi_item_href active" for="#user">Użytkownik</li>
                    <li class="multi_item_href" for="#notifications">Powiadomienia</li>
                    <li class="multi_item_href" for="#share">Udostępnianie</li>
                    <li class="multi_item_href" for="#data">Dane</li>
                  </ul>
                </div>
                <div class="multi-items-item active" data-item="#user">
                  <h3>Użytkownik</h3>
                </div>
                <div class="multi-items-item" data-item="#notifications">
                  <h3>Powiadomienia</h3>
                </div>
                <div class="multi-items-item" data-item="#share">
                  <h3>Udostępnianie</h3>
                </div>
                <div class="multi-items-item" data-item="#data">
                  <h3>Dane</h3>
                </div>
              </div>
              </div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/includes/root/footer.php"); ?>
