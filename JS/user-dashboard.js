$(document).ready(function() {
    $("#editIcon").click(function() {
        $("#editForm").fadeIn(); // Fade in the popup-overlay div
    });
  });
  
  function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;
  
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
  
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
  
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
  }
  
  // Get the element with id="defaultOpen" and click on it to make it the default active tab
  document.getElementById("defaultOpen").click();
  
    // Your JavaScript for Chart.js and 'Add New' functionality
    // window.onload = function() {
    //   var ctx = document.getElementById('articlesChart').getContext('2d');
    //   var chart = new Chart(ctx, {
    //     type: 'bar', // Change to 'bar' for bar graph
    //     data: {
    //       labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
    //       datasets: [{
    //         label: 'Views',
    //         backgroundColor: 'rgba(10, 51, 91, 0.7)', // Increased alpha value
    //         borderColor: '#0056b3',
    //         data: [0, 300, 200, 320, 450, 325, 400, 430, 450, 500, 550, 500],
    //       },
    //       {
    //         label: 'Downloads',
    //         backgroundColor: 'rgba(229, 111, 31, 0.7)', // Increased alpha value
    //         borderColor: '#E56F1F',
    //         data: [0, 200, 300, 350, 400, 420, 410, 480, 400, 430, 570, 600],
    //       }]
    //     },
    //     options: {
    //       scales: {
    //         y: {
    //           beginAtZero: true
    //         }
    //       },
    //       legend: {
    //         labels: {
    //           usePointStyle: true
    //         }
    //       }
    //     }
    //   });
    // };
    /* More badge hover */
    document.addEventListener("DOMContentLoaded", function () {
      var popup = document.getElementById("popup");
      var seeMoreButton = document.getElementById("see-more");
      var hideTimeout;
  
      seeMoreButton.addEventListener("mouseover", function (e) {
          popup.style.display = "block";
          positionPopup(e);
      });
  
      seeMoreButton.addEventListener("mousemove", function (e) {
          positionPopup(e);
      });
  
      seeMoreButton.addEventListener("mouseout", function () {
          hideTimeout = setTimeout(function () {
              popup.style.display = "none";
          }, 200);
      });
  
      popup.addEventListener("mouseover", function () {
          clearTimeout(hideTimeout);
      });
  
      popup.addEventListener("mouseout", function (e) {
          if (!e.relatedTarget || !e.relatedTarget.closest(".popup-container")) {
              popup.style.display = "none";
          }
      });
  
      function positionPopup(e) {
          var buttonRect = seeMoreButton.getBoundingClientRect();
  
          // Position the popup to the right side of the button
          popup.style.top = buttonRect.top + buttonRect.height / 2 - popup.offsetHeight / 2 + "px";
          popup.style.left = buttonRect.left + buttonRect.width + 20 + "px"; // Adjust as needed
      }
  });
  
  
  
    /**Hide details toggle **/
    document.addEventListener('DOMContentLoaded', function () {
      const icon = document.getElementById('toggleIcon');
      const label = document.getElementById('positionLabel');
      const label1 = document.getElementById('genderLabel');
      const label2 = document.getElementById('birthdayLabel');
      const label3 = document.getElementById('countryLabel');
      const label4 = document.getElementById('orcidLabel');
  
      // Store original information
      const originalData = {
          position: label.innerText,
          gender: label1.innerText,
          birthday: label2.innerText,
          country: label3.innerText,
          orcid: label4.innerText
      };
  
      icon.addEventListener('click', toggleDetails);
  
      function toggleDetails() {
          if (label.classList.contains('bullet')) {
              // Toggle back to original information only if it is not null
              if (originalData.position !== null) label.innerText = originalData.position;
              if (originalData.gender !== null) label1.innerText = originalData.gender;
              if (originalData.birthday !== null) label2.innerText = originalData.birthday;
              if (originalData.country !== null) label3.innerText = originalData.country;
              if (originalData.orcid !== null) label4.innerText = originalData.orcid;
  
              label.classList.remove('bullet');
          } else {
              // Mask the information only if it is not null
              label.innerText = originalData.position !== null ? '********' : '';
              label1.innerText = originalData.gender !== null ? '********' : '';
              label2.innerText = originalData.birthday !== null ? '********' : '';
              label3.innerText = originalData.country !== null ? '********' : '';
              label4.innerText = originalData.orcid !== null ? '********' : '';
  
              label.classList.add('bullet');
          }
  
          icon.classList.toggle('ri-eye-line');
          icon.classList.toggle('ri-eye-close-line');
      }
  });
  
  /* edit profile form */
  document.addEventListener('DOMContentLoaded', function () {
    const editIcon = document.getElementById('editIcon');
    const editForm = document.getElementById('editForm');
    const closeIcon = document.getElementById('closeIcon');
   ;
  
    editIcon.addEventListener('click', openEditForm);
    closeIcon.addEventListener('click', closeEditForm);
  
  
    function openEditForm() {
        editForm.style.display = 'block';
    }
  
    function closeEditForm() {
        editForm.style.display = 'none';
    }
  
    function saveChanges() {
        // Add logic to save changes to the backend or perform any necessary actions
        closeEditForm();
    }
  });
  
  
  document.getElementById('phone_number').addEventListener('input', function (event) {
      let input = event.target.value;
      input = input.replace(/\D/g, '');
      if (input.length > 11) {
          input = input.slice(0, 11);
      }
      event.target.value = input;
  });
  
  // document.getElementById('saveButton').addEventListener('click', function(event) {
  //     let phoneNumberInput = document.getElementById('phone_number').value;
      
  //     phoneNumberInput = phoneNumberInput.replace(/\D/g, '');
  
  //     if (phoneNumberInput.length < 11) {
  //         Swal.fire({
  //             icon: 'error',
  //             title: 'Invalid phone number',
  //             text: 'Phone number must contain 11 digits.',
  //         });
  //         event.preventDefault(); 
  //     }
  // });
      
  
  document.getElementById('expertiseData').value = getExpertiseData();
  
  // Add keyword
  document.getElementById('addExpertiseButton').addEventListener('click', function() {
      // Get the input value
      var inputValue = document.getElementById('fieldofexpertise').value.trim();
  
      // Check if the input is empty or contains only spaces
      if (inputValue === "") {
          return; // Exit function if empty or spaces
      }
  
      // Create a new keyword element
      var keywordElement = document.createElement('div');
      keywordElement.className = 'keyword';
  
      // Create a span for the keyword text
      var keywordText = document.createElement('span');
      keywordText.textContent = inputValue;
  
      // Create a close button
      var closeButton = document.createElement('span');
      closeButton.className = 'close-btn';
      closeButton.textContent = 'x';
  
      // Add an event listener to remove the keyword when the close button is clicked
      closeButton.addEventListener('click', function() {
          keywordElement.remove();
          updateHiddenInput(); // Update hidden input after removal
      });
  
      // Append elements to the keyword container
      keywordElement.appendChild(keywordText);
      keywordElement.appendChild(closeButton);
      document.getElementById('keywordContainer').appendChild(keywordElement);
  
      updateHiddenInput(); // Update hidden input after addition
  
      // Clear the input field
      document.getElementById('fieldofexpertise').value = '';
  });
  
  // Update hidden input when changes are made
  document.getElementById('keywordContainer').addEventListener('click', function(event) {
      if (event.target.classList.contains('close-btn')) {
          // Remove the keyword from the UI
          var keywordElement = event.target.closest('.keyword');
          keywordElement.remove();
          updateHiddenInput(); // Update hidden input after removal
      }
  });
  
  // Function to get expertise data
  function getExpertiseData() {
      var expertiseSpans = document.querySelectorAll('.keyword span:not(.close-btn)');
      var expertiseData = [];
  
      expertiseSpans.forEach(function(span) {
          expertiseData.push(span.textContent.trim());
      });
  
      return expertiseData.join(', ');
  }
  
  // Function to update hidden input
  function updateHiddenInput() {
      var currentExpertiseData = getExpertiseData();
      document.getElementById('expertiseData').value = currentExpertiseData;
  }
  
  
  
  
  document.getElementById('firstName').addEventListener('input', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  document.getElementById('middleName').addEventListener('input', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  
  document.getElementById('lastName').addEventListener('input', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  document.getElementById('affix').addEventListener('input', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  
  document.getElementById('birthdate').addEventListener('change', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  document.getElementById('gender').addEventListener('change', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  document.getElementById('status').addEventListener('change', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  document.getElementById('country').addEventListener('change', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  
  
  
  document.getElementById('orcid').addEventListener('input', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  
  document.getElementById('affiliation').addEventListener('input', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  
  document.getElementById('position').addEventListener('input', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  
  
   
  document.getElementById('bio').addEventListener('input', function(event){
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false;
  
  });
  
  
  
  document.getElementById('saveButton').addEventListener('click', function(event){
    event.preventDefault();
    const orcid = document.getElementById('orcid').value;
    const cancelBtn = document.getElementById('cancelBtn');
    let phoneNumberInput = document.getElementById('phone_number').value;
      
    phoneNumberInput = phoneNumberInput.replace(/\D/g, '');
  
    // Regular expression to match the ORCID format "xxxx-xxxx-xxxx-xxxx"
    const orcidPattern = /^\d{4}-\d{4}-\d{4}-\d{4}$/;
  
    Swal.fire({
        title: 'Do you want to make this changes?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, make changes!'
    }).then((result) => {
        if (result.isConfirmed) {
            if(phoneNumberInput === ''){
                document.getElementById('form').submit();
                document.getElementById('loadingOverlay').style.display = 'block';
                document.getElementById('editForm').style.display = 'none';

            }else if(phoneNumberInput.length < 11 && phoneNumberInput !== ''){
              Swal.fire({
                  icon: 'error',
                  title: 'Invalid phone number',
                  text: 'Phone number must contain 11 digits.',
              });
              event.preventDefault(); 
            }else if(orcid) {
                // Check if ORCID is not formatted correctly
                if (!orcidPattern.test(orcid)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'ORCID invalid'
                    }).then((result) => {
                        if(result.isConfirmed){
                          // document.getElementById('editForm').style.display = 'none';
                          // cancelBtn.click();
                         
                        }
                    });
                } else {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '../PHP/orcid.php');
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                document.getElementById('form').submit();
                                document.getElementById('loadingOverlay').style.display = 'block';
                                document.getElementById('editForm').style.display = 'none';
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                      //   document.getElementById('editForm').style.display = 'none';
                                      //   cancelBtn.click();
                                      // orcid = ''; 
                                     
                                    }
                                });
                            }
                        }
                    };
                    xhr.send('orcid=' + encodeURIComponent(orcid));
                }
            } else {
                // If ORCID is not provided, submit the form
                document.getElementById('form').submit();
                document.getElementById('loadingOverlay').style.display = 'block';
                document.getElementById('editForm').style.display = 'none';
            }
        }
    });
  });
  
  
  
  
  document.getElementById('changeProfileBtn').addEventListener('click', function() {
  document.getElementById('selectProfile').click();
  });
  
  document.getElementById('selectProfile').addEventListener('change', function() {
  const fileInput = document.getElementById('selectProfile');
  const profileImage = document.getElementById('profileImage');
  
  const file = fileInput.files[0];
  
  if (file) {
      const reader = new FileReader();
  
      reader.onload = function(e) {
          // Update the profile image source with the selected image
          profileImage.src = e.target.result;
      };
  
      // Read the selected file as a data URL
      reader.readAsDataURL(file);
  }
  });
  
  function openFileInput() {
  const fileInput = document.getElementById('fileInput');
  const imageModal = document.getElementById('imageModal');
  const selectedImagePreview = document.getElementById('selectedImagePreview');
  
  fileInput.click();
  
  fileInput.addEventListener('change', function () {
      const file = fileInput.files[0];
  
      if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
              selectedImagePreview.src = e.target.result;
              imageModal.style.display = 'block';
          };
          reader.readAsDataURL(file);
      }
  });
  }
  
  // function saveProfile() {
  //   const fileInput = document.getElementById('fileInput');
  //   const profileImage = document.getElementById('profileImage');
  //   const imageModal = document.getElementById('imageModal');
  //   imageModal.style.display = 'none';
  //   Swal.fire({
  //     icon: 'success',
  //     text: 'Change profile picture successfully',
  //     showConfirmButton: true
  //   }).then(() => {
  
  //     profileImage.src = document.getElementById('selectedImagePreview').src;
  //     fileInput.value = null;
   
  //   });
  // }
  
  
  function cancelUpdate() {
  const fileInput = document.getElementById('fileInput');
  const imageModal = document.getElementById('imageModal');
  
  // Clear the file input
  fileInput.value = null;
  
  // Hide the image modal without saving
  imageModal.style.display = 'none';
  }
  
  function onclickSupport() {
  Swal.fire({
      icon: 'question',
      text: 'Make your profile private?',
      showCancelButton: true,
      showConfirmButton: true,
  }).then((result) => {
      if (result.isConfirmed) {
         
          $('#exampleModal').modal('hide');
          location.reload();
          document.getElementById('loadingOverlay').style.display = 'block';
  
          $.ajax({
              type: 'POST',
              url: '../PHP/deduct_points.php', 
              data: { points: 2 },
              success: function(response) {
              
                  console.log(response);
                  document.getElementById('loadingOverlay').style.display = 'none';
                 
              },
              error: function(error) {
                
                  console.error(error);
              }
          });
      }
  });
  }
  