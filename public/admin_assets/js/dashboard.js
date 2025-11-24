document.getElementById('importFile').addEventListener('change', function(event) {
    var fileName = event.target.files[0] ? event.target.files[0].name : 'Choose file';
    event.target.nextElementSibling.textContent = fileName;
});