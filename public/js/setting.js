// Preview uploaded image
function previewImage(input, previewId) {
    const file = input.files[0];
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: "error",
                title: "File Too Large",
                text: "Please select an image smaller than 2MB",
                confirmButtonText: "OK",
                customClass: {
                    confirmButton: "swal2-confirm-custom",
                    popup: "swal2-popup-custom",
                },
                buttonsStyling: false,
            });
            input.value = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) =>
            (document.getElementById(previewId).src = e.target.result);
        reader.readAsDataURL(file);
    }
}

// Remove avatar
function removeAvatar(userId) {
    Swal.fire({
        title: "Remove Avatar?",
        text: "Are you sure you want to remove your profile photo?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Remove",
        cancelButtonText: "Cancel",
        customClass: {
            confirmButton: "swal2-confirm-danger",
            cancelButton: "swal2-cancel-custom",
            popup: "swal2-popup-custom",
        },
        buttonsStyling: false,
        didOpen: () => {
            const style = document.createElement("style");
            style.textContent = `
                .swal2-popup-custom { border-radius: 1rem; padding: 2rem; }
                .swal2-confirm-danger { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important; color: white !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 600 !important; margin: 0.5rem !important; }
                .swal2-cancel-custom { background: #f3f4f6 !important; color: #374151 !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 600 !important; margin: 0.5rem !important; }
            `;
            document.head.appendChild(style);
        },
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/dashboard/portfolio/${userId}/remove-avator`, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
            }).then((response) => {
                if (response.ok) {
                    Swal.fire({
                        icon: "success",
                        title: "Avatar Removed",
                        text: "Your profile photo has been removed",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "swal2-confirm-custom",
                            popup: "swal2-popup-custom",
                        },
                        buttonsStyling: false,
                    }).then(() => location.reload());
                }
            });
        }
    });
}

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + "-icon");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

// Password strength checker
function checkPasswordStrength(password) {
    const strengthDiv = document.getElementById("passwordStrength");
    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");

    if (password.length === 0) {
        strengthDiv.classList.add("hidden");
        return;
    }

    strengthDiv.classList.remove("hidden");

    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    const configs = [
        {
            width: "25%",
            color: "bg-red-500",
            text: "Weak",
            textColor: "text-red-600",
        },
        {
            width: "50%",
            color: "bg-orange-500",
            text: "Fair",
            textColor: "text-orange-600",
        },
        {
            width: "75%",
            color: "bg-yellow-500",
            text: "Good",
            textColor: "text-yellow-600",
        },
        {
            width: "100%",
            color: "bg-green-500",
            text: "Strong",
            textColor: "text-green-600",
        },
    ];

    const config = configs[strength - 1] || configs[0];
    strengthBar.style.width = config.width;
    strengthBar.className = `h-full transition-all duration-300 rounded-full ${config.color}`;
    strengthText.textContent = config.text;
    strengthText.className = `text-xs font-bold ${config.textColor}`;
}

// Change Email Modal Functions
function openChangeEmailModal() {
    document.getElementById("changeEmailModal").classList.remove("hidden");
    document.getElementById("changeEmailModal").classList.add("flex");
    document.body.style.overflow = "hidden";
}

function closeChangeEmailModal() {
    document.getElementById("changeEmailModal").classList.add("hidden");
    document.getElementById("changeEmailModal").classList.remove("flex");
    document.body.style.overflow = "";
    document.getElementById("changeEmailForm").reset();
}

// Close modal on escape key
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeChangeEmailModal();
    }
});

// Add custom SweetAlert2 styles
const style = document.createElement("style");
style.textContent = `
    .swal2-popup-custom { border-radius: 1rem; padding: 2rem; }
    .swal2-confirm-custom { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; color: white !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 600 !important; margin: 0.5rem !important; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3) !important; }
    .swal2-cancel-custom { background: #f3f4f6 !important; color: #374151 !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 600 !important; margin: 0.5rem !important; }
`;
document.head.appendChild(style);
