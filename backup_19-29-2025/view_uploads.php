<?php
require_once 'conn/db_conn.php';

// Correct path with trailing slash
$uploadDir = __DIR__ . '/uploads/images/';

try {
    // Latest uploads first
    $stmt = $conn->query("SELECT id, name, hour FROM uploads ORDER BY hour DESC");
    $uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching uploads: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Uploaded Photos</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --blue: #0077b6;
      --yellow: #ffd60a;
      --light-bg: #f9fafb;
      --text-dark: #1b1b1b;
      --white: #ffffff;
      --shadow: rgba(0, 0, 0, 0.1);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Open Sans', sans-serif;
      background-color: var(--light-bg);
      color: var(--text-dark);
      padding: 40px 20px;
    }

    h1 {
      text-align: center;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 2.3rem;
      color: var(--blue);
      margin-bottom: 40px;
      border-bottom: 3px solid var(--yellow);
      display: inline-block;
      padding-bottom: 5px;
      letter-spacing: 1px;
    }

    .gallery {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 25px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .item {
      position: relative;
      background: var(--white);
      border-radius: 10px;
      box-shadow: 0 4px 10px var(--shadow);
      overflow: hidden;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .item:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .item img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      background: #eee;
    }

    .info {
      padding: 12px 14px 16px;
      text-align: left;
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .info strong {
      color: var(--blue);
      display: inline-block;
      width: 90px;
      font-weight: 600;
    }

    .no-uploads {
      text-align: center;
      font-size: 1.1rem;
      color: var(--blue);
      background: var(--yellow);
      padding: 12px 20px;
      border-radius: 6px;
      display: inline-block;
      margin-top: 40px;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
    }

    /* New badge for latest image */
    .new-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: var(--yellow);
      color: var(--blue);
      font-weight: 700;
      font-size: 0.8rem;
      padding: 4px 8px;
      border-radius: 4px;
      box-shadow: 0 2px 6px var(--shadow);
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 1.8rem;
      }
      .item img {
        height: 150px;
      }
    }
  </style>
</head>
<body>
  <div style="text-align:center;">
    <h1>Uploaded Photos</h1>
  </div>

  <div class="gallery">
    <?php if (!$uploads): ?>
      <p class="no-uploads">No uploads found.</p>
    <?php else: ?>
      <?php foreach ($uploads as $index => $upload): 
        $filePath = $uploadDir . $upload['name'];
        if (file_exists($filePath)): ?>
          <div class="item">
            <!-- Show "New" badge on the very first/latest upload -->
            <?php if ($index === 0): ?>
              <div class="new-badge">NEW</div>
            <?php endif; ?>
            <img src="<?php echo 'uploads/images/' . htmlspecialchars($upload['name']); ?>" alt="Uploaded photo" />
            <div class="info">
              <p><strong>File:</strong> <?php echo htmlspecialchars($upload['name']); ?></p>
              <p><strong>Time:</strong> <?php echo htmlspecialchars($upload['hour']); ?></p>
            </div>
          </div>
        <?php else: ?>
          <div class="item">
            <div class="info">
              <p><strong>Missing:</strong> <?php echo htmlspecialchars($upload['name']); ?></p>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>
