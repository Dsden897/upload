const form = document.getElementById('uploadForm');
const fileInput = document.getElementById('fileInput');
const dropZone = document.getElementById('dropZone');
const statusMessage = document.getElementById('statusMessage');

// Клик по зоне вызывает выбор файла
dropZone.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', (e) => {
    if (fileInput.files.length) {
        uploadFile(fileInput.files[0]);
    }
});

// Drag and Drop события
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
});

dropZone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    if (files.length) {
        uploadFile(files[0]);
    }
});

function uploadFile(file) {
    statusMessage.textContent = "Uploading...";
    statusMessage.className = "status-message";
    
    const formData = new FormData();
    formData.append('file', file);

    fetch('index.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        statusMessage.textContent = data.message;
        statusMessage.className = data.success ? "status-message success" : "status-message error";
    })
    .catch(() => {
        statusMessage.textContent = "Error uploading file";
        statusMessage.className = "status-message error";
    });
}