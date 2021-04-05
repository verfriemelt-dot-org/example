### Aufgabe:

> Schreib eine einfache API (unter Nutzung des Symfony Framework), um eine Liste von Benutzern anzuzeigen, neuen Benutzer zu erstellen und einen vorhandenen Benutzer zu ändern oder löschen. Ziel ist es, die Datenquelle (etwa eine Datenbank, eine XML Datei, ...) für Benutzer auszutauschen, ohne den Code berühren zu müssen, der die Datenquelle verwendet und die Antwort zurückgibt. Stelle bitte eine Dokumentation zum Konsumieren der API bereit. Es wäre großartig, wenn Du uns Deine Antwort mit einem GitHub-Link und einer kleinen ReadMe-Datei senden würdest. Viel Spaß!

### abgeleitete Definitionen / Annahmen

 - Benutzer besteht aus Name und Nachname.
 - API hier als RESTFul API
 - Entkopplung von Infrastruktur, Domain und Application Services / Controller
 
### Demo

Entwicklung / Demo über Docker, OpenAPI unter http://localhost:8080/api/doc

```sh
git clone https://github.com/verfriemelt-dot-org/user-api
cd user-api/docker
docker-compose up
xdg-open http://localhost:8080/api/doc
```

Benutzer anlegen
```sh
$ http -b post http://localhost:8080/api/v1/user name=new lastname=user


{
  "id": 1,
  "lastname": "user",
  "name": "new"
}
```

Benutzer abfragen
```sh
$ http -b http://localhost:8080/api/v1/user
[
    {
        "id": 1,
        "lastname": "user",
        "name": "new"
    }
]
```
 
 
### Entwicklungsansatz

 - [TDD mit phpunit, phpstan und github actions](https://github.com/verfriemelt-dot-org/user-api/tree/main/tests)
   - [![tests](https://github.com/verfriemelt-dot-org/user-api/actions/workflows/tests.yml/badge.svg)](https://github.com/verfriemelt-dot-org/user-api/actions/workflows/tests.yml)
   - [![phpstan](https://github.com/verfriemelt-dot-org/user-api/actions/workflows/phpstan.yml/badge.svg)](https://github.com/verfriemelt-dot-org/user-api/actions/workflows/phpstan.yml)
 - [Input und Response DTOs](https://github.com/verfriemelt-dot-org/user-api/tree/main/src/Domain/User)
  - [Validierung mithilfe von Annotations am DTO](https://github.com/verfriemelt-dot-org/user-api/blob/main/src/Domain/User/UserInputDto.php)
  - [DI über DtoParamConverter + Validierung](https://github.com/verfriemelt-dot-org/user-api/blob/main/src/DtoParamConverter.php)
  - [Exceptionhandling über Eventlistener](https://github.com/verfriemelt-dot-org/user-api/blob/main/src/EventListener/ExceptionListener.php)
 - Infrastruktur entkoppelt von der Domain
  - [Implementierung über RepositoryInterface](https://github.com/verfriemelt-dot-org/user-api/blob/main/src/Domain/User/UserRepositoryInterface.php)
  - [Entity / DTO Konvertierung über UserDtoTransformer](https://github.com/verfriemelt-dot-org/user-api/blob/main/src/Infrastructure/UserDtoTransformer.php)
  - [Beispiel: JsonBackend](https://github.com/verfriemelt-dot-org/user-api/tree/main/src/Infrastructure/JsonUser)
  - [Beispiel: RandomUserBackend](https://github.com/verfriemelt-dot-org/user-api/tree/main/src/Infrastructure/RandomUser)
 - OpenAPI Dokumentation über das [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle) unter `/api/doc` mithilfe von Annotations am Controller

### Punkte zur weiteren Betrachtung:

 - Authentication ( zb JWT oder OAuth2 )
 - Logging / Monitoring ( zb Monolog / Prometheus )
 - Funktionale Erweiterungen ( pagination, filtering, sorting )
  
 
