var _paq = (window._paq = window._paq || []);
/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
_paq.push(["setCookieDomain", "*.nabu-netz.de"]);
_paq.push([
    "setExcludedQueryParams",
    [
        "2n",
        "1n",
        "salutation",
        "email",
        "a",
        "ortname",
        "jahr",
        "ort",
        "anrede",
        "vorname",
        "name",
    ],
]);
_paq.push(["trackPageView"]);
_paq.push(["enableLinkTracking"]);
(function () {
    var u = "https://stats.nabu.de/";
    _paq.push(["setTrackerUrl", u + "matomo.php"]);
    _paq.push(["setSiteId", "28"]);
    var d = document,
        g = d.createElement("script"),
        s = d.getElementsByTagName("script")[0];
    g.async = true;
    g.src = u + "matomo.js";
    s.parentNode.insertBefore(g, s);
})();
