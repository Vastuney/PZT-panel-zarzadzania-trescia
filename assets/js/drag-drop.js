var dropzone = document.getElementById('dropzone');
var dropzone_input = dropzone.querySelector('.upload-input');
var multiple = dropzone_input.getAttribute('multiple') ? true : false;

['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach(function(event) {
  dropzone.addEventListener(event, function(e) {
    e.preventDefault();
    e.stopPropagation();
  });
});

dropzone.addEventListener('dragover', function(e) {
  this.classList.add('dropzone-dragging');
}, false);

dropzone.addEventListener('dragleave', function(e) {
  this.classList.remove('dropzone-dragging');
}, false);

dropzone.addEventListener('drop', function(e) {
  this.classList.remove('dropzone-dragging');
  var files = e.dataTransfer.files;
  var dataTransfer = new DataTransfer();
  var for_alert = "";
  Array.prototype.forEach.call(files, file => {
    var filename = file.name;
    if(filename.split('.').pop() == "zip" || filename.split('.').pop() == "rar" || filename.split('.').pop() == "txt") {
      for_alert += file.name +
      " ("+ file.size +
      " bitów)\r\n";
      dataTransfer.items.add(file);
      if (!multiple) {
        return false;
      }
    } else {
      for_alert +="Niepoprawny format plików \r\n";
      dataTransfer.items.add(file);
      if (!multiple) {
        return false;
      }
    }
  });

  var filesToBeAdded = dataTransfer.files;
  dropzone_input.files = filesToBeAdded;
  $('#dropzone span').html(for_alert);

}, false);
