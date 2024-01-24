// auto resize text area
document.addEventListener("input", function (e) {
  if (e.target.tagName.toLowerCase() === "textarea") {
    autoResize(e.target)
  }
})

function autoResize(textarea) {
  textarea.style.height = "auto"
  textarea.style.height = textarea.scrollHeight + "px"
}

// image preview
function previewFile() {
  var preview = document.getElementById("preview")
  var fileInput = document.getElementById("fileInput")
  var file = fileInput.files[0]

  var reader = new FileReader()

  reader.onloadend = function () {
    if (file.type.startsWith("image/")) {
      preview.innerHTML =
        '<img src="' +
        reader.result +
        '" alt="Image Preview" style="max-width:100%">'
    } else if (file.type.startsWith("video/")) {
      preview.innerHTML =
        '<video width="100%" height="auto" controls><source src="' +
        reader.result +
        '" type="' +
        file.type +
        '"></video>'
    } else {
      preview.innerHTML = "<p>Unsupported file type</p>"
    }
  }

  if (file) {
    reader.readAsDataURL(file)
  } else {
    preview.innerHTML = ""
  }
}

// ajax function
const keyword = document.getElementById("keyword")
const userCardContainer = document.getElementById("user-card-container")

keyword.addEventListener("keyup", () => {
  const xhr = new XMLHttpRequest()
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      userCardContainer.innerHTML = xhr.responseText
    }
  }
  xhr.open("GET", `utils/php/search-user.php?keyword=${keyword.value}`, true)
  xhr.send()
})
