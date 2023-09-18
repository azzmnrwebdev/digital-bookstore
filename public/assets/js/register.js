// form steps
const formSteps = document.querySelectorAll(".step");
const nextBtns = document.querySelectorAll(".next-step");
const prevBtns = document.querySelectorAll(".prev-step");

let formStepNum = 0;
nextBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
        formStepNum++;
        updateFormSteps();
    });
});

prevBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
        formStepNum--;
        updateFormSteps();
    });
});

function updateFormSteps() {
    formSteps.forEach((frmStep, index) => {
        frmStep.classList.toggle("step-active", index === formStepNum);
    });
}

// event listener input username
const usernameInput = document.getElementById("username");
usernameInput.addEventListener("input", function () {
    const currentValue = usernameInput.value;
    const newValue = currentValue.toLowerCase().replace(/[^a-z0-9]/g, "");
    usernameInput.value = newValue;
});

// event listener input email
const emailInput = document.getElementById("email");
emailInput.addEventListener("input", function () {
    let currentValue = emailInput.value;
    let newValue = currentValue.toLowerCase().replace(/[^a-z0-9.@]/g, "");

    const atIndex = newValue.indexOf("@");
    if (atIndex !== -1) {
        const username = newValue.slice(0, atIndex);
        const domain = newValue.slice(atIndex + 1);
        newValue = username + "@" + domain.replace(/@/g, "");
    }

    emailInput.value = newValue;
});

// change input cv with select role
const selectRole = document.querySelector("#role");
const fileCV = document.querySelector("#file-cv");

if (selectRole.value === "penulis")
    fileCV.classList.remove("d-none"), fileCV.classList.add("d-block");
else fileCV.classList.remove("d-block"), fileCV.classList.add("d-none");

selectRole.addEventListener("change", function () {
    if (selectRole.value === "penulis")
        fileCV.classList.remove("d-none"), fileCV.classList.add("d-block");
    else fileCV.classList.remove("d-block"), fileCV.classList.add("d-none");
});

// toggle eye icon password
const passwordInput = document.querySelector("#password");
const passwordConfirmInput = document.querySelector("#password-confirm");
const togglePassword = document.querySelector("#toggle-password");

togglePassword.addEventListener("click", function () {
    const eyeIcon = this.querySelector("i");
    const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";

    passwordInput.setAttribute("type", type);
    passwordConfirmInput.setAttribute("type", type);
    if (eyeIcon.classList.contains("bi-eye-fill")) {
        eyeIcon.classList.remove("bi-eye-fill");
        eyeIcon.classList.add("bi-eye-slash-fill");
    } else {
        eyeIcon.classList.remove("bi-eye-slash-fill");
        eyeIcon.classList.add("bi-eye-fill");
    }
});

// view toastr in page
function showToast(type, attributes, title) {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        timeOut: "8000",
        extendedTimeOut: "8000",
    };

    if (type === "success") {
        attributes
            .slice()
            .reverse()
            .forEach((attribute) => {
                toastr.success(attribute, title);
            });
    }

    if (type === "error") {
        attributes
            .slice()
            .reverse()
            .forEach((attribute) => {
                toastr.error(attribute, title);
            });
    }

    if (type === "warning") {
        attributes
            .slice()
            .reverse()
            .forEach((attribute) => {
                toastr.warning(attribute, title);
            });
    }

    if (type === "info") {
        attributes
            .slice()
            .reverse()
            .forEach((attribute) => {
                toastr.info(attribute, title);
            });
    }
}
