[![Build Status](https://travis-ci.org/bliskapaczkapl/prestashop.svg?branch=master)](https://travis-ci.org/bliskapaczkapl/prestashop)

# Moduł bliskapaczka.pl dla Prestashop 1.6.1.x 

## Instalacja modułu

### Wymagania
W celu poprawnej instalacji modułu wymagane są:
- php >= 5.6
- composer

### Instalacja modułu
1. Pobierz repozytorium i skopiuj jego zawartość do katalogu domowego swojego Prestashop
1. Sprawdz czy moduł znajduje się na liście dostępnych modułów w Panelu Admina
1. Zainstaluj moduł z poziomu Panelu Admina
1. Skonfiguruj mododuł, dodaj swój klucz API w poli `API Key`. Znajdziesz go w zakładce Integracja panelu [bliskapaczka.pl](http://bliskapaczka.pl/panel/integracja)
1. Następnie ustal wymiary i wagę standardowej paczki w polach `Fixed parce type size X`, `Fixed parce type size Y`, `Fixed parce type size Z`, `Fixed parce type weight`
1. Sprawdź czy na liście dostępnych metod dostawy pojawiła się nowa metoda wysyłki "Bliskapaczka", skonfiguruj ją

### Konfiguracja modułu

#### Tryb testowy

Tryb testowy, czli komunikacja z testową wersją znajdującą się pod adresem [sandbox-bliskapaczka.pl](https://sandbox-bliskapaczka.pl/) można uruchomić przełączają w ustwieniach modułu opcję `Test mode enabled` na `Yes`.

#### Przewoźnicy i koszty przesyłki

Dostępni przewoźnicy oraz koszty przesyłki dla poszczególnych punktów dostawy są ustawiani po stronie konfiguracji serwisu blikskapaczka.pl. Konfiguracja znajduje się w zakładce "Narzędzia" -> "Integracja".

#### VAT

Moduł bliskapaczka.pl korzysta ze standardowych ustawień VAT dla metod dostawy w PrestaShop. Standardowo moduł nie ustawia stawki VAT dla dostawy bliskapaczka.pl, cena wyświetlana klientowi sklepu jest ceną zawierającą VAT, która została ustawiona w konfiguracji serwisu blikskapaczka.pl. Ustawienie stawki VAT  dla dostawy bliskapaczka.pl  powoduje przeniesienie wyliczania kosztu dostawy na PrestaShop, w takim przypadku moduł bliskapaczka.pl bierze cenę netto z ustawień w konfiguracji serwisu blikskapaczka.pl i dodaje do tej ceny VAT ustawiony w konfiguracji modułu.


#### Dodatkowe opłaty

Wybór przewoźnika DPD lub FedEx dla zleceń D2D z usługą pobrania może wiązać się z dodatkową opłatą podczas wyceny w serwisie bliskapaczka.pl ze względu na obowiązkowe ubezpieczenie, którego wymaga przewoźnika.
Kwota pobrania wolna od dodatkowych opłat uwzględniona jest w cenniku na naszej stronie internetowej bliskapaczka.pl/cennik

## Docker demo

`docker-compose up`

Front PrestaShop jest dostępny po wpisaniu w przeglądarcę adresu `http://127.0.0.1:8080`.

Panel admina jest dostępny pod adresem  `http://127.0.0.1:8080/admin6666ukv7e`, dane dostępowe to `pub@prestashop.com/0123456789`. Moduł należy zainstalować i skonfigurować według instrukcji powyżej.

## Rozwój modułu

### Instalacja zależności
Jeśli masz już plik composer.json musisz zmergować zawartość pliku modułu do własnego. Plik musi zawierać:
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
Następnie zainstaluj zależności composerem. Uruchom poniższą komendę w katalogu domowym Prestashop
```
composer install --dev
```

### Docker

W celu developmentu można uruchomić docker-compose prze komendę:

```
docker-compose -f docker-compose.yml -f dev/docker/docker-compose.dev.yml up
```

### Jak uruchomić testy jednostkowe
```
cd modules/bliskapaczka
php ./vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/unit/
```

### Możliwe konflikty

Moduł bliskapaczka.pl dziedziczy po niektórych elementach PrestaShop przez co może powodować konflikty jeśli w aplikacji są zainstalowane inne moduły dziedziczące po tych samych klasach. Poniżej znajduje się informacja po jakich klasach, kontrolerach i widokach dziedziczy moduł bliskapazka.pl

Klasu:
- `OrderCore`
- `CartCore`

Kontrolery:
- `OrderControllerCore`

Widoki:
- `order-carrier.tpl`