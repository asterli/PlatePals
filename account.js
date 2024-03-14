// Define a function to generate recipe cards
function generateRecipeCards(recipes) {
  const recipeContainer = document.getElementById("recipe-container");

  recipes.forEach((recipe) => {
    const card = document.createElement("div");
    card.classList.add("recipe-card");

    // Create an anchor tag with href pointing to the recipe page
    const recipeLink = document.createElement("a");
    recipeLink.href = `recipepage.html?id=${recipe.id}`; // Assuming you have a recipe page named recipe.html with a query parameter id

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
