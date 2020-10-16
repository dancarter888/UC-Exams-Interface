/**
 * File containing functions to do with making AJAX requests.
 */

/**
 * Function for making async requests, calls the given callback with the response from the server.
 * @param reqType string The type of request to make.
 * @param url string The url to make the request to.
 * @param callback function The function to call if request is successful.
 */
function makeRequest(reqType, url, callback) {
  url += "&nocache=1";
  $.ajax({
    url: url,
    success: function (result) {
      callback(result);
    },
  });
}

/**
 * Function for making async POST requests, calls the given callback with the response from the server.
 * @param url string The url to make the request to.
 * @param body json The json for the body of the request.
 * @param callback function The function to call if request is successful.
 */
function makePostRequest(url, body, callback) {
  $.ajax({
    url: url,
    type: "post",
    data: {"event": body},
    success: callback
  });
}
