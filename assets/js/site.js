(function () {
  const header = document.querySelector(".site-header");
  const menuBtn = document.querySelector(".menu-btn");
  const nav = document.querySelector(".nav");

  if (menuBtn && nav) {
    menuBtn.addEventListener("click", function () {
      nav.classList.toggle("open");
    });
    nav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        nav.classList.remove("open");
      });
    });
  }

  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("in");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12 }
  );
  document.querySelectorAll(".reveal").forEach(function (node, index) {
    node.style.animationDelay = (index % 8) * 80 + "ms";
    observer.observe(node);
  });

  document.querySelectorAll("[data-count]").forEach(function (node) {
    const target = Number(node.getAttribute("data-count"));
    const suffix = node.getAttribute("data-suffix") || "";
    let current = 0;
    const step = Math.max(1, Math.floor(target / 40));
    const timer = setInterval(function () {
      current = Math.min(target, current + step);
      node.textContent = current + suffix;
      if (current >= target) clearInterval(timer);
    }, 28);
  });

  const yearNode = document.querySelector("[data-year]");
  if (yearNode) yearNode.textContent = new Date().getFullYear();

  if (header) {
    window.addEventListener("scroll", function () {
      header.style.boxShadow = window.scrollY > 8 ? "0 10px 30px rgba(27,27,27,0.06)" : "none";
    });
  }

  const banner = document.querySelector("[data-banner]");
  if (banner) {
    const slides = Array.from(banner.querySelectorAll(".ads-slide"));
    const dotsWrap = banner.querySelector("[data-banner-dots]");
    const progress = banner.querySelector("[data-banner-progress]");
    let index = 0;
    let timer;
    slides.forEach(function (_, i) {
      const dot = document.createElement("button");
      dot.type = "button";
      dot.setAttribute("aria-label", "Show campaign " + (i + 1));
      dot.addEventListener("click", function () { go(i); });
      dotsWrap.appendChild(dot);
    });
    function go(next) {
      slides[index].classList.remove("is-active");
      index = (next + slides.length) % slides.length;
      slides[index].classList.add("is-active");
      dotsWrap.querySelectorAll("button").forEach(function (dot, i) {
        dot.classList.toggle("is-on", i === index);
      });
      if (progress) {
        progress.style.transition = "none";
        progress.style.width = "0";
        requestAnimationFrame(function () {
          progress.style.transition = "width 6s linear";
          progress.style.width = "100%";
        });
      }
      clearInterval(timer);
      timer = setInterval(function () { go(index + 1); }, 6000);
    }
    banner.querySelector("[data-banner-prev]").addEventListener("click", function () { go(index - 1); });
    banner.querySelector("[data-banner-next]").addEventListener("click", function () { go(index + 1); });
    go(0);
  }

  const dock = document.querySelector("[data-dock]");
  if (dock) {
    const panels = dock.querySelectorAll("[data-dock-panel]");
    function closeAll() {
      panels.forEach(function (panel) { panel.classList.remove("is-open"); });
      dock.querySelectorAll("[data-dock-open]").forEach(function (btn) { btn.classList.remove("is-on"); });
    }
    dock.querySelectorAll("[data-dock-open]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        const name = btn.getAttribute("data-dock-open");
        const panel = dock.querySelector('[data-dock-panel="' + name + '"]');
        const already = panel.classList.contains("is-open");
        closeAll();
        if (!already) {
          panel.classList.add("is-open");
          btn.classList.add("is-on");
        }
      });
    });
    dock.querySelectorAll("[data-dock-close]").forEach(function (btn) {
      btn.addEventListener("click", closeAll);
    });
  }
})();
