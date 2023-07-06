document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('restaurant-form');
    const msgError = document.getElementById('category-error');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    console.log(button, checkboxes);
    button.addEventListener('click', function (event) {
        let checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);
        msgError.classList.add('d-none');

        if (!checkedOne) {
            event.preventDefault();
            msgError.classList.remove('d-none');
        }
    });
});