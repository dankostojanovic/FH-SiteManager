(function() {
	// Constructor
	this.FloatingModal = function() {
		// Global element references
		this.overlay = null;
		this.floatingModal = null;
		this.closeButton = null;

		// Defaults
		var defaults = {
			className: 'fade-and-drop',
			closeButton: true,
			content: "",
			contentIsFile: false,
			maxWidth: '100%',
			minWidth: '300px',
			overlay: true,
			closeOnOverlayClick: false,
			autoOpen: false,
			contentTitle: "",
		}

		// Override defaults with passed in arguments
		if(arguments[0] && typeof arguments[0] === "object") {
			this.options = extendDefaults(defaults, arguments[0]);
		}

		if(this.options.autoOpen === true) {
			this.open();
		}

		this.transitionEnd = transitionSelect();

		// Create the modal
		buildFloatingModal.call(this);
	}

	/*
		Public Methods
	*/

	FloatingModal.prototype.open = function() {
		// Initialize the events
		initializeEvents.call(this);

		// Browser render new elements.
		window.getComputedStyle(this.floatingModal).height;

		this.floatingModal.className = this.floatingModal.className + (this.floatingModal.offsetHeight > window.innerHeight ? " floating-modal-open floating-modal-anchored" : " floating-modal-open");

		this.overlay.className = this.overlay.className + " floating-modal-open";
	}

	FloatingModal.prototype.close = function() {
		var _ = this;

		// Remove the classes
		this.floatingModal.className = this.floatingModal.className.replace(" floating-modal-open", "");
		this.overlay.className = this.overlay.className.replace(" floating-modal-open", "");

		// Create listeners on the css events
		this.floatingModal.addEventListener(this.transitionEnd, function() {
			_.floatingModal.parentNode.removeChild(_.floatingModal);
		});

		this.overlay.addEventListener(this.transitionEnd, function() {
			if(_.overlay.parentNode) {
				_.overlay.parentNode.removeChild(_.overlay);
			}

			_.overlay.remove();
			_.floatingModal.remove();
		});

		$("body").removeClass("noscroll");
	}

	//	End Public Methods

	/*
		Private Methods
	*/

	function buildFloatingModal() {
		var content = "";
		var contentTitle = "";
		var contentHolder;
		var contentTitleHolder;
		var docFrag;

		// Create document fragment
		docFrag = document.createDocumentFragment();

		// Create Modal
		this.floatingModal = document.createElement("div");
		this.floatingModal.className = "floating-modal " + this.options.className;
		this.floatingModal.style.minWidth = this.options.minWidth;
		this.floatingModal.style.maxWidth = this.options.maxWidth;
		// End Create Modal

		// Create Close Button
		if(this.options.closeButton === true) {
			this.closeButton = document.createElement("button");
			this.closeButton.className = "floating-modal-close close-button";
			this.closeButton.innerHTML = "x";
			this.floatingModal.appendChild(this.closeButton);
		}
		// End Create Close Button

		// Create Overlay
		if(this.options.overlay === true) {
			this.overlay = document.createElement("div");
			this.overlay.className = "floating-modal-overlay " + this.options.className;
			docFrag.appendChild(this.overlay);
		}
		// End Create Overlay

		// Create Content Title Area
		if(typeof this.options.contentTitle === "string") {
			contentTitleHolder = document.createElement("div");
			contentTitleHolder.className = "floating-modal-title-content";

			contentTitle = this.options.contentTitle;

			contentTitleHolder.innerHTML = contentTitle;
			this.floatingModal.appendChild(contentTitleHolder);
		}
		// End Create Content Title Area

		// Create Content Area
		contentHolder = document.createElement("div");
		contentHolder.className = "floating-modal-content";

		if(typeof this.options.content === "string" && this.options.contentIsFile === true) {
			// No allowing me to get the file.
			$(contentHolder).load(this.options.content, function(){
				
			});
		}
		else if(typeof this.options.content === "string") {
			// Input string to content.
			content = this.options.content;
		}
		else {
			// Input innerHTML to content.
			content = this.options.content.innerHTML;
		}

		contentHolder.innerHTML = content;
		this.floatingModal.appendChild(contentHolder);
		// End Create Content Area

		// Append modal to document fragment
		docFrag.appendChild(this.floatingModal);

		// Append fragment to body.
		document.body.appendChild(docFrag);
	}

	function initializeEvents() {
		if(this.closeButton) {
			this.closeButton.addEventListener('click', this.close.bind(this));
		}

		if(this.overlay && this.closeOnOverlayClick) {
			this.overlay.addEventListener('click', this.close.bind(this));
		}
	}

	// End Private Methods

	/*
		Utility Functions
	*/

	// Extend defaults with user options
	function extendDefaults(source, properties) {
		var property;

		for(property in properties) {
			if(properties.hasOwnProperty(property)) {
				source[property] = properties[property];
			}
		}

		return source;
	}

	// Utility method to determine which transistionend event is supported
	function transitionSelect() {
	    var el = document.createElement("div");

	    if (el.style.WebkitTransition) {
	    	return "webkitTransitionEnd";
	    }

	    if (el.style.OTransition) {
	    	return "oTransitionEnd";
	    }

	    return 'transitionend';
	}

	// End Utility Functions
}());