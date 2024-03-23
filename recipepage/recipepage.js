document.addEventListener("DOMContentLoaded", function () {
  function getQueryParam(param) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    return urlParams.get(param);
  }

  function fetchRecipeData(recipeId) {
    fetch(`getRecipe.php?id=${recipeId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success && data.recipe) {
          populateRecipePage(data.recipe);
        } else {
          displayErrorMessage("Recipe not found or does not exist."); // Display an error message
        }
      })
      .catch((error) => {
        console.error("Error fetching recipe:", error);
        displayErrorMessage(
          "An error occurred while fetching the recipe details."
        );
      });
  }

  function displayErrorMessage(message) {
    const container = document.querySelector(".container");
    container.innerHTML = `<div class="error-message">${message}</div>`; // Use your existing CSS styles or add new ones for the error message
  }

  function populateRecipePage(recipe) {
    document.querySelector("h1").textContent = recipe.title;
    document.querySelector("h3").textContent = `By ${recipe.author}`;
    document.getElementById("description").textContent = recipe.description;

    const ingredientsList = document.querySelector(".ingredients ul");
    recipe.ingredients.forEach((ingredient) => {
      const li = document.createElement("li");
      li.textContent = ingredient;
      ingredientsList.appendChild(li);
    });

    const instructionsList = document.querySelector(".instructions ol");
    recipe.instructions.forEach((step) => {
      const li = document.createElement("li");
      li.textContent = step;
      instructionsList.appendChild(li);
    });

    const recipeNav = document.querySelector(".recipe-nav");
    if (recipe.image && recipeNav) {
      recipeNav.style.backgroundImage = `url('${recipe.image}')`;
    }

    const commentsDiv = document.querySelector(".comments");
    commentsDiv.innerHTML = '<h2 class="title">Comments</h2>'; // Reset comments section
    recipe.comments.forEach((comment) => {
      const commentElement = document.createElement("div");
      commentElement.innerHTML = `<strong>${comment.name}</strong><p>${comment.comment}</p>`;
      commentsDiv.appendChild(commentElement);
    });

    document
      .querySelector(".comment-form form")
      .addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const recipeId = new URLSearchParams(window.location.search).get("id");
        if (recipeId) {
          formData.append("recipeId", recipeId);
        }

        fetch("saveComment.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              const commentsDiv = document.querySelector(".comments");
              const newCommentDiv = document.createElement("div");
              newCommentDiv.innerHTML = `<strong>${data.comment.name}</strong><p>${data.comment.text}</p>`;
              commentsDiv.appendChild(newCommentDiv);
              document.getElementById("name").value = "";
              document.getElementById("comment").value = "";
            } else {
              console.error("Failed to save comment:", data.message);
            }
          })
          .catch((error) => console.error("Error submitting comment:", error));
      });
  }

  const recipeId = getQueryParam("id");
  if (recipeId) {
    fetchRecipeData(recipeId);
  } else {
    console.error("No recipe ID in URL");
  }
});
