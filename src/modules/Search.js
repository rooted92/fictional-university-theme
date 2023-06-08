// object oriented javascript
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
        this.events();
    }

    // 2. events 
    // here is where you connect dots between object and events it can perform
    // on method changes value of this keyword so add .bind
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keyup", this.keyPressDispatcher.bind(this));
    }

    // 3. methods (function, action...)
    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
    }

    keyPressDispatcher(e) {
        console.log(e.keyCode);
        if (e.keyCode === 83) {
            this.openOverlay();
        } else if (e.keyCode === 27) {
            this.closeOverlay();
        }
    }
}

export default Search;