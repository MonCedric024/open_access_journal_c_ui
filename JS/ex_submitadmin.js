document.addEventListener("keydown", function (event) {
  // if (event.key === 'Enter') {
  //     event.preventDefault();
  // }
});

function checkDuplication() {
  // Disable the button
  document.getElementById("check-duplication").disabled = true;

  // Hide the text and show the spinner
  document.getElementById("check-text").style.display = "none";
  document.getElementById("check-spinner").style.display = "inline-block";
  document.getElementById("checking-text").style.display = "inline"; // Show the checking text

  // Simulate some asynchronous operation, like an AJAX request
  setTimeout(function () {
    // Once the operation is complete, re-enable the button and revert the state
    document.getElementById("check-duplication").disabled = false;
    document.getElementById("check-text").style.display = "inline";
    document.getElementById("check-spinner").style.display = "none";
    document.getElementById("checking-text").style.display = "none";
  }, 2000); // Adjust the timeout value as needed
}

document.addEventListener("DOMContentLoaded", function () {
  const nextBtnArticle = document.getElementById("next");
  const nextBGuide = document.getElementById("next1");
  const prevBtnArticle = document.getElementById("prev");
  const nextFile = document.getElementById("next3");
  const prevBFile = document.getElementById("prev3");
  const nextCont = document.getElementById("next4");
  const prevBCont = document.getElementById("prev4");
  const authorPcontact = document.getElementById("authorPcontact");
  const authorPcontactValue = document.getElementById("authorPcontactValue");
  const nextNote = document.getElementById("next5");
  const prevNote = document.getElementById("prev5");
  const prevReview = document.getElementById("prevReview");
  const submitBtn = document.getElementById("submit");
  const privacyTab = document.getElementById("privacy-tab");
  const articleTab = document.getElementById("article-tab");
  const fileTab = document.getElementById("file-tab");
  const contTab = document.getElementById("contributors-tab");
  const commentTab = document.getElementById("comment-tab");
  const reviewTab = document.getElementById("review-tab");
  const file_name = document.getElementById("file_name");
  const file_name2 = document.getElementById("file_name2");
  const file_name3 = document.getElementById("file_name3");

  const checkbox1 = document.getElementById("check-1");
  const checkbox2 = document.getElementById("check-2");
  const checkbox3 = document.getElementById("check-3");
  const checkbox4 = document.getElementById("check-4");
  const checkbox5 = document.getElementById("check-6");
  const checkbox6 = document.getElementById("check-7");
  const checkbox7 = document.getElementById("check-8");
  const check = document.getElementById("check");

  const title = document.getElementById("title");
  const abstract = document.getElementById("abstract");
  const keywords = document.getElementById("keywords");
  const reference = document.getElementById("reference");

  articleTab.disabled = true;
  fileTab.disabled = true;
  contTab.disabled = true;
  commentTab.disabled = true;
  reviewTab.disabled = true;

  privacyTab.style.backgroundColor = "var(--main, #0858A4)";
  privacyTab.style.color = "white";

  privacyTab.addEventListener("click", function (event) {
    privacyTab.style.backgroundColor = "var(--main, #0858A4)";
    privacyTab.style.color = "white";

    articleTab.style.backgroundColor = "white";
    articleTab.style.color = "var(--main, #0858A4)";
    fileTab.style.backgroundColor = "white";
    fileTab.style.color = "var(--main, #0858A4)";
    contTab.style.backgroundColor = "white";
    contTab.style.color = "var(--main, #0858A4)";
    commentTab.style.backgroundColor = "white";
    commentTab.style.color = "var(--main, #0858A4)";
    reviewTab.style.backgroundColor = "white";
    reviewTab.style.color = "var(--main, #0858A4)";
  });

  articleTab.addEventListener("click", function (event) {
    privacyTab.style.backgroundColor = "white";
    privacyTab.style.color = "var(--main, #0858A4)";
    articleTab.style.backgroundColor = "var(--main, #0858A4)";
    articleTab.style.color = "white";
    fileTab.style.backgroundColor = "white";
    fileTab.style.color = "var(--main, #0858A4)";
    contTab.style.backgroundColor = "white";
    contTab.style.color = "var(--main, #0858A4)";
    commentTab.style.backgroundColor = "white";
    commentTab.style.color = "var(--main, #0858A4)";
    reviewTab.style.backgroundColor = "white";
    reviewTab.style.color = "var(--main, #0858A4)";
  });

  fileTab.addEventListener("click", function (event) {
    privacyTab.style.backgroundColor = "white";
    privacyTab.style.color = "var(--main, #0858A4)";
    articleTab.style.backgroundColor = "white";
    articleTab.style.color = "var(--main, #0858A4)";
    fileTab.style.backgroundColor = "var(--main, #0858A4)";
    fileTab.style.color = "white";
    contTab.style.backgroundColor = "white";
    contTab.style.color = "var(--main, #0858A4)";
    commentTab.style.backgroundColor = "white";
    commentTab.style.color = "var(--main, #0858A4)";
    reviewTab.style.backgroundColor = "white";
    reviewTab.style.color = "var(--main, #0858A4)";
  });

  contTab.addEventListener("click", function (event) {
    privacyTab.style.backgroundColor = "white";
    privacyTab.style.color = "var(--main, #0858A4)";
    articleTab.style.backgroundColor = "white";
    articleTab.style.color = "var(--main, #0858A4)";
    fileTab.style.backgroundColor = "white";
    fileTab.style.color = "var(--main, #0858A4)";
    contTab.style.backgroundColor = "var(--main, #0858A4)";
    contTab.style.color = "white";
    commentTab.style.backgroundColor = "white";
    commentTab.style.color = "var(--main, #0858A4)";
    reviewTab.style.backgroundColor = "white";
    reviewTab.style.color = "var(--main, #0858A4)";
  });

  commentTab.addEventListener("click", function (event) {
    privacyTab.style.backgroundColor = "white";
    privacyTab.style.color = "var(--main, #0858A4)";
    articleTab.style.backgroundColor = "white";
    articleTab.style.color = "var(--main, #0858A4)";
    fileTab.style.backgroundColor = "white";
    fileTab.style.color = "var(--main, #0858A4)";
    contTab.style.backgroundColor = "white";
    contTab.style.color = "var(--main, #0858A4)";
    commentTab.style.backgroundColor = "var(--main, #0858A4)";
    commentTab.style.color = "white";
    reviewTab.style.backgroundColor = "white";
    reviewTab.style.color = "var(--main, #0858A4)";
  });

  reviewTab.addEventListener("click", function (event) {
    privacyTab.style.backgroundColor = "white";
    privacyTab.style.color = "var(--main, #0858A4)";
    articleTab.style.backgroundColor = "white";
    articleTab.style.color = "var(--main, #0858A4)";
    fileTab.style.backgroundColor = "white";
    fileTab.style.color = "var(--main, #0858A4)";
    contTab.style.backgroundColor = "white";
    contTab.style.color = "var(--main, #0858A4)";
    commentTab.style.backgroundColor = "white";
    commentTab.style.color = "var(--main, #0858A4)";
    reviewTab.style.backgroundColor = "var(--main, #0858A4)";
    reviewTab.style.color = "white";
  });

  //   authorPcontact.addEventListener('change', function(event) {
  //     if (authorPcontact.checked) {
  //         authorPcontactValue.value = ' , Primary Contact';
  //     } else {
  //         authorPcontactValue.value = '';
  //     }
  // });

  const checkArticle = document.getElementById("check-duplication");
  let checkArticleClicked = false;

  checkArticle.addEventListener("click", function () {
    checkArticleClicked = true;
  });

  title.addEventListener("input", function () {
    checkArticleClicked = false;
    fileTab.disabled = true;
  });

  editor.addEventListener("input", function () {
    checkArticleClicked = false;
    fileTab.disabled = true;
  });

  // keywords.addEventListener('input', function() {
  //     checkArticleClicked = false;
  //     fileTab.disabled = true;
  // });

  editor2.addEventListener("input", function () {
    // checkArticleClicked = false;
    fileTab.disabled = true;
  });

  nextBtnArticle.addEventListener("click", function (event) {
    const titleValue = title.value.trim();
    const titleWordCount = titleValue.split(/\s+/).length;
    const abstractValue = abstract.value.trim();
    const abstractWordCount = abstractValue.split(/\s+/).length;
    const keywordsValue = keywords.value.trim();

    if (
      titleValue === "" ||
      abstract.value === "" ||
      keywordArray.length == 0 ||
      reference.value === ""
    ) {
      Swal.fire({
        icon: "info",
        text: "You have to give all the article details before proceeding",
      });
    } else if (titleWordCount < 5 || abstractWordCount < 10) {
      Swal.fire({
        icon: "info",
        text: "Kindly correct the article details by the said validation",
      });
    } else if (keywordArray.length < 5) {
      Swal.fire({
        icon: "info",
        text: "Minimun of 5 keywords",
      });
    } else if (!checkArticleClicked) {
      Swal.fire({
        icon: "info",
        text: "Please check your article before proceeding",
      });
    } else {
      fileTab.disabled = false;
      fileTab.click();
    }
  });

  nextFile.addEventListener("click", function (event) {
    if (
      file_name.value === "" ||
      file_name2.value === "" ||
      file_name3.value === ""
    ) {
      Swal.fire({
        icon: "info",
        text: "You have to provide the files requested",
      });
    } else {
      contTab.disabled = false;
      contTab.click();
    }
  });

  nextCont.addEventListener("click", function (event) {
    commentTab.disabled = false;
    commentTab.click();
  });

  nextNote.addEventListener("click", function (event) {
    reviewTab.disabled = false;
    reviewTab.click();
  });

  prevBtnArticle.addEventListener("click", function (event) {
    privacyTab.click();
  });

  prevBFile.addEventListener("click", function (event) {
    articleTab.click();
  });

  prevBCont.addEventListener("click", function (event) {
    fileTab.click();
  });

  prevBCont.addEventListener("click", function (event) {
    fileTab.click();
  });

  prevNote.addEventListener("click", function (event) {
    contTab.click();
  });

  prevReview.addEventListener("click", function (event) {
    commentTab.click();
  });

  const checkboxes = document.querySelectorAll(".my-checkbox");
  const saveBtn = document.getElementById("submit");

  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
      const anyUnchecked = Array.from(checkboxes).some(function (cb) {
        return !cb.checked;
      });

      saveBtn.disabled = anyUnchecked;
    });
  });

  nextBGuide.addEventListener("click", function () {
    const allChecked = Array.from(checkboxes).every(function (cb) {
      return cb.checked;
    });

    if (!allChecked) {
      Swal.fire({
        icon: "info",
        text: "You have to accept the website submission guidlines",
      });
    } else {
      articleTab.disabled = false;
      articleTab.click();
    }
  });
});

