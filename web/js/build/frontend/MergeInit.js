define(["require","exports"],(function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.MergeInit=void 0;t.MergeInit=class{constructor(e){this.$widget=e,this.$checkboxes=this.$widget.find(".toMergeAmendments .selectSingle"),this.$allCheckbox=this.$widget.find(".selectAll"),this.initExportBtn(),this.initAllCheckbox()}recalcExportBtn(){let e=[];this.$checkboxes.filter(":checked").each(((t,i)=>{e.push(parseInt(i.getAttribute("name").split("[")[1]))}));let t=this.exportLinkTpl.replace(/IDS/,e.join(","));this.$widget.find(".exportHolder a").attr("href",t)}initExportBtn(){this.exportLinkTpl=this.$widget.find(".exportHolder a").attr("href"),this.exportLinkTpl&&(this.$widget.on("change",".toMergeAmendments input[type=checkbox]",(()=>{this.recalcExportBtn()})),this.recalcExportBtn())}recalcAllCheckbox(){let e=!0,t=!0;this.$checkboxes.each(((i,c)=>{$(c).prop("checked")?t=!1:e=!1})),t?this.$allCheckbox.prop("checked",!1).prop("indeterminate",!1):e?this.$allCheckbox.prop("checked",!0).prop("indeterminate",!1):this.$allCheckbox.prop("indeterminate",!0)}initAllCheckbox(){this.recalcAllCheckbox(),this.$allCheckbox.change((()=>{this.$checkboxes.prop("checked",this.$allCheckbox.prop("checked"))})),this.$checkboxes.change((()=>{this.recalcAllCheckbox()}))}}}));
//# sourceMappingURL=MergeInit.js.map
