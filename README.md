[![Build Status](https://travis-ci.org/bliskapaczkapl/prestashop.svg?branch=master)](https://travis-ci.org/bliskapaczkapl/prestashop)

# Moduł Bliskapaczka dla Prestashop 1.6.1.x 

## Instalacja modułu

### Wymagania
W celu poprawnej instalacji modułu wymagane są:
- php >= 5.6
- composer

### Instalacja modułu
1. Pobierz repozytorium i skopiuj jego zawartość do katalogu domowego swojego Prestashop
    - Jeśli masz już plik composer.json musisz zmergować zawartość pliku modułu do własnego. Plik musi zawierać:
        ```
        "repositories": [
            ...
            {
                "type": "vcs",
                "url": "https://github.com/bliskapaczkapl/bliskapaczka-api-client.git"
            }
        ],
        "require": {
            ...
            "bliskapaczka/bliskapaczka-api-client": "^1.0"
        }
        ```
1. Zainstaluj zależności composerem. Uruchom poniższą komendę w katalogu domowym Prestashop
    ```
    composer install --no-dev
    ```
1. Sprawdz czy moduł znajduje się na liście dostępnych modułów w Panelu Admina
1. Zainstaluj moduł z poziomu Panelu Admina
1. Skonfiguruj mododuł, dodaj swój klucz API w poli `API Key`. Znajdziesz go w zakładce Integracja panelu [bliskapaczka.pl](http://bliskapaczka.pl/panel/integracja)
1. Następnie ustal wymiary i wagę standardowej paczki w polach `Fixed parce type size X`, `Fixed parce type size Y`, `Fixed parce type size Z`, `Fixed parce type weight`
1. Sprawdź czy na liście dostępnych metod dostawy pojawiła się nowa metoda wysyłki "Bliskapaczka", skonfiguruj ją

### Tryb testowy

Tryb testowy, czli komunikacja z testową wersją znajdującą się pod adresem [sandbox-bliskapaczka.pl](https://sandbox-bliskapaczka.pl/) można uruchomić przełączają w ustwieniach modułu opcję `Test mode enabled` na `Yes`.

## Rozwój modułu

## Docker demo

`docker pull bliskapaczkapl/prestashop && docker run -d -p 8080:80 bliskapaczkapl/prestashop`

Front PrestaShop jest dostępny po wpisaniu w przeglądarcę adresu `http://127.0.0.1:8080`.

Panel admina jest dostępny pod adresem  `http://127.0.0.1:8080/admin6666ukv7e`, dane dostępowe to `pub@prestashop.com/0123456789`. Moduł należy zainstalować i skonfigurować według instrukcji powyżej.


### Instalacja zależności
```
composer install --dev
```

### Jak uruchomić testy jednostkowe
```
cd modules/bliskapaczka
php ../../vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/unit/
```
