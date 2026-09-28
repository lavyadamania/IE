const form = document.getElementById("feedbackForm");
const comments = document.getElementById("comments");
const counter = document.getElementById("counter");
const ratingMessage = document.getElementById("ratingMessage");
const overallRating = document.getElementById("overallRating");
const formMessage = document.getElementById("formMessage");

if (!form || !comments || !counter || !ratingMessage || !overallRating || !formMessage) {
    console.error("Faculty Feedback form elements not found.");
} else {
    comments.addEventListener("input", function() {
        counter.textContent = "Characters: " + comments.value.length + "/250";
    });

document.querySelectorAll(".stars input").forEach(function(star) {
    star.addEventListener("change", function() {
        const rating = Number(this.value);
        let category = this.name;
        if (category === "teaching") {
            category = "Teaching Quality";
        } else if (category === "communication") {
            category = "Communication";
        } else if (category === "knowledge") {
            category = "Subject Knowledge";
        }

        ratingMessage.textContent = "You selected " + rating + "/5 for " + category + " ⭐";
        calculateOverall();
    });
});

function calculateOverall() {
    const teaching = document.querySelector('input[name="teaching"]:checked');
    const communication = document.querySelector('input[name="communication"]:checked');
    const knowledge = document.querySelector('input[name="knowledge"]:checked');
    if (teaching && communication && knowledge) {
        const average =
            (
                Number(teaching.value) +
                Number(communication.value) +
                Number(knowledge.value)
            ) / 3;
        overallRating.textContent = "⭐ " + average.toFixed(1) + " / 5";
        document.getElementById("overall").value = average.toFixed(1);
    } else {
        overallRating.textContent =
            "⭐ Select all three ratings";
    }
}

    form.addEventListener("submit", function(event) {
        const studentName = document.getElementById("studentName").value.trim();
        const className = document.getElementById("className").value;
        const division = document.getElementById("division").value;
        const rollNumber = Number(document.getElementById("rollNumber").value);
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const faculty = document.getElementById("faculty").value;
        const subject = document.getElementById("subject").value;
        const teaching = document.querySelector('input[name="teaching"]:checked');
        const communication = document.querySelector('input[name="communication"]:checked');
        const knowledge = document.querySelector('input[name="knowledge"]:checked');
        const missingRatings = [teaching, communication, knowledge].some((rating) => rating === null);
        const emailIsValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        const phoneIsValid = /^[6-9][0-9]{9}$/.test(phone);

        formMessage.textContent = "";
        if (
            !/^[A-Za-z][A-Za-z .'-]{1,99}$/.test(studentName) ||
            className === "" ||
            division === "" ||
            !Number.isInteger(rollNumber) ||
            rollNumber < 1 ||
            rollNumber > 100 ||
            !emailIsValid ||
            !phoneIsValid ||
            faculty === "" ||
            subject === "" ||
            missingRatings
        ) {
            event.preventDefault();
            formMessage.textContent = "Enter valid student details and select a faculty, subject, and all three ratings.";
            return;
        }

        calculateOverall();
    });
}