/* =========================================================
   CLUB LUMIÈRE — Design Preview / interactions
   GSAP (ScrollTrigger) + Lenis. Degrades gracefully if CDNs fail.
   ========================================================= */
(function () {
  "use strict";

  var hasGSAP = !!window.gsap;
  var hasST = hasGSAP && !!window.ScrollTrigger;
  var hasLenis = !!window.Lenis;
  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  var navEl = document.getElementById("nav");
  var toggle = document.getElementById("navToggle");
  var overlay = document.getElementById("overlay");
  var loader = document.getElementById("loader");

  /* ---- Always-on: overlay menu + smooth-ish anchor scroll ---- */
  var lenis = null;

  function setupOverlay() {
    if (!toggle || !overlay) return;
    var label = toggle.querySelector(".nav__toggle-label");

    function open() {
      overlay.classList.add("is-open");
      navEl.classList.add("is-open");
      overlay.setAttribute("aria-hidden", "false");
      toggle.setAttribute("aria-expanded", "true");
      if (label) label.textContent = label.dataset.labelClose || "CLOSE";
      if (lenis) lenis.stop();
      document.body.style.overflow = "hidden";
    }
    function close() {
      overlay.classList.remove("is-open");
      navEl.classList.remove("is-open");
      overlay.setAttribute("aria-hidden", "true");
      toggle.setAttribute("aria-expanded", "false");
      if (label) label.textContent = label.dataset.labelOpen || "MENU";
      if (lenis) lenis.start();
      document.body.style.overflow = "";
    }
    toggle.addEventListener("click", function () {
      overlay.classList.contains("is-open") ? close() : open();
    });
    overlay.querySelectorAll("a[data-scroll]").forEach(function (a) {
      a.addEventListener("click", function () { close(); });
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && overlay.classList.contains("is-open")) close();
    });
  }

  function setupAnchors() {
    document.querySelectorAll('a[data-scroll]').forEach(function (a) {
      a.addEventListener("click", function (e) {
        var id = a.getAttribute("href");
        if (!id || id.charAt(0) !== "#") return;
        var target = document.querySelector(id);
        if (!target) return;
        e.preventDefault();
        if (lenis) lenis.scrollTo(target, { offset: 0, duration: 1.4 });
        else target.scrollIntoView({ behavior: reduce ? "auto" : "smooth" });
      });
    });
  }

  /* ---- char splitter for headings ---- */
  function splitChars(el) {
    var text = el.textContent;
    el.setAttribute("aria-label", text);
    el.textContent = "";
    var frag = document.createDocumentFragment();
    text.split("").forEach(function (ch) {
      var s = document.createElement("span");
      s.className = "char";
      s.setAttribute("aria-hidden", "true");
      s.textContent = ch === " " ? " " : ch;
      frag.appendChild(s);
    });
    el.appendChild(frag);
    return el.querySelectorAll(".char");
  }

  /* ---- No animation libs: just enable UI, leave content visible ---- */
  if (!hasGSAP || !hasST || reduce) {
    setupOverlay();
    setupAnchors();
    if (loader) loader.style.display = "none";
    return;
  }

  /* ---- Full experience ---- */
  document.documentElement.classList.add("anim-ready");
  gsap.registerPlugin(ScrollTrigger);

  // Lenis smooth scroll wired into GSAP ticker
  if (hasLenis) {
    lenis = new Lenis({ lerp: 0.1, smoothWheel: true, wheelMultiplier: 0.9 });
    lenis.on("scroll", ScrollTrigger.update);
    gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
    gsap.ticker.lagSmoothing(0);
  }

  setupOverlay();
  setupAnchors();

  // Pre-split non-hero headings, hero handled in intro timeline
  var heroSplit = document.querySelector(".hero [data-split]");
  var heroChars = heroSplit ? splitChars(heroSplit) : [];
  if (heroSplit) gsap.set(heroSplit, { opacity: 1 });

  // Intro timeline
  var tl = gsap.timeline();
  tl.to(".loader__line", { scaleX: 1, duration: 0.9, ease: "power2.inOut" })
    .to(loader, { autoAlpha: 0, duration: 0.6, onComplete: function () { if (loader) loader.remove(); } }, "+=0.15")
    .from(".hero__bg-inner", { scale: 1.28, duration: 2.2, ease: "power2.out" }, 0)
    .from(heroChars, { yPercent: 120, opacity: 0, stagger: 0.05, duration: 1.1, ease: "power4.out" }, "-=0.2")
    .from(".hero__eyebrow, .hero__title-ja, .hero__lead", { y: 30, opacity: 0, stagger: 0.12, duration: 0.9, ease: "power3.out" }, "-=0.7")
    .from(".hero__scroll, .nav", { opacity: 0, duration: 0.8, ease: "power2.out" }, "-=0.5");

  // Generic reveal
  gsap.utils.toArray("[data-reveal]").forEach(function (el) {
    gsap.to(el, {
      y: 0, opacity: 1, duration: 1.1, ease: "power3.out",
      scrollTrigger: { trigger: el, start: "top 86%" }
    });
  });

  // Heading char reveal (skip hero)
  gsap.utils.toArray("[data-split]").forEach(function (el) {
    if (el.closest(".hero")) return;
    var chars = splitChars(el);
    gsap.set(el, { opacity: 1 });
    gsap.from(chars, {
      yPercent: 120, opacity: 0, stagger: 0.025, duration: 0.9, ease: "power4.out",
      scrollTrigger: { trigger: el, start: "top 88%" }
    });
  });

  // Parallax
  gsap.utils.toArray("[data-parallax]").forEach(function (el) {
    var amt = parseFloat(el.dataset.parallax) || 14;
    gsap.to(el, {
      yPercent: amt, ease: "none",
      scrollTrigger: { trigger: el.closest("section") || el, start: "top bottom", end: "bottom top", scrub: true }
    });
  });

  // Number count-up
  gsap.utils.toArray("[data-count]").forEach(function (el) {
    var end = parseFloat(el.dataset.count) || 0;
    var obj = { v: 0 };
    el.textContent = "0";
    gsap.to(obj, {
      v: end, duration: 1.8, ease: "power2.out",
      scrollTrigger: { trigger: el, start: "top 92%" },
      onUpdate: function () { el.textContent = Math.floor(obj.v).toLocaleString("ja-JP"); }
    });
  });

  // Cast horizontal pin (desktop only)
  var mm = gsap.matchMedia();
  mm.add("(min-width: 861px)", function () {
    var track = document.getElementById("castTrack");
    var section = document.querySelector(".cast");
    if (!track || !section) return;
    var viewport = section.querySelector(".cast__viewport");
    viewport.style.overflow = "hidden";
    var getX = function () {
      return -(track.scrollWidth - viewport.clientWidth + 1);
    };
    var tween = gsap.to(track, {
      x: getX, ease: "none",
      scrollTrigger: {
        trigger: section,
        start: "top top",
        end: function () { return "+=" + (track.scrollWidth - viewport.clientWidth + window.innerHeight * 0.6); },
        pin: true, scrub: 1, invalidateOnRefresh: true, anticipatePin: 1
      }
    });
    return function () { tween && tween.scrollTrigger && tween.scrollTrigger.kill(); gsap.set(track, { clearProps: "x" }); };
  });

  window.addEventListener("load", function () { ScrollTrigger.refresh(); });
})();
