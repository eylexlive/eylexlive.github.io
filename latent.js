(() => {
  const TAU = Math.PI * 2;
  const FORM_CLOUD = 0;
  const FORM_WORD = 1;
  const FORM_CLUSTERS = 2;
  const FORM_SPIRAL = 3;
  const TRANSITION = 2.6;
  const STAGGER = 0.38;
  const TIMELINE = [
    { form: FORM_CLOUD, hold: 1.6 },
    { form: FORM_WORD, hold: 5.4 },
    { form: FORM_CLUSTERS, hold: 3.6 },
    { form: FORM_SPIRAL, hold: 3.2 },
    { form: FORM_WORD, hold: 5.4 }
  ];
  const FORM_STYLE = [
    { spin: 0.05, wobble: 16, breathe: 0.02, breatheRate: 0.5 },
    { spin: 0, wobble: 0.9, breathe: 0.012, breatheRate: 1.25 },
    { spin: 0.018, wobble: 5, breathe: 0.01, breatheRate: 0.7 },
    { spin: 0.09, wobble: 3, breathe: 0.015, breatheRate: 0.6 }
  ];
  const BUG_LABELS = [
    "0x7f3a · bug?",
    "0x41ff · oob read?",
    "0x0bad · null deref",
    "0x1c08 · idor?",
    "0x9e21 · race?",
    "0xdead · uaf?",
    "0x3c3c · xss?",
    "0x5a17 · ssrf?"
  ];
  const MONO = '"JetBrains Mono", "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace';
  const DISPLAY = '"Space Grotesk", system-ui, sans-serif';

  const cycleLength = TIMELINE.reduce((sum, stage) => sum + stage.hold + TRANSITION, 0);

  const clamp = (value, low, high) => (value < low ? low : value > high ? high : value);
  const easeInOut = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
  const gaussian = () => {
    let u = 0;
    while (u === 0) u = Math.random();
    return Math.sqrt(-2 * Math.log(u)) * Math.cos(TAU * Math.random());
  };
  const rgba = (rgb, alpha) => `rgba(${rgb[0]},${rgb[1]},${rgb[2]},${alpha})`;

  function readColor(probe, name, fallback) {
    const raw = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    probe.fillStyle = fallback;
    if (raw) probe.fillStyle = raw;
    const normalized = String(probe.fillStyle);
    if (normalized[0] === "#") {
      return [
        parseInt(normalized.slice(1, 3), 16),
        parseInt(normalized.slice(3, 5), 16),
        parseInt(normalized.slice(5, 7), 16)
      ];
    }
    const parts = normalized.match(/[\d.]+/g);
    return parts ? [+parts[0], +parts[1], +parts[2]] : [237, 237, 234];
  }

  function stageAt(time) {
    let local = ((time % cycleLength) + cycleLength) % cycleLength;
    for (let index = 0; index < TIMELINE.length; index++) {
      const stage = TIMELINE[index];
      const next = TIMELINE[(index + 1) % TIMELINE.length];
      if (local < stage.hold) return { from: stage.form, to: stage.form, progress: 1 };
      local -= stage.hold;
      if (local < TRANSITION) return { from: stage.form, to: next.form, progress: local / TRANSITION };
      local -= TRANSITION;
    }
    return { from: FORM_CLOUD, to: FORM_CLOUD, progress: 1 };
  }

  function createField(canvas) {
    const context = canvas.getContext("2d");
    const host = canvas.parentElement || document.body;
    const reduceMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const word = (canvas.dataset.word || "umut").trim() || "umut";
    const wordCenterY = clamp(parseFloat(canvas.dataset.wordY) || 0.5, 0.2, 0.8);

    let width = 0;
    let height = 0;
    let pixelRatio = 1;
    let count = 0;
    let posX, posY, velX, velY;
    let tone, delay, curl, freqA, freqB, phaseA, phaseB;
    let buckets = [];
    let forms = [];
    let colors = null;
    let clock = 0;
    let lastFrame = 0;
    let frameRequest = 0;
    let onScreen = true;
    let fontsSettled = false;

    const pointer = { x: 0, y: 0, active: false, strength: 0 };
    const annotation = { index: -1, born: 0, label: "", nextAt: 2.8 };

    function refreshColors() {
      const probe = document.createElement("canvas").getContext("2d");
      colors = {
        ink: readColor(probe, "--ink", "#ededea"),
        accent: readColor(probe, "--accent", "#ff3b5c"),
        muted: readColor(probe, "--muted", "#6b6b6b"),
        bg: readColor(probe, "--bg", "#0b0b0c")
      };
    }

    function desiredCount() {
      return Math.round(clamp((width * height) / 560, 900, 2000));
    }

    function allocate(total) {
      count = total;
      posX = new Float32Array(count);
      posY = new Float32Array(count);
      velX = new Float32Array(count);
      velY = new Float32Array(count);
      tone = new Uint8Array(count);
      delay = new Float32Array(count);
      curl = new Float32Array(count);
      freqA = new Float32Array(count);
      freqB = new Float32Array(count);
      phaseA = new Float32Array(count);
      phaseB = new Float32Array(count);
      const groups = [[], [], [], []];
      for (let i = 0; i < count; i++) {
        const roll = Math.random();
        const level = roll < 0.06 ? 3 : roll < 0.4 ? 0 : roll < 0.8 ? 1 : 2;
        tone[i] = level;
        groups[level].push(i);
        delay[i] = Math.random() * STAGGER;
        curl[i] = (Math.random() - 0.5) * 0.7;
        freqA[i] = 0.35 + Math.random() * 0.9;
        freqB[i] = 0.35 + Math.random() * 0.9;
        phaseA[i] = Math.random() * TAU;
        phaseB[i] = Math.random() * TAU;
      }
      buckets = groups.map((list) => Uint16Array.from(list));
      forms = [0, 1, 2, 3].map(() => new Float32Array(count * 2));
    }

    function buildCloud(out) {
      const spreadX = width * 0.2;
      const spreadY = height * 0.2;
      const lobes = [
        [0, 0, 1],
        [-0.22, 0.1, 0.55],
        [0.26, -0.12, 0.6]
      ];
      for (let i = 0; i < count; i++) {
        const lobe = lobes[i % lobes.length];
        out[i * 2] = width * (0.5 + lobe[0]) + gaussian() * spreadX * lobe[2];
        out[i * 2 + 1] = height * (0.5 + lobe[1]) + gaussian() * spreadY * lobe[2];
      }
    }

    function buildClusters(out) {
      const base = Math.min(width, height);
      const radiusX = width * (width > height ? 0.3 : 0.28);
      const radiusY = height * (width > height ? 0.28 : 0.22);
      const total = 6;
      const offset = Math.random() * TAU;
      const centers = [];
      for (let k = 0; k < total; k++) {
        const angle = offset + (k / total) * TAU + (Math.random() - 0.5) * 0.5;
        const reach = 0.55 + Math.random() * 0.45;
        centers.push({
          x: width / 2 + Math.cos(angle) * radiusX * reach,
          y: height / 2 + Math.sin(angle) * radiusY * reach,
          major: base * (0.04 + Math.random() * 0.035),
          minor: base * (0.018 + Math.random() * 0.02),
          angle: Math.random() * Math.PI
        });
      }
      const anomaly = {
        x: width / 2 + (Math.random() - 0.5) * radiusX * 0.25,
        y: height / 2 + (Math.random() - 0.5) * radiusY * 0.25,
        spread: base * 0.014
      };
      for (let i = 0; i < count; i++) {
        if (tone[i] === 3) {
          out[i * 2] = anomaly.x + gaussian() * anomaly.spread;
          out[i * 2 + 1] = anomaly.y + gaussian() * anomaly.spread;
          continue;
        }
        const center = centers[Math.floor(Math.pow(Math.random(), 1.15) * total)];
        const along = gaussian() * center.major;
        const across = gaussian() * center.minor;
        const cos = Math.cos(center.angle);
        const sin = Math.sin(center.angle);
        out[i * 2] = center.x + along * cos - across * sin;
        out[i * 2 + 1] = center.y + along * sin + across * cos;
      }
    }

    function buildSpiral(out) {
      const reach = Math.min(width * 0.36, height * 0.42);
      const stretch = width > height ? 1.15 : 1;
      for (let i = 0; i < count; i++) {
        const arm = i % 2;
        const t = Math.sqrt(Math.random());
        const angle = t * 3.1 * Math.PI + arm * Math.PI;
        const radius = 0.06 * reach + t * reach;
        const jitter = reach * 0.035 * (0.5 + t);
        out[i * 2] = width / 2 + Math.cos(angle) * radius * stretch + gaussian() * jitter;
        out[i * 2 + 1] = height / 2 + Math.sin(angle) * radius + gaussian() * jitter;
      }
    }

    function buildWord(out) {
      const surface = document.createElement("canvas");
      surface.width = Math.max(1, Math.ceil(width));
      surface.height = Math.max(1, Math.ceil(height));
      const paint = surface.getContext("2d", { willReadFrequently: true });
      paint.font = `800 100px ${DISPLAY}`;
      const unitWidth = Math.max(1, paint.measureText(word).width / 100);
      const share = width < 560 ? 0.86 : width < 900 ? 0.78 : 0.68;
      const size = Math.min((width * share) / unitWidth, height * 0.62);
      paint.font = `800 ${size}px ${DISPLAY}`;
      paint.textAlign = "center";
      paint.textBaseline = "alphabetic";
      const metrics = paint.measureText(word);
      const ascent = metrics.actualBoundingBoxAscent || size * 0.72;
      const descent = metrics.actualBoundingBoxDescent || 0;
      const baseline = height * wordCenterY + (ascent - descent) / 2;
      paint.fillStyle = "#fff";
      paint.fillText(word, width / 2, baseline);
      const pixels = paint.getImageData(0, 0, surface.width, surface.height).data;
      const stride = surface.width;
      let filled = 0;
      for (let p = 3; p < pixels.length; p += 4) if (pixels[p] > 127) filled++;
      if (filled === 0) {
        buildCloud(out);
        return;
      }
      const spacing = Math.max(1, Math.sqrt(filled / count) * 0.97);
      const candidates = [];
      for (let y = spacing / 2; y < surface.height; y += spacing) {
        const row = (y | 0) * stride;
        const shift = ((Math.round(y / spacing) & 1) * spacing) / 2;
        for (let x = spacing / 2 + shift; x < surface.width; x += spacing) {
          if (pixels[(row + (x | 0)) * 4 + 3] > 127) candidates.push(x, y);
        }
      }
      const available = candidates.length / 2;
      const order = new Uint32Array(available);
      for (let i = 0; i < available; i++) order[i] = i;
      for (let i = available - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        const swap = order[i];
        order[i] = order[j];
        order[j] = swap;
      }
      const jitter = spacing * 0.22;
      for (let i = 0; i < count; i++) {
        const pick = order[i % available];
        const loose = i >= available ? spacing * 0.45 : jitter;
        out[i * 2] = candidates[pick * 2] + (Math.random() - 0.5) * 2 * loose;
        out[i * 2 + 1] = candidates[pick * 2 + 1] + (Math.random() - 0.5) * 2 * loose;
      }
    }

    function buildForms() {
      buildCloud(forms[FORM_CLOUD]);
      buildWord(forms[FORM_WORD]);
      buildClusters(forms[FORM_CLUSTERS]);
      buildSpiral(forms[FORM_SPIRAL]);
    }

    function layout() {
      const nextWidth = host.clientWidth || window.innerWidth;
      const nextHeight = host.clientHeight || window.innerHeight;
      if (nextWidth < 2 || nextHeight < 2) return false;
      const resized = nextWidth !== width || nextHeight !== height;
      const firstLayout = width === 0;
      width = nextWidth;
      height = nextHeight;
      pixelRatio = Math.min(window.devicePixelRatio || 1, 2);
      canvas.width = Math.round(width * pixelRatio);
      canvas.height = Math.round(height * pixelRatio);
      canvas.style.width = width + "px";
      canvas.style.height = height + "px";
      const wanted = desiredCount();
      const reseed = firstLayout || Math.abs(wanted - count) / count > 0.25;
      if (reseed) allocate(wanted);
      if (resized || reseed) buildForms();
      if (reseed) {
        const start = reduceMotion ? forms[FORM_WORD] : forms[FORM_CLOUD];
        for (let i = 0; i < count; i++) {
          posX[i] = start[i * 2];
          posY[i] = start[i * 2 + 1];
          velX[i] = 0;
          velY[i] = 0;
        }
      }
      return true;
    }

    function formFrame(form, time) {
      const style = FORM_STYLE[form];
      const angle = time * style.spin;
      const scale = 1 + Math.sin(time * style.breatheRate) * style.breathe;
      return {
        data: forms[form],
        cos: Math.cos(angle) * scale,
        sin: Math.sin(angle) * scale,
        wobble: style.wobble
      };
    }

    function step(dt) {
      const stage = stageAt(clock);
      const from = formFrame(stage.from, clock);
      const to = formFrame(stage.to, clock);
      const centerX = width / 2;
      const centerY = height / 2;
      const aspect = width / height;
      const frames = dt * 60;
      const stiffness = 0.085 * frames;
      const damping = Math.pow(0.8, frames);
      const repelRadius = Math.max(70, Math.min(width, height) * 0.13);
      const repelSquared = repelRadius * repelRadius;
      const repelForce = 2.4 * pointer.strength * frames;
      const span = 1 - STAGGER;
      const moving = stage.from !== stage.to;
      for (let i = 0; i < count; i++) {
        const k = i * 2;
        const eased = moving ? easeInOut(clamp((stage.progress - delay[i]) / span, 0, 1)) : 1;
        let ax = from.data[k] - centerX;
        let ay = (from.data[k + 1] - centerY) * aspect;
        const fromX = centerX + ax * from.cos - ay * from.sin;
        const fromY = centerY + (ax * from.sin + ay * from.cos) / aspect;
        ax = to.data[k] - centerX;
        ay = (to.data[k + 1] - centerY) * aspect;
        const toX = centerX + ax * to.cos - ay * to.sin;
        const toY = centerY + (ax * to.sin + ay * to.cos) / aspect;
        const deltaX = toX - fromX;
        const deltaY = toY - fromY;
        const bend = Math.sin(Math.PI * eased) * curl[i];
        const wobble = from.wobble + (to.wobble - from.wobble) * eased;
        const homeX = fromX + deltaX * eased - deltaY * bend + Math.sin(clock * freqA[i] + phaseA[i]) * wobble;
        const homeY = fromY + deltaY * eased + deltaX * bend + Math.cos(clock * freqB[i] + phaseB[i]) * wobble;
        let vx = velX[i] + (homeX - posX[i]) * stiffness;
        let vy = velY[i] + (homeY - posY[i]) * stiffness;
        if (repelForce > 0.001) {
          const offX = posX[i] - pointer.x;
          const offY = posY[i] - pointer.y;
          const distSquared = offX * offX + offY * offY;
          if (distSquared < repelSquared && distSquared > 0.01) {
            const dist = Math.sqrt(distSquared);
            const falloff = 1 - dist / repelRadius;
            const push = falloff * falloff * repelForce;
            vx += (offX / dist) * push;
            vy += (offY / dist) * push;
          }
        }
        vx *= damping;
        vy *= damping;
        velX[i] = vx;
        velY[i] = vy;
        posX[i] += vx * frames;
        posY[i] += vy * frames;
      }
    }

    function drawGrid() {
      const muted = colors.muted;
      const gap = width < 560 ? 48 : 72;
      const startX = (width / 2) % gap;
      const startY = (height / 2) % gap;
      context.lineWidth = 1;
      context.strokeStyle = rgba(muted, 0.09);
      context.beginPath();
      for (let x = startX; x < width; x += gap) {
        const snapped = Math.round(x) + 0.5;
        context.moveTo(snapped, 0);
        context.lineTo(snapped, height);
      }
      for (let y = startY; y < height; y += gap) {
        const snapped = Math.round(y) + 0.5;
        context.moveTo(0, snapped);
        context.lineTo(width, snapped);
      }
      context.stroke();

      context.strokeStyle = rgba(muted, 0.3);
      context.beginPath();
      const crossSize = 3;
      for (let x = startX; x < width; x += gap) {
        for (let y = startY; y < height; y += gap) {
          const sx = Math.round(x) + 0.5;
          const sy = Math.round(y) + 0.5;
          context.moveTo(sx - crossSize, sy);
          context.lineTo(sx + crossSize, sy);
          context.moveTo(sx, sy - crossSize);
          context.lineTo(sx, sy + crossSize);
        }
      }
      context.stroke();

      const margin = width < 560 ? 16 : 28;
      const length = Math.min(120, width * 0.22);
      const originX = margin + 0.5;
      const originY = Math.round(height - margin) + 0.5;
      context.strokeStyle = rgba(muted, 0.75);
      context.beginPath();
      context.moveTo(originX, originY - length);
      context.lineTo(originX, originY);
      context.lineTo(originX + length, originY);
      for (let t = 1; t <= 4; t++) {
        const along = (length / 4) * t;
        context.moveTo(originX + along, originY);
        context.lineTo(originX + along, originY + 4);
        context.moveTo(originX, originY - along);
        context.lineTo(originX - 4, originY - along);
      }
      context.stroke();
      context.fillStyle = rgba(muted, 0.95);
      context.font = `10px ${MONO}`;
      context.textBaseline = "middle";
      context.textAlign = "left";
      context.fillText("z₁", originX + length + 6, originY);
      context.textAlign = "center";
      context.fillText("z₂", originX, originY - length - 10);
    }

    function drawTrails() {
      context.lineWidth = 1;
      context.lineCap = "round";
      for (let level = 0; level < 4; level++) {
        const list = buckets[level];
        context.strokeStyle = level === 3 ? rgba(colors.accent, 0.32) : rgba(colors.ink, 0.07 + level * 0.04);
        context.beginPath();
        for (let n = 0; n < list.length; n++) {
          const i = list[n];
          const vx = velX[i];
          const vy = velY[i];
          const speedSquared = vx * vx + vy * vy;
          if (speedSquared < 0.36) continue;
          const reach = Math.min(5, 22 / Math.sqrt(speedSquared));
          context.moveTo(posX[i], posY[i]);
          context.lineTo(posX[i] - vx * reach, posY[i] - vy * reach);
        }
        context.stroke();
      }
    }

    function drawPoints() {
      const sizes = [1.3, 1.6, 1.9, 2.3];
      const alphas = [0.32, 0.58, 0.9, 1];
      for (let level = 0; level < 4; level++) {
        const list = buckets[level];
        const size = sizes[level];
        const half = size / 2;
        context.fillStyle = level === 3 ? rgba(colors.accent, alphas[level]) : rgba(colors.ink, alphas[level]);
        for (let n = 0; n < list.length; n++) {
          const i = list[n];
          context.fillRect(posX[i] - half, posY[i] - half, size, size);
        }
      }
    }

    function pickAnnotation() {
      const accents = buckets[3];
      for (let attempt = 0; attempt < 24; attempt++) {
        const pool = attempt < 12 && accents.length ? accents : null;
        const index = pool ? pool[Math.floor(Math.random() * pool.length)] : Math.floor(Math.random() * count);
        const x = posX[index];
        const y = posY[index];
        if (x > width * 0.12 && x < width * 0.82 && y > height * 0.14 && y < height * 0.86) return index;
      }
      return Math.floor(Math.random() * count);
    }

    function updateAnnotation() {
      if (annotation.index < 0 && clock >= annotation.nextAt) {
        annotation.index = pickAnnotation();
        annotation.born = clock;
        annotation.label = BUG_LABELS[Math.floor(Math.random() * BUG_LABELS.length)];
      }
      if (annotation.index >= 0 && clock - annotation.born > 3.8) {
        annotation.index = -1;
        annotation.nextAt = clock + 2.6 + Math.random() * 3.2;
      }
    }

    function drawAnnotation(opacity) {
      if (annotation.index < 0 || annotation.index >= count) return;
      const x = posX[annotation.index];
      const y = posY[annotation.index];
      const accent = colors.accent;
      const ink = colors.ink;
      const ring = 7;
      const arm = 16;
      context.lineWidth = 1;
      context.strokeStyle = rgba(accent, 0.9 * opacity);
      context.beginPath();
      context.arc(x, y, ring, 0, TAU);
      context.moveTo(x - arm, y);
      context.lineTo(x - ring - 3, y);
      context.moveTo(x + ring + 3, y);
      context.lineTo(x + arm, y);
      context.moveTo(x, y - arm);
      context.lineTo(x, y - ring - 3);
      context.moveTo(x, y + ring + 3);
      context.lineTo(x, y + arm);
      context.stroke();

      context.font = `11px ${MONO}`;
      const textWidth = context.measureText(annotation.label).width;
      const flip = x + 34 + textWidth > width - 12;
      const lift = y - 30 < 12 ? 30 : -30;
      const elbowX = flip ? x - 22 : x + 22;
      const elbowY = y + lift;
      const tailX = flip ? elbowX - 8 : elbowX + 8;
      context.strokeStyle = rgba(ink, 0.45 * opacity);
      context.beginPath();
      context.moveTo(x + (flip ? -ring : ring) * 0.7, y + (lift < 0 ? -ring : ring) * 0.7);
      context.lineTo(elbowX, elbowY);
      context.lineTo(tailX, elbowY);
      context.stroke();
      context.textBaseline = "middle";
      context.textAlign = flip ? "right" : "left";
      const textX = flip ? tailX - 6 : tailX + 6;
      context.fillStyle = rgba(colors.bg, 0.72 * opacity);
      context.fillRect(flip ? textX - textWidth - 4 : textX - 4, elbowY - 9, textWidth + 8, 18);
      context.fillStyle = rgba(ink, 0.92 * opacity);
      context.fillText(annotation.label, textX, elbowY + 0.5);
    }

    function annotationOpacity() {
      if (annotation.index < 0) return 0;
      const age = clock - annotation.born;
      return clamp(Math.min(age / 0.5, (3.8 - age) / 0.7), 0, 1);
    }

    function render(withTrails) {
      context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
      context.clearRect(0, 0, width, height);
      drawGrid();
      if (withTrails) drawTrails();
      drawPoints();
    }

    function frame(now) {
      frameRequest = 0;
      if (!running()) return;
      const dt = lastFrame ? clamp((now - lastFrame) / 1000, 0, 1 / 20) : 1 / 60;
      lastFrame = now;
      clock += dt;
      pointer.strength += ((pointer.active ? 1 : 0) - pointer.strength) * Math.min(1, dt * 6);
      step(dt);
      updateAnnotation();
      render(true);
      drawAnnotation(annotationOpacity());
      frameRequest = requestAnimationFrame(frame);
    }

    function running() {
      return !reduceMotion && fontsSettled && onScreen && !document.hidden && width > 0;
    }

    function resume() {
      if (running() && !frameRequest) {
        lastFrame = 0;
        frameRequest = requestAnimationFrame(frame);
      }
    }

    function renderStill() {
      const target = forms[FORM_WORD];
      for (let i = 0; i < count; i++) {
        posX[i] = target[i * 2];
        posY[i] = target[i * 2 + 1];
        velX[i] = 0;
        velY[i] = 0;
      }
      render(false);
      let best = -1;
      let bestScore = Infinity;
      for (let n = 0; n < buckets[3].length; n++) {
        const i = buckets[3][n];
        const score = Math.abs(posX[i] - width * 0.62) + Math.abs(posY[i] - height * 0.45);
        if (score < bestScore) {
          bestScore = score;
          best = i;
        }
      }
      if (best >= 0) {
        annotation.index = best;
        annotation.label = BUG_LABELS[0];
        drawAnnotation(1);
      }
    }

    function relayout() {
      if (!layout()) return;
      if (reduceMotion) {
        if (fontsSettled) renderStill();
      } else if (!frameRequest && fontsSettled) {
        render(true);
      }
    }

    function trackPointer(clientX, clientY) {
      const rect = canvas.getBoundingClientRect();
      const x = clientX - rect.left;
      const y = clientY - rect.top;
      pointer.x = x;
      pointer.y = y;
      pointer.active = x >= 0 && y >= 0 && x <= rect.width && y <= rect.height;
    }

    refreshColors();
    if (!layout()) {
      width = 0;
      height = 0;
    }

    if (!reduceMotion) {
      window.addEventListener("pointermove", (event) => trackPointer(event.clientX, event.clientY), { passive: true });
      window.addEventListener("pointerdown", (event) => trackPointer(event.clientX, event.clientY), { passive: true });
      window.addEventListener("pointerup", (event) => {
        if (event.pointerType !== "mouse") pointer.active = false;
      }, { passive: true });
      window.addEventListener("pointercancel", () => { pointer.active = false; }, { passive: true });
      document.addEventListener("pointerleave", () => { pointer.active = false; });
      window.addEventListener("blur", () => { pointer.active = false; });
    }

    if ("ResizeObserver" in window) {
      new ResizeObserver(relayout).observe(host);
    }
    window.addEventListener("resize", relayout);

    if ("IntersectionObserver" in window) {
      new IntersectionObserver((entries) => {
        onScreen = entries[entries.length - 1].isIntersecting;
        resume();
      }).observe(canvas);
    }
    document.addEventListener("visibilitychange", resume);

    if (window.matchMedia) {
      const scheme = window.matchMedia("(prefers-color-scheme: dark)");
      const onScheme = () => {
        refreshColors();
        if (reduceMotion && fontsSettled) renderStill();
      };
      if (scheme.addEventListener) scheme.addEventListener("change", onScheme);
    }

    const settle = () => {
      if (fontsSettled) return;
      fontsSettled = true;
      if (width === 0 && !layout()) return;
      buildWord(forms[FORM_WORD]);
      if (reduceMotion) renderStill();
      else resume();
    };

    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(settle, settle);
      setTimeout(settle, 1500);
      document.fonts.addEventListener && document.fonts.addEventListener("loadingdone", () => {
        if (!fontsSettled || width === 0) return;
        buildWord(forms[FORM_WORD]);
        if (reduceMotion) renderStill();
      });
    } else {
      settle();
    }
  }

  function boot() {
    const canvas = document.getElementById("latent");
    if (!canvas || !canvas.getContext) return;
    createField(canvas);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }
})();
