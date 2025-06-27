// Debounce function to limit how often the content is captured
function debounce(fn, delay) {
    let timeout;
    return function() {
        clearTimeout(timeout);
        timeout = setTimeout(fn, delay);
    };
}

// Function to initialize a Quill editor
function initializeQuill(editorId, detailsId) {
    const editorElement = document.getElementById(editorId);
    const detailsElement = document.getElementById(detailsId);

    if (editorElement && detailsElement) {
        const quill = new Quill(editorElement, {
            theme: 'snow',
        });

        // Set initial content from hidden input
        if (detailsElement.value) {
            quill.root.innerHTML = detailsElement.value;
        }

        // Capture the HTML content from the Quill editor
        quill.on('text-change', debounce(function() {
            detailsElement.value = quill.root.innerHTML;
        }, 300));
    }
}

// Initialize the Quill editor when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeQuill('editor', 'details');
});
