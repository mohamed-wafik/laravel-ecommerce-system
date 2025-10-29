const btnAdd = document.getElementById("add_new_item");
const formAdd = document.getElementById("add_new_item_form");
const btnCloseadd = document.querySelector("#add_new_item_form #closeForm");
const cointainerForm = document.querySelector(
    "#add_new_item_form .form-container"
);
const btnCancelAdd = document.getElementById("canecl_add_item");

const fieldsProduct = [
    { name: "title", type: "text", required: true },
    { name: "description", type: "text", required: true },
    { name: "price", type: "number", min: 0, required: true },
    { name: "stock", type: "number", min: 0, required: true },
    { name: "discount", type: "number", min: 0, max: 100 },
    { name: "image", type: "file" },
];
const fieldsCategory = [
    { name: "title", type: "text", required: true },
    { name: "body", type: "text", required: true },
];

const validationInputs = (parent, fields) => {
    const parentEl = document.getElementById(parent);

    let hasErrors = false;

    fields.forEach((field) => {
        const input = parentEl.querySelector(`[name="${field.name}"]`);
        if (!input) return;

        let error = input.nextElementSibling;
        if (!error || !error.classList.contains("error-message")) {
            error = document.createElement("p");
            error.className = "error-message text-red-500 text-sm mt-1";
            input.insertAdjacentElement("afterend", error);
        }

        error.style.display = "none";
        error.textContent = "";

        if (field.required && !input.value.trim()) {
            error.textContent = `${field.name} is required.`;
            error.style.display = "block";
            hasErrors = true;
        } else if (field.type === "number") {
            const val = parseFloat(input.value);
            if (isNaN(val)) {
                error.textContent = `${field.name} must be a number.`;
                error.style.display = "block";
                hasErrors = true;
            } else {
                if (field.min !== undefined && val < field.min) {
                    error.textContent = `${field.name} must be >= ${field.min}.`;
                    error.style.display = "block";
                    hasErrors = true;
                }
                if (field.max !== undefined && val > field.max) {
                    error.textContent = `${field.name} must be <= ${field.max}.`;
                    error.style.display = "block";
                    hasErrors = true;
                }
            }
        }
    });

    return hasErrors;
};

const openModal = (modal) => {
    modal.classList.remove("hidden");
    modal.classList.add("flex");
};
const closeModal = (modal) => {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
};

formAdd.addEventListener("click", (e) => {
    if (e.target !== cointainerForm && !cointainerForm.contains(e.target)) {
        closeModal(formAdd);
    }
});
btnCloseadd.addEventListener("click", () => {
    closeModal(formAdd);
});

document.querySelectorAll(".edit_item").forEach((btn) => {
    btn.addEventListener("click", () =>
        openModal(document.getElementById("edit_item_form_" + btn.dataset.id))
    );
});

document.querySelectorAll(".canecl_edit_item").forEach((btn) => {
    btn.addEventListener("click", () =>
        closeModal(document.getElementById("edit_item_form_" + btn.dataset.id))
    );
});

document.querySelectorAll(".submit-edit-item").forEach((btn) => {
    const form = document.querySelector(
        `#edit_item_form_${btn.dataset.id} form`
    );
    form.addEventListener("submit", (e) => {
        let hasErrors = false;
        if (e.target.dataset.type === "product") {
            hasErrors = validationInputs(
                `edit_item_form_${btn.dataset.id}`,
                fieldsProduct
            );
        } else if (e.target.dataset.type === "category") {
            hasErrors = validationInputs(
                `edit_item_form_${btn.dataset.id}`,
                fieldsCategory
            );
        }

        if (hasErrors) {
            e.preventDefault();
        }
    });
});

document.querySelectorAll(".formEdit").forEach((modal) => {
    modal.addEventListener("click", (e) => {
        const container = modal.querySelector(".form-container");
        if (e.target !== container && !container.contains(e.target)) {
            closeModal(modal);
        }
    });
});

btnAdd.addEventListener("click", () => {
    openModal(formAdd);
});
btnCancelAdd.addEventListener("click", () => {
    closeModal(formAdd);
});

function confirmItemDelete(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This item will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        reverseButtons: true,
        customClass: {
            popup: "rounded-2xl shadow-xl",
            confirmButton: "rounded-lg px-4 py-2 font-semibold",
            cancelButton: "rounded-lg px-4 py-2 font-semibold",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("delete_item_form_" + id).submit();
        }
    });
}
formAdd.querySelector("form").addEventListener("submit", (e) => {
    e.preventDefault();
    let hasErrors = false;
    if (e.target.dataset.type === "product") {
        hasErrors = validationInputs("add_new_item_form", fieldsProduct);
    } else if (e.target.dataset.type === "category") {
        hasErrors = validationInputs("add_new_item_form", fieldsCategory);
    }
    if (hasErrors) {
        e.preventDefault();
    }
});
