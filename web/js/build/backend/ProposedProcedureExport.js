define(["require","exports"],(function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.ProposedProcedureExport=void 0;t.ProposedProcedureExport=class{constructor(e){this.$widget=e,this.initExportRow()}recalcLinks(){let e=this.$widget.find("input[name=comments]").prop("checked")?1:0,t=this.$widget.find("input[name=onlypublic]").prop("checked")?1:0;this.$widget.find("a.odsLink").each(((i,o)=>{let c=$(o).data("href-tpl");c=c.replace("COMMENTS",e),c=c.replace("ONLYPUBLIC",t),$(o).attr("href",c)}))}initExportRow(){this.$widget.find("li.checkbox").on("click",(function(e){e.stopPropagation()})),this.$widget.find("input[type=checkbox]").change(this.recalcLinks.bind(this)).trigger("change")}}}));
//# sourceMappingURL=ProposedProcedureExport.js.map
