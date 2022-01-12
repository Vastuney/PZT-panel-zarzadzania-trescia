# Documentation functions
### function User($searchkey,$value)
    Funkcja zwraca wynik w tym przypadku wiersz odpowiadający kluczowi poniżej
    *searchkey = Kolumna po której filtrujemy
    *value = Wartość którą chcemy znaleźć w kolumnie

### function validate($array)
    Funkcja zwraca przefiltrowane wartości które zostały zapisane w tabeli
    *array = Tabela zawiera klucz i przypisaną do niego wartość

### function extensions()
    Funkcja zwraca ciąg akceptowalnych rozszerzeń zapisanych w pliku config.php

### function uploadSize($bytes)
    Funkcja zwraca zaokrąglona wartość z odpowiednią jednostki miary: B, KB, MB, GB, TB
    *bytes = Wartość w bitach którą chcemy zaokrąglić i przypisać jednostkę

### function usedSpace($userid)
    Funkcja zwraca zużytą przestrzeń serwerową w bitach przez użytkownika
    userid = Id użytkownika dla którego chcemy sprawdzić zużytą przestrzeń