document.getElementById("title").addEventListener("input", function (event) {
  const title = document.getElementById("title").value;
  const titlePreview = document.getElementById("input5f1");

  titlePreview.value = title;
});

document.getElementById("editor").addEventListener("input", function (event) {
  const editor = document.getElementById("editor").value;
  const abstractPreview = document.getElementById("input7");
  const abstract = document.getElementById("abstract");

  abstractPreview.value = editor;
  abstract.value = editor;
});

document.getElementById("keywords").addEventListener("input", function (event) {
  const keywords = document.getElementById("keywords").value;
  // const keywordsPreview = document.getElementById('input6');

  // keywordsPreview.value = keywordArray.join(',');
  //   console.log(keywordArray)
  //   console.log(keywordArray.join(','));
  // console.log(keywordsPreview.value,"df")
});

document.getElementById("editor2").addEventListener("input", function (event) {
  const editor2 = document.getElementById("editor2").value;
  const referencePreview = document.getElementById("input8");
  const reference = document.getElementById("reference");

  referencePreview.value = editor2;
  reference.value = editor2.split("\n").join("\\n");
});

document.getElementById("editor3").addEventListener("input", function (event) {
  const editor3 = document.getElementById("editor3").value;
  const notes = document.getElementById("notes");

  notes.value = editor3;
});

