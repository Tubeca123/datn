/**
 * image-dropzone.js
 * Reusable dropzone for image upload. 
 * Usage:
 *  - Wrap the drop area + preview inside a container with class .js-image-dropzone
 *  - Use the recommended child classes or data-* attributes (see Blade example)
 *  - The script auto-inits all .js-image-dropzone on DOMContentLoaded
 *
 * It exposes:
 *  - window.ImageDropzone.initAll()  // re-init if needed
 *  - window.ImageDropzone.init(containerElement) // init single container
 */

(function(window, document){
  const MAX_BYTES = 5 * 1024 * 1024;
  const ALLOWED = ['image/jpeg', 'image/png', 'image/gif'];

  function formatSize(bytes){
    if(bytes < 1024) return bytes + ' B';
    if(bytes < 1024*1024) return Math.round(bytes/1024) + ' KB';
    return (bytes/(1024*1024)).toFixed(2) + ' MB';
  }

  function initElement(container){
    if(!container) return;
    // find elements by data-attribute, fallback to common class
    const dropArea = container.querySelector('[data-drop]') || container.querySelector('.drop-area') || container;
    const chooseBtn = container.querySelector('[data-choose]') || container.querySelector('.btn-choose');
    const inputFile = container.querySelector('[data-input]') || container.querySelector('input[type="file"]');
    const previewWrap = container.querySelector('[data-preview]') || container.querySelector('.image-preview');
    const previewImg = container.querySelector('[data-preview-img]') || container.querySelector('img.preview-img');
    const fileNameEl = container.querySelector('[data-filename]') || container.querySelector('.file-name');
    const fileInfoEl = container.querySelector('[data-fileinfo]') || container.querySelector('.file-info');
    const dropHint = container.querySelector('[data-hint]') || container.querySelector('.drop-hint');
    const removeBtn = container.querySelector('[data-remove]') || container.querySelector('.btn-remove');
    const changeBtn = container.querySelector('[data-change]') || container.querySelector('.btn-change');

    if(!inputFile) return;

    // helpers
    function setInputFile(file){
      // try to set input.files via DataTransfer (modern browsers)
      try {
        const dt = new DataTransfer();
        dt.items.add(file);
        inputFile.files = dt.files;
      } catch (err) {
        // fallback: cannot programmatically set files on some browsers
        console.warn('ImageDropzone: cannot set input.files programmatically', err);
      }
    }

    function resetPreview(){
      if(previewImg) previewImg.src = '';
      if(previewWrap) previewWrap.style.display = 'none';
      if(fileNameEl) fileNameEl.textContent = '';
      if(fileInfoEl) fileInfoEl.textContent = '';
      if(dropHint) dropHint.textContent = (dropHint.dataset?.default || 'Chấp nhận: JPG, PNG, GIF — tối đa 5MB');
      inputFile.value = '';
    }

    function showPreview(file, dataUrl){
      if(previewImg) previewImg.src = dataUrl;
      if(previewWrap) previewWrap.style.display = 'block';
      if(fileNameEl) fileNameEl.textContent = file.name;
      if(fileInfoEl) fileInfoEl.textContent = `${formatSize(file.size)} • ${file.type}`;
      if(dropHint) dropHint.textContent = 'Ảnh đã chọn';
    }

    // handle file
    function handleFile(file){
      if(!file) return;
      if(!ALLOWED.includes(file.type)){
        alert('Định dạng không hợp lệ. Vui lòng chọn JPG, PNG hoặc GIF.');
        inputFile.value = '';
        return;
      }
      if(file.size > MAX_BYTES){
        alert('File quá lớn. Vui lòng chọn ảnh <= 5MB.');
        inputFile.value = '';
        return;
      }

      setInputFile(file);
      const reader = new FileReader();
      reader.onload = function(e){
        showPreview(file, e.target.result);
      };
      reader.readAsDataURL(file);
    }

    // open file dialog
    if(chooseBtn) chooseBtn.addEventListener('click', ()=> inputFile.click());
    if(changeBtn) changeBtn.addEventListener('click', ()=> inputFile.click());

    // click drop area opens picker
    if(dropArea){
      dropArea.addEventListener('click', e=>{
        // avoid triggering when clicking controls inside
        const target = e.target;
        if(target && (target.closest('[data-choose]') || target.closest('.btn-choose'))) return;
        inputFile.click();
      });
    }

    // drag events
    ['dragenter','dragover'].forEach(ev=>{
      container.addEventListener(ev, e=>{
        e.preventDefault(); e.stopPropagation();
        dropArea && dropArea.classList.add('dragover');
      });
    });
    ['dragleave','drop'].forEach(ev=>{
      container.addEventListener(ev, e=>{
        e.preventDefault(); e.stopPropagation();
        dropArea && dropArea.classList.remove('dragover');
      });
    });

    // drop
    container.addEventListener('drop', e=>{
      e.preventDefault();
      const files = e.dataTransfer && e.dataTransfer.files;
      if(files && files.length) handleFile(files[0]);
    });

    // choose file via input
    inputFile.addEventListener('change', e=>{
      const f = e.target.files && e.target.files[0];
      if(f) handleFile(f);
    });

    // remove
    if(removeBtn){
      removeBtn.addEventListener('click', e=>{
        e.preventDefault();
        resetPreview();
      });
    }

    // store default hint text
    if(dropHint && !dropHint.dataset.default){
      dropHint.dataset.default = dropHint.textContent;
    }
  }

  // public API
  window.ImageDropzone = {
    init: function(container){
      if(!container) return;
      initElement(container);
    },
    initAll: function(selector = '.js-image-dropzone'){
      const nodes = document.querySelectorAll(selector);
      nodes.forEach(n => initElement(n));
    }
  };

  // auto init on DOMContentLoaded
  document.addEventListener('DOMContentLoaded', function(){
    window.ImageDropzone.initAll();
  });

})(window, document);
