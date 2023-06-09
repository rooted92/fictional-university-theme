// object oriented javascript
// AVOID dealing with dom when ever possible to keep code fast
import $ from 'jquery';
// create search class
class Search {
    // set constructor function
    // 1. describe and create/initiate our object
    constructor() {
        // your constructor is where you describe/give birth to your object
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
                this.typingTimer = setTimeout(this.getResults.bind(this), 1000);
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }

        }
        this.previousValue = this.searchField.val();// using jquery val method to save value in search field
    }

    getResults() {
        // getJSON takes two args url and function
        // using an arrow function as the call back function does not change the 'this' keyword meaning
        $.getJSON(university_data.root_url + "/wp-json/wp/v2/posts?search=" + this.searchField.val(), (posts) => {
            // within this anonymous function, 'this'keyword will point towards getJSON because that is what executed the method
           
            this.resultsDiv.html(`
                <h2 class="search-overla__section-title">General Information</h2>
                ${posts.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                    ${posts.map(post => `<li><a href=${post.link}>${post.title.rendered}</a></li>` ).join('')}
                ${posts.length ? '</ul>' : ''}
            `);
            this.isSpinnerVisible = false;
        });
    }
}

export default Search;