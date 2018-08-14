   var searchTimer = 0;
   var hideTimer = 0;
   var hideFlag = 0;
   var sElement = 0;
   var stVal = 0;
   var sdVal = 0;
   var sOutput = 0;

   function setSearchVars() {
    if(sElement != document.getElementById("q")) {
     hideFlag = 1;
    }
    sElement = document.getElementById("q");
    stVal = document.getElementById("t").value;
    sdVal = document.getElementById("d").value;
    sOutput = document.getElementById("search-output");
    sOutput.onmouseover = "";
   }

   function setEmbeddedSearchVars(element, tVal, dVal, iVal) {
    if(sElement != element) {
     hideFlag = 1;
    }
    sElement = element;
    stVal = tVal;
    sdVal = dVal;
    sOutput = document.getElementById("search-output");
    sOutput.onmouseover = function(event) {
     var obj = event.target;
     while(typeof obj.name == 'undefined') {
      obj = obj.parent;
      if(obj == sOutput) {
       return;
      }
     }
     sElement.value = obj.name;
    };
   }

   function hideSearchNow() {
    sOutput.style.display = "none";
   }

   function hideSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(hideSearchNow, 150);
   }

   function unhideSearch() {
    if(hideFlag == 1) {
     hideFlag = 0;
     return;
    }
    clearTimeout(hideTimer);
    if(!(sOutput.innerHTML == "")) {
     sOutput.style.display = "block";
    }
   }

   function fixPosition() {
    var p = getPosition(sElement);
    sOutput.style.top = (p.y + sElement.offsetHeight + window.pageYOffset) + "px";
    sOutput.style.left = p.x + window.pageXOffset + "px";
   }

   function getPosition(element) {
    var xPosition = element.offsetLeft + element.clientLeft;
    var yPosition = element.offsetTop + element.clientTop;
    element = element.offsetParent;
    while(element) {
     xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
     yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
     element = element.offsetParent;
    }
    return { x: xPosition, y: yPosition };
   }

   function showSearch() {
    if(sElement.value == "") {
     sOutput.innerHTML = "";
     sOutput.style.border = "0px";
     sOutput.style.display = "none";
     return;
    }
    if(window.XMLHttpRequest) {
     xmlhttp = new XMLHttpRequest();
    } else {
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
     if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      sOutput.innerHTML = xmlhttp.responseText;
      sOutput.style.border = "1px solid black";
      document.body.onresize = fixPosition;
      fixPosition();
      sOutput.style.display = "block";
      eval(sOutput.innerHTML);
     }
    };
    query = "q=" + sElement.value + "&t=" + stVal + "&d=" + sdVal;
    xmlhttp.open("GET", "search.php?" + query, true);
    xmlhttp.send();
   }

   function setSearchTimer() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(showSearch, 500);
   }

   function setQuery(val) {
    alert(val);
    sElement.value = val;
   }