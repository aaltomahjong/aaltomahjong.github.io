var successMessage = "";

// Loads a partial html inside the page content
var loadPartial = function() {
  // Get the fragment identifier and remove the '#' at the beginning (default to 'home')
  var hash = window.location.hash.substring(1) || '/home'; 
  // Construct the url for the partial
  var url = "partials" + hash + ".html";
  // Empty the page contents and start loading new partial
  $("#partial-container").empty();
  // Display the loader
  $("#loader").show();
  
  $.ajax({url: url, 
          beforeSend: function( xhr ) {
            xhr.overrideMimeType( "text/html; charset=UTF-8" );
          },
          success: function(data, textStatus, jqXHR) {
            $("#partial-container").html(data);
            updateLanguage();
          }, 
          complete: function() {
            // When done, hide the loader
            $("#loader").hide();
          }});
};

var submitForm = function(id, url, success) {
  var form = $(id);
  form.on('submit', function (e) {
    e.preventDefault();
    // Hide the form while processing and display the loader
    $(".form-container>*").hide();
    $("#form-loader").show();
    $.ajax({
      type: 'POST',
      url: url,
      data: form.serialize(),
      success: success,
      complete: function() {
        // When done, hide the loader and show the form
        $(".form-container>*").show();
        $("#form-loader").hide();
      }
    });
  });
};

var showError = function(selector, errorMsg) {
  var element = $(selector);
  var errorElement = $(selector + "-error");
  element.hide();
  errorElement.text(errorMsg);
  errorElement.addClass("error-message");
  errorElement.show();
}

var clearErrors = function(selectors) {
  for (var i = 0; i < selectors.length; ++i) {
    var element = $(selectors[i]);
    var errorElement = $(selectors[i] + "-error");
    errorElement.hide();
    element.show();
  }
}

var getLanguage = function(){
  if (typeof(Storage) !== "undefined") {
    var lang = localStorage.getItem("lang");
    if (lang) {
      return lang;
    }
  }
  return "fi";
}

var updateLanguage = function() {
  setLanguage(getLanguage());
}

var setLanguage = function(language) {
  $(".fi-text").hide();
  $(".en-text").hide();
  $("." + language + "-text").show();
  if (typeof(Storage) !== "undefined") {
    localStorage.setItem("lang", language);
  }
}

$(document).ready(function() {
  loadPartial();
  
  // If hash change is not supported, make a timer to check if it's changed
  if (!('onhashchange' in window)) {
    var oldHref = location.href;
    setInterval(function() {
      var newHref = location.href;
      if (oldHref !== newHref) {
        oldHref = newHref;
        loadPartial();
      }
    }, 100);
  } else {
    // Otherwise bind the event
    $(window).on('hashchange', function() {
      loadPartial();
    });
  }
  
  updateLanguage();
  
  $("#en-flag.flag").click(function() {
    setLanguage("en");
  });
  
  $("#fi-flag.flag").click(function() {
    setLanguage("fi");
  });
});