document.addEventListener("DOMContentLoaded", function () {
  const titleInput = document.getElementById("title");
  const editor = document.getElementById("editor");
  const keywords = document.getElementById("keywords");
  const editor2 = document.getElementById("editor2");
  const checkDuplicaton = document.getElementById("check-duplication");

  const titleValidation = document.getElementById("title-validation");
  const abstractValidation = document.getElementById("abstract-validation");
  const keywordsValidation = document.getElementById("keywords-validation");
  const referenceValidation = document.getElementById("reference-validation");

  const formFloating = document.getElementById("form-floating");
  const formFloating2 = document.getElementById("form-floating-2");
  const formFloating3 = document.getElementById("form-floating-3");

  let isValidationFailed = false;

  function checkValidations() {
    // Check if all validations have passed and all inputs have a value
    if (
      // titleValidation.style.display === 'none' &&
      // abstractValidation.style.display === 'none'
      // keywordsValidation.style.display === 'none' &&
      // referenceValidation.style.display === 'none' &&
      !isValidationFailed &&
      titleInput.value.trim() !== "" &&
      editor.value.trim() !== ""
      // // keywords.value.trim() !== '' &&
      // keywordArray.length !=0 &&
      // editor2.value.trim() !== ''
    ) {
      formFloating.style.width = "60%";
      formFloating2.style.display = "inline-block";
      formFloating3.style.width = "60%";
      checkDuplicaton.style.display = "block";
    } else {
      formFloating.style.width = "100%";
      formFloating2.style.display = "none";
      formFloating3.style.width = "100%";
      checkDuplicaton.style.display = "none";
    }
  }

  titleInput.addEventListener("input", function () {
    const wordCount = titleInput.value.trim().split(/\s+/).length;

    if (wordCount < 4) {
      titleValidation.innerHTML =
        "Title is too short. Please provide a comprehensive title.";
      titleValidation.style.display = "block";
      isValidationFailed = true;
    } else if (wordCount > 100) {
      titleValidation.innerHTML = "Title is too long.";
      titleValidation.style.display = "block";
      isValidationFailed = true;
    } else {
      titleValidation.style.display = "none";
      isValidationFailed = false;
    }

    checkValidations();
  });

  editor.addEventListener("input", function () {
    const text = editor.value.trim();
    const wordCount =
      text === "" ? 0 : text.match(/\b(?![\(\)\[\]\{\}]+)\S+\b/g).length;
    if (wordCount < 100) {
      abstractValidation.innerHTML = "Abstract too short";
      abstractValidation.style.display = "block";
      isValidationFailed = true;
    } else if (wordCount > 300) {
      abstractValidation.innerHTML =
        "Please limit your abstract to a maximum of 300 words";
      abstractValidation.style.display = "block";
      isValidationFailed = true;
    } else {
      abstractValidation.style.display = "none";
      isValidationFailed = false;
    }
    checkValidations();
  });

  editor.addEventListener("input", function () {
    const text = editor.value.trim();
    const wordCount =
      text === "" ? 0 : text.match(/\b(?![\(\)\[\]\{\}]+)\S+\b/g).length;
    document.querySelector(
      "#total-words-abstract"
    ).innerHTML = `${wordCount} / 300 words`;
  });
  document.querySelector("#keyword-btn").addEventListener("click", function () {
    checkValidations();
  });
  // keywords.addEventListener('blur', function () {
  //     // const wordCount = keywords.value.trim().split(",").length;
  //     const wordCount = keywordArray.length;
  //     if (wordCount > 5) {
  //         keywordsValidation.style.display = 'block';
  //     } else {
  //         keywordsValidation.style.display = 'none';
  //     }
  //     checkValidations();
  //   })

  editor2.addEventListener("input", function () {
    const referenceText = editor2.value.trim();

    if (referenceText === "") {
      referenceValidation.style.display = "block";
    } else {
      referenceValidation.style.display = "none";
    }

    checkValidations();
  });
});

