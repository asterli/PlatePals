window.onload = function () {
  loadRecipes();
  document
    .getElementById("searchInput")
    .addEventListener("keyup", function (event) {
      var searchQuery = this.value.trim();
      if (searchQuery !== "") {
        fetchRecipes(searchQuery);
      } else {
        loadRecipes();
      }
    });
};

function loadRecipes() {
  fetchRecipes("");
}

function fetchRecipes(searchQuery) {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "discover.php?q=" + encodeURIComponent(searchQuery), true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var recipes = JSON.parse(xhr.responseText);
        displayRecipes(recipes, initialize);
      } else {
        console.error("Error fetching recipes:", xhr.status);
      }
    }
  };
  xhr.send();
}

function displayRecipes(recipes, callback) {
  var recipeGrid = document.getElementById("recipeGrid");
  recipeGrid.innerHTML = "";
  recipes.forEach(function (recipe) {
    var recipeContainer = document.createElement("section");

    var recipeLink = document.createElement("a");
    recipeLink.href = `../recipepage/recipepage.html?id=${recipe.id}`;
    recipeLink.classList.add("recipe-card-link");

    var recipeCard = document.createElement("div");
    recipeCard.classList.add("recipe-card");
    recipeCard.style.backgroundImage = 'url("' + recipe.image + '")';

    var tintFade = document.createElement("div");
    tintFade.classList.add("tint-fade");
    recipeCard.appendChild(tintFade);

    var recipeDesc = document.createElement("section");
    recipeDesc.classList.add("recipe-desc");

    var title = document.createElement("h3");
    title.classList.add("rufina");
    title.textContent = recipe.title;
    recipeDesc.appendChild(title);

    var categoryContainer = document.createElement("div");
    categoryContainer.classList.add("category-container");

    var categories = JSON.parse(recipe.category);
    categories.forEach((categoryName, index) => {
      var category = document.createElement("p");
      category.classList.add("lato");
      category.classList.add("category");
      
      if (index > 0) {
        var buffer = document.createElement("p");
        buffer.textContent = "•"
        categoryContainer.appendChild(buffer);
      }
      
      category.textContent = categoryName;
      categoryContainer.appendChild(category);
    });

    recipeDesc.appendChild(categoryContainer);
    recipeCard.appendChild(recipeDesc);
    recipeLink.appendChild(recipeCard);
    recipeContainer.appendChild(recipeLink);
    recipeGrid.appendChild(recipeContainer);
  });

  callback();
}

let selectedCategory = '';

function initialize() {
  const params = new URLSearchParams(window.location.search);
  params.getAll("category").forEach(addSearchTerm);
}

function addSearchTerm(searchTerm) {
  if (selectedCategory !== searchTerm) {
    selectedCategory = searchTerm;
    hideRecipes();
  }
}

// document.addEventListener("DOMContentLoaded", function() {
//   initialize();
// });

// toggle article visibility with tags
function hideRecipes() {
  const recipes = document.querySelectorAll("section");
  if (selectedCategory == '' || selectedCategory == 'all') {
    recipes.forEach((recipe) => {
      recipe.classList.remove("hidden");
    });
    return;
  } else {
    let includedRecipes = [];
    recipes.forEach((recipe) => {
      recipe.querySelectorAll(".category").forEach((category) => {
        if (selectedCategory == category.textContent.trim().toLowerCase()) {
          includedRecipes.push(recipe);
        }
      });
      if (includedRecipes.includes(recipe)) {
        recipe.classList.remove("hidden");
      } else {
        recipe.classList.add("hidden");
      }
    });
  }
  return;
}