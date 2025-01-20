document
  .getElementById("open-login-popup")
  .addEventListener("click", function () {
    document.getElementById("login-popup").classList.remove("hidden");
    document.getElementById("signup-popup").classList.add("hidden");
  });

document
  .getElementById("open-signup-popup")
  .addEventListener("click", function () {
    document.getElementById("signup-popup").classList.remove("hidden");
    document.getElementById("login-popup").classList.add("hidden");
  });

document.getElementById("popup-close").addEventListener("click", function () {
  document.getElementById("login-popup").classList.add("hidden");
});

document.getElementById("signup-close").addEventListener("click", function () {
  document.getElementById("signup-popup").classList.add("hidden");
});

function toggleForms() {
  const loginPopup = document.getElementById("login-popup");
  const signupPopup = document.getElementById("signup-popup");

  if (loginPopup.classList.contains("hidden")) {
    loginPopup.classList.remove("hidden");
    signupPopup.classList.add("hidden");
  } else {
    signupPopup.classList.remove("hidden");
    loginPopup.classList.add("hidden");
  }
}

let currentPage = 0;
const cardsPerPage = 3;
const cards = document.querySelectorAll(".course-card");
const totalCards = cards.length;
const totalPages = Math.ceil(totalCards / cardsPerPage);

function showPage(page) {
  // Hide all cards
  cards.forEach((card) => card.classList.add("hidden"));
  // Calculate start and end index
  const start = page * cardsPerPage;
  const end = start + cardsPerPage;

  // Show cards for the current page
  for (let i = start; i < end && i < totalCards; i++) {
    cards[i].classList.remove("hidden");
  }

  // Update page number display
  document.getElementById("page-number").textContent = page + 1;
  document.getElementById("total-pages").textContent = totalPages;

  // Update pagination links
  updatePagination(page);

  // Disable buttons if on first or last page
  document.getElementById("prev").disabled = page === 0;
  document.getElementById("next").disabled = page === totalPages - 1;
}

function changePage(direction) {
  currentPage += direction;
  showPage(currentPage);
}

function updatePagination(page) {
  const paginationContainer = document.getElementById("pagination");
  paginationContainer.innerHTML = ""; 

  for (let i = 0; i < totalPages; i++) {
    const pageLink = document.createElement("button");
    pageLink.textContent = i + 1;
    pageLink.className =
      "bg-gray-200 text-gray-800 rounded px-2 py-1 mx-1" +
      (i === page ? " font-bold" : "");
    pageLink.onclick = () => {
      currentPage = i;
      showPage(currentPage);
    };
    paginationContainer.appendChild(pageLink);
  }
}

// Initial page
showPage(currentPage);
updatePagination(currentPage);

// Function to show the popup with course details
function showPopup(course) {
  const popup = document.getElementById("course-popup");
  const popupContent = document.getElementById("popup-content");

  let content = "";
  if (course.contenu === "video") {
    const embedUrl = course.video_url
      ? course.video_url.replace("watch?v=", "embed/")
      : null;
    content = embedUrl
      ? `<iframe src="${embedUrl}" class="w-full h-96 rounded" frameborder="0" allowfullscreen></iframe>`
      : '<p class="text-red-600">URL de vidéo invalide.</p>';
  } else if (course.contenu === "document") {
    content = `
<textarea class="w-full h-96 p-4 border border-gray-300 rounded" readonly>${course.document_text}</textarea>
`;
  }

  content += `
<h2 class="text-xl font-bold mt-4">${course.titre}</h2>
<p class="text-gray-700 mt-2">${course.description}</p>
`;

  popupContent.innerHTML = content;
  popup.classList.remove("hidden");
}

// Function to close the popup
function closePopup() {
  const popup = document.getElementById("course-popup");
  popup.classList.add("hidden");
}
