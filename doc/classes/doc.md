# Documentation classes

## class DB_PDO()
    Klasa odpowiadająca za połącznie z bazą danych metodą objektową (PDO), używana wartości zapisanych
    w pliku db_config.php

#### function dbDisconnect()
    Funkcja odpowiedzialna za przerwanie połączenia z bazą danych

#### function selectAll($tableName)
    Funkcja zwraca wiersze z tabeli
    *tableName = Nazwa tabeli z której chcemy uzyskać wiersze

#### function selectWhere($tableName,$rowName,$operator,$value,$valueType)
    Funkcja zwraca wiersze z tabeli z zastosowaniem warunków
    *tableName = Nazwa tabeli z której chcemy uzyskać wiersze
    *rowName = Kolumna warunku
    *operator = Operator warunku
    *value = Wartość warunku
    *valueType = Typ wartości [int, char]

#### function insertInto($tableName,$values)
    Funkcja wprowadza wiersze do tabeli
    *tableName = Nazwa tabeli do której chcemy wprowadzić dane
    *values = Tablica wartości które chcemy wprowadzić
    *np. $values = [
                    [ "type" => "char", "val" => $this->name ],
                    [ "type" => "char", "val" => $this->fname ],
                    [ "type" => "char", "val" => $this->date ]
                  ];

#### function freeRun($query)
    Funkcja zapewnia połączenie z bazą danych i wykonuje odpowiednią kwarendę
    *query = Kwarenda która ma zostać wykonana
---
---



## class Feedback($state, $text)
    Klasa odpowiedzialna za przesłanie komunikatu zwrotnego dla użytkownika
    *state = Status komunikatu [location, success, error]
    *np. new Feedback("location", "/index.php");

#### function error()
    Odpowiedzialna za komunikat błędu

#### function success()
    Odpowiedzialna za komunikat powodzenia

#### function location()
    Odpowiedzialna za relokacje użytkownika na witrynie www
---
---


## class Token()
    Klasa odpowiedzialna za tokeny używane podczas rejestracji

#### function ifExistToken($token)
    Funkcja odpowiedzialna za sprawdzenie czy istnieje token zwraca true, false
    token = Token który chcemy sprawdzić, przypisywany do obiektu

#### function ifActiveToken()
    Funkcja sprawdza czy token przypisany do obiektu jest aktywny zwraca true, false

#### function getToken()
    Funkcja pobiera informacje o tokenie który jest przypisany do obiektu, zwraca wiersz

#### function updateToken()
    Funkcja zwiększa kolumnę `used` o jeden punkt (int) ilekroć został użyty dany token
---
---


## class User()
    Klasa odpowiedzialna za informacje o użytkowniku

#### function getUser($searchkey, $value)
    Funkcja zwraca wynik w tym przypadku wiersz odpowiadający kluczowi poniżej
    *searchkey = Kolumna po której filtrujemy
    *value = Wartość którą chcemy znaleźć w kolumnie

#### function existUser($searchkey, $value)
    Funkcja sprawdza czy dany użytkownik istnieje w bazie danych zwraca true, false
    *searchkey = Kolumna po której filtrujemy
    *value = Wartość którą chcemy znaleźć w kolumnie

#### function remindPassword($email)
    Funkcja odpowiedzialna za wysyłkę maila z linkiem do resetowania hasła
    email = Adres email użytkownika który chcę zresetować hasło

#### resetPassword($email, $oldpassword, $newpassword)
    Funkcja odpowiedzialna za zmianę hasła
    email = Adres email użytkownika który chcę zresetować hasło
    oldpassword = Zaszyfrowane hasło (md5)
    password = Hasło na które chcę zmienić użytkownik

## class Person($email, $name, $lastname, $password, $token)
    Klasa odpowiedzialna za stworzenie nowego użytkownika w bazie danych i stworzenie jego miejsc roboczych
    email = Adres email na które ma zostać założone konto
    name = Imię na które ma zostać założone konto
    lastname = Nazwisko na które ma zostać założone konto
    password = Hasło użytkownika
    token = Token podany przy rejestracji

#### function createUser($group)
    Funkcja odpowiedzialna za stworzenie nowego użytkownika w bazie danych i stworzenie jego miejsc roboczych, walidacja czy token jest aktywny, sprawdzenie czy istnieje użytkownik o podanym adresie email
    group = Grupa do której ma zostać przypisany użytkownik
---
---



## class UserLogin($email, $password)
    Klasa odpowiedzialna za logowanie użytkownika
    email = Wprowadzony adres email
    password = Wprowadzone hasło

#### function logIn()
    Funkcja odpowiedzialna za logowanie użytkownika i sprawdzenie poprawności danych
---
---


## class Workspace($userid)
    Klasa odpowiedzialna za całą przestrzeń roboczą użytkowników
    userid = Id użytkownika na którym operujemy

#### ifWorkspace()
    Funkcja odpowiedzialna za sprawdzenie czy dana przestrzeń robocza o danym id istnieje zwraca true,false

#### createWorkspace()
    Funkcja odpowiedzialna za stworzenie przestrzeni roboczej użytkownika
---
---


## class Files($userid, $type)
    Klasa odpowiedzialna za zarządzanie plikami użytkowników
    userid = Id użytkownika na którym operujemy
    type = Typ przestrzeni roboczej [files, codes]

#### function count()
    Funkcja odpowiedzialna za przeliczeni ilości plików użytkowników z przestrzeni roboczej

#### function ifExist($filename)
    Funkcja odpowiedzialna za sprawdzenie czy dany plik istnieje w przestrzeni roboczej zwraca true,false
    filename = Nazwa pliku razem z rozszerzeniem który chcemy sprawdzić

#### function getFiles()
    Funkcja odpowiedzialna za ściągnięcie informacji o plikach w przestrzeni roboczej zwraca tablicę
    klucz = Nazwa pliku
    value = Rozmiar pliku
    [$klucz => $value]

#### function fileExt($file)
    Funkcja odpowiedzialna za zwrócenie rozszerzenia pliku
    file = Plik którego rozszerzenie chcemy zdobyć

#### function upload($title, $file)
    Funkcja odpowiedzialna za wrzucenie pliku na serwer i jego informacji do bazy danych
    title = Tytuł pliku
    file = Plik który chcemy wrzucić ($_FILE)

#### function fileDelete($id)
    Funkcja odpowiedzialna za usunięcie pliku o podanym id z bazy danych i serwera
    id = Id pliku w bazie danych

#### function showFiles()
    Funkcja wyświetla wszystkie pliki użytkownika jak i te które zostały mu udostępnione
---
---


## class Share($type, $fileid, $userid, $shared = 0)
    Klasa odpowiedzialna za udostępnianie plików między użytkownikami
    type = Typ przestrzeni roboczej [files, codes]
    fileid = Id pliku w bazie danych który chcemy udostępnić
    userid = Id użytkownika któremu chcemy udostępnić plik
    shared = Ciąg znaków zawierający id użytkowników którym plik został udostępniony

#### function labels()
    Funkcja odpowiedzialna za wyświetlenie użytkowników a także informacji czy plik można im udostępnić bądź zabrać prawa do niego

#### function authentication()
    Funkcja odpowiedzialna za sprawdzenie czy dany użytkownik jest właścicielem pliku zwraca true,false

#### function fileExist()
    Funkcja odpowiedzialna za sprawdzenie czy plik istnieje zwraca true,false

#### function shareFile()
    Funkcja odpowiedzialna za udostępnienie pliku

#### function unshareFile()
    Funkcja odpowiedzialna za zatrzymanie udostępniania pliku
---
---
