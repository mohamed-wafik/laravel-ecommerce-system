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
