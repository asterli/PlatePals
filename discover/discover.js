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
        displayRecipes(recipes);
      } else {
        console.error("Error fetching recipes:", xhr.status);
      }
    }
  };
  xhr.send();
}

function displayRecipes(recipes) {
  var recipeGrid = document.getElementById("recipeGrid");
  recipeGrid.innerHTML = "";
  recipes.forEach(function (recipe) {
    var recipeLink = document.createElement("a");
    recipeLink.href = `../recipepage/recipepage.html?id=${recipe.id}`;
    recipeLink.classList.add("recipe-card-link");

    var recipeCard = document.createElement("div");
    recipeCard.classList.add("recipe-card");
    recipeCard.style.backgroundImage = 'url("' + recipe.image + '")';

    var tintFade = document.createElement("div");
    tintFade.classList.add("tint-fade");
    recipeCard.appendChild(tintFade);

    var recipeDesc = document.createElement("div");
    recipeDesc.classList.add("recipe-desc");

    var title = document.createElement("h3");
    title.classList.add("rufina");
    title.textContent = recipe.title;
    recipeDesc.appendChild(title);

    var category = document.createElement("p");
    category.classList.add("lato");
    category.textContent = recipe.category;
    recipeDesc.appendChild(category);

    recipeCard.appendChild(recipeDesc);
    recipeLink.appendChild(recipeCard);
    recipeGrid.appendChild(recipeLink);
  });
}

// let includedCategory = '';
// initialize();

// function createTag(tagName) {
//     const newButton = document.createElement('button');
//     newButton.classList.add('tag');
//     newButton.textContent = tagName;
//     newButton.addEventListener('click', function() {
//         let url = new URL(window.location.href);
// 		url.searchParams.delete('tag', tagName.trim().toLowerCase());
// 		history.pushState({}, '', url.href);
//         newButton.remove();
//         const indexRemove = includedTags.indexOf(tagName.trim().toLowerCase());
//         if (indexRemove != -1){
//             includedTags.splice(indexRemove, 1);
//         }
//         hideArticles();
//     });
//     return newButton;
// }

// function hideArticles() {
//     const articles = document.querySelectorAll('article');
//     if (includedTags.length == 0) {
//         articles.forEach(article => {
//             article.classList.remove('hidden');
//         });
//         return;
//     } else {
//         let includedArticles = [];
//         articles.forEach(article => {
//             const tags = article.querySelectorAll('.tag');
//             tags.forEach(tag => {
//                 if (includedTags.includes(tag.textContent.trim().toLowerCase())){
//                     includedArticles.push(article);
//                 }
//             });
//             if (includedArticles.includes(article)){
//                 article.classList.remove('hidden');
//             } else {
//                 article.classList.add('hidden');
//             }
//         });
//     }
//     return;
// }

// function addSearchTerm(searchTerm) {
//     const trimTerm = searchTerm.trim().toLowerCase();
//     includedCategory = trimTerm;
//     hideArticles();
//     let url = new URL(window.location.href);
//     url.searchParams.append('category', trimTerm);
//     history.pushState({}, '', url.href);
// }

// function initialize() {
//     const params = new URLSearchParams(window.location.search);
//     params.getAll('category').forEach(category => {
//         addSearchTerm(category.trim().toLowerCase());
//     })
//     return;
// }