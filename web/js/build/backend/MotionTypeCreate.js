define(["require","exports"],(function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.MotionTypeCreate=void 0;e.MotionTypeCreate=class{constructor(t){this.$form=t,this.$inputSingle=t.find("#typeTitleSingular"),this.$inputPlural=t.find("#typeTitlePlural"),this.$inputCta=t.find("#typeCreateTitle"),this.$inputPrefix=t.find("#typeMotionPrefix"),this.$inputSingle.on("keyup keypress",(t=>{$(t.currentTarget).data("changed","1")})),this.$inputPlural.on("keyup keypress",(t=>{$(t.currentTarget).data("changed","1")})),this.$inputCta.on("keyup keypress",(t=>{$(t.currentTarget).data("changed","1")})),this.$inputPrefix.on("keyup keypress",(t=>{$(t.currentTarget).data("changed","1")})),this.$presets=t.find('input[name="type[preset]"]'),this.$presets.on("change",(()=>{const t=this.$presets.filter(":checked").parents(".typePreset").first();"1"!==this.$inputSingle.data("changed")&&t.data("label-single")&&this.$inputSingle.val(t.data("label-single")),"1"!==this.$inputPlural.data("changed")&&t.data("label-plural")&&this.$inputPlural.val(t.data("label-plural")),"1"!==this.$inputCta.data("changed")&&t.data("label-cta")&&this.$inputCta.val(t.data("label-cta")),"1"!==this.$inputPrefix.data("changed")&&this.$inputPrefix.val(t.data("label-prefix"))}))}}}));
//# sourceMappingURL=MotionTypeCreate.js.map
