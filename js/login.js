function showForm(formID){
    document.querySelectorAll(".container").forEach(form => form.classList.remove("active"));
    document.getElementById(formID).classList.add("active");
}