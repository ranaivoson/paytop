# Paytop

## listes des commandes à lancer
```shell
docker-compose up --build -d
```

```shell
docker-compose exec api bin/console hautelook:fixtures:load
```
#### puis choisissez 'y'

```shell
docker-compose exec api bin/console lexik:jwt:generate-keypair
```
