/* =========================================================
   CLUB LUMIÈRE theme — interactions (GSAP + ScrollTrigger + Lenis)
   Degrades gracefully if libraries or motion are unavailable.
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
  var lenis = null;

  function setupOverlay() {
    if (!toggle || !overlay) return;
    var label = toggle.querySelector(".nav__toggle-label");
    function open() {
      overlay.classList.add("is-open");
      if (navEl) navEl.classList.add("is-open");
      overlay.setAttribute("aria-hidden", "false");
      toggle.setAttribute("aria-expanded", "true");
      if (label) label.textContent = label.dataset.labelClose || "CLOSE";
      if (lenis) lenis.stop();
      document.body.style.overflow = "hidden";
    }
    function close() {
      overlay.classList.remove("is-open");
      if (navEl) navEl.classList.remove("is-open");
      overlay.setAttribute("aria-hidden", "true");
      toggle.setAttribute("aria-expanded", "false");
      if (label) label.textContent = label.dataset.labelOpen || "MENU";
      if (lenis) lenis.start();
      document.body.style.overflow = "";
    }
    toggle.addEventListener("click", function () {
      overlay.classList.contains("is-open") ? close() : open();
    });
    overlay.querySelectorAll("a").forEach(function (a) {
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

  if (!hasGSAP || !hasST || reduce) {
    setupOverlay();
    setupAnchors();
    if (loader) loader.style.display = "none";
    return;
  }

  document.documentElement.classList.add("anim-ready");
  gsap.registerPlugin(ScrollTrigger);

  if (hasLenis) {
    lenis = new Lenis({ lerp: 0.1, smoothWheel: true, wheelMultiplier: 0.9 });
    lenis.on("scroll", ScrollTrigger.update);
    gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
    gsap.ticker.lagSmoothing(0);
  }

  setupOverlay();
  setupAnchors();

  // Hero intro (only if a hero exists on this page)
  var heroSplit = document.querySelector(".hero [data-split]");
  if (loader || heroSplit) {
    var heroChars = heroSplit ? splitChars(heroSplit) : [];
    if (heroSplit) gsap.set(heroSplit, { opacity: 1 });
    var tl = gsap.timeline();
    if (loader) {
      tl.to(".loader__line", { scaleX: 1, duration: 0.9, ease: "power2.inOut" })
        .to(loader, { autoAlpha: 0, duration: 0.6, onComplete: function () { loader.remove(); } }, "+=0.15");
    }
    if (heroChars.length) {
      tl.from(".hero__bg-inner", { scale: 1.28, duration: 2.2, ease: "power2.out" }, 0)
        .from(heroChars, { yPercent: 120, opacity: 0, stagger: 0.05, duration: 1.1, ease: "power4.out" }, "-=0.2")
        .from(".hero__eyebrow, .hero__title-ja, .hero__lead", { y: 30, opacity: 0, stagger: 0.12, duration: 0.9, ease: "power3.out" }, "-=0.7")
        .from(".hero__scroll, .nav", { opacity: 0, duration: 0.8, ease: "power2.out" }, "-=0.5");
    }
  }

  // Generic reveal
  gsap.utils.toArray("[data-reveal]").forEach(function (el) {
    gsap.to(el, { y: 0, opacity: 1, duration: 1.1, ease: "power3.out", scrollTrigger: { trigger: el, start: "top 86%" } });
  });

  // Heading char reveal (skip hero)
  gsap.utils.toArray("[data-split]").forEach(function (el) {
    if (el.closest(".hero")) return;
    var chars = splitChars(el);
    gsap.set(el, { opacity: 1 });
    gsap.from(chars, { yPercent: 120, opacity: 0, stagger: 0.025, duration: 0.9, ease: "power4.out", scrollTrigger: { trigger: el, start: "top 88%" } });
  });

  // Parallax
  gsap.utils.toArray("[data-parallax]").forEach(function (el) {
    var amt = parseFloat(el.dataset.parallax) || 14;
    gsap.to(el, { yPercent: amt, ease: "none", scrollTrigger: { trigger: el.closest("section") || el, start: "top bottom", end: "bottom top", scrub: true } });
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

  // Cast horizontal pin (front page, desktop only)
  var mm = gsap.matchMedia();
  mm.add("(min-width: 861px)", function () {
    var track = document.getElementById("castTrack");
    var section = document.querySelector(".cast");
    if (!track || !section) return;
    var viewport = section.querySelector(".cast__viewport");
    if (!viewport) return;
    viewport.style.overflow = "hidden";
    var tween = gsap.to(track, {
      x: function () { return -(track.scrollWidth - viewport.clientWidth + 1); },
      ease: "none",
      scrollTrigger: {
        trigger: section,
        start: "top top",
        end: function () { return "+=" + (track.scrollWidth - viewport.clientWidth + window.innerHeight * 0.6); },
        pin: true, scrub: 1, invalidateOnRefresh: true, anticipatePin: 1
      }
    });
    return function () {
      if (tween && tween.scrollTrigger) tween.scrollTrigger.kill();
      gsap.set(track, { clearProps: "x" });
    };
  });

  window.addEventListener("load", function () { ScrollTrigger.refresh(); });
})();
