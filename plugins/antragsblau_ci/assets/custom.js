document.addEventListener("DOMContentLoaded", () => {
    let el = document.querySelector(".scrollToBottom");
    el.addEventListener("click", function () {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: "smooth",
        });
    });

    let hideScrollElement = () => {
        if (!el.classList.contains("hidden")) {
            el.classList.add("hidden");
        }
    };

    let showScrollElement = () => {
        if (el.classList.contains("hidden")) {
            el.classList.remove("hidden");
        }
    };

    let toggleScrollBar = () => {
        if (
            window.innerHeight + window.scrollY >=
            document.body.scrollHeight - 500
        ) {
            hideScrollElement();
        } else {
            showScrollElement();
        }
    };
    // Initial scroll button
    toggleScrollBar();

    const resizeObserver = new ResizeObserver((entries) => toggleScrollBar());
    resizeObserver.observe(document.body);

    window.onresize = function (ev) {
        toggleScrollBar();
    };

    window.onscroll = function (ev) {
        toggleScrollBar();
    };
});
