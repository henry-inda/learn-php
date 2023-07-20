var sidebar = document.getElementById('sidebar');
var forms = Array.from(document.getElementsByClassName('summary-form'));

function toggleForm(formId) {
  var form = document.getElementById(formId + '-form');
  
  if (form.style.display === 'block') {
    return; // Do nothing if the form is already open
  } else {
    // Hide all other forms
    forms.forEach(function(f) {
      if (f.id !== formId + '-form') {
        f.style.display = 'none';
      }
    });
    
    // Show the selected form
    form.style.display = 'block';
  }
}

function toggleSidebar() {
  var sidebarWidth = sidebar.offsetWidth;
  var isOpen = sidebar.style.left === '0px';
  
  if (isOpen) {
    sidebar.style.left = -sidebarWidth + 'px';
  } else {
    sidebar.style.left = '0px';
  }
}