YUI.add("moodle-mod_dialogue-clickredirector",function(e,t){M.mod_dialogue=M.mod_dialogue||{},M.mod_dialogue.clickredirector={cmid:null,modroot:M.cfg.wwwroot+"/mod/dialogue/",init:function(t){this.cmid=t;var n=e.all(".conversation-list tr");n.on("click",e.bind(this.handle,this))},handle:function(e){var t=[];t.id=this.cmid,page=e.currentTarget.getAttribute("data-redirect");if(!page)return new M.core.exception({name:"data-redirect not defined as attribute"});action=e.currentTarget.getAttribute("data-action"),action&&(t.action=action),conversationid=e.currentTarget.getAttribute("data-conversationid"),conversationid&&(t.conversationid=conversationid),messageid=e.currentTarget.getAttribute("data-messageid"),messageid&&(t.messageid=messageid);var n=[];for(var r in t)n.push(r+"="+t[r]);return redirect=this.modroot+page+".php?"+n.join("&"),e.ctrlKey||e.metaKey?(window.getSelection&&window.getSelection().removeAllRanges(),window.open(redirect,"_blank")):window.location.href=redirect}}},"@VERSION@",{requires:["base","node","json-parse","clickredirector","clickredirector-filters","clickredirector-highlighters","event","event-key"]});