<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Filtered Bynder Embeds</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding: 2rem;
      background: #f8f9fa;
    }

    .category-filters {
      margin-bottom: 2rem;
    }

    .file-card {
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
    }

    [data-bynder-widget] {
      min-height: 300px;
      display: block !important;
      visibility: visible !important;
      opacity: 1 !important;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1 class="mb-4">Bynder Resources</h1>

    <!-- Radio Filter -->
    <div class="category-filters">
      <div class="form-check form-check-inline">
        <input class="form-check-input category-radio" type="radio" name="category" id="all" value="all" checked>
        <label class="form-check-label" for="all">All</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input category-radio" type="radio" name="category" id="manuals" value="manuals">
        <label class="form-check-label" for="manuals">Training Manuals</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input category-radio" type="radio" name="category" id="videos" value="videos">
        <label class="form-check-label" for="videos">Training Videos</label>
      </div>
    </div>

    <!-- File Container -->
    <div id="fileContainer" class="row">
      <!-- Injected file cards will appear here -->
    </div>
  </div>

  <!-- Bynder Embed Script -->
  <script
    id="bynder-widgets-js"
    data-account-url="dam.redshieldtoolkit.org"
    data-language="en"
    src="https://d8ejoa1fys2rk.cloudfront.net/bynder-embed/latest/bynder-embed.js">
  </script>

  <script>
    // Sample file data (you can load this from JSON)
    const files = [
      {
        title: "Training Manual A",
        mediaId: "FFA02AE7-8506-4E18-B460CB31569BE343",
        category: "manuals"
      },
      {
        title: "Training Video A",
        mediaId: "FFA02AE7-8506-4E18-B460CB31569BE343",
        category: "videos"
      },
      {
        title: "Training Manual B",
        mediaId: "FFA02AE7-8506-4E18-B460CB31569BE343",
        category: "manuals"
      }
    ];

    function createFileCard(file) {
      return `
        <div class="col-12 col-md-6 file-card">
          <h5>${file.title}</h5>
          <div data-bynder-widget="media-item"
               data-media-id="${file.mediaId}"
               data-title="true"
               data-download="false"
               data-lightbox="false">
          </div>
        </div>
      `;
    }

   function displayFiles(filtered) {
  const container = document.getElementById("fileContainer");
  container.innerHTML = filtered.map(createFileCard).join("");

  setTimeout(() => {
    const oldScript = document.getElementById("bynder-widgets-js");
    if (oldScript) oldScript.remove();

    const script = document.createElement("script");
    script.id = "bynder-widgets-js";
    script.src = "https://d8ejoa1fys2rk.cloudfront.net/bynder-embed/latest/bynder-embed.js";
    script.setAttribute("data-account-url", "dam.redshieldtoolkit.org");
    script.setAttribute("data-language", "en");
    document.body.appendChild(script);
  }, 200);
}

// Inject a fresh script tag
const script = document.createElement("script");
script.id = "bynder-widgets-js";
script.src = "https://d8ejoa1fys2rk.cloudfront.net/bynder-embed/latest/bynder-embed.js";
script.setAttribute("data-account-url", "dam.redshieldtoolkit.org");
script.setAttribute("data-language", "en");
document.body.appendChild(script);
      }, 300); // slight delay helps ensure all elements are in DOM
    }

    function filterFiles(category) {
      if (category === "all") {
        displayFiles(files);
      } else {
        const filtered = files.filter(file => file.category === category);
        displayFiles(filtered);
      }
    }

    // Initial load
    displayFiles(files);

    // Set up radio button filtering
    document.querySelectorAll(".category-radio").forEach(radio => {
      radio.addEventListener("change", (e) => {
        filterFiles(e.target.value);
      });
    });
  </script>

</body>
</html>
