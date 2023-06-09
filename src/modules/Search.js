// object oriented javascript
// AVOID dealing with dom when ever possible to keep code fast
import $ from 'jquery';
// create search class
class Search {
    // set constructor function
    // 1. describe and create/initiate our object
    constructor() {
        // your constructor is where you describe/give birth to your object
        this.addSearchHTML();
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $('#search-term'); // now this element is re-usable(improves speed)
        this.resultsDiv = $('#search-overlay__results');
        this.events();
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;
        // make property for input id
        this.typingTimer;
    }

    // 2. events 
    // here is where you connect dots between object and events it can perform
    // on method changes value of this keyword so add .bind
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on('keyup', this.typingLogic.bind(this));
    }

    // 3. methods (function, action...)
    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val('');
        setTimeout(() => { this.searchField.trigger('focus') }, 301)
        this.isOverlayOpen = true;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
    }

    keyPressDispatcher(e) {
        if (e.keyCode === 83 && !this.isOverlayOpen && $('input', 'textarea').is('focus')) {
            this.openOverlay();
        } else if (e.keyCode === 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }

    typingLogic() {
        if (this.searchField.val() !== this.previousValue) {
            clearTimeout(this.typingTimer);// now everytime we press a key we clear/reset the timer

            if (this.searchField.val() !== '') {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }

                // takes two args function and time
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }

        }
        this.previousValue = this.searchField.val();// using jquery val method to save value in search field
    }

    getResults() {
        // j query's version of async functions when() and then()
        // when taks get requests as parameters

        // we no longer need the when then combo because we are using one url for all requests
        $.getJSON(university_data.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
            this.resultsDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${results.general_info.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                        ${results.general_info.map(item => `<li><a href=${item.permalink}>${item.title}</a> ${item.post_type === 'post' ? `by ${item.author_name}` : ''}</li>`).join('')}
                        ${results.general_info.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${university_data.root_url}/programs">View all programs.</a></p>`}
                        ${results.programs.map(item => `<li><a href=${item.permalink}>${item.title}</a></li>`).join('')}
                        ${results.programs.length ? '</ul>' : ''}
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${results.professors.length ? '<ul class="professor-cards">' : `<p>No professors match that search.</p>`}
                        ${results.professors.map(item => `
                        <li class="professor-card__list-item">
                            <a class="professor-card" href="${item.permalink}">
                                <img class="professor-card__image" src="${item.image}" alt="image of professor">
                                <span class="professor-card__name">
                                    ${item.title}
                                </span>
                            </a>
                        </li>
                        `).join('')}
                        ${results.professors.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${university_data.root_url}/programs">View all campuses.</a></p>`}
                        ${results.campuses.map(item => `<li><a href=${item.permalink}>${item.title}</a></li>`).join('')}
                        ${results.campuses.length ? '</ul>' : ''}
                        <h2 class="search-overlay__section-title">Events</h2>

                    </div>
                </div>
            `);
            this.isSpinnerVisible = false;
        });

        // getJSON takes two args url and function
        // using an arrow function as the call back function does not change the 'this' keyword meaning
        // within this anonymous function, 'this'keyword will point towards getJSON because that is what executed the method
    }

    // this method will create the html for overaly div. It is called immediately when search icon is clicked
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
                <div id="search-overlay__results">
                    
                </div>
            </div>
        </div>
        `);
    }
}

export default Search;