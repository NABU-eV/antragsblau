var __awaiter=this&&this.__awaiter||function(e,t,i,l){return new(i||(i=Promise))((function(n,a){function o(e){try{d(l.next(e))}catch(e){a(e)}}function s(e){try{d(l.throw(e))}catch(e){a(e)}}function d(e){var t;e.done?n(e.value):(t=e.value,t instanceof i?t:new i((function(e){e(t)}))).then(o,s)}d((l=l.apply(e,t||[])).next())}))};define(["require","exports","../shared/PolicySetter"],(function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.ContentPageEdit=void 0;t.ContentPageEdit=class{constructor(e){this.$form=e,e.on("submit",(e=>e.preventDefault())),this.$textSaver=$(this.$form.data("save-selector")),this.$textHolder=$(this.$form.data("text-selector")),this.$editCaller=$(this.$form.data("edit-selector")),this.$contentSettings=e.find(".contentSettingsToolbar"),this.$downloadableFiles=e.find(".downloadableFiles"),this.$allConsultationsCheckbox=e.find("input[name=allConsultations]"),this.$policyWidget=e.find(".policyWidget"),this.$editCaller.on("click",this.editCalled.bind(this)),this.$textSaver.addClass("hidden"),this.$textSaver.find("button").on("click",this.save.bind(this)),this.$contentSettings.length>0&&this.initContentSettings(),this.$downloadableFiles.length>0&&this.initDownloadableFiles(),this.$policyWidget.length>0&&new PolicySetter(this.$policyWidget),$(".deletePageForm").on("submit",this.onSubmitDeleteForm.bind(this))}editCalled(e){e.preventDefault(),this.$editCaller.addClass("hidden"),this.$textHolder.attr("contenteditable","true"),this.editor=CKEDITOR.inline(this.$textHolder.attr("id"),{versionCheck:!1,scayt_sLang:"de_DE",removePlugins:"lite,showbloks,about,selectall,forms",extraPlugins:"uploadimage",uploadUrl:this.$form.data("upload-url"),filebrowserBrowseUrl:this.$form.data("image-browse-url"),toolbarGroups:[{name:"basicstyles",groups:["basicstyles","cleanup"]},{name:"colors"},{name:"links"},{name:"insert"},{name:"others"},{name:"tools"},"/",{name:"styles"},{name:"paragraph",groups:["list","indent","blocks","align","bidi"]}]}),this.editor.on("fileUploadRequest",(e=>{e.data.requestData._csrf=this.$form.find("> input[name=_csrf]").val()})),this.$textHolder.trigger("focus"),this.$textSaver.removeClass("hidden"),this.$contentSettings.removeClass("hidden"),this.$allConsultationsCheckbox.prop("checked")||this.$policyWidget.removeClass("hidden"),this.showDownloadableFiles()}initContentSettings(){this.$contentSettings.find("input[name=url]").on("keyup change keypress",(e=>{let t=$(e.currentTarget);t.val(t.val().replace(/[^\w_\-,\.äöüß]/,""))})),this.$allConsultationsCheckbox.on("change",(()=>{this.$allConsultationsCheckbox.prop("checked")?this.$policyWidget.addClass("hidden"):this.$policyWidget.removeClass("hidden")}))}initDownloadableFiles(){const e=this.$downloadableFiles.find(".uploadCol .text");this.$downloadableFiles.find("input[type=file]").on("change",(()=>{const t=this.$downloadableFiles.find("input[type=file]").val().split("\\"),i=t[t.length-1];e.text(i)})),this.$downloadableFiles.find(".fileList").on("click",".deleteFile",(e=>{const t=$(e.currentTarget).parents("li").first().data("id"),i=this.$form.data("del-confirmation");bootbox.confirm(i,(e=>{e&&this.deleteDownloadableFile(t)}))}))}deleteDownloadableFile(e){const t=this.$form.data("file-delete-url"),i={id:e,_csrf:this.$form.find("> input[name=_csrf]").val()};$.post(t,i,(t=>{t.success?(this.$downloadableFiles.find(".fileList li[data-id="+e+"]").remove(),0===this.$downloadableFiles.find(".fileList").children().length&&this.$downloadableFiles.find(".none").removeClass("hidden")):alert(t.message)}))}addUploadedFileCb(e){const t=$('<li><a><span class="glyphicon glyphicon-download-alt"></span> <span class="title"></span></a><button type="button" class="btn btn-link deleteFile"><span class="glyphicon glyphicon-trash"></span></button></li>');t.find("a").attr("href",e.url),t.find("a .title").text(e.title),t.attr("data-id",e.id),this.$downloadableFiles.find("ul").append(t),this.$downloadableFiles.find(".none").addClass("hidden")}hideDownloadableFiles(){this.$downloadableFiles.find("ul li").length>0||this.$downloadableFiles.addClass("hidden"),this.$downloadableFiles.find(".downloadableFilesUpload").addClass("hidden"),this.$downloadableFiles.find("#downloadableFileNew").val(""),this.$downloadableFiles.find("#downloadableFileTitle").val(""),this.$downloadableFiles.find(".uploadCol .text").text(this.$downloadableFiles.find(".uploadCol .text").data("title"))}showDownloadableFiles(){this.$downloadableFiles.removeClass("hidden"),this.$downloadableFiles.find(".downloadableFilesUpload").removeClass("hidden")}readUploadableFile(e){return __awaiter(this,void 0,void 0,(function*(){return new Promise(((t,i)=>{const l=new FileReader;l.onload=function(){const e=l.result.split(";base64,");2===e.length?t(e[1]):(alert("Could not read the given file"),t(null))},e.files.length>0?l.readAsDataURL(e.files[0]):t(null)}))}))}save(e){return __awaiter(this,void 0,void 0,(function*(){e.preventDefault();let t={data:this.editor.getData(),_csrf:this.$form.find("> input[name=_csrf]").val()};if(this.$contentSettings.length>0&&(t.url=this.$contentSettings.find("input[name=url]").val(),t.title=this.$contentSettings.find("input[name=title]").val(),t.allConsultations=this.$allConsultationsCheckbox.prop("checked")?1:0,t.inMenu=this.$contentSettings.find("input[name=inMenu]").prop("checked")?1:0),this.$policyWidget.length>0&&(t.policyReadPage={id:this.$policyWidget.find(".policySelect").val(),groups:this.$policyWidget.find(".userGroupSelect select").val()}),this.$downloadableFiles.length>0){const e=this.$downloadableFiles.find("input[type=file]")[0],i=yield this.readUploadableFile(e);if(i){const e=this.$downloadableFiles.find("input[type=file]").val().split("\\"),l=e[e.length-1];t.uploadDownloadableFile=i,t.uploadDownloadableTitle=this.$downloadableFiles.find("#downloadableFileTitle").val(),t.uploadDownloadableFilename=l}}$.post(this.$form.attr("action"),t,(e=>{e.success?(window.setTimeout((()=>{this.editor.destroy()}),100),this.$textSaver.addClass("hidden"),this.$textHolder.attr("contenteditable","false"),this.$editCaller.removeClass("hidden"),this.$contentSettings.addClass("hidden"),this.$policyWidget.addClass("hidden"),null!==e.title&&($(".pageTitle").text(e.title),document.title=e.title,$("#mainmenu .page"+e.id).text(e.title),$(".breadcrumb").children().last().text(e.title)),e.uploadedFile&&this.addUploadedFileCb(e.uploadedFile),this.hideDownloadableFiles(),e.message&&alert(e.message),e.redirectTo&&(window.location.href=e.redirectTo)):alert("Something went wrong...")}))}))}onSubmitDeleteForm(e,t){t&&(t.confirmed,1)&&!0===t.confirmed||(e.preventDefault(),bootbox.confirm(__t("admin","delPageConfirm"),(function(e){e&&$(".deletePageForm").trigger("submit",{confirmed:!0})})))}}}));
//# sourceMappingURL=ContentPageEdit.js.map
