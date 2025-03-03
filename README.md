## License

[MIT](https://opensource.org/licenses/MIT)

## API Endpoints

### Create Lead
```http
POST /leads
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "source": "facebook"
}
```

**Response:**
```json
{
    "message": "Lead created successfully",
    "id": 123,
    "api_status": "success"
}
```

The `api_status` field can have two values:
- `success`: The lead was saved and successfully sent to the external service
- `pending`: The lead was saved but there was a problem sending it to the external service

## Logs

Application logs are stored in:
```
./logs/app.log
```

# Lead Management API

A REST API for lead management with external service integration.

## Prerequisites

- Docker
- Docker Compose
- Git

## Installation and Execution

1. Clone the repository:
```bash
git clone <repository-url>
cd <directory-name>
```

2. Give execution permissions to scripts:
```bash
chmod +x ./start.sh ./stop.sh
```

3. Start the application:
```bash
./start.sh
```

4. To stop the application:
```bash
./stop.sh
```

The start script will:
- Build Docker images
- Start containers
- Install Composer dependencies
- Run database migrations
- Start the PHP server

## API Endpoints

### Create Lead
```http
POST /leads
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "source": "facebook"
}
```

**Response:**
```json
{
    "message": "Lead created successfully",
    "id": 123,
    "api_status": "success"
}
```

The `api_status` field can have two values:
- `success`: The lead was saved and successfully sent to the external service
- `pending`: The lead was saved but there was a problem sending it to the external service

## Logs

Application logs are stored in:
```
./logs/app.log
```