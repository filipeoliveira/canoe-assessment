## Unit tests

Unit tests are written on the (tests folder)[../canoe-app/tests]. To run the test suite, make sure you have the docker compose running and execute from within the container:
```
php artisan test
```

*** Querying funds by manager's name ***

```
curl --request GET \
  --url 'http://localhost:8000/api/funds?manager_name=<XXX>'
```

*** Query funds by start_year ***
```
curl --request GET \
  --url 'http://localhost:8000/api/funds?start_year=<XXX>'
```

*** Query funds by start_year ***
```
curl --request GET \
  --url 'http://localhost:8000/api/funds?start_year=<XXX>'
```

*** Parameters are incremental ***
```
curl --request GET \
  --url 'http://localhost:8000/api/funds?start_year=<XXX>&name=<YYY>'
```

*** Retrieve potentially duplicates for a fund ***

```
curl --request GET \
  --url http://localhost:8000/api/funds/109/duplicates \
  --header 'Content-Type: application/json' \
```

*** Show a single fund ***

```
curl --request GET \
  --url 'http://localhost:8000/api/funds/2'
```

*** Update a fund ***
```
curl --request PUT \
  --url http://localhost:8000/api/funds/2 \
  --header 'Content-Type: application/json' \
  --data '{
	"name": "YYY",
	"fund_manager_id": "XX"
}'
```


*** Delete a fund ***
```
curl --request DELETE \
  --url http://localhost:8000/api/funds/2 \
  --header 'Content-Type: application/json' \
```
