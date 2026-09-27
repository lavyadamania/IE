# Faculty Feedback System

A clean project structure for the Student 1 + Student 2 faculty feedback application.

## Project structure

- backend/
  - db.php
  - submit.php
  - dashboard.php
  - connection_test.php
- frontend/
  - index.html
  - style.css
  - script.js
  - assets/
    - tcet-header.jpeg
    - tcet-watermark.png

## Run locally

1. Start Apache in XAMPP and make sure MongoDB is running.
2. Put this folder inside htdocs.
3. Enable the `mongodb` PHP extension in XAMPP.
4. Open the frontend in a browser:
  - http://localhost:8080/faculty_feedback/frontend/index.html
5. Submit feedback and view it at:
  - http://localhost:8080/faculty_feedback/backend/dashboard.php

## Notes

- The frontend form submits to ../backend/submit.php.
- The backend connects to MongoDB at `mongodb://127.0.0.1:27017` by default.
- For Atlas, set the `MONGODB_URI` environment variable before starting Apache.
- Set `MONGODB_URI` and `MONGODB_DB` to use another MongoDB server or database.
- Feedback is stored in the `feedback` collection in the `faculty_feedback` database.
- The dashboard reads all feedback records and shows them in a table.
