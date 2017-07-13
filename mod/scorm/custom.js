/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var isInit = true;

//var transport = new easyXDM.Socket(/** The configuration */{
//    remote: "http://us.tellmemorepro.com/LMS/LmsAccess.aspx?ticket=61cedcd0291f466eb10fe70a56e8eef3",
//
//    //ID of the element to attach the inline frame to
//    container: "rosseta-content",
//    onMessage: function (message, origin) {
//        debugger;
//        var settings = message.split(",");
//        //Use jquery on a masterpage.
//        //$('iframe').height(settings[0]);
//        //$('iframe').width(settings[1]);
//
//        //The normal solution without jquery if not using any complex pages (default)
//        this.container.getElementsByTagName("iframe")[0].style.height = settings[0];
//        this.container.getElementsByTagName("iframe")[0].style.width = settings[1];
//    }
//});

function exitRS(){
    var exitButton = document.getElementById("rosseta-exit");
    exitButton.classList.add('hidden');
    var iframe = document.getElementById("scorm_object");
    iframe.contentWindow.exitRS();
    var iframeRS = document.getElementById("rosseta-window");
    iframeRS.scr = "";
    iframeRS.style.display = "none";
    var scromPage = document.getElementById("scormpage");
    scromPage.style.display = "";
    isInit = true;
}

function resizeIframe(obj){
    if(isInit){
        isInit = false;
        return;
    }
    
    this.focus();
    obj.style.display = "block";
    var exitButton = document.getElementById("rosseta-exit");
    exitButton.classList.remove("hidden");
    var scromPage = document.getElementById("scormpage");
    scromPage.style.display = "none";
    obj.contentWindow.focus();       
};


