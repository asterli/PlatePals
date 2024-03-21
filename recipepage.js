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
          console.error("Recipe not found");
        }
      })
      .catch((error) => console.error("Error fetching recipe:", error));
  }

  function populateRecipePage(recipe) {
    document.querySelector("h1").textContent = recipe.title;
    document.querySelector("h3").textContent = `By ${recipe.author}`;
    document.getElementById("description").textContent = recipe.description;

    // Populate ingredients
    const ingredientsList = document.querySelector(".ingredients ul");
    ingredientsList.innerHTML = "";
    recipe.ingredients.forEach((ingredient) => {
      const li = document.createElement("li");
      li.textContent = ingredient;
      ingredientsList.appendChild(li);
    });

    const instructionsList = document.querySelector(".instructions ol");
    instructionsList.innerHTML = "";
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
    commentsDiv.innerHTML = '<h2 class="title">Comments</h2>';
    recipe.comments.forEach((comment) => {
      const commentElement = document.createElement("div");
      commentElement.innerHTML = `<strong>${comment.name}</strong><p>${comment.comment}</p>`;
      commentsDiv.appendChild(commentElement);
    });
  }

  const recipeId = getQueryParam("id");
  if (recipeId) {
    fetchRecipeData(recipeId);
  } else {
    console.error("No recipe ID in URL");
  }
});
