# Laravel Project

## Prerequisites

- Docker
- Docker Compose

## Setup Instructions

Follow these steps to get the application up and running:

### 1. create .env file 

cp .env.example .env

### Create and start containers

docker compose up


### Running Migrations

docker compose exec app php artisan migrate



### Stops containers and removes containers

docker compose down

