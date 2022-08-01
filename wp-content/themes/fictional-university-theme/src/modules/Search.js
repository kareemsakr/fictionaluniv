import $ from "jquery";

class Search {
  // Section 1: describe our object
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = $('#search-overlay__results');
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $('.search-term');
    this.isOpen = false;
    this.typingTimer;
    this.events();
  }

  // Section 2: events
  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keyup", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  isLetterOrNumber(x) {
    return (x >= 48 && x <= 57)  || (x >= 65 && x <= 90) || (x >= 97 && x <= 122) || (x == 8);
  }

  typingLogic(event) {
    if (!this.isLetterOrNumber(event.keyCode)) return;

    clearTimeout(this.typingTimer);
    if(this.resultsDiv.html() !== '<div class="spinner-loader"></div>')
        this.resultsDiv.html('<div class="spinner-loader"></div>');
    this.typingTimer = setTimeout(this.getResults.bind(this),500);
  }

  async getResults () {
    // check functions.js to see where univData camee from
    // try {
        
    //     const searchTerm = this.searchField.val();
    //     const [posts, pages] = await Promise.all([
    //         fetch(`${univData['root_url']}/wp-json/wp/v2/posts?search=${searchTerm}`).then(res => res.json()),
    //         fetch(`${univData['root_url']}/wp-json/wp/v2/pages?search=${searchTerm}`).then(res => res.json()),
    //       ]);
    
    //     const results = [...posts, ...pages];
    
    
            
    //     this.resultsDiv.html(`
    //     <h2 class="search-overlay__section-title">General Information</h2>
    //     <ul class="link-list min-list">
    //         ${results.map((item) => {
    //             return `<li><a href="${item.link}">${item.title.rendered}</a>${item.type === 'post' ? ` by ${item.authorName}` : ''}</li>`;
    //         }).join('')}
    //     </ul>`);
    // } catch (error) {
    //     this.resultsDiv.html(`
    //     <p>An error occured during the search process</p>
    //    `);
    // }


    $.getJSON(univData.root_url + "/wp-json/university/v1/search?term=" + this.searchField.val(), results => {
      console.log(results);
      this.resultsDiv.html(`
        <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${results.generalInfo?.length ? '<ul class="link-list min-list">' : "<p>No general information matches that search.</p>"}
              ${(results.generalInfo || []).map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == "post" ? `by ${item.authorName}` : ""}</li>`).join("")}
            ${results.generalInfo?.length ? "</ul>" : ""}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${results.programs?.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${univData.root_url}/programs">View all programs</a></p>`}
              ${(results.programs || []).map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
            ${results.programs?.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Professors</h2>

          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${results.campuses?.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${univData.root_url}/campuses">View all campuses</a></p>`}
              ${(results.campuses || []).map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
            ${results.campuses?.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Events</h2>
          </div>
        </div>
      `)
    })

  }

  keyPressDispatcher(event) {
    // s key
    if (event.keyCode === 83 && !this.isOpen && !$("input, textarea").is(":focus")) {
        this.openOverlay();
    }
    
    // esc key
    if (event.keyCode === 27 && this.isOpen) {
        this.closeOverlay();
    }
  }

  // Section 3: Methods
  openOverlay() {
      this.searchField.val('');
    this.isOpen = true;
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    setTimeout(() => {
        this.searchField.focus();
    }, 350);

    return false; // now we're working with an a tag so this is like prevent default so the page does not refresh
}

closeOverlay() {
      this.isOpen = false;
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
  }

  addSearchHTML() {
    $('body').append(`
    <div class="search-overlay">
        <div class="search-overlay__top">
            <div class="container">
                <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
                <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
            </div>
        </div>
        <div class="container">
            <div id="search-overlay__results"></div>
        </div>
    </div>`)
  }
}

export default Search;
