from flask import Flask, request, jsonify
import face_recognition
import os
import numpy as np
import cv2
from werkzeug.utils import secure_filename
from flask_cors import CORS
from flask_cors import cross_origin
from PIL import Image

app = Flask(__name__)
CORS(app) 

KNOWN_FACES_DIR = "known_faces"
if not os.path.exists(KNOWN_FACES_DIR):
    os.makedirs(KNOWN_FACES_DIR)

def encode_face(image_path):
    try:
        import face_recognition
        image = face_recognition.load_image_file(image_path)
        encodings = face_recognition.face_encodings(image)
        if len(encodings) > 0:
            return encodings[0]
        else:
            print("No face found in the image")
            return None
    except Exception as e:
        print(f"Error in encode_face: {e}")
        return None

def load_known_faces():
    known_encodings = []
    known_names = []
    email_map = {}

    # First pass: build a mapping of safe filenames to original emails
    for filename in os.listdir(KNOWN_FACES_DIR):
        if not filename.lower().endswith(('.png', '.jpg', '.jpeg')):
            continue
            
        # Extract the base name without extension
        base_name = os.path.splitext(filename)[0]
        # Convert back to original email format for matching
        original_email = base_name.replace('_at_', '@')
        email_map[base_name] = original_email

    # Second pass: process faces and use original emails
    for filename in os.listdir(KNOWN_FACES_DIR):
        if not filename.lower().endswith(('.png', '.jpg', '.jpeg')):
            continue
            
        path = os.path.join(KNOWN_FACES_DIR, filename)
        encoding = encode_face(path)
        
        if encoding is not None:
            base_name = os.path.splitext(filename)[0]
            original_email = email_map.get(base_name, base_name)
            
            known_encodings.append(encoding)
            known_names.append(original_email)
            
    return known_encodings, known_names


@app.route("/register", methods=["POST"])
@cross_origin() 
def register():
    try:
        file = request.files.get("image")
        email = request.form.get("email")

        if not file or not email:
            print("Missing image or email")
            return jsonify({"error": "Image and email required"}), 400

        # Validate the file type
        if not file.content_type.startswith("image/"):
            print("Uploaded file is not an image")
            return jsonify({"error": "Uploaded file is not an image"}), 400

        # Save the file with email in filename (replace @ with _at_ for filesystem safety)
        safe_email = email.replace('@', '_at_')
        filename = secure_filename(f"{safe_email}.jpg")
        save_path = os.path.join(KNOWN_FACES_DIR, filename)
        file.save(save_path)

        # Verify the saved file is a valid image
        try:
            with Image.open(save_path) as img:
                img.verify()  # Verify the image file
        except Exception as e:
            print(f"Invalid image file: {e}")
            os.remove(save_path)  # Remove the invalid file
            return jsonify({"error": "Invalid image file"}), 400

        # Encode the face
        encoding = encode_face(save_path)
        if encoding is None:
            os.remove(save_path)
            print("No face found in the image")
            return jsonify({"error": "No face found in the image"}), 400

        return jsonify({"message": f"{email} registered successfully"}), 200
    except Exception as e:
        print(f"Error in /register: {e}")
        return jsonify({"error": "Internal server error"}), 500

@app.route("/login", methods=["POST"])
def login():
    file = request.files.get("image")
    if not file:
        return jsonify({"error": "Image is required"}), 400

    input_image = face_recognition.load_image_file(file)
    input_encodings = face_recognition.face_encodings(input_image)

    if not input_encodings:
        return jsonify({"error": "No face found"}), 400

    known_encodings, known_names = load_known_faces()
    matches = face_recognition.compare_faces(known_encodings, input_encodings[0], tolerance=0.5)  # Adjusted tolerance

    if True in matches:
        index = matches.index(True)
        # Ensure we're returning the email in the correct format
        email = known_names[index]
        return jsonify({"message": f"Welcome {email}!", "email": email}), 200
    else:
        return jsonify({"message": "Face not recognized"}), 401

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5000, debug=True)
