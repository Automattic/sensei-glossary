/****************************************
 *
 * Glossary Popup
 * Props to Kyle Schaeffer: kyleschaeffer.com for the
 * foundation code
 *
 ****************************************/

/**
 *
 * @param string queryIdentifier a custom jquery identifier to attache the click event listerner to
 */
var senseiPopupPrototype = function( $ , queryIdentifier ) {
	var theLightbox, theShadow;

	// this queryId will be use when setting up the element click event listener
	var queryId = queryIdentifier || 'a[rel="sensei-glossary"] ';

	/**
	 * get the query id
	 *
	 * @return string queryId
	 */
	this.getLinkQueryId = function (){
		return this.queryId;
	}

	/**
	 * This function responds to a popup click
	 *
	 * @param event
	 */
	this.popupClickListener = function( event ){

		// make sure that nothing else happens
		event.preventDefault();

		// retrieve the content data
		popupContent = $( event.target).data( 'glossary-content' );

		//show the popup
		this.glossaryPopup( popupContent );
	}

	// find all the popup links on the page and attach a click event listener
	// and make sure this within the call back always points to the senseiPopup object
	$( queryId ).on( 'click', $.proxy( this.popupClickListener, this ) );

	// display the lightbox
	this.glossaryPopup = function (insertContent) {

		// add lightbox/shadow <div/>'s if not previously added
		if ($('#sensei-glossary-popup').size() == 0) {
			this.theLightbox = $('<div id="sensei-glossary-popup"/>');
			this.theShadow = $('<div id="sensei-glossary-shadow"/>');
			$(theShadow).click(function (e) {
				closeLightbox();
			});
			$('body').append(this.theShadow);
			$('body').append(this.theLightbox);
		}

		// remove any previously added content
		this.theLightbox.empty();

		// insert HTML content
		this.theLightbox.append(insertContent);

		// move the lightbox to the current window top + 100px
		this.theLightbox.css('top', $(window).scrollTop() + 100 + 'px');

		// display the lightbox
		this.theLightbox.show();
		this.theShadow.show();

	}

	// close the lightbox
	 this.closeLightbox =  function() {

		// hide lightbox and shadow <div/>'s
		this.theLightbox.hide();
		this.theShadow.hide();

		// remove contents of lightbox in case a video or other content is actively playing
		this.theLightbox.empty();
	}

} // senseiPopup object declaration

//initiate the object
window.senseiPopup = new senseiPopupPrototype ( jQuery );