# Faculty Feedback System

A web-based faculty feedback application for collecting ratings and comments about faculty members and subjects.

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

## Technology stack

- HTML and CSS for the feedback form and dashboard presentation
- JavaScript for rating calculation, character counting, and client-side validation
- PHP for server-side request handling
- MongoDB for persistent feedback storage

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
- Faculty and subject selections are checked against the allowed values.
- Each response stores the student's name, class, division, and roll number.
- TCET email ID and a 10-digit Indian phone number are required for each response.
- Students can submit anonymously; anonymous records hide their name, email, phone number, and roll number on the dashboard while retaining class and division for reporting.
- Student identity is scoped by class, division, and roll number, so the same roll number can be used independently in different divisions.
- Each rating must be an integer from 1 to 5.
- The overall rating is calculated from the three category ratings and verified on the server.
- Comments are optional and limited to 250 characters.
- The backend connects to MongoDB at `mongodb://127.0.0.1:27017` by default.
- For Atlas, set the `MONGODB_URI` environment variable before starting Apache.
- Set `MONGODB_URI` and `MONGODB_DB` to use another MongoDB server or database.
- Feedback is stored in the `feedback` collection in the `faculty_feedback` database.
- The dashboard reads all feedback records and shows them in a table.
