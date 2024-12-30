# Kiu Challenge - Flight Search Service ✈️

## Project Purpose

This project implements the first version of a flight search service for an airline company. The solution focuses on building an API endpoint to find flight sequences (referred to as “journeys”) based on specific criteria such as date, origin, and destination.

## Domain-Driven Design (DDD) Structure

This project is organized following **Domain-Driven Design (DDD)** and **Clean Architecture** principles. Here's a breakdown of the primary structure:

### Layer Overview

1. **Application Layer**:
    - Coordinates use cases like searching for journeys using flight events.

2. **Domain Layer**:
    - Contains core entities, value objects, and interfaces for business logic and rules.

3. **Infrastructure Layer**:
    -Implements external APIs, dependency injection, and HTTP controllers for API interaction.


## Installation Instructions

This project utilizes **Laravel Sail** to provide a robust and containerized backend development environment. Follow these steps to set up the API:

### Prerequisites
Ensure the following are installed:
- **Docker** and **Docker Compose**: Required for Laravel Sail containers.
- **PHP 8.1+**.
- **Composer**: To install Laravel dependencies.

---

### Steps

1. **Clone the repository**:

   ```bash
   git clone https://github.com/MendyAndres/kiu-challenge.git
   cd kiu-challenge
   ```

2. **Install dependencies**:

    ```bash
   composer install
   ```

3. **Copy the `.env` file**:

   ```bash
   cp .env.example .env
   ```

   You can adjust any necessary environment variables in `.env` as required (e.g., database). Laravel Sail defaults should work fine out of the box.


4. **Build and start the Docker containers:**:

   ```bash
   docker-compose up -d
   ```

---

### Accessing the API

Once the setup is complete, the API will be available at:  
`http://localhost`.

You can interact with endpoints under `http://localhost/api/v1` using tools like **Postman** or **cURL**.

---

### Testing the API

You can run all tests using PHPUnit to ensure that everything is working as expected:
```bash
  ./vendor/bin/phpunit
```

---




## Available Endpoints

| **HTTP Method** | **Endpoint**                    | **Description**                            |
                        |------------------|--------------------------------|--------------------------------------------|
| **GET**          | `http://localhost:8080/api/v1/journeys/search`          | Lists all flights availables with filters. |

---

## API Documentation

### **1. List Tournaments**

- **URL**: `/api/v1/tournaments`
- **Method**: `GET`
- **Description**: Returns a list of all registered tournaments. Filters can be applied optionally.

#### **Query Parameters**:
| **Parameter**   | **Type**   | **Description**              |
|------------------|------------|------------------------------|
| `date`         | `string`   | Date Format (`YYYY-MM-DD`)   |
| `departureCity`      | `string`   | Departure City Format (`BUE`). |
| `arrivalCity`        | `string`   | Arrival City Format(`MAD`).    |

#### **Example Successful Response (HTTP 200)**:
```json
[
  {
    "connections": 2,
    "path": [
      {
        "flightNumber": "IB1234",
        "departureTime": {
          "date": "2023-12-31 23:59:59.000000",
          "timezone_type": 2,
          "timezone": "Z"
        },
        "arrivalTime": {
          "date": "2024-01-01 00:00:00.000000",
          "timezone_type": 2,
          "timezone": "Z"
        },
        "origin": "MAD",
        "destination": "BUE"
      },
      {
        "flightNumber": "IB1234",
        "departureTime": {
          "date": "2024-01-01 01:00:00.000000",
          "timezone_type": 2,
          "timezone": "Z"
        },
        "arrivalTime": {
          "date": "2024-01-01 02:30:00.000000",
          "timezone_type": 2,
          "timezone": "Z"
        },
        "origin": "BUE",
        "destination": "MDZ"
      }
    ]
  },
  {
    "connections": 1,
    "path": [
      {
        "flightNumber": "IB1234",
        "departureTime": {
          "date": "2023-12-31 23:59:59.000000",
          "timezone_type": 2,
          "timezone": "Z"
        },
        "arrivalTime": {
          "date": "2024-01-01 06:00:00.000000",
          "timezone_type": 2,
          "timezone": "Z"
        },
        "origin": "MAD",
        "destination": "MDZ"
      }
    ]
  }
]
```
---

