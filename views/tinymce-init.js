
function initializeTinyMCE() {
  tinymce.init({
    selector: '.tinymce-editor',
    height: 500,
    menubar: true,
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
      'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
      'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image media link | code fullscreen | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; line-height: 1.6; }',

    // Image upload handler
    images_upload_handler: function (blobInfo, progress) {
      return new Promise(function (resolve, reject) {
        const formData = new FormData();
        formData.append('image', blobInfo.blob(), blobInfo.filename());

        fetch('index.php?controller=profile&action=uploadImage', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            resolve(result.url);
          } else {
            reject('Upload failed: ' + result.message);
          }
        })
        .catch(error => {
          reject('Upload error: ' + error.message);
        });
      });
    },

    // File picker for PDF, DOC, etc
    file_picker_callback: function (callback, value, meta) {
      if (meta.filetype === 'file') {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar');

        input.onchange = function () {
          const file = this.files[0];
          if (!file) return;

          if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB');
            return;
          }

          const formData = new FormData();
          formData.append('file', file);

          fetch('index.php?controller=profile&action=uploadFile', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              callback(result.url, { text: result.filename || file.name });
            } else {
              alert('Upload failed: ' + result.message);
            }
          })
          .catch(error => {
            alert('Upload error: ' + error.message);
          });
        };

        input.click();
      }

      if (meta.filetype === 'image') {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.onchange = function () {
          const file = this.files[0];
          if (!file) return;

          if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 5MB');
            return;
          }

          const formData = new FormData();
          formData.append('image', file);

          fetch('index.php?controller=profile&action=uploadImage', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              callback(result.url, { alt: file.name });
            } else {
              alert('Upload failed: ' + result.message);
            }
          })
          .catch(error => {
            alert('Upload error: ' + error.message);
          });
        };

        input.click();
      }
    },

    setup: function (editor) {
      console.log('TinyMCE editor initialized:', editor.id);
    }
  });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    initializeTinyMCE();
  }, 300);
});

// Reinitialize when tab changes
document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(tabButton) {
  tabButton.addEventListener('shown.bs.tab', function() {
    setTimeout(function() {
      tinymce.remove();
      initializeTinyMCE();
    }, 200);
  });
});

// Reinitialize when accordion opens
document.querySelectorAll('.collapse').forEach(function(collapse) {
  collapse.addEventListener('shown.bs.collapse', function() {
    setTimeout(function() {
      tinymce.remove();
      initializeTinyMCE();
    }, 200);
  });
});

// Handle modal
const addProfileModal = document.getElementById('addProfileModal');
if (addProfileModal) {
  addProfileModal.addEventListener('shown.bs.modal', function() {
    console.log('Modal opened, initializing TinyMCE...');
    setTimeout(function() {
      tinymce.remove();
      initializeTinyMCE();
    }, 300);
  });

  addProfileModal.addEventListener('hidden.bs.modal', function() {
    console.log('Modal closed, cleaning up...');
    if (tinymce.get('newEditor')) {
      tinymce.get('newEditor').remove();
    }
    const form = addProfileModal.querySelector('form');
    if (form) {
      form.reset();
    }
  });
}
