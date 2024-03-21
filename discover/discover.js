// discover.js

window.onload = function() {
    loadRecipes(); // Load all recipes on page load
  
    // Add event listener for search input keyup event
    document.getElementById('searchInput').addEventListener('keyup', function(event) {
      var searchQuery = this.value.trim(); // Get search query from input field
      if (searchQuery !== '') {
        fetchRecipes(searchQuery); // Call function to fetch recipes based on search query
      } else {
        loadRecipes(); // If search query is empty, load all recipes
      }
    });
  };
  
  function loadRecipes() {
    fetchRecipes(''); // Load all recipes initially
  }
  
  function fetchRecipes(searchQuery) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'discover.php?q=' + encodeURIComponent(searchQuery), true); // Pass search query as parameter
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          var recipes = JSON.parse(xhr.responseText);
          displayRecipes(recipes);
        } else {
          console.error('Error fetching recipes:', xhr.status);
        }
      }
    };
    xhr.send();
  }
  
  function displayRecipes(recipes) {
    var recipeGrid = document.getElementById('recipeGrid');
    recipeGrid.innerHTML = ''; // Clear existing content
    recipes.forEach(function(recipe) {
      var recipeCard = document.createElement('div');
      recipeCard.classList.add('recipe-card');
      recipeCard.style.backgroundImage = 'url("' + recipe.image + '")';
  
      var tintFade = document.createElement('div');
      tintFade.classList.add('tint-fade');
      recipeCard.appendChild(tintFade);
  
      var recipeDesc = document.createElement('div');
      recipeDesc.classList.add('recipe-desc');
  
      var title = document.createElement('h3');
      title.classList.add('rufina');
      title.textContent = recipe.title;
      recipeDesc.appendChild(title);
  
      var category = document.createElement('p');
      category.classList.add('lato');
      category.textContent = recipe.category;
      recipeDesc.appendChild(category);
  
      recipeCard.appendChild(recipeDesc);
      recipeGrid.appendChild(recipeCard);
    });
  }
  