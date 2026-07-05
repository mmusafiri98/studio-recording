<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Studio — Console d'enregistrement</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
  :root{
    --panel-dark:   #212023;
    --panel-mid:    #2c2b2f;
    --panel-light:  #3a393e;
    --panel-edge:   #171618;
    --wood:         #4a2f1c;
    --wood-light:   #6e4626;
    --wood-grain:   #3a2415;
    --cream:        #e9e3d4;
    --steel-text:   #a9a7ad;
    --steel-dim:    #6b696f;
    --led-green:    #59d488;
    --led-amber:    #f2b134;
    --led-red:      #e2483d;
    --accent-rec:   #d9463b;
    --focus-ring:   #f2b134;
  }

  *{ box-sizing:border-box; }
  @media (prefers-reduced-motion: reduce){
    *{ animation-duration:0.001ms !important; transition-duration:0.001ms !important; }
  }

  html,body{
    margin:0; padding:0;
    background: #0e0d0f;
    color: var(--cream);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    min-height:100vh;
  }

  body{
    display:flex;
    align-items:flex-start;
    justify-content:center;
    padding: 28px 12px 60px;
  }

  ::selection{ background: var(--led-amber); color:#1a1a1a; }

  button, input, select{ font-family:inherit; }
  button{ cursor:pointer; }
  :focus-visible{ outline: 2px solid var(--focus-ring); outline-offset: 2px; }

  .console{
    width:100%;
    max-width: 1180px;
    background:
      linear-gradient(180deg, var(--panel-dark), #1a191c 92%);
    border-radius: 14px;
    box-shadow:
      0 0 0 1px var(--panel-edge),
      0 30px 60px -20px rgba(0,0,0,0.7),
      inset 0 1px 0 rgba(255,255,255,0.04);
    overflow:hidden;
  }

  /* ── Rail du haut : marque + transport ─────────────────── */
  .top-rail{
    display:flex;
    align-items:center;
    gap: 22px;
    padding: 14px 22px;
    background:
      repeating-linear-gradient(115deg, var(--wood) 0 10px, var(--wood-grain) 10px 12px, var(--wood-light) 12px 22px);
    border-bottom: 3px solid var(--panel-edge);
    flex-wrap: wrap;
  }

  .brand{
    font-family:"Big Shoulders Display", sans-serif;
    font-weight:700;
    font-size: 26px;
    letter-spacing: 1px;
    color: var(--cream);
    text-shadow: 0 1px 0 rgba(0,0,0,0.5);
    white-space:nowrap;
  }
  .brand span{ color: var(--led-amber); }

  .transport{
    display:flex;
    align-items:center;
    gap:10px;
    margin-left: auto;
    flex-wrap: wrap;
  }

  .xport-btn{
    width:44px; height:44px;
    border-radius:50%;
    border: none;
    background: radial-gradient(circle at 35% 30%, #4a4950, #201f22 75%);
    box-shadow:
      0 3px 0 var(--panel-edge),
      inset 0 1px 1px rgba(255,255,255,0.15);
    color: var(--cream);
    font-size:16px;
    display:flex; align-items:center; justify-content:center;
    transition: transform 60ms ease;
  }
  .xport-btn:active{ transform: translateY(2px); box-shadow: 0 1px 0 var(--panel-edge); }
  .xport-btn.rec{ color:#ffd4d0; }
  .xport-btn.rec.is-live{
    background: radial-gradient(circle at 35% 30%, #ff6b5e, var(--accent-rec) 75%);
    animation: pulse-rec 1.1s ease-in-out infinite;
  }
  @keyframes pulse-rec{
    0%,100%{ box-shadow: 0 3px 0 var(--panel-edge), 0 0 0 0 rgba(217,70,59,0.55); }
    50%{ box-shadow: 0 3px 0 var(--panel-edge), 0 0 0 8px rgba(217,70,59,0); }
  }
  .xport-btn:disabled{ opacity:0.35; cursor:not-allowed; }

  .readout{
    font-family:"Space Mono", monospace;
    font-size: 20px;
    letter-spacing: 1px;
    background: #0e0d0f;
    color: var(--led-amber);
    padding: 8px 14px;
    border-radius: 6px;
    box-shadow: inset 0 0 6px rgba(0,0,0,0.8), inset 0 0 0 1px #000;
    min-width: 108px;
    text-align:center;
  }

  .status-line{
    font-size:12px;
    color: var(--cream);
    opacity:0.85;
    background: rgba(0,0,0,0.35);
    padding: 6px 12px;
    border-radius: 6px;
    width:100%;
    margin-top: 2px;
  }

  /* ── Bandeau des tranches ───────────────────────────────── */
  .strips{
    display:flex;
    gap:14px;
    padding: 20px;
    overflow-x:auto;
    background:
      radial-gradient(ellipse at top, #29282c, #201f22 70%);
  }

  .strip{
    flex: 0 0 132px;
    background: linear-gradient(180deg, var(--panel-mid), var(--panel-dark));
    border-radius: 8px;
    box-shadow:
      0 0 0 1px var(--panel-edge),
      inset 0 1px 0 rgba(255,255,255,0.05);
    padding: 12px 10px 16px;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:10px;
  }
  .strip.master{
    flex: 0 0 148px;
    background: linear-gradient(180deg, #33302a, #201d19);
    box-shadow: 0 0 0 1px var(--panel-edge), inset 0 0 0 2px rgba(242,177,52,0.15);
  }

  .strip-name{
    width:100%;
    text-align:center;
    background:none;
    border:none;
    border-bottom: 1px dashed var(--steel-dim);
    color: var(--cream);
    font-family:"Big Shoulders Display", sans-serif;
    font-weight:600;
    font-size:15px;
    letter-spacing:0.5px;
    text-transform:uppercase;
    padding:2px 0 4px;
  }
  .strip-name:disabled{ color: var(--led-amber); opacity:1; -webkit-text-fill-color: var(--led-amber); }

  .mini-btn-row{ display:flex; gap:6px; }
  .mini-btn{
    width:26px; height:26px;
    border-radius:5px;
    border:none;
    background: #232226;
    color: var(--steel-text);
    font-size:11px;
    font-weight:700;
    box-shadow: inset 0 0 0 1px #050505, 0 1px 0 rgba(255,255,255,0.05);
  }
  .mini-btn.arm.is-armed{ background: var(--accent-rec); color:#fff0ee; }
  .mini-btn.mute.is-on{ background:#4a4640; color: var(--led-amber); }
  .mini-btn.solo.is-on{ background:#3a4a3d; color: var(--led-green); }

  /* VU meter */
  .vu{
    display:flex; flex-direction:column-reverse;
    gap:2px;
    width:22px; height:96px;
    padding:4px;
    background:#08080a;
    border-radius:4px;
    box-shadow: inset 0 0 4px rgba(0,0,0,0.9), inset 0 0 0 1px #000;
  }
  .vu-seg{
    flex:1;
    border-radius:1px;
    background:#1c1c1e;
    transition: background-color 45ms linear, box-shadow 45ms linear;
  }
  .vu-seg.g{ }
  .vu-seg.on.g{ background: var(--led-green); box-shadow: 0 0 5px var(--led-green); }
  .vu-seg.on.a{ background: var(--led-amber); box-shadow: 0 0 5px var(--led-amber); }
  .vu-seg.on.r{ background: var(--led-red); box-shadow: 0 0 6px var(--led-red); }

  .mid-row{ display:flex; gap:10px; align-items:flex-end; }

  /* Fader vertical (input range tourné) */
  .fader-wrap{
    position:relative;
    width:36px; height:140px;
    background: linear-gradient(90deg, transparent 46%, #0c0c0d 46% 54%, transparent 54%);
    border-radius:3px;
  }
  .fader-wrap input[type=range]{
    position:absolute;
    width:140px; height:34px;
    top:53px; left:-52px;
    transform: rotate(-90deg);
    -webkit-appearance:none;
    appearance:none;
    background:transparent;
  }
  .fader-wrap input[type=range]::-webkit-slider-runnable-track{
    height:4px; background:transparent;
  }
  .fader-wrap input[type=range]::-webkit-slider-thumb{
    -webkit-appearance:none;
    width:44px; height:20px;
    margin-top:-8px;
    border-radius:3px;
    background: linear-gradient(180deg, #cfcdd2, #8f8d93 45%, #55545a);
    box-shadow: 0 1px 3px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.4);
    border: 1px solid #1c1c1e;
  }
  .fader-wrap input[type=range]::-moz-range-thumb{
    width:44px; height:20px;
    border-radius:3px;
    background: linear-gradient(180deg, #cfcdd2, #8f8d93 45%, #55545a);
    box-shadow: 0 1px 3px rgba(0,0,0,0.6);
    border: 1px solid #1c1c1e;
  }
  .fader-wrap input[type=range]::-moz-range-track{ height:4px; background:transparent; }

  .fader-ticks{
    position:absolute; left:-14px; top:0; bottom:0;
    display:flex; flex-direction:column; justify-content:space-between;
    font-family:"Space Mono", monospace;
    font-size:7px; color: var(--steel-dim);
    pointer-events:none;
  }

  /* Potard de panoramique */
  .knob-block{ display:flex; flex-direction:column; align-items:center; gap:4px; }
  .knob{
    width:38px; height:38px;
    border-radius:50%;
    background: radial-gradient(circle at 33% 28%, #57565c, #29282c 70%);
    box-shadow: 0 2px 0 #0c0c0d, inset 0 0 0 1px rgba(255,255,255,0.06);
    position:relative;
    touch-action:none;
  }
  .knob::after{
    content:"";
    position:absolute;
    top:4px; left:50%;
    width:3px; height:12px;
    background: var(--led-amber);
    border-radius:2px;
    transform-origin: 50% 15px;
    transform: translateX(-50%) rotate(var(--angle, 0deg));
  }
  .knob-label{
    font-family:"Space Mono", monospace;
    font-size:9px;
    color: var(--steel-text);
  }

  .clip-status{
    font-size:10px;
    text-align:center;
    color: var(--steel-text);
    min-height: 26px;
    line-height:1.3;
  }
  .clip-status.recording{ color: var(--led-red); font-weight:bold; }
  .clip-status.has-clip{ color: var(--led-green); }

  .dl-link{
    font-size:10px;
    color: var(--led-amber);
    text-decoration:none;
    border:1px solid var(--led-amber);
    border-radius:4px;
    padding:2px 6px;
    display:none;
  }
  .dl-link.visible{ display:inline-block; }

  .master-label{
    font-family:"Big Shoulders Display", sans-serif;
    font-size:16px;
    letter-spacing:1px;
    color: var(--led-amber);
    text-transform:uppercase;
  }

  /* ── Rail du bas : métronome + aide ─────────────────────── */
  .bottom-rail{
    display:flex;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
    padding: 14px 22px 18px;
    background: var(--panel-dark);
    border-top: 1px solid var(--panel-edge);
  }

  .metro{
    display:flex; align-items:center; gap:10px;
    background: rgba(0,0,0,0.3);
    padding: 8px 12px;
    border-radius: 8px;
  }
  .metro label{
    font-family:"Big Shoulders Display", sans-serif;
    font-size:13px;
    letter-spacing:0.5px;
    color: var(--steel-text);
    text-transform:uppercase;
  }
  .bpm-input{
    width:54px;
    background:#0e0d0f;
    border: 1px solid #050505;
    color: var(--led-amber);
    font-family:"Space Mono", monospace;
    font-size:14px;
    text-align:center;
    border-radius:4px;
    padding:4px 2px;
  }
  .click-toggle{
    border:none;
    border-radius:6px;
    padding: 6px 12px;
    font-size:12px;
    font-weight:700;
    background:#232226;
    color: var(--steel-text);
    box-shadow: inset 0 0 0 1px #050505;
  }
  .click-toggle.is-on{ background: var(--led-green); color:#0e2015; }

  .help{
    font-size:11px;
    color: var(--steel-dim);
    max-width: 460px;
    line-height:1.5;
  }

  @media (max-width: 640px){
    .top-rail{ justify-content:center; }
    .brand{ width:100%; text-align:center; }
    .transport{ margin-left:0; width:100%; justify-content:center; }
    .strips{ padding:14px; }
  }
</style>
</head>
<body>

<div class="console">

  <!-- ═══ TRANSPORT ═══ -->
  <div class="top-rail">
    <div class="brand">STUDIO <span>4</span>-PISTES</div>
    <div class="transport">
      <button class="xport-btn rec" id="btnRec" title="Enregistrer la piste armée">●</button>
      <button class="xport-btn" id="btnPlay" title="Écouter le mixage">▶</button>
      <button class="xport-btn" id="btnStop" title="Arrêter">■</button>
      <div class="readout" id="clock">00:00.0</div>
    </div>
    <div class="status-line" id="statusLine">Arme une piste (bouton rouge) puis appuie sur ● pour enregistrer. Utilise un casque pour éviter le larsen pendant les prises superposées.</div>
  </div>

  <!-- ═══ TRANCHES DE VOIE ═══ -->
  <div class="strips" id="strips"><!-- généré en JS --></div>

  <!-- ═══ MÉTRONOME / AIDE ═══ -->
  <div class="bottom-rail">
    <div class="metro">
      <label for="bpm">Tempo</label>
      <input type="number" id="bpm" class="bpm-input" value="96" min="30" max="240">
      <label>bpm</label>
      <button class="click-toggle" id="btnClick">CLIC ○</button>
    </div>
    <div class="help">
      Chaque piste s'arme individuellement (bouton rouge) : une seule piste enregistre à la fois,
      les autres se rejouent pour que tu puisses chanter ou jouer par-dessus — comme en studio.
      Fader = volume, potard = panoramique (glisser verticalement). Le bouton de téléchargement
      apparaît sous chaque piste une fois la prise terminée.
    </div>
  </div>

</div>

<script>
(() => {
  "use strict";

  const NUM_TRACKS = 4;
  const stripsEl   = document.getElementById("strips");
  const statusEl   = document.getElementById("statusLine");
  const clockEl    = document.getElementById("clock");

  let audioCtx = null;
  let masterGain = null, masterAnalyser = null;
  let armedTrackId = null;
  let isRecording = false;
  let isPlaying = false;
  let clockStart = 0;
  let clockRAF = null;
  let liveSources = [];      // AudioBufferSourceNode en cours de lecture
  let liveMicAnalyser = null;
  let currentStream = null;
  let meterRAF = null;

  function ensureContext(){
    if (!audioCtx){
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      masterGain = audioCtx.createGain();
      masterGain.gain.value = 0.9;
      masterAnalyser = audioCtx.createAnalyser();
      masterAnalyser.fftSize = 256;
      masterGain.connect(masterAnalyser);
      masterAnalyser.connect(audioCtx.destination);
    }
    if (audioCtx.state === "suspended") audioCtx.resume();
    return audioCtx;
  }

  function setStatus(msg){ statusEl.textContent = msg; }

  function formatTime(sec){
    const m = Math.floor(sec / 60);
    const s = (sec % 60).toFixed(1).padStart(4, "0");
    return `${String(m).padStart(2,"0")}:${s}`;
  }

  // ── Construction des tranches ────────────────────────────
  const tracks = [];

  function buildMeterHTML(){
    let html = "";
    const colors = ["g","g","g","g","g","g","a","a","r","r"];
    for (const c of colors) html += `<div class="vu-seg ${c}"></div>`;
    return html;
  }

  function createStrip(index){
    const wrap = document.createElement("div");
    wrap.className = "strip";
    wrap.innerHTML = `
      <input class="strip-name" value="Piste ${index+1}" maxlength="14">
      <div class="mini-btn-row">
        <button class="mini-btn arm" title="Armer l'enregistrement">●</button>
        <button class="mini-btn mute" title="Muet">M</button>
        <button class="mini-btn solo" title="Solo">S</button>
      </div>
      <div class="vu">${buildMeterHTML()}</div>
      <div class="mid-row">
        <div class="fader-wrap">
          <div class="fader-ticks"><span>+6</span><span>0</span><span>-∞</span></div>
          <input type="range" min="0" max="1.2" step="0.01" value="0.85" class="fader">
        </div>
        <div class="knob-block">
          <div class="knob" style="--angle:0deg"></div>
          <div class="knob-label">PAN</div>
        </div>
      </div>
      <div class="clip-status">Vide</div>
      <a class="dl-link" download>⬇ Télécharger</a>
    `;
    stripsEl.appendChild(wrap);

    const track = {
      id: index,
      el: wrap,
      nameInput: wrap.querySelector(".strip-name"),
      armBtn: wrap.querySelector(".arm"),
      muteBtn: wrap.querySelector(".mute"),
      soloBtn: wrap.querySelector(".solo"),
      vuSegs: [...wrap.querySelectorAll(".vu-seg")],
      fader: wrap.querySelector(".fader"),
      knob: wrap.querySelector(".knob"),
      statusEl: wrap.querySelector(".clip-status"),
      dlLink: wrap.querySelector(".dl-link"),
      armed: false,
      muted: false,
      solo: false,
      pan: 0,
      hasClip: false,
      audioBuffer: null,
      blobUrl: null,
      gainNode: null,
      pannerNode: null,
      analyserNode: null,
      recorder: null,
      chunks: [],
    };
    tracks.push(track);
    wireStrip(track);
    return track;
  }

  function wireStrip(track){
    track.armBtn.addEventListener("click", () => {
      if (isRecording) return; // pas de changement d'armement en pleine prise
      if (armedTrackId === track.id){
        armedTrackId = null;
        track.armBtn.classList.remove("is-armed");
        setStatus("Piste désarmée.");
        return;
      }
      tracks.forEach(t => t.armBtn.classList.remove("is-armed"));
      armedTrackId = track.id;
      track.armBtn.classList.add("is-armed");
      setStatus(`« ${track.nameInput.value} » armée. Appuie sur ● pour enregistrer.`);
    });

    track.muteBtn.addEventListener("click", () => {
      track.muted = !track.muted;
      track.muteBtn.classList.toggle("is-on", track.muted);
      recomputeGains();
    });

    track.soloBtn.addEventListener("click", () => {
      track.solo = !track.solo;
      track.soloBtn.classList.toggle("is-on", track.solo);
      recomputeGains();
    });

    track.fader.addEventListener("input", recomputeGains);

    attachKnob(track.knob, (value) => {
      track.pan = value; // -1..1
      track.knob.style.setProperty("--angle", `${value * 135}deg`);
      if (track.pannerNode) track.pannerNode.pan.value = value;
    });
  }

  function attachKnob(knobEl, onChange){
    let dragging = false;
    let startY = 0;
    let startVal = 0;
    let value = 0;

    function pointerDown(e){
      dragging = true;
      startY = (e.touches ? e.touches[0].clientY : e.clientY);
      startVal = value;
      knobEl.setPointerCapture && e.pointerId != null && knobEl.setPointerCapture(e.pointerId);
    }
    function pointerMove(e){
      if (!dragging) return;
      const y = (e.touches ? e.touches[0].clientY : e.clientY);
      const delta = (startY - y) / 90; // sensibilité
      value = Math.max(-1, Math.min(1, startVal + delta));
      onChange(value);
    }
    function pointerUp(){ dragging = false; }

    knobEl.addEventListener("pointerdown", pointerDown);
    window.addEventListener("pointermove", pointerMove);
    window.addEventListener("pointerup", pointerUp);
    knobEl.addEventListener("dblclick", () => { value = 0; onChange(0); });
  }

  function recomputeGains(){
    const anySolo = tracks.some(t => t.solo);
    tracks.forEach(t => {
      if (!t.gainNode) return;
      let g = parseFloat(t.fader.value);
      if (t.muted) g = 0;
      else if (anySolo && !t.solo) g = 0;
      t.gainNode.gain.value = g;
    });
  }

  // ── Enregistrement ────────────────────────────────────────
  async function startRecording(){
    if (armedTrackId === null){
      setStatus("Aucune piste armée : clique sur le bouton rouge d'une piste avant d'enregistrer.");
      return;
    }
    ensureContext();
    const track = tracks[armedTrackId];

    try{
      currentStream = await navigator.mediaDevices.getUserMedia({ audio: true });
    }catch(err){
      setStatus("Micro inaccessible : vérifie les autorisations du navigateur pour ce site.");
      return;
    }

    // Mètre d'entrée sur le flux micro brut (pas connecté à la sortie : pas de larsen)
    const micSource = audioCtx.createMediaStreamSource(currentStream);
    liveMicAnalyser = audioCtx.createAnalyser();
    liveMicAnalyser.fftSize = 256;
    micSource.connect(liveMicAnalyser);

    track.chunks = [];
    track.recorder = new MediaRecorder(currentStream);
    track.recorder.ondataavailable = (e) => { if (e.data.size > 0) track.chunks.push(e.data); };
    track.recorder.onstop = () => finalizeRecording(track);
    track.recorder.start();

    isRecording = true;
    track.statusEl.textContent = "● Enregistrement…";
    track.statusEl.className = "clip-status recording";
    document.getElementById("btnRec").classList.add("is-live");
    setStatus(`Enregistrement de « ${track.nameInput.value} »… Les autres pistes se rejouent si elles ont déjà une prise.`);

    startClock();
    startMeterLoop();
    playOtherTracks(track.id); // écoute des autres pistes pendant la prise
  }

  async function finalizeRecording(track){
    const blob = new Blob(track.chunks, { type: track.recorder.mimeType || "audio/webm" });
    const arrayBuf = await blob.arrayBuffer();
    try{
      track.audioBuffer = await audioCtx.decodeAudioData(arrayBuf);
    }catch(err){
      setStatus("Erreur au décodage de l'enregistrement. Réessaie.");
      return;
    }
    track.hasClip = true;
    track.statusEl.textContent = `✓ Enregistrée (${track.audioBuffer.duration.toFixed(1)}s)`;
    track.statusEl.className = "clip-status has-clip";

    if (track.blobUrl) URL.revokeObjectURL(track.blobUrl);
    track.blobUrl = URL.createObjectURL(blob);
    track.dlLink.href = track.blobUrl;
    track.dlLink.download = `${track.nameInput.value.trim() || "piste"}.webm`;
    track.dlLink.classList.add("visible");

    if (currentStream){
      currentStream.getTracks().forEach(t => t.stop());
      currentStream = null;
    }
  }

  // ── Lecture ────────────────────────────────────────────────
  function buildChainIfNeeded(track){
    if (track.gainNode) return;
    track.gainNode = audioCtx.createGain();
    track.gainNode.gain.value = parseFloat(track.fader.value);
    track.pannerNode = audioCtx.createStereoPanner();
    track.pannerNode.pan.value = track.pan;
    track.analyserNode = audioCtx.createAnalyser();
    track.analyserNode.fftSize = 256;
    track.gainNode.connect(track.pannerNode);
    track.pannerNode.connect(track.analyserNode);
    track.analyserNode.connect(masterGain);
  }

  function playOtherTracks(excludeId){
    tracks.forEach(t => {
      if (t.id === excludeId || !t.hasClip) return;
      playSingleTrack(t);
    });
  }

  function playSingleTrack(track){
    buildChainIfNeeded(track);
    const src = audioCtx.createBufferSource();
    src.buffer = track.audioBuffer;
    src.connect(track.gainNode);
    src.start();
    liveSources.push(src);
    return src;
  }

  function playAll(){
    ensureContext();
    const withClip = tracks.filter(t => t.hasClip);
    if (withClip.length === 0){
      setStatus("Aucune piste enregistrée pour l'instant.");
      return;
    }
    recomputeGains();
    let maxDur = 0;
    withClip.forEach(t => {
      const src = playSingleTrack(t);
      maxDur = Math.max(maxDur, t.audioBuffer.duration);
    });
    isPlaying = true;
    setStatus("Lecture du mixage…");
    startClock();
    startMeterLoop();
    setTimeout(() => { if (isPlaying) stopAll(); }, maxDur * 1000 + 120);
  }

  function stopAll(){
    liveSources.forEach(s => { try{ s.stop(); }catch(e){} });
    liveSources = [];

    if (isRecording && armedTrackId !== null){
      const track = tracks[armedTrackId];
      if (track.recorder && track.recorder.state !== "inactive") track.recorder.stop();
    }
    isRecording = false;
    isPlaying = false;
    document.getElementById("btnRec").classList.remove("is-live");
    stopClock();
    stopMeterLoop();
    resetMeters();
    setStatus("Arrêté.");
  }

  // ── Horloge ───────────────────────────────────────────────
  function startClock(){
    clockStart = audioCtx.currentTime;
    function tick(){
      const elapsed = audioCtx.currentTime - clockStart;
      clockEl.textContent = formatTime(elapsed);
      clockRAF = requestAnimationFrame(tick);
    }
    tick();
  }
  function stopClock(){
    if (clockRAF) cancelAnimationFrame(clockRAF);
    clockRAF = null;
  }

  // ── VU-mètres ─────────────────────────────────────────────
  function levelFromAnalyser(analyser){
    const data = new Uint8Array(analyser.fftSize);
    analyser.getByteTimeDomainData(data);
    let sumSquares = 0;
    for (let i = 0; i < data.length; i++){
      const v = (data[i] - 128) / 128;
      sumSquares += v * v;
    }
    return Math.sqrt(sumSquares / data.length); // RMS 0..1
  }

  function paintMeter(track, level){
    const litCount = Math.round(Math.min(1, level * 3.2) * track.vuSegs.length);
    track.vuSegs.forEach((seg, i) => seg.classList.toggle("on", i < litCount));
  }

  function resetMeters(){
    tracks.forEach(t => t.vuSegs.forEach(s => s.classList.remove("on")));
  }

  function startMeterLoop(){
    function loop(){
      if (isRecording && armedTrackId !== null && liveMicAnalyser){
        paintMeter(tracks[armedTrackId], levelFromAnalyser(liveMicAnalyser));
      }
      tracks.forEach(t => {
        if (t.id === armedTrackId && isRecording) return;
        if (t.analyserNode) paintMeter(t, levelFromAnalyser(t.analyserNode));
      });
      meterRAF = requestAnimationFrame(loop);
    }
    loop();
  }
  function stopMeterLoop(){
    if (meterRAF) cancelAnimationFrame(meterRAF);
    meterRAF = null;
  }

  // ── Métronome (planification anticipée) ────────────────────
  let clickOn = false;
  let nextNoteTime = 0;
  let schedulerTimer = null;
  const scheduleAheadTime = 0.1;
  const lookaheadMs = 25;

  function scheduleClick(time){
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.frequency.value = 1000;
    gain.gain.setValueAtTime(0.25, time);
    gain.gain.exponentialRampToValueAtTime(0.001, time + 0.05);
    osc.connect(gain);
    gain.connect(audioCtx.destination);
    osc.start(time);
    osc.stop(time + 0.06);
  }

  function metronomeScheduler(){
    const bpm = parseFloat(document.getElementById("bpm").value) || 96;
    const secondsPerBeat = 60 / bpm;
    while (nextNoteTime < audioCtx.currentTime + scheduleAheadTime){
      scheduleClick(nextNoteTime);
      nextNoteTime += secondsPerBeat;
    }
  }

  document.getElementById("btnClick").addEventListener("click", (e) => {
    ensureContext();
    clickOn = !clickOn;
    e.target.classList.toggle("is-on", clickOn);
    e.target.textContent = clickOn ? "CLIC ●" : "CLIC ○";
    if (clickOn){
      nextNoteTime = audioCtx.currentTime + 0.05;
      schedulerTimer = setInterval(metronomeScheduler, lookaheadMs);
    } else {
      clearInterval(schedulerTimer);
    }
  });

  // ── Transport : câblage des boutons ────────────────────────
  document.getElementById("btnRec").addEventListener("click", () => {
    if (isRecording || isPlaying) return;
    startRecording();
  });
  document.getElementById("btnPlay").addEventListener("click", () => {
    if (isRecording || isPlaying) return;
    playAll();
  });
  document.getElementById("btnStop").addEventListener("click", stopAll);

  // ── Construction des 4 tranches + tranche master ──────────
  for (let i = 0; i < NUM_TRACKS; i++) createStrip(i);

  const masterStrip = document.createElement("div");
  masterStrip.className = "strip master";
  masterStrip.innerHTML = `
    <div class="master-label">Master</div>
    <div class="vu">${buildMeterHTML()}</div>
    <div class="fader-wrap">
      <div class="fader-ticks"><span>+6</span><span>0</span><span>-∞</span></div>
      <input type="range" min="0" max="1.2" step="0.01" value="0.9" class="fader" id="masterFader">
    </div>
    <div class="clip-status">Sortie</div>
  `;
  stripsEl.appendChild(masterStrip);
  const masterVuSegs = [...masterStrip.querySelectorAll(".vu-seg")];

  document.getElementById("masterFader") || null;
  stripsEl.addEventListener("input", (e) => {
    if (e.target.id === "masterFader" && masterGain){
      masterGain.gain.value = parseFloat(e.target.value);
    }
  });

  // Ajoute le mètre master à la boucle d'animation générale
  const originalStartMeterLoop = startMeterLoop;
  startMeterLoop = function(){
    function loop(){
      if (isRecording && armedTrackId !== null && liveMicAnalyser){
        paintMeter(tracks[armedTrackId], levelFromAnalyser(liveMicAnalyser));
      }
      tracks.forEach(t => {
        if (t.id === armedTrackId && isRecording) return;
        if (t.analyserNode) paintMeter(t, levelFromAnalyser(t.analyserNode));
      });
      if (masterAnalyser){
        const data = new Uint8Array(masterAnalyser.fftSize);
        masterAnalyser.getByteTimeDomainData(data);
        let sumSquares = 0;
        for (let i = 0; i < data.length; i++){
          const v = (data[i] - 128) / 128;
          sumSquares += v * v;
        }
        const level = Math.sqrt(sumSquares / data.length);
        const litCount = Math.round(Math.min(1, level * 3.2) * masterVuSegs.length);
        masterVuSegs.forEach((seg, i) => seg.classList.toggle("on", i < litCount));
      }
      meterRAF = requestAnimationFrame(loop);
    }
    loop();
  };

})();
</script>
</body>
</html>
