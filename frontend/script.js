const form = document.getElementById("feedbackForm");
const comments = document.getElementById("comments");
const counter = document.getElementById("counter");
const ratingMessage = document.getElementById("ratingMessage");
const overallRating = document.getElementById("overallRating");

if (!form || !comments || !counter || !ratingMessage || !overallRating) {
    console.error("Faculty Feedback form elements not found.");
}

comments.addEventListener("input", function() {
    counter.textContent =
        "Characters: " + comments.value.length + "/250";
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
    const faculty = document.getElementById("faculty").value;
    const subject = document.getElementById("subject").value;
    const teaching = document.querySelector('input[name="teaching"]:checked');
    const communication = document.querySelector('input[name="communication"]:checked');
    const knowledge = document.querySelector('input[name="knowledge"]:checked');
    if (
        faculty === "" ||
        subject === "" ||
        teaching === null ||
        communication === null ||
        knowledge === null
    ) {
        event.preventDefault();
        alert("Please complete all required fields.");
    }
});