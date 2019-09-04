










function getUserIP(onNewIP) { 
    //compatibility for firefox and chrome
    var myPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;
    var pc = new myPeerConnection({
    iceServers: []
    }),
    noop = function() {},
    localIPs = {},
    ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/g,
    key;

    function iterateIP(ip) {
    if (!localIPs[ip]) onNewIP(ip);
    localIPs[ip] = true;
    }

    //create a bogus data channel
    pc.createDataChannel("");

    // create offer and set local description
    pc.createOffer().then(function(sdp) {
    sdp.sdp.split('\n').forEach(function(line) {
    if (line.indexOf('candidate') < 0) return;
    line.match(ipRegex).forEach(iterateIP);
    });

    pc.setLocalDescription(sdp, noop, noop);
    }).catch(function(reason) {
    // An error occurred, so handle the failure to connect
    });

    //listen for candidate events
    pc.onicecandidate = function(ice) {
    if (!ice || !ice.candidate || !ice.candidate.candidate || !ice.candidate.candidate.match(ipRegex)) return;
    ice.candidate.candidate.match(ipRegex).forEach(iterateIP);
    };
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*function getCourseId() {
    var vars = {};
    var items = document.getElementsByClassName('list-group-item list-group-item-action');
    console.log(items);
    for (var i = 0; i < items.length; i++)
    {
        if(items[i].getAttribute("data-key") == "coursehome")
        {
            var parts = items[i].href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
        }
    }
    return false;
}

function getUserId() {
    var vars = {};
    var items = document.getElementsByClassName('dropdown-item menu-action');
    for (var i = 0; i < items.length; i++)
    {
        if(items[i].getAttribute("data-title") == "profile,moodle")
        {
            var parts = items[i].href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
        }
    }
    return false;
}*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function publicIP(){
    if (window.XMLHttpRequest) xmlhttp = new XMLHttpRequest();
        else xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.open("GET",'https://api.ipify.org',false);
        xmlhttp.send();
        return xmlhttp.responseText;
} 


var action = function(callback) {
    if ('function' == typeof callback) {
      var value = settings.track_links_timeout;
      return '[object Number]' != toString.call(value) ? (global.setTimeout(callback), void 0) : (global.setTimeout(callback, value), void 0);
    }
}
// ***************************************    *************************  
// ***************************************  ***************************
// ***************************************      ********************** 

    var clientPrivateIP  = '';
    var getCourseId_var = '';


//////////////////////////////////////////////////////
    //var coursename = '';
    //var username = '';
    //var company = '';
//////////////////////////////////////////////////////////

    getUserIP(function(ip){
      clientPrivateIP = ip;          
    });  


function checkVariable() {
   //getCourseId_var = getCourseId().id;
   //console.log("hiii :) : " + getCourseId_var);
   if (clientPrivateIP != '') {       
       last_me(clientPrivateIP,getCourseId_var);       
   }   
 }

setTimeout(checkVariable, 2000);


/*var refreshIntervalId = setInterval(checkVariable, 1000);
if (clientPrivateIP != '' ){clearInterval(refreshIntervalId);}*/
//////////////////////////////////////////////////////////////////////////////
function last_me(clientPrivateIP1,getCourseId_var1){
    //var courseID='';
    var userID='';
    
    var clientPublicIP='';
//console.log('COMPANY INSIDE      '+companyname);
    clientPublicIP = publicIP();
    
    var clientSite = window.location.host;
    var clientSiteTitle = document.title;
    //alert("clientPrivateIP2"+clientPrivateIP);
    //console.log(clientPrivateIP1);
    //*********************

    var path = 'https://lms.sunshowerlearning.com/report/scorm_track/WebTrackHandling.php?clientsite='+escape(clientSite)+'&clientprivateip='+clientPrivateIP1+'&clientpublicip='+clientPublicIP+'&clientsitetitle='+escape(clientSiteTitle)+'&coursename='+escape(coursename)+'&company='+escape(companyname);
        
        path = path.trim();

      // console.log(path);

             var xhr = new XMLHttpRequest();
             xhr.onreadystatechange = function () {
                 if (xhr.readyState === 4) {
                     if(xhr.status == 200)
                     {
                        
                     }
                 }
             }
             xhr.open('GET', path, true);
             xhr.withCredentials = true;
             xhr.send();
}


//************************************************************
// ********************************************************************    
var defaultEventName = 'pageview';
var defaultTrackName = 'sp_alias';

var sp = (function(self, undefined) {
  var host;
  var async = true;
  var global = window;
  var location = document.location;
  var withCredentials = global.XMLHttpRequest && 'withCredentials' in new XMLHttpRequest();
  var toString = Object.prototype.toString;
  var settings = {
    api_host : ('https:' == location.protocol ? 'https://' : 'http://') + location.hostname + location.pathname,
    track_pageview : true,
    track_links_timeout : 300,
  };

  var doTrackPageView = false;
  var functionName = '';
  var queueSize = self.length;
  for (var i=0; queueSize > i; i++) {
    functionName = self[i][0];
    if ('pageview' == functionName) {
      doTrackPageView = true;
    }
    self[functionName].apply(null, self[i].slice(1));
  }

  self.pageview = function(url) {
    var props = {
      url : url || location.href,
      name : document.title,
      referrer : document.referrer
    };
    if (settings.track_pageview) {   
      return self.track(defaultEventName, props);
    }
    return 1;
};


  self.track = function(event, properties, callback) {
    properties = properties || {};  
    var props = {
      e : event,
      t : (new Date()).toISOString(),
      kv : properties
    };
    return action(callback), self;
  };

  return self.splice(0, queueSize), !doTrackPageView && (settings.track_pageview && self.pageview()), self;
})(sp || []);
