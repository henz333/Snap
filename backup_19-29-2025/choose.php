<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Select Frame — abcSnap</title>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --blue1: #1E3C72;
      --blue2: #2A5298;
      --yellow: #FFD93D;
      --accent: #2A5298;
    }
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, var(--blue1), var(--blue2));
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
      padding: 24px;
      box-sizing: border-box;
    }
    h1 {
      font-family: 'Baloo 2', cursive;
      color: var(--yellow);
      margin: 0 0 8px;
      font-size: 2rem;
    }
    p.lead {
      margin: 0 0 18px;
      color: rgba(255, 255, 255, 0.9);
    }
    .layout {
      display: flex;
      gap: 24px;
      width: 100%;
      max-width: 1400px;
      align-items: flex-start;
      justify-content: center;
      flex-wrap: wrap;
    }
    .frame-grid {
      width: 480px;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }
    .frame {
      background: rgba(255, 255, 255, 0.06);
      border-radius: 12px;
      padding: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform .15s, box-shadow .15s;
    }
    .frame:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }
    .frame img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      object-fit: contain;
      display: block;
    }
    #preview-container {
      width: 540px;
      min-height: 640px;
      background: rgba(255, 255, 255, 0.06);
      border-radius: 14px;
      padding: 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }
    #preview-title {
      color: rgba(255, 255, 255, 0.9);
      margin-bottom: 8px;
      font-weight: 600;
    }
    #preview-canvas {
      background: #111;
      border-radius: 8px;
      max-width: 100%;
      height: auto;
      display: block;
    }
    .help {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 10px;
    }
    .controls {
      margin-top: 16px;
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }
    button.primary {
      background: var(--yellow);
      color: var(--accent);
      border: none;
      padding: 12px 18px;
      border-radius: 10px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    button.ghost {
      background: transparent;
      color: rgba(255, 255, 255, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.08);
      padding: 10px 14px;
      border-radius: 10px;
      cursor: pointer;
    }
    footer {
      margin-top: 28px;
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.9rem;
    }
    @media (max-width: 1000px) {
      .layout { flex-direction: column; align-items: center; }
      .frame-grid { width: 90%; grid-template-columns: repeat(3, 1fr); }
      #preview-container { width: 90%; }
    }
  </style>
</head>
<body>
  <h1>🎨 Choose Your Frame</h1>
  <p class="lead">Click a frame on the left — preview will show on the right. When it looks good, press <strong>Confirm Frame</strong>.</p>

  <div class="layout">
    <div class="frame-grid" id="frameGrid"></div>

    <div id="preview-container" aria-live="polite">
      <div id="preview-title">Preview</div>
      <canvas id="preview-canvas"></canvas>
      <div class="help">Preview updates immediately when you click a frame. Confirm to continue.</div>

      <div class="controls">
        <button id="confirmBtn" class="primary" disabled>Confirm Frame</button>
        <button id="backBtn" class="ghost">Back to Capture</button>
      </div>
    </div>
  </div>

  <footer>abcSnap Booth — Business Week 2025</footer>

  <script>
  (function(){
    const FRAME_COUNT = 6;
    const frameData = {
      1: { w:1748, h:1240, slots:[
        {x:18, y:18, w:256, h:146},
        {x:286, y:18, w:256, h:146},
        {x:18, y:175, w:256, h:146},
        {x:286, y:175, w:256, h:146}
      ]},
      2: { w:1748, h:1240, slots:[
        {x:18, y:18, w:256, h:146},
        {x:286, y:18, w:256, h:146},
        {x:18, y:175, w:256, h:146},
        {x:286, y:175, w:256, h:146}
      ]},
      3: { w:1748, h:1240, slots:[
        {x:18, y:31, w:276, h:184},
        {x:18, y:231, w:168, h:134},
        {x:200, y:231, w:164, h:134},
        {x:378, y:231, w:163, h:134}
      ]},
      4: { w:1748, h:1240, slots:[
        {x:18, y:31, w:276, h:184},
        {x:18, y:231, w:168, h:134},
        {x:200, y:231, w:164, h:134},
        {x:378, y:231, w:163, h:134}
      ]},
      5: { w:707, h:2000, slots:[
        {x:24, y:25, w:349, h:210},
        {x:24, y:264, w:349, h:210},
        {x:24, y:501, w:349, h:210},
        {x:24, y:738, w:349, h:210}
      ]},
      6: { w:707, h:2000, slots:[
        {x:24, y:25, w:349, h:210},
        {x:24, y:264, w:349, h:210},
        {x:24, y:501, w:349, h:210},
        {x:24, y:738, w:349, h:210}
      ]}
    };
     
      // 🎯 Scaling + per-frame + per-slot offsets
     const gridAScale = 3.2;  // Frames 1–2 (2x2 layout)
     const gridBScaleBig = 3.9;  // Frame 3–4 slot 1 (large)
     const gridBScaleSmall = 4.5; // Frame 3–4 slots 2–4 (small)
     const stripScale = 2.1;  // Frames 5–6 (vertical strip)

     // 🧭 Offsets for each frame type (per slot)
    const offsets = {
      gridA: [
        {x: -10, y: -6},
        {x: -30, y: -6},
        {x: -10, y: -21},
        {x: -30, y: -20}
      ],
      gridB: [
        {x: -20, y: -75},   // Slot 1 (big)
        {x: -65, y: -405},  // Slot 2
        {x: -305, y: -410}, // Slot 3
        {x: -530, y: -410}  // Slot 4
      ],
      strip: [
        {x: -10, y: -30},
        {x: -10, y: -120},
        {x: -10, y: -180},
        {x: -10, y: -250}
      ]
    };

    // 🧩 Apply scaling + offset adjustments
   for (const key in frameData) {
     const frameNum = parseInt(key);
     let slotOffsets;

     if (frameNum <= 2) {
       // Frames 1–2: Grid Type A
      slotOffsets = offsets.gridA;
      frameData[key].slots = frameData[key].slots.map((s, i) => ({
        x: s.x * gridAScale + (slotOffsets[i]?.x || 0),
        y: s.y * gridAScale + (slotOffsets[i]?.y || 0),
        w: s.w * gridAScale,
        h: s.h * gridAScale
      }));
    } else if (frameNum <= 4) {
      // Frames 3–4: Grid Type B (1 large + 3 small)
      slotOffsets = offsets.gridB;
      frameData[key].slots = frameData[key].slots.map((s, i) => {
      const scale = i === 0 ? gridBScaleBig : gridBScaleSmall;
      return {
        x: s.x * scale + (slotOffsets[i]?.x || 0),
        y: s.y * scale + (slotOffsets[i]?.y || 0),
        w: s.w * scale,
        h: s.h * scale
      };
    });
  } else {
    // Frames 5–6: Strip Type
    slotOffsets = offsets.strip;
    frameData[key].slots = frameData[key].slots.map((s, i) => ({
      x: s.x * stripScale + (slotOffsets[i]?.x || 0),
      y: s.y * stripScale + (slotOffsets[i]?.y || 0),
      w: s.w * stripScale,
      h: s.h * stripScale
    }));
  } // <-- closes the else
} // <-- closes the for (const key in frameData)

    const frameGrid = document.getElementById('frameGrid');
    const previewCanvas = document.getElementById('preview-canvas');
    const confirmBtn = document.getElementById('confirmBtn');
    const backBtn = document.getElementById('backBtn');

    const photos = JSON.parse(sessionStorage.getItem('photos') || '[]');
    console.log("Loaded photos:", photos);

    if (!Array.isArray(photos) || photos.length !== 4) {
      alert('No photos found — please take your 4 photos first.');
      window.location.href = 'snap.php';
      return;
    }

    // Build frame grid
    for (let i = 1; i <= FRAME_COUNT; i++) {
      const div = document.createElement('div');
      div.className = 'frame';
        
      // Use "des" for 1–4 and "photo" for 5–6    
      const fileName = (i <= 4) ? `des${i}.png` : `photo${i}.png`;
        
      div.innerHTML = `<img src="resources/designs/${fileName}" alt="Frame ${i}" data-index="${i}">`;
      div.addEventListener('click', () => onFrameClick(i));
      frameGrid.appendChild(div); 
    }

    async function loadImage(src) {
      return new Promise((res, rej) => {
        const img = new Image();
        img.onload = () => res(img);
        img.onerror = (e) => rej(e);
        img.src = src;
      });
    }

    async function onFrameClick(index) {
      confirmBtn.disabled = false;        
      const frame = frameData[index];
      const fileName = (index <= 4) ? `des${index}.png` : `photo${index}.png`;
      const frameImg = await loadImage(`resources/designs/${fileName}`);

      previewCanvas.width = frame.w;
      previewCanvas.height = frame.h;
       
      const ctx = previewCanvas.getContext("2d");
      ctx.clearRect(0, 0, frame.w, frame.h);

      // Draw the 4 photos in their respective slots
      for (let i = 0; i < 4; i++) {
        const slot = frame.slots[i];
        const img = await loadImage(photos[i]);
        await drawPhotoToFit(ctx, photos[i], slot.x, slot.y, slot.w, slot.h);
      }

      // Overlay the frame design on top
      ctx.drawImage(frameImg, 0, 0, frame.w, frame.h);   
        
      previewCanvas.dataset.index = index;
      const frameSrc = (index <= 4)
          ? `resources/designs/des${index}.png`
          : `resources/designs/photo${index}.png`;
        
        previewCanvas.dataset.frameSrc = frameSrc;
    }

    async function drawPhotoToFit(ctx, photoSrc, x, y, w, h){
      const img = await loadImage(photoSrc);
      const scale = Math.min(w / img.naturalWidth, h / img.naturalHeight);
      const drawW = img.naturalWidth * scale;
      const drawH = img.naturalHeight * scale;
      const offsetX = x + (w - drawW) / 2;
      const offsetY = y + (h - drawH) / 2;
      ctx.drawImage(img, offsetX, offsetY, drawW, drawH);
    }

    confirmBtn.addEventListener('click', async () => {
      const frameSrc = previewCanvas.dataset.frameSrc;
      const index = Number(previewCanvas.dataset.index);
      if (!frameSrc) return alert('Select a frame first.');

      const frameImg = await loadImage(frameSrc);
      const finalCanvas = document.createElement('canvas');
      finalCanvas.width = previewCanvas.width;
      finalCanvas.height = previewCanvas.height;
      const fctx = finalCanvas.getContext('2d');

      const frame = frameData[index];
      // Draw photos using frameData slot positions
      for (let i = 0; i < 4; i++) {
        const slot = frame.slots[i];
        await drawPhotoToFit(fctx, photos[i], slot.x, slot.y, slot.w, slot.h);
      }

      // Draw the selected frame overlay
      fctx.drawImage(frameImg, 0, 0, frame.w, frame.h);

      const finalDataURL = finalCanvas.toDataURL('image/png');

      try {
        const formData = new FormData();
        formData.append('image', finalDataURL);
        const res = await fetch('upload_snap.php', { method: 'POST', body: formData });
        const result = await res.json();
        if (result.status === 'success') {
          alert('Upload successful!');
          sessionStorage.setItem('finalPhoto', finalDataURL);
          sessionStorage.setItem('selectedFrame', frameSrc);
          window.location.href = 'success.php';
        } else {
          //alert('Upload failed: ' + result.message);
        }
      } catch (err) {
        alert('Error uploading image.');
        console.error(err);
      }
    });

    backBtn.addEventListener('click', () => window.location.href = 'snap.php');
  })();
  </script>
</body>
</html> 