const filePreview = document.getElementById("input9");
const filePreview2 = document.getElementById("input9f");
const filePreview3 = document.getElementById("input9g");

// Function to open file input based on index
function openFilename(index) {
  var input = document.getElementById("file_name" + index);
  input.click();

  input.addEventListener("change", function () {
    checkFileSize(input, 1.5 * 1024 * 1024, index);
  });
}

// Setup event listeners for file inputs
document.getElementById("addFileName").addEventListener("click", function () {
  // Trigger the hidden file input click
  const fileInput = document.getElementById("file_name");
  fileInput.click();

  // Listen for changes in the file input
  fileInput.addEventListener("change", function () {
    const maxSize = 1.5 * 1024 * 1024; // 1.5MB
    const fileButton = document.getElementById("addFileName");

    if (fileInput.files.length > 0) {
      const fileSize = fileInput.files[0].size; // in bytes
      const fileName = fileInput.files[0].name;

      if (fileSize > maxSize) {
        Swal.fire({
          icon: "info",
          text: "Please select a file 1.5mb or less",
        });
        // Clear the value of the file input

        fileInput.value = "";
        fileButton.innerHTML =
          '<i class="fa-solid fa-arrow-up-from-bracket" style="margin-right: 10px; color:#699BF7;"></i> Upload your file here';
      } else {
        fileButton.innerHTML = fileName;
        filePreview.value = fileName;
      }
    }
  });
});

