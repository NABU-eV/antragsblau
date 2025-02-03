define(["require","exports"],(function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.VotingBlock=void 0;e.VotingBlock=class{constructor(t){const e=t[0],o=e.querySelector(".currentVoting"),n=e.getAttribute("data-voting"),i=e.getAttribute("data-url-poll"),s=e.getAttribute("data-url-vote"),a=e.getAttribute("data-show-admin-link");this.widget=Vue.createApp({template:'\n                <div class="currentVotings">\n                <voting-block-widget v-for="voting in votings" :voting="voting" @vote="vote" @abstain="abstain" :showAdminLink="showAdminLink"></voting-block-widget>\n                </div>',data:()=>({votings:JSON.parse(n),pollingId:null,showAdminLink:a,onReloadedCbs:[]}),methods:{_votePost:function(t,e){const o=this;$.ajax({url:s.replace(/VOTINGBLOCKID/,t),type:"POST",data:JSON.stringify(e),processData:!1,contentType:"application/json; charset=utf-8",dataType:"json",headers:{"X-CSRF-Token":document.querySelector("head meta[name=csrf-token]").getAttribute("content")},success:t=>{void 0===t.success||t.success?(o.votings=t,o.onReloadedCbs.forEach((t=>{t(o.votings)}))):alert(t.message)}})},vote:function(t,e,o,n,i,s){this._votePost(t,{votes:[{itemGroupSameVote:e,itemType:o,itemId:n,vote:i,public:s}]})},abstain:function(t,e,o){this._votePost(t,{abstention:{abstain:e,public:o}})},addReloadedCb:function(t){this.onReloadedCbs.push(t)},reloadData:function(){if(null===i)return;const t=this;$.get(i,(function(e){t.votings=e,t.onReloadedCbs.forEach((e=>{e(t.votings)}))})).catch((function(t){console.error("Could not load voting data from backend",t)}))},startPolling:function(){const t=this;this.pollingId=window.setInterval((function(){t.reloadData()}),3e3)}},beforeUnmount(){window.clearInterval(this.pollingId)},created(){this.startPolling()}}),this.widget.config.compilerOptions.whitespace="condense",window.__initVueComponents(this.widget,"voting"),this.widgetComponent=this.widget.mount(o);const d=document.querySelectorAll(".votingsNoneIndicator");this.widgetComponent.addReloadedCb((t=>{0===t.length?d.forEach((t=>t.classList.remove("hidden"))):d.forEach((t=>t.classList.add("hidden")))}))}}}));
//# sourceMappingURL=VotingBlock.js.map
