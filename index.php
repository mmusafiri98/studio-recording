<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Studio Voce — Timeline</title>
<style>
  :root{
    --bg-app:      #16151a;
    --bg-topbar:   #1c1b21;
    --bg-panel:    #1e1d23;
    --bg-lane:     #17161b;
    --bg-lane-alt: #1b1a1f;
    --border:      #2a2933;
    --text:        #e7e6ea;
    --text-dim:    #8b899a;
    --accent:      #7c6cf0;
    --accent-2:    #5b4bd8;
    --rec-red:     #e2483d;
    --voice-color: #8b6df0;
    --voice-color-dark: #5c47ad;
    --music-color: #e2584f;
    --music-color-dark: #a13d36;
    --green:       #4fd18b;
  }
  *{ box-sizing:border-box; }
  html,body{ margin:0; padding:0; background:var(--bg-app); color:var(--text); font-family:-apple-system, BlinkMacSystemFont,"Segoe UI",sans-serif; height:100%; }
  button,input,select{ font-family:inherit; }
  button{ cursor:pointer; background:none; border:none; color:inherit; }
  :focus-visible{ outline:2px solid var(--accent); outline-offset:1px; }
  ::selection{ background:var(--accent); color:#fff; }

  .daw{ display:flex; flex-direction:column; height:100vh; }

  /* ═══ TOPBAR ═══ */
  .topbar{
    display:flex; align-items:center; gap:18px; padding:0 16px; height:48px;
    background:var(--bg-topbar); border-bottom:1px solid var(--border); position:relative; z-index:40;
  }
  .menu-group{ display:flex; gap:4px; }
  .menu-btn{ padding:7px 10px; font-size:13px; color:var(--text-dim); border-radius:6px; }
  .menu-btn:hover, .menu-btn.active{ background:#2a2933; color:var(--text); }
  .history-icons{ display:flex; gap:2px; margin-left:4px; }
  .history-icons button{ width:28px; height:28px; border-radius:6px; font-size:15px; color:var(--text-dim); }
  .history-icons button:hover:not(:disabled){ background:#2a2933; color:var(--text); }
  .history-icons button:disabled{ opacity:0.3; cursor:not-allowed; }
  .project-title{
    flex:1; text-align:center; background:none; border:none; color:var(--text);
    font-size:14px; font-weight:600; max-width:320px; margin:0 auto; padding:6px 8px; border-radius:6px;
  }
  .project-title:hover, .project-title:focus{ background:#2a2933; }
  .topbar-actions{ display:flex; gap:8px; }
  .pill-btn{
    padding:8px 16px; border-radius:20px; font-size:13px; font-weight:600;
    background:var(--accent); color:#fff;
  }
  .pill-btn:hover{ background:var(--accent-2); }

  /* ═══ DROPDOWN ═══ */
  .dropdown{
    display:none; position:absolute; top:48px; background:#232128; border:1px solid var(--border);
    border-radius:10px; padding:8px; min-width:220px; box-shadow:0 14px 34px rgba(0,0,0,0.55); z-index:50;
  }
  .dropdown.open{ display:block; }
  .dropdown-item{
    display:block; width:100%; text-align:left; padding:9px 10px; border-radius:6px; font-size:13px; color:var(--text);
  }
  .dropdown-item:hover{ background:#33313a; }
  .dropdown-sep{ height:1px; background:var(--border); margin:6px 0; }
  .dropdown h4{ margin:2px 0 8px; font-size:11px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dim); }
  .settings-panel{ min-width:280px; }
  .settings-row{ display:flex; align-items:center; gap:8px; margin-bottom:10px; flex-wrap:wrap; }
  .settings-row label{ font-size:12px; color:var(--text-dim); min-width:64px; }
  .settings-panel select, .settings-panel input[type=number]{
    background:#141318; border:1px solid var(--border); color:var(--text); border-radius:5px; padding:5px 7px; font-size:12px;
  }
  .settings-panel input[type=range]{ flex:1; accent-color:var(--accent); }
  .settings-btn{
    border-radius:6px; padding:6px 10px; font-size:12px; font-weight:600; background:#2f2d38; color:var(--text);
  }
  .settings-btn.primary{ background:var(--accent); color:#fff; }
  .settings-btn:disabled{ opacity:0.35; cursor:not-allowed; }
  .toggle-line{ display:flex; align-items:center; gap:8px; font-size:12px; }
  .toggle-line input{ width:15px; height:15px; accent-color:var(--green); }
  .settings-note{ font-size:10.5px; color:var(--text-dim); line-height:1.5; margin-top:6px; }
  .help-text{ font-size:12px; color:var(--text-dim); line-height:1.6; max-width:260px; }

  /* ═══ WORKSPACE ═══ */
  .workspace{ flex:1; display:flex; overflow:hidden; }

  .track-headers{ width:240px; flex-shrink:0; background:var(--bg-panel); border-right:1px solid var(--border); overflow-y:auto; }
  .track-row{ display:flex; align-items:stretch; border-bottom:1px solid var(--border); height:88px; }
  .track-color{ width:5px; flex-shrink:0; }
  .track-row[data-track="voice"] .track-color{ background:var(--voice-color); }
  .track-row[data-track="music"] .track-color{ background:var(--music-color); }
  .track-info{ flex:1; display:flex; flex-direction:column; justify-content:center; gap:6px; padding:8px 10px; min-width:0; }
  .track-name-row{ display:flex; align-items:center; justify-content:space-between; gap:6px; }
  .track-name{ font-size:13px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .track-more{ font-size:14px; color:var(--text-dim); padding:2px 4px; border-radius:4px; }
  .track-more:hover{ background:#2a2933; }
  .track-icons{ display:flex; gap:5px; }
  .ic{
    width:24px; height:22px; border-radius:5px; font-size:10px; font-weight:700; color:var(--text-dim);
    background:#26252c; display:flex; align-items:center; justify-content:center;
  }
  .ic.mute.is-on{ background:#4a3a2c; color:#f2b134; }
  .ic.solo.is-on{ background:#2c4a35; color:var(--green); }
  .ic.arm.is-on{ background:var(--rec-red); color:#fff; }
  .ic.importbtn{ color:var(--text-dim); }
  .ic.importbtn:hover{ color:var(--text); }
  .vol-row{ display:flex; align-items:center; gap:6px; }
  .vol-row span{ font-size:9px; color:var(--text-dim); width:16px; }
  input[type=range].vol-slider{ flex:1; height:3px; accent-color:var(--text-dim); }
  .track-status{ font-size:10px; color:var(--text-dim); }
  .track-status.recording{ color:var(--rec-red); font-weight:700; }
  .track-status.has-clip{ color:var(--green); }

  .timeline-area{ flex:1; display:flex; flex-direction:column; overflow:hidden; }
  .timeline-scroll{ flex:1; overflow:auto; position:relative; background:var(--bg-lane); }
  .ruler{
    position:sticky; top:0; height:26px; background:var(--bg-panel); border-bottom:1px solid var(--border);
    z-index:5;
  }
  .ruler-label{
    position:absolute; top:0; height:100%; display:flex; align-items:center; padding-left:4px;
    font-size:10px; color:var(--text-dim); border-left:1px solid var(--border);
  }
  .lanes{ position:relative; }
  .lane{ position:relative; height:88px; border-bottom:1px solid var(--border); background:var(--bg-lane); }
  .lane:nth-child(even){ background:var(--bg-lane-alt); }
  .lane-clip{
    position:absolute; top:8px; left:0; height:72px; border-radius:8px; overflow:hidden;
    display:flex; flex-direction:column; box-shadow:0 0 0 1px rgba(0,0,0,0.4);
  }
  .lane-clip.voice{ background:linear-gradient(180deg, var(--voice-color), var(--voice-color-dark)); }
  .lane-clip.music{ background:linear-gradient(180deg, var(--music-color), var(--music-color-dark)); }
  .lane-clip-label{ font-size:10px; font-weight:700; color:rgba(255,255,255,0.92); padding:3px 8px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .lane-clip canvas{ flex:1; width:100%; display:block; }
  .lane-empty-hint{ position:absolute; left:10px; top:50%; transform:translateY(-50%); font-size:11px; color:var(--text-dim); }

  .playhead-line{ position:absolute; top:0; bottom:0; width:1px; background:var(--accent); z-index:6; pointer-events:none; box-shadow:0 0 6px var(--accent); }

  /* ═══ BOTTOM BAR ═══ */
  .bottom-bar{
    display:flex; align-items:center; gap:18px; padding:0 16px; height:52px;
    background:var(--bg-topbar); border-top:1px solid var(--border); flex-wrap:wrap;
  }
  .master-vol{ display:flex; align-items:center; gap:6px; min-width:120px; }
  .master-vol input[type=range]{ width:80px; accent-color:var(--text-dim); }
  .clock-display{
    font-family:"Courier New",monospace; font-size:15px; letter-spacing:1px; color:var(--accent);
    background:#0e0d10; padding:5px 10px; border-radius:5px; min-width:82px; text-align:center;
  }
  .transport-controls{ display:flex; align-items:center; gap:6px; }
  .t-btn{ width:34px; height:34px; border-radius:50%; background:#26252c; font-size:13px; display:flex; align-items:center; justify-content:center; }
  .t-btn:hover{ background:#302e37; }
  .t-btn.small{ width:28px; height:28px; font-size:11px; }
  .t-btn.rec{ color:#ffb3ac; }
  .t-btn.rec.is-live{ background:var(--rec-red); color:#fff; animation:pulse 1.1s ease-in-out infinite; }
  @keyframes pulse{ 0%,100%{ box-shadow:0 0 0 0 rgba(226,72,61,0.5);} 50%{ box-shadow:0 0 0 7px rgba(226,72,61,0);} }
  .t-btn.loop.is-on{ background:var(--accent); color:#fff; }
  .key-bpm{ display:flex; align-items:center; gap:8px; font-size:12px; color:var(--text-dim); }
  .key-bpm #keyDisplay{ color:var(--text); font-weight:600; }
  .key-bpm input[type=number]{
    width:48px; background:#0e0d10; border:1px solid var(--border); color:var(--text); border-radius:4px;
    padding:4px 2px; text-align:center; font-size:12px;
  }
  .click-toggle{ width:30px; height:30px; border-radius:6px; background:#26252c; font-size:13px; }
  .click-toggle.is-on{ background:var(--green); color:#0e2015; }
  .zoom-controls{ display:flex; gap:4px; margin-left:auto; }
  .zoom-controls button{ width:26px; height:26px; border-radius:5px; background:#26252c; font-size:13px; }
  .help-btn{ padding:7px 14px; border-radius:16px; background:#26252c; font-size:12px; color:var(--text-dim); }
  .help-btn:hover{ color:var(--text); }

  @media (max-width:820px){
    .track-headers{ width:170px; }
    .master-vol{ display:none; }
    .project-title{ display:none; }
  }
</style>
</head>
<body>

<div class="daw">

  <!-- ═══ TOPBAR ═══ -->
  <div class="topbar">
    <div class="menu-group">
      <button class="menu-btn" data-menu="file">File</button>
      <button class="menu-btn" data-menu="edit">Modifica</button>
      <button class="menu-btn" data-menu="settings">Impostazioni</button>
      <button class="menu-btn" data-menu="help">Help</button>
    </div>
    <div class="history-icons">
      <button id="btnUndo" title="Annulla" disabled>↶</button>
      <button id="btnRedo" title="Ripeti" disabled>↷</button>
    </div>
    <input class="project-title" id="projectTitle" value="La mia canzone">
    <div class="topbar-actions">
      <button class="pill-btn" id="btnExportTop">⬇ Esporta</button>
    </div>
  </div>

  <div class="dropdown" id="menuFile">
    <h4>File</h4>
    <button class="dropdown-item" id="miImport">📂 Importa base musicale…</button>
    <button class="dropdown-item" id="miDownloadVoice">⬇ Scarica registrazione voce</button>
    <div class="dropdown-sep"></div>
    <button class="dropdown-item" id="miExport">⬇ Esporta mix (WAV)</button>
  </div>

  <div class="dropdown" id="menuEdit">
    <h4>Modifica</h4>
    <button class="dropdown-item" id="miApplyAutotune">🎵 Applica autotune</button>
    <button class="dropdown-item" id="miRestoreVoice">↺ Ripristina voce originale</button>
    <div class="dropdown-sep"></div>
    <button class="dropdown-item" id="miReset">🗑 Reimposta progetto</button>
  </div>

  <div class="dropdown settings-panel" id="menuSettings">
    <h4>Autotune</h4>
    <div class="settings-row">
      <label>Tonalità</label>
      <select id="atRoot">
        <option>C</option><option>C#</option><option>D</option><option>D#</option>
        <option>E</option><option>F</option><option>F#</option><option>G</option>
        <option>G#</option><option>A</option><option>A#</option><option>B</option>
      </select>
      <select id="atScale">
        <option value="chromatic" selected>Cromatico</option>
        <option value="major">Maggiore</option>
        <option value="minor">Minore</option>
      </select>
    </div>
    <div class="settings-row">
      <label>Intensità</label>
      <input type="range" id="atIntensity" min="0" max="100" value="70">
      <span id="atIntensityVal" style="font-size:11px;">70%</span>
    </div>
    <div class="settings-row">
      <button class="settings-btn primary" id="btnAutotune">Applica</button>
      <button class="settings-btn" id="btnRestoreVoice" disabled>Ripristina originale</button>
    </div>
    <h4 style="margin-top:12px;">Potenziamento vocale</h4>
    <div class="toggle-line">
      <input type="checkbox" id="voiceEnhancer">
      <label for="voiceEnhancer">Anti-rombo + compressore + presenza + riverbero</label>
    </div>
    <div class="settings-note">
      L'autotune analizza l'intonazione e la avvicina alla nota più vicina della scala scelta
      (elaborazione del segnale, applicata dopo la registrazione — non una rete neurale).
      Il potenziamento vocale è una catena di filtri professionale in tempo reale.
    </div>
  </div>

  <div class="dropdown" id="menuHelp">
    <h4>Come funziona</h4>
    <div class="help-text">
      1) Importa la base musicale da <b>File</b> (facoltativo).<br>
      2) Premi <b>●</b> per registrare la voce: la base si riproduce insieme, se presente.<br>
      3) Premi di nuovo <b>●</b> (ora rosso e lampeggiante) per fermare la registrazione.<br>
      4) Regola volume/mute/solo sulle tracce a sinistra.<br>
      5) Apri <b>Impostazioni</b> per autotune e potenziamento vocale.<br>
      6) Premi <b>▶</b> per riascoltare, oppure esporta il mix finale in WAV.
    </div>
  </div>

  <!-- ═══ WORKSPACE ═══ -->
  <div class="workspace">
    <div class="track-headers" id="trackHeaders"><!-- generato in JS --></div>
    <div class="timeline-area">
      <div class="timeline-scroll" id="timelineScroll">
        <div class="ruler" id="ruler"></div>
        <div class="lanes" id="lanes">
          <div class="lane" data-track="voice"><div class="lane-clip voice" id="voiceClip" style="width:0"></div><div class="lane-empty-hint" id="voiceHint">Nessuna registrazione — premi ●</div></div>
          <div class="lane" data-track="music"><div class="lane-clip music" id="musicClip" style="width:0"></div><div class="lane-empty-hint" id="musicHint">Nessun file — importa da File</div></div>
        </div>
        <div class="playhead-line" id="playheadLine" style="left:0"></div>
      </div>
    </div>
  </div>

  <!-- ═══ BOTTOM BAR ═══ -->
  <div class="bottom-bar">
    <div class="master-vol">
      <span>🔊</span>
      <input type="range" id="masterFader" min="0" max="1.2" step="0.01" value="0.9">
    </div>
    <div class="clock-display" id="clock">00:00.0</div>
    <div class="transport-controls">
      <button class="t-btn rec" id="btnRec" title="Registra / Ferma registrazione voce">●</button>
      <button class="t-btn" id="btnPlay" title="Play">▶</button>
      <button class="t-btn small" id="btnToStart" title="Torna all'inizio">|◀</button>
      <button class="t-btn small" id="btnRewind" title="Indietro 5s">◀◀</button>
      <button class="t-btn small" id="btnForward" title="Avanti 5s">▶▶</button>
      <button class="t-btn small loop" id="btnLoop" title="Loop">🔁</button>
    </div>
    <div class="key-bpm">
      <span id="keyDisplay">Cromatico</span>
      <input type="number" id="bpm" value="96" min="30" max="240">
      <span>bpm</span>
      <span>4/4</span>
      <button class="click-toggle" id="btnClick" title="Metronomo">🔔</button>
    </div>
    <div class="zoom-controls">
      <button id="zoomOut" title="Riduci zoom">−</button>
      <button id="zoomIn" title="Aumenta zoom">+</button>
    </div>
    <button class="help-btn" id="btnHelpBottom">Assistenza</button>
  </div>

</div>

<input type="file" id="musicFileInput" accept="audio/*" style="display:none">

<script>
(() => {
  "use strict";

  // ── Stato globale ─────────────────────────────────────────
  let audioCtx=null, masterGain=null;
  let isRecording=false, isPlaying=false, loopOn=false;
  let clockRAF=null, meterLoopRAF=null;
  let timelineStartRef=0;      // riferimento audioCtx.currentTime - offset, per calcolare la posizione assoluta
  let seekOffset=0;            // secondi: punto da cui riparte la riproduzione
  let liveSources=[];
  let currentMicStream=null;
  let pixelsPerSecond=28;

  const statusEl = null; // (nessuna status-line dedicata in questo layout: usiamo gli hint sulle clip)
  const clockEl = document.getElementById("clock");

  function ensureContext(){
    if (!audioCtx){
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      masterGain = audioCtx.createGain();
      masterGain.gain.value = parseFloat(document.getElementById("masterFader").value);
      masterGain.connect(audioCtx.destination);
    }
    if (audioCtx.state === "suspended") audioCtx.resume();
    return audioCtx;
  }

  function formatTime(sec){
    const m = Math.floor(sec/60);
    const s = (sec%60).toFixed(1).padStart(4,"0");
    return `${String(m).padStart(2,"0")}:${s}`;
  }

  // ── Tracce ────────────────────────────────────────────────
  function makeTrack(id, isVoice){
    return {
      id, isVoice,
      muted:false, solo:false,
      hasClip:false, audioBuffer:null, originalBuffer:null,
      gainNode:null, analyserNode:null, dryGain:null, wetGain:null, enhancerInput:null,
      volSlider: document.querySelector(`.track-row[data-track="${id}"] .vol-slider`),
      muteBtn: document.querySelector(`.track-row[data-track="${id}"] .ic.mute`),
      soloBtn: document.querySelector(`.track-row[data-track="${id}"] .ic.solo`),
      statusEl: document.querySelector(`.track-row[data-track="${id}"] .track-status`),
      clipEl: document.getElementById(id+"Clip"),
      hintEl: document.getElementById(id+"Hint"),
    };
  }

  // ── Costruzione righe traccia nel pannello sinistro ────────
  const headersEl = document.getElementById("trackHeaders");
  headersEl.innerHTML = `
    <div class="track-row" data-track="voice">
      <div class="track-color"></div>
      <div class="track-info">
        <div class="track-name-row">
          <div class="track-name">Voce</div>
          <button class="track-more">⋯</button>
        </div>
        <div class="track-icons">
          <button class="ic mute" title="Muto">M</button>
          <button class="ic solo" title="Solo">S</button>
          <button class="ic arm" title="Abilita registrazione" disabled style="opacity:.5">●</button>
        </div>
        <div class="vol-row"><span>Vol</span><input type="range" class="vol-slider" min="0" max="1.2" step="0.01" value="0.9"></div>
        <div class="track-status">Vuota</div>
      </div>
    </div>
    <div class="track-row" data-track="music">
      <div class="track-color"></div>
      <div class="track-info">
        <div class="track-name-row">
          <div class="track-name">Base musicale</div>
          <button class="track-more">⋯</button>
        </div>
        <div class="track-icons">
          <button class="ic mute" title="Muto">M</button>
          <button class="ic solo" title="Solo">S</button>
          <button class="ic importbtn" id="musicImportIcon" title="Importa audio">📂</button>
        </div>
        <div class="vol-row"><span>Vol</span><input type="range" class="vol-slider" min="0" max="1.2" step="0.01" value="0.9"></div>
        <div class="track-status">Vuota</div>
      </div>
    </div>
  `;

  const voice = makeTrack("voice", true);
  const music = makeTrack("music", false);
  const tracks = [voice, music];

  document.getElementById("musicImportIcon").addEventListener("click", () => document.getElementById("musicFileInput").click());

  function wireCommon(track){
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
    track.volSlider.addEventListener("input", recomputeGains);
  }
  wireCommon(voice);
  wireCommon(music);

  function recomputeGains(){
    const anySolo = tracks.some(t => t.solo);
    tracks.forEach(t => {
      if (!t.gainNode) return;
      let g = parseFloat(t.volSlider.value);
      if (t.muted) g = 0;
      else if (anySolo && !t.solo) g = 0;
      t.gainNode.gain.value = g;
    });
  }

  document.getElementById("masterFader").addEventListener("input", (e) => {
    if (masterGain) masterGain.gain.value = parseFloat(e.target.value);
  });

  // ── Catena di potenziamento vocale (DSP) ───────────────────
  function makeSyntheticImpulse(ctx, duration, decay){
    const rate = ctx.sampleRate;
    const length = Math.max(1, Math.floor(rate*duration));
    const impulse = ctx.createBuffer(2, length, rate);
    for (let ch=0; ch<2; ch++){
      const data = impulse.getChannelData(ch);
      for (let i=0;i<length;i++) data[i] = (Math.random()*2-1) * Math.pow(1 - i/length, decay);
    }
    return impulse;
  }
  function buildVoiceEnhancerChain(ctx){
    const hp = ctx.createBiquadFilter(); hp.type="highpass"; hp.frequency.value=90;
    const comp = ctx.createDynamicsCompressor();
    comp.threshold.value=-24; comp.knee.value=20; comp.ratio.value=3.5; comp.attack.value=0.006; comp.release.value=0.15;
    const presence = ctx.createBiquadFilter(); presence.type="peaking"; presence.frequency.value=4200; presence.Q.value=0.9; presence.gain.value=4;
    const warmth = ctx.createBiquadFilter(); warmth.type="peaking"; warmth.frequency.value=220; warmth.Q.value=0.8; warmth.gain.value=2;
    const convolver = ctx.createConvolver(); convolver.buffer = makeSyntheticImpulse(ctx, 1.4, 2.2);
    const reverbGain = ctx.createGain(); reverbGain.gain.value=0.16;
    const dryPath = ctx.createGain(); dryPath.gain.value=1;
    const mixOut = ctx.createGain();
    hp.connect(comp); comp.connect(presence); presence.connect(warmth);
    warmth.connect(dryPath); warmth.connect(convolver); convolver.connect(reverbGain);
    dryPath.connect(mixOut); reverbGain.connect(mixOut);
    return { input: hp, output: mixOut };
  }
  function applyEnhancerState(){
    if (!voice.dryGain) return;
    const on = document.getElementById("voiceEnhancer").checked;
    voice.dryGain.gain.value = on ? 0 : 1;
    voice.wetGain.gain.value = on ? 1 : 0;
  }
  document.getElementById("voiceEnhancer").addEventListener("change", applyEnhancerState);

  function buildChainIfNeeded(track){
    if (track.gainNode) return;
    track.gainNode = audioCtx.createGain();
    track.analyserNode = audioCtx.createAnalyser();
    track.analyserNode.fftSize = 256;
    track.gainNode.connect(track.analyserNode);
    track.analyserNode.connect(masterGain);
    if (track.isVoice){
      track.dryGain = audioCtx.createGain();
      track.wetGain = audioCtx.createGain();
      track.dryGain.connect(track.gainNode);
      const chain = buildVoiceEnhancerChain(audioCtx);
      track.enhancerInput = chain.input;
      chain.output.connect(track.wetGain);
      track.wetGain.connect(track.gainNode);
      applyEnhancerState();
    }
  }

  function playSingleTrack(track, offsetSeconds){
    buildChainIfNeeded(track);
    const src = audioCtx.createBufferSource();
    src.buffer = track.audioBuffer;
    const off = Math.min(offsetSeconds || 0, track.audioBuffer.duration - 0.01);
    if (track.isVoice){
      src.connect(track.dryGain);
      src.connect(track.enhancerInput);
    } else {
      src.connect(track.gainNode);
    }
    src.start(0, Math.max(0, off));
    liveSources.push(src);
    return src;
  }

  // ── Timeline: righello, forma d'onda, playhead ─────────────
  function currentBpm(){ return parseFloat(document.getElementById("bpm").value) || 96; }

  function totalDuration(){
    return Math.max(voice.hasClip?voice.audioBuffer.duration:0, music.hasClip?music.audioBuffer.duration:0, 20);
  }

  function updateTimelineWidth(){
    const totalW = Math.max(600, totalDuration()*pixelsPerSecond + 200);
    document.getElementById("ruler").style.width = totalW+"px";
    document.getElementById("lanes").style.width = totalW+"px";
    renderRuler(totalW);
  }

  function renderRuler(totalW){
    const ruler = document.getElementById("ruler");
    ruler.innerHTML = "";
    const bpm = currentBpm();
    const barDuration = (60/bpm)*4; // secondi per battuta (4/4)
    const barPx = barDuration*pixelsPerSecond;
    let step = Math.max(1, Math.ceil(48/barPx));
    // arrotonda step a 1,2,4,8,16...
    const niceSteps=[1,2,4,8,16,32,64];
    step = niceSteps.find(s => s>=step) || 64;
    let bar=1;
    while ((bar-1)*barPx < totalW){
      const label = document.createElement("div");
      label.className="ruler-label";
      label.style.left = ((bar-1)*barPx)+"px";
      label.textContent = bar;
      ruler.appendChild(label);
      bar += step;
    }
  }

  function drawWaveform(canvas, audioBuffer, color){
    const ctx = canvas.getContext("2d");
    const width = canvas.width, height = canvas.height;
    ctx.clearRect(0,0,width,height);
    const data = audioBuffer.numberOfChannels>1 ? downmix(audioBuffer) : audioBuffer.getChannelData(0);
    const step = Math.max(1, Math.ceil(data.length/width));
    const amp = height/2;
    ctx.strokeStyle = color;
    ctx.lineWidth = 1;
    ctx.beginPath();
    for (let x=0; x<width; x++){
      let min=1.0, max=-1.0;
      const base = x*step;
      for (let j=0;j<step;j++){
        const idx = base+j;
        if (idx>=data.length) break;
        const v = data[idx];
        if (v<min) min=v;
        if (v>max) max=v;
      }
      if (min>max){ min=0; max=0; }
      ctx.moveTo(x, amp + min*amp*0.9);
      ctx.lineTo(x, amp + max*amp*0.9);
    }
    ctx.stroke();
  }

  function renderClip(track, label){
    if (!track.hasClip) return;
    const widthPx = Math.max(30, Math.round(track.audioBuffer.duration*pixelsPerSecond));
    track.clipEl.style.width = widthPx+"px";
    track.hintEl.style.display = "none";
    track.clipEl.innerHTML = `<div class="lane-clip-label">${label}</div><canvas></canvas>`;
    const canvas = track.clipEl.querySelector("canvas");
    canvas.width = widthPx;
    canvas.height = 50;
    drawWaveform(canvas, track.audioBuffer, "rgba(255,255,255,0.85)");
  }

  function refreshTimeline(){
    updateTimelineWidth();
    if (voice.hasClip) renderClip(voice, "Voce");
    if (music.hasClip) renderClip(music, music.fileName || "Base musicale");
    updatePlayheadDisplay(seekOffset);
  }

  function updatePlayheadDisplay(seconds){
    document.getElementById("playheadLine").style.left = (seconds*pixelsPerSecond)+"px";
    clockEl.textContent = formatTime(seconds);
  }

  // ── Zoom ─────────────────────────────────────────────────
  document.getElementById("zoomIn").addEventListener("click", () => {
    pixelsPerSecond = Math.min(200, pixelsPerSecond*1.3);
    refreshTimeline();
  });
  document.getElementById("zoomOut").addEventListener("click", () => {
    pixelsPerSecond = Math.max(6, pixelsPerSecond/1.3);
    refreshTimeline();
  });

  document.getElementById("bpm").addEventListener("input", () => updateTimelineWidth());

  // ── Registrazione voce ─────────────────────────────────────
  async function startRecording(){
    if (voice.hasClip && !confirm("Questo sovrascriverà la registrazione vocale attuale. Continuare?")) return;
    ensureContext();
    try{
      currentMicStream = await navigator.mediaDevices.getUserMedia({ audio:true });
    }catch(err){
      alert("Microfono inaccessibile: controlla i permessi del browser per questo sito.");
      return;
    }
    voice.chunks = [];
    voice.recorder = new MediaRecorder(currentMicStream);
    voice.recorder.ondataavailable = (e) => { if (e.data.size>0) voice.chunks.push(e.data); };
    voice.recorder.onstop = () => finalizeRecording();
    voice.recorder.start();

    isRecording = true;
    voice.statusEl.textContent = "● Registrazione…";
    voice.statusEl.className = "track-status recording";
    document.getElementById("btnRec").classList.add("is-live");

    seekOffset = 0;
    timelineStartRef = audioCtx.currentTime;
    startClockLoop();
    if (music.hasClip) loopMusicDuringRecording();
  }

  function loopMusicDuringRecording(){
    if (!music.hasClip) return;
    const src = playSingleTrack(music, 0);
    src.onended = () => {
      // Se il loop è attivo e stiamo ancora registrando, la base musicale
      // riparte da capo così puoi continuare a cantare senza interruzioni.
      if (isRecording && loopOn) loopMusicDuringRecording();
    };
  }

  function stopRecording(){
    if (voice.recorder && voice.recorder.state !== "inactive") voice.recorder.stop();
    stopSourcesOnly(); // ferma anche la base musicale insieme alla voce
    isRecording = false;
    document.getElementById("btnRec").classList.remove("is-live");
    stopClockLoop();
  }

  async function finalizeRecording(){
    const blob = new Blob(voice.chunks, { type: voice.recorder.mimeType || "audio/webm" });
    const arrayBuf = await blob.arrayBuffer();
    try{
      voice.audioBuffer = await audioCtx.decodeAudioData(arrayBuf);
    }catch(err){
      alert("Errore nella decodifica della registrazione. Riprova.");
      return;
    }
    voice.originalBuffer = null;
    lastAutotuneBuffer = null;
    setUndoRedoState();
    voice.hasClip = true;
    voice.statusEl.textContent = `✓ ${voice.audioBuffer.duration.toFixed(1)}s`;
    voice.statusEl.className = "track-status has-clip";
    document.getElementById("btnRestoreVoice").disabled = true;

    if (voice.blobUrl) URL.revokeObjectURL(voice.blobUrl);
    voice.blobUrl = URL.createObjectURL(blob);

    if (currentMicStream){ currentMicStream.getTracks().forEach(t => t.stop()); currentMicStream = null; }

    stopClockLoop();
    isRecording = false;
    document.getElementById("btnRec").classList.remove("is-live");
    refreshTimeline();
  }

  // ── Importazione base musicale ──────────────────────────────
  document.getElementById("musicFileInput").addEventListener("change", async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    ensureContext();
    const arrayBuf = await file.arrayBuffer();
    try{
      music.audioBuffer = await audioCtx.decodeAudioData(arrayBuf);
    }catch(err){
      alert("Impossibile leggere questo file audio.");
      return;
    }
    music.hasClip = true;
    music.fileName = file.name.replace(/\.[^.]+$/,"");
    music.statusEl.textContent = `✓ ${music.audioBuffer.duration.toFixed(1)}s`;
    music.statusEl.className = "track-status has-clip";
    refreshTimeline();
  });

  // ── Trasporto: play / stop / seek / loop ───────────────────
  function playAll(){
    ensureContext();
    const withClip = tracks.filter(t => t.hasClip);
    if (withClip.length === 0){ alert("Registra la voce o importa una base musicale prima di ascoltare."); return; }
    recomputeGains();
    let maxDur = 0;
    withClip.forEach(t => { playSingleTrack(t, seekOffset); maxDur = Math.max(maxDur, t.audioBuffer.duration); });
    isPlaying = true;
    timelineStartRef = audioCtx.currentTime - seekOffset;
    startClockLoop();
    const remaining = Math.max(0, maxDur - seekOffset);
    playAll._timer = setTimeout(() => {
      if (!isPlaying) return;
      if (loopOn){ stopSourcesOnly(); seekOffset = 0; playAll(); }
      else stopAll();
    }, remaining*1000 + 120);
  }

  function stopSourcesOnly(){
    liveSources.forEach(s => { try{ s.stop(); }catch(e){} });
    liveSources = [];
  }

  function stopAll(){
    stopSourcesOnly();
    if (isRecording && voice.recorder && voice.recorder.state !== "inactive") voice.recorder.stop();
    isRecording = false; isPlaying = false;
    document.getElementById("btnRec").classList.remove("is-live");
    clearTimeout(playAll._timer);
    stopClockLoop();
  }

  function startClockLoop(){
    function tick(){
      const elapsed = audioCtx.currentTime - timelineStartRef;
      updatePlayheadDisplay(Math.max(0, elapsed));
      clockRAF = requestAnimationFrame(tick);
    }
    tick();
  }
  function stopClockLoop(){
    if (clockRAF) cancelAnimationFrame(clockRAF);
    clockRAF = null;
    updatePlayheadDisplay(seekOffset);
  }

  // ═══ CORREZIONE ═══
  // Prima il pulsante ● avviava SOLO la registrazione: un secondo click, mentre
  // era già attivo, non faceva nulla. Il MediaRecorder restava acceso per
  // sempre e l'evento "onstop" (che decodifica l'audio e lo mette in traccia)
  // non veniva mai generato: per questo la voce non risultava mai registrata
  // e non c'era modo di fermarla. Ora il pulsante è un vero toggle:
  // - se non stai registrando -> avvia la registrazione
  // - se stai registrando -> la ferma, il che genera "onstop" e finalizza la clip
  document.getElementById("btnRec").addEventListener("click", () => {
    if (isRecording){
      stopRecording();
    } else if (!isPlaying){
      startRecording();
    }
  });

  document.getElementById("btnPlay").addEventListener("click", () => {
    if (isRecording) return;
    if (isPlaying) stopAll(); else playAll();
  });
  document.getElementById("btnToStart").addEventListener("click", () => { if (!isPlaying && !isRecording){ seekOffset=0; updatePlayheadDisplay(0);} });
  document.getElementById("btnRewind").addEventListener("click", () => { if (!isPlaying && !isRecording){ seekOffset=Math.max(0,seekOffset-5); updatePlayheadDisplay(seekOffset);} });
  document.getElementById("btnForward").addEventListener("click", () => { if (!isPlaying && !isRecording){ seekOffset=Math.min(totalDuration(),seekOffset+5); updatePlayheadDisplay(seekOffset);} });
  document.getElementById("btnLoop").addEventListener("click", (e) => { loopOn=!loopOn; e.currentTarget.classList.toggle("is-on", loopOn); });

  // ── Metronomo ───────────────────────────────────────────────
  let clickOn=false, nextNoteTime=0, schedulerTimer=null;
  function scheduleClick(time){
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.frequency.value = 1000;
    gain.gain.setValueAtTime(0.25, time);
    gain.gain.exponentialRampToValueAtTime(0.001, time+0.05);
    osc.connect(gain); gain.connect(audioCtx.destination);
    osc.start(time); osc.stop(time+0.06);
  }
  function metronomeScheduler(){
    const bpm = currentBpm();
    const secondsPerBeat = 60/bpm;
    while (nextNoteTime < audioCtx.currentTime + 0.1){ scheduleClick(nextNoteTime); nextNoteTime += secondsPerBeat; }
  }
  document.getElementById("btnClick").addEventListener("click", (e) => {
    ensureContext();
    clickOn = !clickOn;
    e.currentTarget.classList.toggle("is-on", clickOn);
    if (clickOn){ nextNoteTime = audioCtx.currentTime+0.05; schedulerTimer = setInterval(metronomeScheduler, 25); }
    else clearInterval(schedulerTimer);
  });

  // ── Autotune: rilevamento + correzione intonazione ──────────
  const KEY_ROOTS = {C:0,"C#":1,D:2,"D#":3,E:4,F:5,"F#":6,G:7,"G#":8,A:9,"A#":10,B:11};
  const SCALE_DEFS = { chromatic:[0,1,2,3,4,5,6,7,8,9,10,11], major:[0,2,4,5,7,9,11], minor:[0,2,3,5,7,8,10] };

  function buildAllowedSet(rootPc, intervals){ const s=new Set(); intervals.forEach(i=>s.add((rootPc+i)%12)); return s; }
  function nearestNoteFreq(freq, allowedPc){
    if (!freq || !isFinite(freq)) return freq;
    const midi = 69+12*Math.log2(freq/440);
    const base = Math.round(midi);
    let best=null, bestDist=Infinity;
    for (let m=base-7;m<=base+7;m++){
      const pc=((m%12)+12)%12;
      if (allowedPc.has(pc)){ const d=Math.abs(m-midi); if (d<bestDist){bestDist=d; best=m;} }
    }
    return best===null ? freq : 440*Math.pow(2,(best-69)/12);
  }
  function detectPitch(buf, sampleRate){
    const SIZE=buf.length;
    let rms=0; for (let i=0;i<SIZE;i++) rms+=buf[i]*buf[i];
    rms=Math.sqrt(rms/SIZE);
    if (rms<0.012) return null;
    const minLag=Math.max(2,Math.floor(sampleRate/1000));
    const maxLag=Math.min(SIZE-1,Math.floor(sampleRate/70));
    let bestLag=-1, bestCorr=0;
    for (let lag=minLag; lag<=maxLag; lag++){
      let corr=0; for (let i=0;i<SIZE-lag;i++) corr+=buf[i]*buf[i+lag];
      if (corr>bestCorr){ bestCorr=corr; bestLag=lag; }
    }
    if (bestLag<=0) return null;
    let energy=0; for (let i=0;i<SIZE-bestLag;i++) energy += buf[i]*buf[i]+buf[i+bestLag]*buf[i+bestLag];
    const confidence=(2*bestCorr)/(energy+1e-9);
    if (confidence<0.35) return null;
    return sampleRate/bestLag;
  }
  function downmix(buffer){
    const len=buffer.length; const out=new Float32Array(len);
    for (let ch=0; ch<buffer.numberOfChannels; ch++){ const data=buffer.getChannelData(ch); for (let i=0;i<len;i++) out[i]+=data[i]/buffer.numberOfChannels; }
    return out;
  }
  function pitchCorrectBuffer(inputBuffer, opts){
    const { rootPc, intervals, intensity } = opts;
    const sampleRate = inputBuffer.sampleRate;
    const allowedPc = buildAllowedSet(rootPc, intervals);
    const monoForDetection = inputBuffer.numberOfChannels>1 ? downmix(inputBuffer) : inputBuffer.getChannelData(0);

    const DETECT_BLOCK=1024;
    const numBlocks=Math.ceil(monoForDetection.length/DETECT_BLOCK);
    const pitchPerBlock=new Array(numBlocks);
    for (let b=0;b<numBlocks;b++){
      const start=b*DETECT_BLOCK;
      const frame=monoForDetection.subarray(start, Math.min(start+DETECT_BLOCK, monoForDetection.length));
      pitchPerBlock[b]=detectPitch(frame, sampleRate);
    }
    const SYNTH_WINDOW=1024, SYNTH_HOP=256;
    const hann=new Float32Array(SYNTH_WINDOW);
    for (let i=0;i<SYNTH_WINDOW;i++) hann[i]=0.5-0.5*Math.cos(2*Math.PI*i/(SYNTH_WINDOW-1));
    const length=inputBuffer.length;
    const weightSum=new Float32Array(length);
    const outputChannels=[];
    for (let ch=0; ch<inputBuffer.numberOfChannels; ch++){
      const srcData=inputBuffer.getChannelData(ch);
      const out=new Float32Array(length);
      const firstPass=(ch===0);
      for (let pos=0; pos<length; pos+=SYNTH_HOP){
        const blockIdx=Math.min(pitchPerBlock.length-1, Math.floor(pos/DETECT_BLOCK));
        const detected=pitchPerBlock[blockIdx];
        let ratio=1;
        if (detected){
          const target=nearestNoteFreq(detected, allowedPc);
          let raw=target/detected;
          raw=Math.max(0.5, Math.min(2, raw));
          ratio=1+intensity*(raw-1);
        }
        for (let n=0;n<SYNTH_WINDOW;n++){
          const srcIdx=pos+n*ratio;
          const i0=Math.floor(srcIdx);
          const frac=srcIdx-i0;
          const s0=(i0>=0 && i0<srcData.length)?srcData[i0]:0;
          const s1=(i0+1>=0 && i0+1<srcData.length)?srcData[i0+1]:0;
          const sample=s0+(s1-s0)*frac;
          const w=hann[n];
          const outIdx=pos+n;
          if (outIdx<length){ out[outIdx]+=sample*w; if (firstPass) weightSum[outIdx]+=w; }
        }
      }
      outputChannels.push(out);
    }
    for (let ch=0; ch<outputChannels.length; ch++){
      const out=outputChannels[ch];
      for (let i=0;i<length;i++) out[i]=out[i]/(weightSum[i]||1e-6);
    }
    const newBuffer=audioCtx.createBuffer(inputBuffer.numberOfChannels, length, sampleRate);
    for (let ch=0; ch<inputBuffer.numberOfChannels; ch++) newBuffer.copyToChannel(outputChannels[ch], ch);
    return newBuffer;
  }

  document.getElementById("atIntensity").addEventListener("input", (e) => {
    document.getElementById("atIntensityVal").textContent = e.target.value+"%";
  });
  function updateKeyDisplay(){
    const root = document.getElementById("atRoot").value;
    const scale = document.getElementById("atScale").value;
    document.getElementById("keyDisplay").textContent = scale==="chromatic" ? "Cromatico" : `${root} ${scale==="major"?"maj":"min"}`;
  }
  document.getElementById("atRoot").addEventListener("change", updateKeyDisplay);
  document.getElementById("atScale").addEventListener("change", updateKeyDisplay);
  updateKeyDisplay();

  let lastAutotuneBuffer = null;
  function setUndoRedoState(){
    document.getElementById("btnUndo").disabled = !(voice.originalBuffer && voice.audioBuffer!==voice.originalBuffer);
    document.getElementById("btnRedo").disabled = !lastAutotuneBuffer;
  }

  async function applyAutotune(){
    if (!voice.hasClip){ alert("Registra prima la voce."); return; }
    ensureContext();
    const btn = document.getElementById("btnAutotune");
    btn.disabled = true;
    await new Promise(r => setTimeout(r, 30));
    const root = document.getElementById("atRoot").value;
    const scaleType = document.getElementById("atScale").value;
    const intensity = parseFloat(document.getElementById("atIntensity").value)/100;
    if (!voice.originalBuffer) voice.originalBuffer = voice.audioBuffer;
    try{
      voice.audioBuffer = pitchCorrectBuffer(voice.originalBuffer, { rootPc: KEY_ROOTS[root], intervals: SCALE_DEFS[scaleType], intensity });
      voice.statusEl.textContent = `✓ autotune`;
      voice.statusEl.className = "track-status has-clip";
      document.getElementById("btnRestoreVoice").disabled = false;
      lastAutotuneBuffer = null;
      setUndoRedoState();
      refreshTimeline();
    }catch(err){
      alert("Errore durante l'elaborazione dell'autotune.");
    }finally{
      btn.disabled = false;
    }
  }
  function restoreVoice(){
    if (voice.originalBuffer){
      lastAutotuneBuffer = voice.audioBuffer;
      voice.audioBuffer = voice.originalBuffer;
      voice.statusEl.textContent = `✓ originale`;
      voice.statusEl.className = "track-status has-clip";
      setUndoRedoState();
      refreshTimeline();
    }
  }
  document.getElementById("btnAutotune").addEventListener("click", applyAutotune);
  document.getElementById("btnRestoreVoice").addEventListener("click", restoreVoice);

  document.getElementById("btnUndo").addEventListener("click", restoreVoice);
  document.getElementById("btnRedo").addEventListener("click", () => {
    if (lastAutotuneBuffer){
      voice.audioBuffer = lastAutotuneBuffer;
      lastAutotuneBuffer = null;
      voice.statusEl.textContent = "✓ autotune";
      setUndoRedoState();
      refreshTimeline();
    }
  });

  // ── Esportazione WAV ─────────────────────────────────────────
  function writeString(view, offset, str){ for (let i=0;i<str.length;i++) view.setUint8(offset+i, str.charCodeAt(i)); }
  function floatTo16BitPCM(view, offset, input){
    for (let i=0;i<input.length;i++, offset+=2){ const s=Math.max(-1,Math.min(1,input[i])); view.setInt16(offset, s<0?s*0x8000:s*0x7FFF, true); }
  }
  function interleaveStereo(left,right){
    const length=left.length+right.length; const result=new Float32Array(length);
    let index=0, inputIndex=0;
    while (index<length){ result[index++]=left[inputIndex]; result[index++]=right[inputIndex]; inputIndex++; }
    return result;
  }
  function audioBufferToWav(buffer){
    const numChannels=buffer.numberOfChannels, sampleRate=buffer.sampleRate, bitDepth=16;
    const interleaved = numChannels===2 ? interleaveStereo(buffer.getChannelData(0), buffer.getChannelData(1)) : buffer.getChannelData(0);
    const dataLength = interleaved.length*(bitDepth/8);
    const arrayBuf = new ArrayBuffer(44+dataLength);
    const view = new DataView(arrayBuf);
    writeString(view,0,"RIFF"); view.setUint32(4,36+dataLength,true); writeString(view,8,"WAVE");
    writeString(view,12,"fmt "); view.setUint32(16,16,true); view.setUint16(20,1,true);
    view.setUint16(22,numChannels,true); view.setUint32(24,sampleRate,true);
    view.setUint32(28,sampleRate*numChannels*bitDepth/8,true); view.setUint16(32,numChannels*bitDepth/8,true);
    view.setUint16(34,bitDepth,true); writeString(view,36,"data"); view.setUint32(40,dataLength,true);
    floatTo16BitPCM(view,44,interleaved);
    return new Blob([view], { type:"audio/wav" });
  }

  async function exportMix(){
    if (!voice.hasClip && !music.hasClip){ alert("Registra la voce o importa una base prima di esportare."); return; }
    const duration = Math.max(voice.hasClip?voice.audioBuffer.duration:0, music.hasClip?music.audioBuffer.duration:0) + 0.2;
    const sr = 44100;
    const offlineCtx = new OfflineAudioContext(2, Math.ceil(duration*sr), sr);
    const offlineMaster = offlineCtx.createGain();
    offlineMaster.gain.value = parseFloat(document.getElementById("masterFader").value);
    offlineMaster.connect(offlineCtx.destination);
    const anySolo = tracks.some(t => t.solo);

    function setupOffline(track){
      if (!track.hasClip) return;
      const src = offlineCtx.createBufferSource();
      src.buffer = track.audioBuffer;
      const gainNode = offlineCtx.createGain();
      let g = parseFloat(track.volSlider.value);
      if (track.muted) g=0; else if (anySolo && !track.solo) g=0;
      gainNode.gain.value = g;
      if (track.isVoice && document.getElementById("voiceEnhancer").checked){
        const chain = buildVoiceEnhancerChain(offlineCtx);
        src.connect(chain.input); chain.output.connect(gainNode);
      } else {
        src.connect(gainNode);
      }
      gainNode.connect(offlineMaster);
      src.start(0);
    }
    setupOffline(voice); setupOffline(music);

    try{
      const rendered = await offlineCtx.startRendering();
      const blob = audioBufferToWav(rendered);
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url; a.download = (document.getElementById("projectTitle").value||"mix").trim()+".wav"; a.click();
      setTimeout(() => URL.revokeObjectURL(url), 4000);
    }catch(err){
      alert("Errore durante l'esportazione del mix.");
    }
  }

  // ── Menu a tendina ────────────────────────────────────────
  const MENU_IDS = { file:"menuFile", edit:"menuEdit", settings:"menuSettings", help:"menuHelp" };
  document.querySelectorAll(".menu-btn").forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      const dd = document.getElementById(MENU_IDS[btn.dataset.menu]);
      const isOpen = dd.classList.contains("open");
      document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("open"));
      document.querySelectorAll(".menu-btn").forEach(b => b.classList.remove("active"));
      if (!isOpen){
        dd.style.left = btn.offsetLeft+"px";
        dd.classList.add("open");
        btn.classList.add("active");
      }
    });
  });
  document.querySelectorAll(".dropdown").forEach(dd => dd.addEventListener("click", e => e.stopPropagation()));
  document.addEventListener("click", () => {
    document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("open"));
    document.querySelectorAll(".menu-btn").forEach(b => b.classList.remove("active"));
  });

  document.getElementById("miImport").addEventListener("click", () => document.getElementById("musicFileInput").click());
  document.getElementById("miDownloadVoice").addEventListener("click", () => {
    if (!voice.blobUrl){ alert("Nessuna registrazione vocale da scaricare."); return; }
    const a = document.createElement("a"); a.href=voice.blobUrl; a.download="voce.webm"; a.click();
  });
  document.getElementById("miExport").addEventListener("click", exportMix);
  document.getElementById("btnExportTop").addEventListener("click", exportMix);
  document.getElementById("miApplyAutotune").addEventListener("click", applyAutotune);
  document.getElementById("miRestoreVoice").addEventListener("click", restoreVoice);
  document.getElementById("miReset").addEventListener("click", () => {
    if (confirm("Questo cancellerà voce, base musicale e tutte le impostazioni. Continuare?")) location.reload();
  });
  document.getElementById("btnHelpBottom").addEventListener("click", (e) => {
    e.stopPropagation();
    document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("open"));
    const dd = document.getElementById("menuHelp");
    dd.style.left = "auto"; dd.style.right = "16px";
    dd.classList.add("open");
  });

  // ── Inizializzazione ────────────────────────────────────────
  refreshTimeline();

})();
</script>
</body>
</html>
