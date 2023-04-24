define(["require","exports","../shared/AntragsgruenEditor"],(function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.MotionMergeAmendments=t.MotionMergeChangeActions=void 0;const n=4,s=6,i=17,r=8,d=9;var o;!function(e){e.ORIGINAL="orig",e.PROPOSED_PROCEDURE="prop"}(o||(o={}));class h{static init(e,t,a){h.statuses=e,h.versions=t,h.votingData=a,Object.keys(e).forEach((e=>{h.statusListeners[e]=[]}))}static registerNewAmendment(e,t,a,n){h.statuses[e]=t,h.versions[e]=a,h.votingData[e]=n,h.statusListeners[e]=[],console.log("registered new amendment status",h.statuses,h.versions,h.votingData)}static deleteAmendment(e){delete h.statuses[e],delete h.versions[e],delete h.votingData[e]}static getAmendmentStatus(e){return h.statuses[e]}static getAmendmentVersion(e){return h.versions[e]}static getAmendmentVotingData(e){return h.votingData[e]}static registerParagraph(e,t){h.statusListeners[e].push(t)}static setStatus(e,t){h.statuses[e]=t,h.statusListeners[e].forEach((a=>{a.onAmendmentStatusChanged(e,t)}))}static setVersion(e,t){h.versions[e]=t,h.statusListeners[e].forEach((a=>{a.onAmendmentVersionChanged(e,t)}))}static setVotesData(e,t){h.votingData[e]=t,h.statusListeners[e].forEach((a=>{a.onAmendmentVotingChanged(e,t)}))}static getAmendmentIds(){return Object.keys(h.statuses).map((e=>parseInt(e,10)))}static getAllStatuses(){return h.statuses}static getAllVersions(){return h.versions}static getAllVotingData(){return h.votingData}}h.statusListeners={};class l{static removeEmptyParagraphs(){$(".texteditor").each(((e,t)=>{0==t.childNodes.length&&$(t).remove()}))}static accept(e,t=null){let a=$(e);a.hasClass("ice-ins")&&l.insertAccept(e,t),a.hasClass("ice-del")&&l.deleteAccept(e,t)}static reject(e,t=null){let a=$(e);a.hasClass("ice-ins")&&l.insertReject(a,t),a.hasClass("ice-del")&&l.deleteReject(a,t)}static insertReject(e,t=null){let a,n=e[0].nodeName.toLowerCase();a="li"==n?e.parent():e,"ul"==n||"ol"==n||"li"==n||"blockquote"==n||"pre"==n||"p"==n?(a.css("overflow","hidden").height(a.height()),a.animate({height:"0"},250,(()=>{a.remove(),$(".collidingParagraph:empty").remove(),l.removeEmptyParagraphs(),t&&t()}))):(a.remove(),t&&t())}static insertAccept(e,t=null){let a=$(e);a.removeClass("ice-cts ice-ins appendHint moved"),a.removeAttr("data-moving-partner data-moving-partner-id data-moving-partner-paragraph data-moving-msg"),"ul"!=e.nodeName.toLowerCase()&&"ol"!=e.nodeName.toLowerCase()||a.children().removeClass("ice-cts").removeClass("ice-ins").removeClass("appendHint"),"li"==e.nodeName.toLowerCase()&&a.parent().removeClass("ice-cts").removeClass("ice-ins").removeClass("appendHint"),"ins"==e.nodeName.toLowerCase()&&a.replaceWith(a.html()),t&&t()}static deleteReject(e,t=null){e.removeClass("ice-cts ice-del appendHint"),e.removeAttr("data-moving-partner data-moving-partner-id data-moving-partner-paragraph data-moving-msg");let a=e[0].nodeName.toLowerCase();"ul"!=a&&"ol"!=a||e.children().removeClass("ice-cts").removeClass("ice-del").removeClass("appendHint"),"li"==a&&e.parent().removeClass("ice-cts").removeClass("ice-del").removeClass("appendHint"),"del"==a&&e.replaceWith(e.html()),t&&t()}static deleteAccept(e,t=null){let a,n=e.nodeName.toLowerCase();a="li"==n?$(e).parent():$(e),"ul"==n||"ol"==n||"li"==n||"blockquote"==n||"pre"==n||"p"==n?(a.css("overflow","hidden").height(a.height()),a.animate({height:"0"},250,(()=>{a.remove(),$(".collidingParagraph:empty").remove(),l.removeEmptyParagraphs(),t&&t()}))):(a.remove(),t&&t())}}t.MotionMergeChangeActions=l;class c{constructor(e,t,a,n){this.$element=e,this.parent=n;let s=null,i=null;e.popover({container:"body",animation:!1,trigger:"manual",placement:function(n){let r=$(n);return r.data("element",e),window.setTimeout((()=>{let n=r.width(),d=e.offset().top,o=e.height();null===s&&n>0&&(s=t-n/2,i=a+10,i<d+19&&(i=d+19),i>d+o&&(i=d+o)),r.css("left",s+"px"),r.css("top",i+"px")}),1),"bottom"},html:!0,content:this.getContent.bind(this)}),e.popover("show"),e.find("> .popover").on("mousemove",(e=>{e.stopPropagation()})),window.setTimeout(this.removePopupIfInactive.bind(this),1e3)}getContent(){let e,t=this.$element,a=t.data("cid"),n=1===t.data("appended-collision")||1===t.parent().data("appended-collision"),s=1===t.data("is-modu");null==a&&(a=t.parent().data("cid")),t.parents(".texteditor").first().find("[data-cid="+a+"]").addClass("hover"),e="",n&&(e+='<div class="mergingPopoverCollisionHint">⚠️ '+__t("merge","mergedCollisionHint")+"</div>"),e+='<div class="mergingPopoverButtons">',e+='<button type="button" class="accept btn btn-sm btn-default"></button>',e+='<button type="button" class="reject btn btn-sm btn-default"></button>',e+='<a href="#" class="btn btn-small btn-default opener" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a>',e+='<div class="initiator" style="font-size: 0.8em;"></div>',e+="</div>";let i=$(e);if(i.find(".opener").attr("href",t.data("link")).attr("title",__t("merge","title_open_in_blank")),s?i.find(".initiator").text(__t("merge","modU")):i.find(".initiator").text(__t("merge","initiated_by")+": "+t.data("username")),t.hasClass("ice-ins"))i.find("button.accept").text(__t("merge","change_accept")).on("click",this.accept.bind(this)),i.find("button.reject").text(__t("merge","change_reject")).on("click",this.reject.bind(this));else if(t.hasClass("ice-del"))i.find("button.accept").text(__t("merge","change_accept")).on("click",this.accept.bind(this)),i.find("button.reject").text(__t("merge","change_reject")).on("click",this.reject.bind(this));else if("li"==t[0].nodeName.toLowerCase()){let e=t.parent();e.hasClass("ice-ins")||e.hasClass("ice-del")?(i.find("button.accept").text(__t("merge","change_accept")).on("click",this.accept.bind(this)),i.find("button.reject").text(__t("merge","change_reject")).on("click",this.reject.bind(this))):console.log("unknown",e)}else console.log("unknown",t),alert("unknown");return i}removePopupIfInactive(){return this.$element.is(":hover")||$("body").find(".popover:hover").length>0?window.setTimeout(this.removePopupIfInactive.bind(this),1e3):void this.destroy()}affectedChangesets(){let e=this.$element.data("cid");return null==e&&(e=this.$element.parent().data("cid")),this.$element.parents(".texteditor").find("[data-cid="+e+"]")}performActionWithUI(e){let t=window.scrollX,a=window.scrollY;this.parent.saveEditorSnapshot(),this.destroy(),e.call(this),this.parent.focusTextarea(),window.scrollTo(t,a)}accept(){this.performActionWithUI((()=>{this.affectedChangesets().each(((e,t)=>{l.accept(t,(()=>{this.parent.onChanged()}))}))}))}reject(){this.performActionWithUI((()=>{this.affectedChangesets().each(((e,t)=>{l.reject(t,(()=>{this.parent.onChanged()}))}))}))}destroy(){this.$element.popover("hide").popover("destroy");let e=this.$element.data("cid");null==e&&(e=this.$element.parent().data("cid"));let t=!1;this.$element.parents(".texteditor").first().find("[data-cid="+e+"]").each(((e,a)=>{$(a).is(":hover")&&(t=!0)})),t||this.$element.parents(".texteditor").first().find("[data-cid="+e+"]").removeClass("hover");try{$(".popover").each(((e,t)=>{const a=$(t);a.data("element").is(":hover")||(a.popover("hide").popover("destroy"),a.remove(),console.warn("Removed stale window: ",a))}))}catch(e){}}}class m{prepareText(e){let t=$("<div>"+e+"</div>");t.find("ul.appendHint, ol.appendHint").each(((e,t)=>{let a=$(t),n=a.data("append-hint");a.find("> li").addClass("appendHint").attr("data-append-hint",n).attr("data-link",a.data("link")).attr("data-username",a.data("username")),a.removeClass("appendHint").removeData("append-hint")})),t.find(".moved .moved").removeClass("moved"),t.find(".moved").each(this.markupMovedParagraph.bind(this));let a=t.html();this.texteditor.setData(a),this.unchangedText=this.normalizeHtml(this.texteditor.getData()),this.texteditor.fire("saveSnapshot"),this.onChanged()}addChangedListener(e){this.changedListeners.push(e)}markupMovedParagraph(e,t){let a,n=$(t),s=n.data("moving-partner-paragraph");a=n.hasClass("inserted")?__t("std","moved_paragraph_from"):__t("std","moved_paragraph_to"),a=a.replace(/##PARA##/,s+1),"LI"===n[0].nodeName&&(n=n.parent()),n.attr("data-moving-msg",a)}initializeTooltips(){this.$holder.on("mouseover",".appendHint",(e=>{const t=$(e.currentTarget);t.parents(".paragraphWrapper").first().find(".amendmentStatus.open").length>0||(p.activePopup&&p.activePopup.destroy(),p.activePopup=new c(t,e.pageX,e.pageY,this))}))}acceptAll(){this.saveEditorSnapshot(),this.$holder.find(".ice-ins").each(((e,t)=>{l.insertAccept(t)})),this.$holder.find(".ice-del").each(((e,t)=>{l.deleteAccept(t)})),this.onChanged(),window.setTimeout((()=>{this.onChanged(),this.saveEditorSnapshot()}),1e3)}rejectAll(){this.saveEditorSnapshot(),this.$holder.find(".ice-ins").each(((e,t)=>{l.insertReject($(t))})),this.$holder.find(".ice-del").each(((e,t)=>{l.deleteReject($(t))})),this.onChanged(),window.setTimeout((()=>{this.onChanged(),this.saveEditorSnapshot()}),1e3)}saveEditorSnapshot(){this.texteditor.fire("saveSnapshot")}focusTextarea(){}getContent(){return this.texteditor.getData()}getUnchangedContent(){return this.unchangedText}setText(e){this.prepareText(e),this.initializeTooltips()}normalizeHtml(e){const t={"&nbsp;":" ","&ndash;":"-","&auml;":"ä","&ouml;":"ö","&uuml;":"ü","&Auml;":"Ä","&Ouml;":"Ö","&Uuml;":"Ü","&szlig;":"ß","&bdquo;":"„","&ldquo;":"“","&bull;":"•","&sect;":"§","&eacute;":"é","&rsquo;":"’","&euro;":"€"};return Object.keys(t).forEach((a=>{e=e.replace(new RegExp(a,"g"),t[a])})),e.replace(/\s+</g,"<").replace(/>\s+/g,">").replace(/<[^>]*ice-ins[^>]*>/g,"ice-ins").replace(/<ins[^>]*>/g,"ice-ins").replace(/<[^>]*>/g,"")}onChanged(){this.normalizeHtml(this.texteditor.getData())===this.unchangedText?(this.$changedIndicator.addClass("unchanged"),this.hasChanged=!1):(this.$changedIndicator.removeClass("unchanged"),this.hasChanged=!0),this.$holder.find(".ice-ins").length>0||this.$holder.find(".ice-del").length>0?this.$mergeActionHolder.removeClass("hidden"):this.$mergeActionHolder.addClass("hidden"),this.changedListeners.forEach((e=>e()))}hasChanges(){return this.hasChanged}constructor(e,t,n){this.$holder=e,this.$changedIndicator=t,this.$mergeActionHolder=n,this.unchangedText=null,this.hasChanged=!1,this.changedListeners=[];let s=e.find(".texteditor"),i=new a.AntragsgruenEditor(s.attr("id"));this.texteditor=i.getEditor(),this.setText(this.texteditor.getData()),e.data("unchanged")&&(this.unchangedText=e.data("unchanged"),this.onChanged()),this.texteditor.on("change",this.onChanged.bind(this))}}class g{constructor(e,t,a){this.$holder=e,this.hasUnsavedChanges=!1,this.handledCollisions=[],this.sectionId=parseInt(e.data("sectionId")),this.paragraphId=parseInt(e.data("paragraphId"));const n=t.paragraphs&&t.paragraphs[this.sectionId+"_"+this.paragraphId]?t.paragraphs[this.sectionId+"_"+this.paragraphId]:null;n.handledCollisions&&(this.handledCollisions=n.handledCollisions);const s=e.find(".wysiwyg-textarea"),i=e.find(".changedIndicator"),r=e.find(".mergeActionHolder");this.textarea=new m(s,i,r),this.initButtons(),this.initSetCollisionsAsHandled(),this.initStatusWidget(a),e.find(".amendmentStatus").each(((e,t)=>{h.registerParagraph($(t).data("amendment-id"),this)})),this.textarea.addChangedListener((()=>this.hasUnsavedChanges=!0))}initStatusWidget(e){const t=this.$holder.find(".changeToolbar .statuses").data("amendments");for(let a=0;a<t.length;a++){const n=t[a].amendmentId;t[a].amendment=e.find((e=>e.id===n)),t[a].status=h.getAmendmentStatus(n),t[a].version=h.getAmendmentVersion(n),t[a].votingData=JSON.parse(JSON.stringify(h.getAmendmentVotingData(n)))}const a=this,o=e=>{a.textarea.hasChanges()?bootbox.confirm(__t("merge","reloadParagraph"),(t=>{t&&e()})):e()};a.statusWidget=Vue.createApp({template:'\n                <div class="statuses">\n                    <paragraph-amendment-settings v-for="data in amendmentParagraphData"\n                                                  v-bind:amendment="data.amendment"\n                                                  v-bind:nameBase="data.nameBase"\n                                                  v-bind:idAdd="data.idAdd"\n                                                  v-bind:active="data.active"\n                                                  v-bind:status="data.status"\n                                                  v-bind:version="data.version"\n                                                  v-bind:votingData="data.votingData"\n                                                  v-on:update="update($event)"\n                    ></paragraph-amendment-settings>\n                </div>',data:()=>({amendmentParagraphData:t}),methods:{getAllAmendmentData(){return this.amendmentParagraphData},getAmendmentData(e){for(let t=0;t<this.amendmentParagraphData.length;t++)if(this.amendmentParagraphData[t].amendmentId==e)return this.amendmentParagraphData[t];return null},setAmendmentActive(e,t){e.active=t,a.reloadText()},update(e){const t=e[0],n=e[1],s=this.getAmendmentData(n);if(s){switch(t){case"set-active":o((()=>this.setAmendmentActive(s,e[2])));break;case"set-status":h.setStatus(n,parseInt(e[2]));break;case"set-votes":h.setVotesData(n,e[2]);break;case"set-version":o((()=>{h.setVersion(n,e[2]),a.reloadText()}))}a.hasUnsavedChanges=!0}},onStatusUpdated(e,t){const o=this.getAmendmentData(e);o&&(o.status=t,a.textarea.hasChanges()||(o.active=-1!==[n,s,i,r,d].indexOf(t),a.reloadText()))},onVotingUpdated(e,t){const a=this.getAmendmentData(e);a&&(a.votingData=t)},onVersionUpdated(e,t){const n=this.getAmendmentData(e);n&&(n.version=t,a.textarea.hasChanges()||a.reloadText())},onAmendmentAdded(e,t,a,n,s,i,r){this.amendmentParagraphData.push({amendmentId:e.id,amendment:e,nameBase:t,idAdd:a,active:n,status:s,verstion:i,votingData:r})},onAmendmentDeleted(e){this.amendmentParagraphData=this.amendmentParagraphData.filter((t=>t.amendmentId!=e))}}}),a.statusWidget.config.compilerOptions.whitespace="condense",window.__initVueComponents(a.statusWidget,"merging"),a.statusWidgetComponent=a.statusWidget.mount(this.$holder.find(".changeToolbar .statuses")[0])}onAmendmentVersionChanged(e,t){this.statusWidgetComponent.onVersionUpdated(e,t)}onAmendmentVotingChanged(e,t){this.statusWidgetComponent.onVotingUpdated(e,t)}onAmendmentStatusChanged(e,t){this.statusWidgetComponent.onStatusUpdated(e,t)}onAmendmentAdded(e,t,a,n,s,i,r){this.statusWidgetComponent.onAmendmentAdded(e,t,a,n,s,i,r)}onAmendmentDeleted(e){this.statusWidgetComponent.onAmendmentDeleted(e)}initSetCollisionsAsHandled(){this.$holder.on("click","button.hideCollision",(e=>{const t=$(e.currentTarget).parents(".collidingParagraph").first(),a=parseInt(t.data("amendment-id"),10),n=t.parent();t.remove(),0===n.children().length&&this.$holder.removeClass("hasCollisions"),this.handledCollisions.push(a),this.hasUnsavedChanges=!0}))}initButtons(){this.$holder.find(".mergeActionHolder .acceptAll").on("click",(e=>{e.preventDefault(),this.textarea.acceptAll(),this.hasUnsavedChanges=!0})),this.$holder.find(".mergeActionHolder .rejectAll").on("click",(e=>{e.preventDefault(),this.textarea.rejectAll(),this.hasUnsavedChanges=!0}))}reloadText(){const e=this.statusWidgetComponent.getAllAmendmentData().filter((e=>e.active)).map((e=>({id:e.amendmentId,version:h.getAmendmentVersion(e.amendmentId)}))),t=this.$holder.data("reload-url").replace("DUMMY",JSON.stringify(e));$.get(t,(e=>{this.textarea.setText(e.text);let t="";e.collisions.forEach((e=>{t+=e})),this.$holder.find(".collisionsHolder").html(t),e.collisions.length>0?this.$holder.addClass("hasCollisions"):this.$holder.removeClass("hasCollisions"),this.handledCollisions=[],this.hasUnsavedChanges=!0}))}getDraftData(){return{amendmentToggles:this.statusWidgetComponent.getAllAmendmentData().filter((e=>e.active)).map((e=>e.amendmentId)),text:this.textarea.getContent(),unchanged:this.textarea.getUnchangedContent(),handledCollisions:this.handledCollisions}}onDraftChanged(){this.hasUnsavedChanges=!1}}class p{constructor(e){this.paragraphsByTypeAndNo={},this.hasUnsavedChanges=!1,p.$form=e;const t=JSON.parse(document.getElementById("mergeDraft").getAttribute("value"));h.init(t.amendmentStatuses,t.amendmentVersions,t.amendmentVotingData);const a=e.data("amendment-static-data");$(".paragraphWrapper").each(((e,n)=>{const s=$(n),i=s.data("section-id"),r=s.data("paragraph-id");s.find(".wysiwyg-textarea").on("mousemove",(e=>{p.currMouseX=e.offsetX})),this.paragraphsByTypeAndNo[i+"_"+r]=new g(s,t,a)})),p.$form.on("submit",(()=>{this.hasUnsavedChanges=!0,this.saveDraft(!0),$(window).off("beforeunload",p.onLeavePage)})),$(window).on("beforeunload",p.onLeavePage),this.initDraftSaving(),this.initNewAmendmentAlert(),this.initCheckBackendStatus(),this.initRemovingSectionTexts(),this.initProtocol()}static onLeavePage(){return __t("std","leave_changed_page")}setDraftDate(e){this.$draftSavingPanel.find(".lastSaved .none").hide();let t=$("html").attr("lang"),a=new Intl.DateTimeFormat(t,{year:"numeric",month:"numeric",day:"numeric",hour:"numeric",minute:"numeric",hour12:!1}).format(e);this.$draftSavingPanel.find(".lastSaved .value").text(a)}initRemovingSectionTexts(){p.$form.find(".removeSection input[type=checkbox]").on("change",(e=>{const t=$(e.currentTarget),a=t.parents(".section").first();t.prop("checked")?a.find(".sectionHolder").addClass("hidden"):a.find(".sectionHolder").removeClass("hidden")})).trigger("change")}initProtocol(){const e=$("#protocol_text_wysiwyg");e.attr("contenteditable","true");const t=new a.AntragsgruenEditor(e.attr("id")).getEditor();e.parents("form").on("submit",(()=>{e.parent().find("textarea").val(t.getData())}))}saveDraft(e=!1){if(0===Object.keys(this.paragraphsByTypeAndNo).map((e=>this.paragraphsByTypeAndNo[e])).filter((e=>e.hasUnsavedChanges)).length&&!this.hasUnsavedChanges)return;console.log("Has unsaved changes");const t=$("input[name=protocol_public]:checked").val(),a={amendmentStatuses:h.getAllStatuses(),amendmentVersions:h.getAllVersions(),amendmentVotingData:h.getAllVotingData(),paragraphs:{},sections:{},removedSections:[],protocol:CKEDITOR.instances.protocol_text_wysiwyg.getData(),protocolPublic:1===parseInt(t)};$(".sectionType0").each(((e,t)=>{const n=$(t),s=n.data("section-id");a.sections[s]=n.find(".form-control").val()})),p.$form.find(".removeSection input[type=checkbox]:checked").each(((e,t)=>{a.removedSections.push(parseInt($(t).val()))})),Object.keys(this.paragraphsByTypeAndNo).forEach((e=>{a.paragraphs[e]=this.paragraphsByTypeAndNo[e].getDraftData()}));let n=this.$draftSavingPanel.find("input[name=public]").prop("checked");const s=JSON.stringify(a);document.getElementById("mergeDraft").setAttribute("value",s),e||$.ajax({type:"POST",url:p.$form.data("draftSavingUrl"),data:{public:n?1:0,data:s,_csrf:p.$form.find("> input[name=_csrf]").val()},success:e=>{e.success?(this.$draftSavingPanel.find(".savingError").addClass("hidden"),this.setDraftDate(new Date(e.date)),n?p.$form.find(".publicLink").removeClass("hidden"):p.$form.find(".publicLink").addClass("hidden")):(this.$draftSavingPanel.find(".savingError").removeClass("hidden"),this.$draftSavingPanel.find(".savingError .errorNetwork").addClass("hidden"),this.$draftSavingPanel.find(".savingError .errorHolder").text(e.error).removeClass("hidden")),Object.keys(this.paragraphsByTypeAndNo).forEach((e=>this.paragraphsByTypeAndNo[e].onDraftChanged())),this.hasUnsavedChanges=!1},error:()=>{this.$draftSavingPanel.find(".savingError").removeClass("hidden"),this.$draftSavingPanel.find(".savingError .errorNetwork").removeClass("hidden"),this.$draftSavingPanel.find(".savingError .errorHolder").text("").addClass("hidden")}})}initAutosavingDraft(){let e=this.$draftSavingPanel.find("input[name=autosave]");if(window.setInterval((()=>{e.prop("checked")&&this.saveDraft(!1)}),5e3),localStorage){let t=localStorage.getItem("merging-draft-auto-save");null!==t&&e.prop("checked","1"==t)}e.on("change",(()=>{let t=e.prop("checked");localStorage&&localStorage.setItem("merging-draft-auto-save",t?"1":"0")})).trigger("change")}initDraftSaving(){if(this.$draftSavingPanel=p.$form.find("#draftSavingPanel"),this.$draftSavingPanel.find(".saveDraft").on("click",(()=>{this.hasUnsavedChanges=!0,this.saveDraft(!1)})),this.$draftSavingPanel.find("input[name=public]").on("change",(()=>{this.hasUnsavedChanges=!0,this.saveDraft(!1)})),this.initAutosavingDraft(),this.$draftSavingPanel.data("resumed-date")){let e=new Date(this.$draftSavingPanel.data("resumed-date"));this.setDraftDate(e)}$(".sectionType0").on("change",(()=>this.hasUnsavedChanges=!0)),$("#yii-debug-toolbar").remove()}initNewAmendmentAlert(){this.$newAmendmentAlert=p.$form.find("#newAmendmentAlert"),this.$newAmendmentAlert.find(".closeLink").on("click",(()=>{this.$newAmendmentAlert.find(".buttons").children().remove(),this.$newAmendmentAlert.removeClass("revealed"),window.setTimeout((()=>{this.$newAmendmentAlert.addClass("hidden")}),1e3)}))}alertAboutNewAmendment(e,t){const a=this.$newAmendmentAlert.find(".buttons"),n=$('<button class="btn-link gotoAmendment" type="button"></button>').text(t);n.on("click",(()=>{$(".amendmentStatus"+e).first().parents(".paragraphWrapper").scrollintoview({top_offset:-100})})),a.append(n),a.children().length>1?(this.$newAmendmentAlert.find(".message .one").addClass("hidden"),this.$newAmendmentAlert.find(".message .many").removeClass("hidden")):(this.$newAmendmentAlert.find(".message .one").removeClass("hidden"),this.$newAmendmentAlert.find(".message .many").addClass("hidden")),this.$newAmendmentAlert.hasClass("hidden")&&(this.$newAmendmentAlert.removeClass("hidden"),window.setTimeout((()=>{this.$newAmendmentAlert.addClass("revealed")}),100))}initCheckBackendStatus(){window.setInterval((()=>{let e=p.$form.data("checkStatusUrl");const t=h.getAmendmentIds();e=e.replace(/AMENDMENTS/,t.join(",")),$.get(e,(e=>{e.success?this.onReceivedBackendStatus(e.new,e.deleted):console.warn(e)}))}),3e3)}onReceivedBackendStatus(e,t){const a={},n={};e.staticData.forEach((t=>{const s=e.status[t.id];a[t.id]=t,n[t.id]=s,h.registerNewAmendment(t.id,s.status,s.version,s.votingData),this.alertAboutNewAmendment(t.id,t.titlePrefix)})),Object.keys(e.paragraphs).forEach((t=>{Object.keys(e.paragraphs[t]).forEach((s=>{const i=this.paragraphsByTypeAndNo[t+"_"+s];e.paragraphs[t][s].forEach((e=>{const t=a[e.amendmentId],s=n[e.amendmentId];i.onAmendmentAdded(t,e.nameBase,e.idAdd,e.active,s.status,s.version,s.votingData),h.registerParagraph(e.amendmentId,i)}))}))})),t.forEach((e=>{console.log("Removing amendment",e),h.deleteAmendment(e),Object.keys(this.paragraphsByTypeAndNo).forEach((t=>{this.paragraphsByTypeAndNo[t].onAmendmentDeleted(e)}))}))}}t.MotionMergeAmendments=p,p.activePopup=null,p.currMouseX=null}));
//# sourceMappingURL=MotionMergeAmendments.js.map