document.getElementById("file_name2").addEventListener("change", function () {
  handleFileInputChange(2);
});

document.getElementById("file_name3").addEventListener("change", function () {
  handleFileInputChange(3);
});

// Handle file input change and display selected file name
function handleFileInputChange(index) {
  var input = document.getElementById("file_name" + index);
  checkFileSize(input, 1.5 * 1024 * 1024, index);
}

// Function to check file size and display file name
function checkFileSize(input, maxSizeInBytes, index) {
  var files = input.files;
  var fileButton = document.getElementById("addFileName" + index);

  if (files.length > 0) {
    var fileSize = files[0].size; // in bytes
    var maxSize = maxSizeInBytes;

    if (fileSize > maxSize) {
      Swal.fire({
        icon: "info",
        text: "Please select a file 1.5mb or less",
      });
      // Clear the value of the file input
      input.value = "";
    } else {
      var fileName = input.files[0].name;
      fileButton.innerText = fileName;

      // Assign value to filePreview2 or filePreview3 based on index
      if (index === 2) {
        filePreview2.value = fileName;
      } else if (index === 3) {
        filePreview3.value = fileName;
      }
    }
  }
}
// Function to clear file input and reset button text
function deleteFilename(index) {
  var fileInput = document.getElementById("file_name" + index);
  var fileButton = document.getElementById("addFileName" + index);

  fileInput.value = "";
  fileButton.innerHTML =
    '<i class="fa-solid fa-arrow-up-from-bracket" style="margin-right: 10px; color:#699BF7;"></i> Upload your file here';
}

// Setup delete file input for each button
document
  .getElementById("deleteFileName")
  .addEventListener("click", function () {
    // Clear the file input and reset button text
    const fileInput = document.getElementById("file_name");
    const fileButton = document.getElementById("addFileName");

    filePreview.value = "";
    fileInput.value = "";
    fileButton.innerHTML =
      '<i class="fa-solid fa-arrow-up-from-bracket" style="margin-right: 10px; color:#699BF7;"></i> Upload your file here';
  });

document
  .getElementById("deleteFileName2")
  .addEventListener("click", function () {
    deleteFilename(2);
    filePreview2.value = "";
  });

document
  .getElementById("deleteFileName3")
  .addEventListener("click", function () {
    deleteFilename(3);
    filePreview3.value = "";
  });

// Set up listeners for each file input and corresponding text input
setupFileInput("file_name", "input9");
setupFileInput("file_name2", "input9f");
setupFileInput("file_name3", "input9g");
setupFileInput("file_name", "file1UpdatePreview");
setupFileInput("file_name2", "file2UpdatePreview");
setupFileInput("file_name3", "file3UpdatePreview");

