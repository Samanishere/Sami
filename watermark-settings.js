document.getElementById('watermark-image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('watermark-preview-container');
    const watermarkPath = document.getElementById('watermark-preview').src;

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = '';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.id = 'watermark-preview';
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.innerHTML = '<p>No preview available</p>';
    }
});

const regions = document.querySelectorAll('.region');
const hiddenInput = document.getElementById('watermark-position-hidden');

function highlightRegion(region) {
    regions.forEach(r => r.classList.remove('highlight'));
    if (region) {
        region.classList.add('highlight');
    }
}

regions.forEach(region => {
    if (region.getAttribute('data-region') === hiddenInput.value) {
        highlightRegion(region);
    }
});

document.getElementById('watermark-position').addEventListener('change', function() {
    const selectedValue = this.value;
    const selectedRegion = document.querySelector(`.region[data-region="${selectedValue}"]`);
    highlightRegion(selectedRegion);
    hiddenInput.value = selectedValue;
});