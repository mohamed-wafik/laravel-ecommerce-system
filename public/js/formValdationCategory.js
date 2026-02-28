document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("categoryForm");
    const titleInput = document.getElementById("title");
    const descriptionInput = document.getElementById("description");
    const imageInput = document.getElementById("image");
    const dropArea = document.getElementById("dropArea");
    const submitButton = form.querySelector(".submit-item");
    const cancelButton = form.querySelector(".cancel-item");
    const charCount = document.getElementById("charCount");

    // Character counter for description
    function updateCharCount() {
        const length = descriptionInput.value.length;
        charCount.textContent = length;

        if (length > 500) {
            charCount.classList.add("text-red-500", "font-bold");
        } else if (length > 450) {
            charCount.classList.add("text-orange-500");
            charCount.classList.remove("text-red-500", "font-bold");
        } else {
            charCount.classList.remove(
                "text-red-500",
                "text-orange-500",
                "font-bold",
            );
        }
    }

    descriptionInput.addEventListener("input", updateCharCount);
    updateCharCount(); // Initialize

    // Validation Functions
    function validateTitle() {
        const value = titleInput.value.trim();
        removeError(titleInput);

        if (value === "") {
            showError(titleInput, "Title is required");
            return false;
        }

        if (value.length < 3) {
            showError(titleInput, "Title must be at least 3 characters");
            return false;
        }

        if (value.length > 100) {
            showError(titleInput, "Title must not exceed 100 characters");
            return false;
        }

        showSuccess(titleInput);
        return true;
    }

    function validateDescription() {
        const value = descriptionInput.value.trim();
        removeError(descriptionInput);

        if (value.length > 500) {
            showError(
                descriptionInput,
                "Description must not exceed 500 characters",
            );
            return false;
        }

        if (value.length > 0) {
            showSuccess(descriptionInput);
        }
        return true;
    }

    function validateImage() {
        const file = imageInput.files[0];
        removeError(dropArea);

        if (!file) {
            return true; // Optional
        }

        const validTypes = [
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/gif",
            "image/webp",
        ];
        if (!validTypes.includes(file.type)) {
            showError(
                dropArea,
                "Please upload a valid image file (JPG, PNG, GIF, WEBP)",
            );
            return false;
        }

        const maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize) {
            showError(dropArea, "Image size must not exceed 10MB");
            return false;
        }

        showSuccess(dropArea);
        return true;
    }

    function showError(element, message) {
        const errorDiv = document.createElement("div");
        errorDiv.className =
            "error-message flex items-center gap-2 text-red-600 text-sm mt-2 animate-shake";
        errorDiv.innerHTML = `
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>${message}</span>
        `;

        if (element === dropArea) {
            element.classList.add("border-red-500", "bg-red-50");
            element.parentNode.appendChild(errorDiv);
        } else {
            element.classList.add(
                "border-red-500",
                "focus:border-red-500",
                "focus:ring-red-200",
                "bg-red-50",
            );
            element.parentNode.appendChild(errorDiv);
        }
    }

    function showSuccess(element) {
        if (element === dropArea) {
            element.classList.add("border-green-500", "bg-green-50");
        } else {
            element.classList.add(
                "border-green-500",
                "focus:border-green-500",
                "focus:ring-green-200",
            );
        }
    }

    function removeError(element) {
        const errorDiv = element.parentNode.querySelector(".error-message");
        if (errorDiv) {
            errorDiv.remove();
        }

        if (element === dropArea) {
            element.classList.remove(
                "border-red-500",
                "bg-red-50",
                "border-green-500",
                "bg-green-50",
            );
        } else {
            element.classList.remove(
                "border-red-500",
                "focus:border-red-500",
                "focus:ring-red-200",
                "bg-red-50",
                "border-green-500",
                "focus:border-green-500",
                "focus:ring-green-200",
            );
        }
    }

    // Real-time validation
    titleInput.addEventListener("blur", validateTitle);
    titleInput.addEventListener("input", function () {
        if (this.classList.contains("border-red-500")) {
            validateTitle();
        }
    });

    descriptionInput.addEventListener("blur", validateDescription);
    descriptionInput.addEventListener("input", function () {
        if (this.classList.contains("border-red-500")) {
            validateDescription();
        }
    });

    imageInput.addEventListener("change", function () {
        validateImage();
        if (this.files && this.files[0]) {
            updateFilePreview(this.files[0]);
        }
    });

    // Drag and Drop
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ["dragenter", "dragover"].forEach((eventName) => {
        dropArea.addEventListener(eventName, () => {
            dropArea.classList.add(
                "border-blue-500",
                "bg-blue-100",
                "scale-105",
            );
        });
    });

    ["dragleave", "drop"].forEach((eventName) => {
        dropArea.addEventListener(eventName, () => {
            dropArea.classList.remove(
                "border-blue-500",
                "bg-blue-100",
                "scale-105",
            );
        });
    });

    dropArea.addEventListener("drop", function (e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            validateImage();
            updateFilePreview(files[0]);
        }
    });

    // File Preview
    function updateFilePreview(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            let previewDiv = dropArea.querySelector(".preview-image");

            if (!previewDiv) {
                previewDiv = document.createElement("div");
                previewDiv.className =
                    "preview-image mt-4 p-4 bg-white rounded-lg border-2 border-green-200";
                dropArea.appendChild(previewDiv);
            }

            previewDiv.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">New Image Preview:</span>
                    <button type="button" class="remove-preview text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                <img src="${e.target.result}" alt="Preview" class="mx-auto max-h-40 rounded-lg shadow-md">
                <p class="text-sm text-gray-600 mt-2 text-center">
                    <span class="font-medium">${file.name}</span> 
                    <span class="text-gray-400">(${(file.size / 1024).toFixed(2)} KB)</span>
                </p>
            `;

            previewDiv
                .querySelector(".remove-preview")
                .addEventListener("click", function () {
                    imageInput.value = "";
                    previewDiv.remove();
                    removeError(dropArea);
                });
        };
        reader.readAsDataURL(file);
    }

    // Form Submission
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const isTitleValid = validateTitle();
        const isDescriptionValid = validateDescription();
        const isImageValid = validateImage();

        if (isTitleValid && isDescriptionValid && isImageValid) {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Updating...</span>
            `;
            submitButton.classList.add("opacity-75", "cursor-not-allowed");

            this.submit();
        } else {
            const firstError = form.querySelector(".border-red-500");
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
                firstError.focus();
            }
        }
    });

    // Cancel Button
    cancelButton.addEventListener("click", function () {
        const hasChanges =
            titleInput.value.trim() !== titleInput.defaultValue.trim() ||
            descriptionInput.value.trim() !==
                descriptionInput.defaultValue.trim() ||
            imageInput.files.length > 0;

        if (hasChanges) {
            if (
                confirm(
                    "⚠️ You have unsaved changes. Are you sure you want to cancel?",
                )
            ) {
                window.history.back();
            }
        } else {
            window.history.back();
        }
    });
});