// function addRow() {
//   var index = $('#contributorTable tbody tr').length; // Get the current row index
//   var newRow = '<tr>' +
//       '<td><input class="form-control email-input" type="email" name="emailC[]" style="height: 30px;">' +
//       '<div class="form-check cAuthor" style="margin-right: 10px">' +
//       '<input class="form-check-input" type="checkbox" name="contributor_type_coauthor[' + index + ']" value="Co-Author" style="width:15px;">' +
//       '<label class="form-check-label"> Co-Author</label>' +
//       '</div>' +
//       '<div class="form-check pContact">' +
//       '<input class="form-check-input p-contact" type="checkbox" name="contributor_type_primarycontact[' + index + ']" value="Primary Contact" style="width:15px;">' +
//       '<label class="form-check-label"> Primary Contact</label>' +
//       '</div>' +
//       '<div class="form-check editor">' +
//       '<input class="form-check-input" type="checkbox" name="contributor_type_editor[' + index + ']" value="Editor" style="width:15px;">' +
//       '<label class="form-check-label"> Editor</label>' +
//       '</div>' +
//       '<div class="form-check translator">' +
//       '<input class="form-check-input" type="checkbox" name="contributor_type_translator[' + index + ']" value="Translator" style="width:15px;">' +
//       '<label class="form-check-label"> Translator</label>' +
//       '</div>' +
//       '</td>' +
//       '<td><input class="form-control" type="text" name="firstnameC[]" style="height: 30px;"></td>' +
//       '<td><input class="form-control" type="text" name="lastnameC[]" style="height: 30px;"></td>' +
//       '<td><input class="form-control" type="text" name="orcidC[]" style="height: 30px;"></td>' +
//       '<td><button type="button" class="btn btn-danger btn-sm deleteCont" onclick="deleteRow(this)"><i class="fa-solid fa-minus"></i></button></td>' +
//       '</tr>';

//   $('#contributorTable tbody').append(newRow);
// }

// // Attach event listener to the email input field for fetching data on blur
// $('#contributorTable tbody').on('blur', 'input.email-input', function() {
//   var email = $(this).val();
//   var currentRow = $(this).closest('tr');

//   const inputElement = document.querySelector('input[name="orcidC[]"]');

// // Add an event listener to the input element
// inputElement.addEventListener('input', function (event) {
//   // Get the input value
//   let inputValue = event.target.value;

//   // Remove non-numeric characters using a regular expression
//   inputValue = inputValue.replace(/\D/g, '');

//   // Update the input field with the cleaned value
//   event.target.value = inputValue;
// });

//   if (email !== '') {

//       $.ajax({
//           type: 'POST',
//           url: '../PHP/fetch_author_data.php',
//           data: { email: email },
//           dataType: 'json',
//           success: function(response) {
//               if (response.success) {
//                   // Update the current row with fetched data
//                   currentRow.find('input[name="firstnameC[]"]').val(response.data.first_name);
//                   currentRow.find('input[name="lastnameC[]"]').val(response.data.last_name);
//                   // currentRow.find('input[name="publicnameC[]"]').val(response.data.public_name);
//                   currentRow.find('input[name="orcidC[]"]').val(response.data.orc_id);
//               } else {
//                   // Handle the case where the email does not exist in the database
//                   Swal.fire({
//                   icon: "question",
//                   title: "This email is new to us",
//                   text: "Please try to input the contributors info manually."

//                 });
//               }
//           },
//           error: function(xhr, status, error) {
//               console.error('Error fetching data:', error);
//           }
//       });
//   }
// });

