// Define a function to generate recipe cards
function generateRecipeCards(recipes) {
  const recipeContainer = document.getElementById("recipe-container");

  recipes.forEach((recipe) => {
    const card = document.createElement("div");
    card.classList.add("recipe-card");

    card.innerHTML = `
        <div class="recipe-image">
          <img src="${recipe.image}" alt="${recipe.title}" />
        </div>
        <div class="recipe-info">
          <h2>${recipe.title}</h2>
          <p><strong>Author:</strong> ${recipe.author}</p>
          <p><strong>Description:</strong> ${recipe.description}</p>
          <p><strong>Category:</strong> ${recipe.category}</p>
          <p><strong>Rating:</strong> ${recipe.rating}</p>
        </div>
      `;

    recipeContainer.appendChild(card);
  });
}

// Fetch JSON data from an external file
fetch("recipes.json")
  .then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  })
  .then((data) => {
    generateRecipeCards(data.recipes);
  })
  .catch((error) => console.error("Error fetching JSON:", error));
