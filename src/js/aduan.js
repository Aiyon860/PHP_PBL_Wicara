
feather.replace();

// JavaScript untuk menampilkan gambar yang diupload dan menghilangkan tombol "Choose A Photo"
function loadImage(event) {
    const file = event.target.files[0];
    const imgElement = document.getElementById('chosen-image');
    const fileNameElement = document.getElementById('file-name');
    const uploadButtonContainer = document.getElementById('upload-button-container');

    if (file) {
        imgElement.src = URL.createObjectURL(file);
        fileNameElement.textContent = file.name;
        uploadButtonContainer.style.display = 'none'; // Menghilangkan tombol ketika gambar diupload
    }
}
        
function toggleCheckbox(checkbox) {
    const checkboxSpan = document.getElementById('checkbox-span');
    const checkboxIcon = document.getElementById('checkbox-icon');
            
    if (checkbox.checked) {
        // Jika checkbox dicentang
        checkboxSpan.classList.remove('border-gray-400');
        checkboxSpan.classList.add('bg-blue-500', 'border-blue-500');
        checkboxIcon.classList.remove('hidden');
    } else {
        // Jika checkbox tidak dicentang
        checkboxSpan.classList.remove('bg-blue-500', 'border-blue-500');
        checkboxSpan.classList.add('border-gray-400');
        checkboxIcon.classList.add('hidden');
    }
}