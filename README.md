# Payment Gateway Integration
Integrates payment gateways: Shift4 and ACI. Done as part of testing exercise. Developed with php8.2 and Symfony 7

## Requirements
- Docker

## Installation
Build and run the containers
```
docker compose up
```

## Environment vars
Configurable environment variables
```
# Secret key from Shift4
SHIFT4_SECRET_KEY=

# Bearer token from ACI
ACI_BEARER_TOKEN=
```

## API Reference
The following endpoints are accessible via http

### Payments API

`POST /payments/{gateway}`

- Gateway alias: `aci` `shift4`

## Command Reference
The following commands are available via console

### Payments Command

`./bin/console app:process-payment {gateway} {amount} {currency} {card_number} {card_holder} {expiry_year} {expiry_month} {cvv}`

- Gateway alias: `aci` `shift4`

## Examples

Via console command:
```
docker exec -it payment-app ./bin/console app:process-payment aci 100 EUR 4242424242424242 'John Doe' 2027 12 123
```
Via curl:
```
curl --location 'http://localhost/api/payments/aci' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "amount": 100.0,
    "currency": "EUR",
    "cardNumber": "4242424242424242",
    "expiryMonth": "11",
    "expiryYear": "2027",
    "cardHolder": "John Doe",
    "cvv": "123"
}'
```