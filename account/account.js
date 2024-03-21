function generateRecipeCards(recipes) {
  const recipeContainer = document.getElementById("recipe-container");

  recipes.forEach((recipe) => {
    const card = document.createElement("div");
    card.classList.add("recipe-card");

    const recipeLink = document.createElement("a");
    recipeLink.href = `../recipepage/recipepage.html?id=${recipe.id}`;

    recipeLink.innerHTML = `
      <div class="recipe-image">
        <img src="${recipe.image}" alt="${recipe.title}" />
      </div>
      <div class="recipe-info">
        <h2>${recipe.title}</h2>
        <p><strong></strong> ${recipe.description}</p>
        <p id="category"><strong></strong> ${recipe.category}</p>
      </div>
    `;

    card.appendChild(recipeLink);
    recipeContainer.appendChild(card);
  });
}

document.addEventListener("DOMContentLoaded", function () {
  var modal = document.getElementById("addRecipeModal");
  var btn = document.getElementById("add-recipe");
  var span = document.getElementsByClassName("close-button")[0];

  btn.onclick = function () {
    modal.style.display = "block";
    document.body.classList.add("modal-active");
  };

  span.onclick = function () {
    modal.style.display = "none";
    document.body.classList.remove("modal-active");
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
      document.body.classList.remove("modal-active");
    }
  };

  document
    .getElementById("recipeForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      var formData = new FormData(this);
      fetch("addRecipe.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Success:", data);
          document.getElementById("addRecipeModal").style.display = "none";
          window.location.href = `../recipepage/recipepage.html?id=${data.id}`;
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    });

  const signOutButton = document.getElementById("sign-out");

  if (signOutButton) {
    signOutButton.addEventListener("click", function () {
      fetch("signout.php", {
        method: "POST",
      })
        .then((response) => {
          if (response.ok) {
            window.location.href = "../login/login.html";
          } else {
            console.error("Sign out failed.");
          }
        })
        .catch((error) => console.error("Error signing out:", error));
    });
  }
  var imageInput = document.getElementById("recipeImage");
  var imagePreviewContainer = document.getElementById("imagePreviewContainer");
  var imagePreview = document.getElementById("imagePreview");

  imageInput.addEventListener("change", function () {
    var file = this.files[0];
    if (file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        imagePreview.src = e.target.result;
        imagePreviewContainer.style.display = "block";
      };

      reader.readAsDataURL(file);
    } else {
      imagePreview.src = "";
      imagePreviewContainer.style.display = "none";
    }
  });
});

// Fetch recipes from account.php
fetch("account.php")
  .then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  })
  .then((data) => {
    console.log(data);
    generateRecipeCards(data.recipes);
  })
  .catch((error) => console.error("Error fetching recipes:", error));
