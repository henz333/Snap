<!DOCTYPE html> 
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>abcSnap | Frame Editor</title>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
  body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #1E3C72, #2A5298);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    min-height: 100vh;
  }

  header {
    font-family: 'Baloo 2', cursive;
    font-size: 2rem;
    color: #FFD93D;
    text-align: center;
    padding: 1.2rem;
    width: 100%;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  }

  main {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem;
    width: 100%;
    box-sizing: border-box;
  }

  #preview-area {
    position: relative;
    background: #111;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    margin-bottom: 2rem;
    transition: all 0.3s ease;
  }

  #preview {
    width: 100%;
    height: auto;
    display: block;
  }

  h2 { color: #FFD93D; }

  #frames {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .frame-option {
    width: 150px;
    height: 100px;
    border-radius: 12px;
    overflow: hidden;
    border: 3px solid transparent;
    cursor: pointer;
    transition: 0.2s;
    background: rgba(255,255,255,0.08);
  }

  .frame-option img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .frame-option:hover { transform: scale(1.05); }
  .frame-option.selected { border-color: #FFD93D; transform: scale(1.07); }

  #downloadBtn, #printBtn {
    background: #FFD93D;
    color: #2A5298;
    border: none;
    padding: 14px 26px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    box-shadow: 0 6px 14px rgba(0,0,0,0.2);
    margin: 0.4rem;
  }

  #downloadBtn:hover, #printBtn:hover { transform: translateY(-2px); }

  footer {
    margin-top: auto;
    text-align: center;
    padding: 1rem;
    opacity: 0.8;
  }
</style>
</head>
<body>
  <header>🎞️ abcSnap — Frame Selection</header>

  <main>
    <div id="preview-area">
      <img id="preview" alt="Preview of framed photo">
    </div>

    <h2>Select Your Frame</h2>
    <div id="frames">
      <div class="frame-option"><img src="frames/frame1.png" alt="Frame 1"></div>
      <div class="frame-option"><img src="frames/frame2.png" alt="Frame 2"></div>
      <div class="frame-option"><img src="frames/frame3.png" alt="Frame 3"></div>
      <div class="frame-option"><img src="frames/frame4.png" alt="Frame 4"></div>
      <div class="frame-option"><img src="frames/frame5.png" alt="Frame 5"></div>
      <div class="frame-option"><img src="frames/frame6.png" alt="Frame 6"></div>
    </div>

    <div>
      <button id="downloadBtn">Download Final Photo</button>
      <button id="printBtn">Print</button>
    </div>
  </main>

  <footer>© 2025 abcSnap | Business Week</footer>

<script>
const preview = document.getElementById('preview');
const frameOptions = document.querySelectorAll('.frame-option');
const previewArea = document.getElementById('preview-area');
const downloadBtn = document.getElementById('downloadBtn');
const printBtn = document.getElementById('printBtn');

// === Your frame coordinate data (converted from cm to px) ===
const frameData = { /* (keep all existing frameData code here unchanged) */ };

// (keep all existing scaling, offsets, logic, etc. unchanged)

// --- Download final image ---
downloadBtn.addEventListener('click', () => {
  if (!currentCanvas) {
    alert("Select a frame first!");
    return;
  }
  const link = document.createElement('a');
  link.download = 'abcSnap-Framed.png';
  link.href = currentCanvas.toDataURL('image/png');
  link.click();
});

// --- Print/Upload image to server ---
printBtn.addEventListener('click', async () => {
  if (!currentCanvas) {
    alert("Select a frame first!");
    return;
  }
  const blob = await new Promise(resolve => currentCanvas.toBlob(resolve, 'image/png'));
  const formData = new FormData();
  const filename = 'framed_' + Date.now() + '.png';
  formData.append('file', blob, filename);

  fetch('upload.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(response => {
    alert(response);
  })
  .catch(err => {
    alert('Upload failed: ' + err);
  });
});
</script>
</body>
</html>
