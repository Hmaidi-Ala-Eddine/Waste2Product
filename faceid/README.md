# Face Recognition API - Docker Setup

This is a Flask-based Face Recognition API that provides user registration and login via facial recognition.

## Features
- `/register` - Register a new user with their face image
- `/login` - Authenticate users using face recognition

## Docker Setup

### Files Created
- `Dockerfile` - Container configuration for the Python app
- `requirements.txt` - Python dependencies
- `.dockerignore` - Files to exclude from Docker build
- `face_recognition_app.py` - Main Flask application

### Running with Docker Compose

From the project root directory (`Waste2Product`), run:

```bash
# Build and start only the faceid service
docker-compose up -d faceid

# Or build and start all services
docker-compose up -d
```

### Accessing the API
The Face Recognition API will be available at:
- **URL**: `http://localhost:5000`
- **Register endpoint**: `http://localhost:5000/register`
- **Login endpoint**: `http://localhost:5000/login`

### Useful Commands

```bash
# View logs
docker-compose logs -f faceid

# Rebuild the service (after code changes)
docker-compose build faceid
docker-compose up -d faceid

# Stop the service
docker-compose stop faceid

# Remove the service
docker-compose down faceid
```

### Known Faces Storage
Registered face images are stored in the `known_faces/` directory, which is mounted as a volume to persist data between container restarts.

## API Endpoints

### POST /register
Register a new user with their face image.

**Request:**
- Method: `POST`
- Content-Type: `multipart/form-data`
- Body:
  - `image`: Image file containing the user's face
  - `email`: User's email address

**Response:**
- Success (200): `{"message": "<email> registered successfully"}`
- Error (400): `{"error": "Error message"}`

### POST /login
Authenticate a user using face recognition.

**Request:**
- Method: `POST`
- Content-Type: `multipart/form-data`
- Body:
  - `image`: Image file containing the user's face

**Response:**
- Success (200): `{"message": "Welcome <name>!"}`
- Not recognized (401): `{"message": "Face not recognized"}`
- Error (400): `{"error": "Error message"}`
