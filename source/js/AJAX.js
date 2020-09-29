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

function makePostRequest(url, body, callback) {
  let params = "url=" + JSON.stringify(body);
  console.log(params);
  let request = new asyncRequest();
  request.open("POST", url, true);
  request.onreadystatechange = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        if (this.responseText != null) {
          callback(this.responseText);
        } else alert("Communication error: No data received");
      } else alert("Communication error: " + this.statusText);
    }
  };
  request.send(params);
}

function asyncRequest() {
  let request;
  try {
    request = new XMLHttpRequest();
  } catch (e1) {
    try {
      request = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e2) {
      try {
        request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e3) {
        request = false;
      }
    }
  }

  return request;
}