function saveData() {
  const title = document.getElementById("title");
  const abstract = document.getElementById("abstract");
  const keywords = document.getElementById("keywords");
  const reference = document.getElementById("reference");
  const form = document.getElementById("form");

  const file_name = document.getElementById("file_name");
  const file_name2 = document.getElementById("file_name2");
  const file_name3 = document.getElementById("file_name3");

  // const titlePreview = document.getElementById('input5f1');
  // const abstractPreview = document.getElementById('input7');
  // const keywordsPreview = document.getElementById('input6');
  // const referencePreview = document.getElementById('input8');

  // const filePreview = document.getElementById('input9');
  // const filePreview2 = document.getElementById('input9f');
  // const filePreview3 = document.getElementById('input9g');

  const submitBtn = document.getElementById("submit");

  if (
    title.value === "" ||
    abstract.value === "" ||
    keywordArray.length == 0 ||
    reference.value === "" ||
    file_name.value === "" ||
    file_name2.value === "" ||
    file_name3.value === ""
  ) {
    submitBtn.type = "button";

    Swal.fire({
      icon: "info",
      text: "Please complete the requested article information",
    });

    return;
  } else {
    var formData = new FormData(form);
    keywords.value = keywordArray.join(",");

    $("#contributorTable tbody tr").each(function (index, row) {
      var coAuthorCheckbox = $(row).find(
        'input[name="contributor_type_coauthor[]"]'
      );
      var primaryContactCheckbox = $(row).find(
        'input[name="contributor_type_primarycontact[]"]'
      );

      if (coAuthorCheckbox.is(":checked")) {
        formData.append(
          "contributor_type_coauthor[" + index + "]",
          "Co-Author"
        );
      }

      if (primaryContactCheckbox.is(":checked")) {
        formData.append(
          "contributor_type_primarycontact[" + index + "]",
          "Primary Contact"
        );
      }
    });

    submitBtn.type = "submit";

    $("#loadingOverlay").show();

    form.submit();
  }
}

// function deleteData() {
// // Iterate through each checkbox
// $('input[name="selectToDelete"]:checked').each(function() {
//     // Delete the corresponding row
//     $(this).closest('tr').remove();
// });
// }

function deleteRow(button) {
  $(button).closest("tr").remove(); // Remove the closest parent <tr> of the clicked button
}

// Event listener for changes in "Primary Contact" checkboxes in added rows
$(document).on("change", ".p-contact", function (event) {
  handlePrimaryContactChange(event.target);
});

// Event listener for main "authorPcontact" checkbox
document
  .getElementById("authorPcontact")
  .addEventListener("change", function (event) {
    handleMainAuthorPcontactChange(event.target);
  });

// Function to handle changes in "Primary Contact" checkboxes
function handlePrimaryContactChange(checkbox) {
  // Check if any "Primary Contact" checkbox is checked
  var anyChecked = Array.from(document.querySelectorAll(".p-contact")).some(
    function (elem) {
      return elem.checked;
    }
  );

  // Enable all "Primary Contact" checkboxes
  enableAllPrimaryContacts();

  // Disable "Primary Contact" checkboxes if any checked except the main checkbox
  if (anyChecked) {
    disablePrimaryContacts();
  }
}

// Function to handle changes in main "authorPcontact" checkbox
function handleMainAuthorPcontactChange(checkbox) {
  if (checkbox.checked) {
    disableOtherPrimaryContacts();
  } else {
    enableAllPrimaryContacts();
  }
}

// Function to disable "Primary Contact" checkboxes except the main "authorPcontact" checkbox
function disablePrimaryContacts() {
  var checkboxes = document.querySelectorAll(".p-contact");
  checkboxes.forEach(function (checkbox) {
    if (
      checkbox !== document.getElementById("authorPcontact") &&
      !checkbox.checked
    ) {
      checkbox.disabled = true;
    }
  });

  document.getElementById("authorPcontact").disabled = true;
}

// Function to disable other "Primary Contact" checkboxes when main "authorPcontact" is checked
function disableOtherPrimaryContacts() {
  var checkboxes = document.querySelectorAll(".p-contact");
  checkboxes.forEach(function (checkbox) {
    if (checkbox !== document.getElementById("authorPcontact")) {
      checkbox.disabled = true;
    }
  });
}

// Function to enable all "Primary Contact" checkboxes and main "authorPcontact" checkbox
function enableAllPrimaryContacts() {
  var checkboxes = document.querySelectorAll(".p-contact");
  checkboxes.forEach(function (checkbox) {
    checkbox.disabled = false;
  });

  document.getElementById("authorPcontact").disabled = false;
}
