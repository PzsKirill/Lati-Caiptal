(function () {
  "use strict";

  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ------------------------------------------------------------------------
     Header: shadow once the page is scrolled
     ------------------------------------------------------------------------ */
  function initHeader() {
    var header = document.querySelector("[data-header]");
    if (!header) return;

    var update = function () {
      header.classList.toggle("is-scrolled", window.scrollY > 8);
    };

    update();
    window.addEventListener("scroll", update, { passive: true });
  }

  /* ------------------------------------------------------------------------
     Tablet / mobile menu: the burger morphs into a cross (CSS), the card
     floats in under the header (absolute, the page doesn't move) and the page
     behind dims. Closes on a link, Esc, the
     dimmed page or when the layout switches to desktop.
     ------------------------------------------------------------------------ */
  function initMenu() {
    var header = document.querySelector("[data-header]");
    var toggle = document.querySelector("[data-menu-toggle]");
    var menu = document.querySelector("[data-menu]");
    var backdrop = document.querySelector("[data-menu-backdrop]");
    if (!toggle || !menu) return;

    var CLOSE_MS = 500;   // matches the closing transition of the card
    var closeTimer = 0;

    function setOpen(open) {
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
      header.classList.toggle("is-menu-open", open);
      clearTimeout(closeTimer);

      if (open) {
        menu.hidden = false;
        void menu.offsetWidth;   // start the reveal from the closed state
        menu.classList.add("is-open");
      } else {
        menu.classList.remove("is-open");
        closeTimer = setTimeout(function () { menu.hidden = true; }, reduceMotion ? 0 : CLOSE_MS);
      }
    }

    toggle.addEventListener("click", function () {
      setOpen(toggle.getAttribute("aria-expanded") !== "true");
    });

    menu.addEventListener("click", function (e) {
      if (e.target.closest("a")) setOpen(false);
    });

    if (backdrop) {
      backdrop.addEventListener("click", function () { setOpen(false); });
    }

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && toggle.getAttribute("aria-expanded") === "true") {
        setOpen(false);
        toggle.focus();
      }
    });

    document.addEventListener("click", function (e) {
      if (toggle.getAttribute("aria-expanded") === "true" && !e.target.closest("[data-header]")) setOpen(false);
    });

    // Close when switching to the desktop layout
    window.matchMedia("(min-width: 1200px)").addEventListener("change", function (mq) {
      if (mq.matches) setOpen(false);
    });
  }

  /* ------------------------------------------------------------------------
     Hero slider: crossfading photos, captions and progress bars. Plays through
     once and stays on the last slide.
     Timing is driven by the bar's CSS animation, so pause/resume stays in sync.
     ------------------------------------------------------------------------ */
  function initHeroSlider(root) {
    var slides = root.querySelectorAll("[data-slide]");
    var captions = root.querySelectorAll("[data-caption]");
    var bars = root.querySelectorAll("[data-bar]");
    var total = slides.length;
    if (total < 2) return;

    var duration = parseInt(root.getAttribute("data-duration"), 10) || 6000;
    var current = 0;

    root.style.setProperty("--slide-duration", duration + "ms");

    function goTo(index) {
      current = (index + total) % total;

      for (var i = 0; i < total; i++) {
        var isActive = i === current;
        slides[i].classList.toggle("is-active", isActive);
        slides[i].setAttribute("aria-hidden", isActive ? "false" : "true");
        if (captions[i]) captions[i].classList.toggle("is-active", isActive);
        if (bars[i]) {
          bars[i].classList.toggle("is-active", isActive);
          bars[i].classList.toggle("is-done", i < current);
          bars[i].setAttribute("aria-current", isActive ? "true" : "false");
        }
      }

      // Restart the fill animation on the active bar
      var fill = bars[current] && bars[current].querySelector(".hero-slider__bar-fill");
      if (fill) {
        fill.style.animation = "none";
        void fill.offsetWidth;
        fill.style.animation = "";
      }
    }

    Array.prototype.forEach.call(bars, function (bar, i) {
      bar.addEventListener("click", function () {
        goTo(i);
      });

      var fill = bar.querySelector(".hero-slider__bar-fill");
      if (fill) {
        fill.addEventListener("animationend", function () {
          // One pass only: stop on the last slide (bars stay clickable)
          if (i === current && current < total - 1) goTo(current + 1);
        });
      }
    });

    // Pause on hover / keyboard focus and when the tab is hidden
    var pause = function () { root.classList.add("is-paused"); };
    var resume = function () { root.classList.remove("is-paused"); };

    root.addEventListener("mouseenter", pause);
    root.addEventListener("mouseleave", resume);
    root.addEventListener("focusin", pause);
    root.addEventListener("focusout", resume);
    document.addEventListener("visibilitychange", function () {
      document.hidden ? pause() : resume();
    });

    if (reduceMotion) pause();

    goTo(0);
  }

  /* ------------------------------------------------------------------------
     Helpers
     ------------------------------------------------------------------------ */
  var SVG_NS = "http://www.w3.org/2000/svg";
  var MONTHS = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

  function svgEl(name, attrs) {
    var el = document.createElementNS(SVG_NS, name);
    for (var key in attrs) el.setAttribute(key, attrs[key]);
    return el;
  }

  // US number format: $1,234.56 / –$327.33
  function usd(value) {
    var abs = Math.abs(value).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    return (value < 0 ? "–$" : "$") + abs;
  }

  // Runs callback(true/false) when the element enters/leaves the viewport
  function onVisible(el, callback) {
    if (!("IntersectionObserver" in window)) {
      callback(true);
      return;
    }
    new IntersectionObserver(function (entries) {
      callback(entries[0].isIntersecting);
    }, { threshold: 0.25 }).observe(el);
  }

  /* ------------------------------------------------------------------------
     "What you will learn": price chart + data table, animated together.
     A cursor walks through the data set once; the spot line is revealed up to
     it, coupon dots appear once passed, and the table keeps the current day in
     its second row. Pauses while off-screen, never replays.
     ------------------------------------------------------------------------ */
  function initNoteChart(chart, table) {
    var data = window.LATI_NOTE_DATA;
    if (!data || !chart) return;

    var points = data.points;
    var count = points.length;
    var grid = chart.querySelector("[data-grid]");
    var svg = chart.querySelector("[data-svg]");
    var axis = chart.querySelector("[data-x-axis]");
    var rowsWrap = table && table.querySelector("[data-rows]");
    var minValue = parseFloat(grid.getAttribute("data-min"));
    var maxValue = parseFloat(grid.getAttribute("data-max"));

    var STEP_MS = 220;          // time per trading day
    var RIGHT_INSET = 12;       // the series ends 12px before the grid lines

    var xs = [], ys = [], lengths = [], totalLength = 0;
    var spotPath, cursorDot, cursorLine, couponDots = [];
    var rows = [];
    var progress = count - 1;   // fully drawn until the animation starts
    var activeRow = -1;
    var playing = false, finished = false, visible = false, rafId = 0, lastTime = 0;

    /* Table rows */
    if (rowsWrap) {
      var html = "";
      points.forEach(function (p) {
        html += '<div class="note-table__row" role="row">' +
          '<span role="cell">' + usd(p[1]) + "</span>" +
          '<span role="cell">' + p[2].toFixed(2) + "</span>" +
          '<span role="cell">' + usd(p[3]) + "</span>" +
          '<span role="cell">' + (p[4] === null ? "–" : usd(p[4])) + "</span>" +
          '<span role="cell">' + usd(data.barrier) + "</span>" +
          '<span role="cell">' + usd(data.autocall) + "</span>" +
          "</div>";
      });
      rowsWrap.innerHTML = html;
      rows = rowsWrap.children;
    }

    /* Geometry: y comes from the dashed grid lines themselves */
    function layout() {
      var gridRows = grid.querySelectorAll(".price-chart__row");
      var top = gridRows[0].offsetTop + 9.5;
      var bottom = gridRows[gridRows.length - 1].offsetTop + 9.5;
      var width = svg.getBoundingClientRect().width - RIGHT_INSET;
      var yOf = function (v) {
        return top + (maxValue - v) / (maxValue - minValue) * (bottom - top);
      };

      xs = []; ys = []; lengths = []; totalLength = 0;
      points.forEach(function (p, i) {
        xs.push(i / (count - 1) * width);
        ys.push(yOf(p[1]));
        if (i > 0) totalLength += Math.hypot(xs[i] - xs[i - 1], ys[i] - ys[i - 1]);
        lengths.push(totalLength);
      });

      svg.innerHTML = "";
      // 2px dashed levels on whole pixels so they stay crisp
      var full = width + RIGHT_INSET;
      var autocallY = Math.round(yOf(data.autocall));
      var barrierY = Math.round(yOf(data.barrier));
      svg.appendChild(svgEl("line", { class: "pc-level pc-level--autocall", x1: 0, x2: full, y1: autocallY, y2: autocallY }));
      svg.appendChild(svgEl("line", { class: "pc-level pc-level--barrier", x1: 0, x2: full, y1: barrierY, y2: barrierY }));

      cursorLine = svgEl("line", { class: "pc-cursor-line", y1: top, y2: bottom });
      svg.appendChild(cursorLine);

      spotPath = svgEl("path", {
        class: "pc-spot",
        d: xs.map(function (x, i) { return (i ? "L" : "M") + x.toFixed(1) + " " + ys[i].toFixed(1); }).join(""),
        "stroke-dasharray": totalLength + " " + totalLength
      });
      svg.appendChild(spotPath);

      couponDots = [];
      points.forEach(function (p, i) {
        if (p[4] === null) return;
        var dot = svgEl("circle", { class: "pc-coupon", cx: xs[i], cy: ys[i], r: 6 });
        dot.dataset.index = i;
        svg.appendChild(dot);
        couponDots.push(dot);
      });

      cursorDot = svgEl("circle", { class: "pc-cursor", r: 4 });
      svg.appendChild(cursorDot);

      buildAxis(width);
      render();
    }

    /* X axis: weekly ticks, thinned evenly to fit; month name on the first
       shown tick of a month */
    function buildAxis(width) {
      var ticks = [], prevDay = 7;
      points.forEach(function (p, i) {
        var d = new Date(p[0] + "T12:00:00");
        if (d.getDay() < prevDay) ticks.push({ i: i, d: d });   // weekday went down: new week
        prevDay = d.getDay();
      });

      var weekPx = width / Math.max(ticks.length - 1, 1);
      var step = Math.max(1, Math.ceil((width < 500 ? 22 : 26) / weekPx));
      var html = "", lastMonth = -1;

      ticks.forEach(function (t, k) {
        if (k % step) return;
        var x = t.i / (count - 1) * width;
        var isMonth = t.d.getMonth() !== lastMonth;
        lastMonth = t.d.getMonth();
        html += '<span class="' + (isMonth ? "is-month" : "") + '" style="left:' + x.toFixed(1) + 'px">' +
          (isMonth ? MONTHS[t.d.getMonth()] : String(t.d.getDate()).padStart(2, "0")) + "</span>";
      });
      axis.innerHTML = html;
    }

    function render() {
      var i = Math.floor(progress);
      var t = progress - i;
      var j = Math.min(i + 1, count - 1);
      var len = lengths[i] + (lengths[j] - lengths[i]) * t;
      var x = xs[i] + (xs[j] - xs[i]) * t;
      var y = ys[i] + (ys[j] - ys[i]) * t;

      spotPath.setAttribute("stroke-dashoffset", totalLength - len);
      cursorDot.setAttribute("cx", x);
      cursorDot.setAttribute("cy", y);
      cursorLine.setAttribute("x1", x);
      cursorLine.setAttribute("x2", x);

      couponDots.forEach(function (dot) {
        dot.classList.toggle("is-hidden", +dot.dataset.index > progress + 0.001);
      });

      if (playing || progress < count - 1) setRow(Math.round(progress));
    }

    function setRow(row) {
      if (row === activeRow || !rows.length) return;
      if (rows[activeRow]) rows[activeRow].classList.remove("is-active");
      activeRow = row;
      rows[row].classList.add("is-active");
      // Keep the active day in the second visible row (row height comes from CSS)
      var rowH = rows[0].offsetHeight || 56;
      var visibleRows = Math.round(rowsWrap.parentNode.clientHeight / rowH) || 4;
      var offset = Math.min(Math.max(row - 1, 0), rows.length - visibleRows);
      rowsWrap.style.transform = "translateY(" + (-offset * rowH) + "px)";
    }

    function frame(now) {
      if (!playing) return;
      var dt = now - lastTime;
      lastTime = now;

      progress = Math.min(progress + dt / STEP_MS, count - 1);
      render();

      // One pass only: stay on the fully drawn chart
      if (progress >= count - 1) {
        finished = true;
        stop();
        chart.classList.remove("is-playing");
        return;
      }

      rafId = requestAnimationFrame(frame);
    }

    function play() {
      if (playing || finished || reduceMotion) return;
      playing = true;
      chart.classList.add("is-playing");
      lastTime = performance.now();
      rafId = requestAnimationFrame(frame);
    }

    function stop() {
      playing = false;
      cancelAnimationFrame(rafId);
    }

    layout();

    var resizeTimer;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(layout, 150);
    });

    if (reduceMotion) {
      setRow(1);   // static state as in the design: second day highlighted
      return;
    }

    // Start from an empty chart the first time it scrolls into view
    progress = 0;
    render();

    onVisible(chart, function (isVisible) {
      visible = isVisible;
      isVisible && !document.hidden ? play() : stop();
    });
    document.addEventListener("visibilitychange", function () {
      document.hidden || !visible ? stop() : play();
    });
  }

  /* ------------------------------------------------------------------------
     Card highlight: when the list first scrolls into view it walks through
     the cards once and returns to the first one (the design state). Hovering
     a card highlights it and ends the auto pass for good.
     Items are [data-cycle-item] descendants, or the direct children.
     ------------------------------------------------------------------------ */
  function initCycle(list) {
    var cards = list.querySelectorAll("[data-cycle-item]");
    if (!cards.length) cards = list.children;
    var current = 0, step = 0, timer = 0, done = reduceMotion;

    function setActive(index) {
      cards[current].classList.remove("is-active");
      current = index;
      cards[current].classList.add("is-active");
    }

    function finish() {
      done = true;
      clearInterval(timer);
    }

    function run() {
      if (done || timer) return;
      timer = setInterval(function () {
        step++;
        setActive(step % cards.length);
        if (step >= cards.length) finish();   // back on the first card
      }, 2400);
    }

    function pause() {
      clearInterval(timer);
      timer = 0;
    }

    Array.prototype.forEach.call(cards, function (card, i) {
      card.addEventListener("mouseenter", function () {
        finish();
        setActive(i);
      });
    });

    onVisible(list, function (isVisible) {
      isVisible ? run() : pause();
    });
  }

  /* ------------------------------------------------------------------------
     Payoff chart, linked both ways with the three paragraphs:
     - hovering / tapping / focusing a paragraph moves the point to its scenario;
     - moving over the chart moves the point and highlights the paragraph of that
       zone (below the barrier -> capital at risk, above -> coupon paid);
       leaving the chart returns to the last chosen paragraph.
     Schematic model from the design: barrier at 75% of initial level,
     x axis 0–140%. Below the barrier the payoff equals the underlying level.
     ------------------------------------------------------------------------ */
  function initPayoff(root) {
    var chart = root.querySelector("[data-payoff]");
    var plot = root.querySelector("[data-plot]");
    var point = root.querySelector("[data-point]");
    var tip = root.querySelector("[data-tip]");
    var tipTitle = root.querySelector("[data-tip-title]");
    var tipSub = root.querySelector("[data-tip-sub]");
    var steps = root.querySelectorAll("[data-step]");
    if (!chart || !plot) return;

    var BARRIER = 75;        // % of initial level
    var MAX_LEVEL = 140;
    var BARRIER_X = 36.45;   // % of plot width
    // y positions in % of the plot height (380px tall at 1440)
    var Y_ZERO = 100;        // 0 % payoff
    var Y_BARRIER = 44.21;   // payoff at the barrier
    var Y_SAFE = 18.68;      // principal + coupon
    var defaultLevel = 45;

    function xOf(level) {
      return level < BARRIER
        ? level / BARRIER * BARRIER_X
        : BARRIER_X + (level - BARRIER) / (MAX_LEVEL - BARRIER) * (100 - BARRIER_X);
    }

    function levelOf(xPct) {
      return xPct < BARRIER_X
        ? xPct / BARRIER_X * BARRIER
        : BARRIER + (xPct - BARRIER_X) / (100 - BARRIER_X) * (MAX_LEVEL - BARRIER);
    }

    function show(level) {
      level = Math.round(Math.min(Math.max(level, 0), MAX_LEVEL));
      var safe = level >= BARRIER;
      var x = xOf(level);
      var y = safe ? Y_SAFE : Y_ZERO - level / BARRIER * (Y_ZERO - Y_BARRIER);

      point.style.left = tip.style.left = x + "%";
      point.style.top = tip.style.top = y + "%";
      point.classList.toggle("is-safe", safe);
      tip.classList.toggle("is-safe", safe);
      tip.classList.toggle("is-flipped", x > 62);

      tipTitle.textContent = safe
        ? level + "% of initial → Principal + coupon"
        : level + "% of initial → Payoff " + level + "%";
      tipSub.textContent = safe ? "Barrier not breached" : "Capital at risk";
    }

    // The paragraph chosen last (hover / tap / focus) is what the chart returns to
    var defaultStep = root.querySelector("[data-step].is-active") || steps[0];
    var zoneStep = {
      safe: root.querySelector('[data-step][data-zone="safe"]'),
      risk: root.querySelector('[data-step][data-zone="risk"]')
    };

    function setStep(active) {
      steps.forEach(function (s) { s.classList.toggle("is-active", s === active); });
    }

    function selectStep(step) {
      defaultStep = step;
      defaultLevel = parseFloat(step.getAttribute("data-level"));
      setStep(step);
      show(defaultLevel);
    }

    function restore() {
      chart.classList.remove("is-dragging");
      setStep(defaultStep);
      show(defaultLevel);
    }

    // Chart -> text: the point follows the cursor and the paragraph of that zone lights up
    function track(e) {
      var rect = plot.getBoundingClientRect();
      var level = levelOf((e.clientX - rect.left) / rect.width * 100);
      chart.classList.add("is-dragging");
      show(level);
      var step = zoneStep[level >= BARRIER ? "safe" : "risk"];
      if (step) setStep(step);
    }

    plot.addEventListener("pointermove", track);
    plot.addEventListener("pointerdown", track);

    // A mouse leaving restores the chosen state; a lifted finger keeps the tapped point
    plot.addEventListener("pointerleave", function (e) {
      if (e.pointerType !== "touch") restore();
    });

    // Text -> chart
    steps.forEach(function (step) {
      step.addEventListener("mouseenter", function () { selectStep(step); });
      step.addEventListener("click", function () { selectStep(step); });
      step.addEventListener("focus", function () { selectStep(step); });
    });

    show(defaultLevel);
  }

  /* ------------------------------------------------------------------------
     FAQ accordion: one item open at a time
     ------------------------------------------------------------------------ */
  function initAccordion(list) {
    var items = list.querySelectorAll(".faq-item");

    function toggle(item, open) {
      item.classList.toggle("is-open", open);
      item.querySelector(".faq-item__question").setAttribute("aria-expanded", open ? "true" : "false");
    }

    Array.prototype.forEach.call(items, function (item) {
      item.querySelector(".faq-item__question").addEventListener("click", function () {
        var open = !item.classList.contains("is-open");
        Array.prototype.forEach.call(items, function (other) {
          if (other !== item) toggle(other, false);
        });
        toggle(item, open);
      });
    });
  }

  /* ------------------------------------------------------------------------
     Registration form: validates, posts to the WordPress endpoint, shows the
     success state and pushes a dataLayer event for GTM (GA4 / Meta conversions).
     Without JS the form still works as a regular POST (admin-post.php).
     ------------------------------------------------------------------------ */
  var UTM_KEYS = ["utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];

  // Remember UTM tags from the landing URL for the whole visit
  function readUtm() {
    var params = new URLSearchParams(window.location.search);
    var utm = {};
    try {
      utm = JSON.parse(sessionStorage.getItem("lati_utm") || "{}");
    } catch (e) {}
    UTM_KEYS.forEach(function (key) {
      if (params.get(key)) utm[key] = params.get(key);
    });
    try {
      sessionStorage.setItem("lati_utm", JSON.stringify(utm));
    } catch (e) {}
    return utm;
  }

  function initRegistration(form) {
    var config = window.LATI || {};
    var started = form.querySelector("[data-reg-started]");
    var error = form.querySelector("[data-reg-error]");
    var success = document.querySelector("[data-reg-success]");
    var utm = readUtm();
    var referrer = document.referrer;

    if (started) started.value = String(Date.now());
    if (!config.registerUrl || !window.fetch) return;   // fall back to the regular POST

    function showError(message) {
      error.textContent = message || config.errorText;
      error.hidden = false;
    }

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      error.hidden = true;
      if (!form.reportValidity()) return;

      var data = {};
      new FormData(form).forEach(function (value, key) {
        data[key] = value;
      });
      Object.keys(utm).forEach(function (key) {
        data[key] = utm[key];
      });
      data.referrer = referrer;

      form.classList.add("is-sending");

      fetch(config.registerUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      })
        .then(function (res) {
          return res.json().then(function (body) {
            return { ok: res.ok && body.ok, body: body };
          });
        })
        .then(function (result) {
          form.classList.remove("is-sending");
          if (!result.ok) {
            showError(result.body && result.body.message);
            return;
          }

          if (result.body.title) success.querySelector("[data-reg-success-title]").textContent = result.body.title;
          if (result.body.text) success.querySelector("[data-reg-success-text]").textContent = result.body.text;
          form.hidden = true;
          success.hidden = false;
          success.setAttribute("tabindex", "-1");
          success.focus({ preventScroll: true });

          window.dataLayer = window.dataLayer || [];
          window.dataLayer.push({ event: "webinar_registration", role: data.role || "" });
        })
        .catch(function () {
          form.classList.remove("is-sending");
          showError();
        });
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initHeader();
    initMenu();
    document.querySelectorAll("[data-hero-slider]").forEach(initHeroSlider);
    initNoteChart(document.querySelector("[data-price-chart]"), document.querySelector("[data-note-table]"));
    document.querySelectorAll("[data-learn-grid], [data-cycle]").forEach(initCycle);
    document.querySelectorAll("[data-reveal]").forEach(function (el) {
      onVisible(el, function (isVisible) {
        if (isVisible) el.classList.add("is-visible");
      });
    });
    document.querySelectorAll(".payoff").forEach(initPayoff);
    document.querySelectorAll("[data-accordion]").forEach(initAccordion);
    document.querySelectorAll("[data-reg-form]").forEach(initRegistration);
  });
})();
