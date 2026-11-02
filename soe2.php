<style>
    .standardsOfExcellence {background:#f3f3f3 !important;}
    .standardsRow {
   background-color: white;
    border: 2px solid #efefef;
    padding: 15px;
    padding-left: 25px;
    margin-bottom: 0px;
    border-radius: 7px;}
  
  
  .standardsRow:hover {
  border: 1px solid #ee3a43;
  background: white;
  transition: .3s;}
  
  .standardsRow ul {
   
    list-style: circle;
    padding-left: 10px !important;
}
  
  .standardsRow li {
  margin-bottom: 3px;}
  .standardsHeader, .standardsRow p {display: block;
  font-weight: 700;
  font-size; 1.25em;}
  
  .standardsHeader {
  display: block;
    font-weight: 700;
   
    text-decoration: underline;
    text-transform: uppercase;
}

  .fas {font-size: 2em;}
  .silverCert {color: #b5b5b5!important;
    position: relative;
    top: 26px;
    left: -14px;
}
   .goldCert {color: #e0b577 !important;
   position: relative;
    top: 26px;
    left: -14px;}

.left{
text-align:left!important
  }

.container .certHolder i{
text-align:left;
  
  }
  
.btn-block-half {
      width: 48%;
      display: inline-block;
      margin-right: 2%;
    }
    .btn-block-half:last-child {
      margin-right: 0;
    }

    .section2, .content {
      display: none;
      margin-top: 15px;
    }

  .group-btn {margin: 4px !important;}

    .btn-group .btn.active,
    .btn-group .btn:focus,
    .btn-group .btn:hover {
       background: white !important;
    color: #ee3a43 !important;
     
}

    .cert-btn.active,
    .cert-btn:focus,
    .cert-btn:hover {
        background: white !important;
    color: #ee3a43 !important;
    }

    .cert-content {
      display: none;
    }

#certification-buttons {
  text-align: center;
  
}

.SoErequirements {
  background: #f3f3f3;
  padding: 15px;
  border-radius: 10px;
}

.btn.active {

    border-color: #ee3a43 !important;
}

/* ---- SOE video carousel ---- */
.soeVideoSection {
    padding: 15px 0 40px;
}

.soeVideoTitle-main {
    text-align: center;
    margin-bottom: 1.5rem;
}

.soeVideoCarousel {
    background: #ffffff;
    border: 2px solid #efefef;
    border-radius: 10px;
    padding: 1.5rem;
    max-width: 900px;
    margin: 0 auto;
}

.soeVideoCarousel .carousel-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.soeVideoCarousel .carousel-btn {
    background: #ee3a43;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.soeVideoCarousel .carousel-btn:hover:not(:disabled) {
    background: #c72c34;
    transform: translateY(-2px);
}

.soeVideoCarousel .carousel-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.soeVideoCarousel .video-counter {
    font-weight: bold;
    color: #555;
}

.soeVideoCarousel .progress-indicator {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.soeVideoCarousel .progress-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #ddd;
    cursor: pointer;
    border: none;
    padding: 0;
    transition: all 0.3s ease;
}

.soeVideoCarousel .progress-dot.active {
    background: #ee3a43;
    transform: scale(1.2);
}

.soeVideoCarousel .video-info {
    text-align: center;
    margin-bottom: 1.25rem;
}

.soeVideoCarousel .video-title {
    color: #333;
    font-weight: 700;
    font-size: 1.35rem;
    margin-bottom: 0;
}

.soeVideoCarousel .video-slides {
    position: relative;
}

.soeVideoCarousel .video-slide {
    display: none;
}

.soeVideoCarousel .video-slide.active {
    display: block;
}
    </style>

<!-- keep css above -->
<div id="soe" class="container-fluid SoErequirements mt-5 py-5">
    <div class="container SoErequirements mt-4">
        
        <div class="container" id="SOE">
	<h2 style="text-align: center;">Standards of Excellence</h2>

	<p>
		The Salvation Army has created a national recognition program to honor Advisory Organizations (Board/Councils, Echelon Chapters, and Women's Auxiliary Units) that meet and exceed national standards of excellence (SOE). You are invited to apply for the appropriate certification for your Advisory Organization.
	</p>

	<p>
	Please review the guidelines below to ensure your understanding of the requirements and process necessary to complete your application. For your convenience, template documents are provided below, which should be completed and submitted with your application.
	</p>



</div>
        <div class="container-fluid soeVideoSection" id="soeVideoLibrary">
            <div class="container">
                <h2 class="soeVideoTitle-main" id="soeImpact">See the Impact of the Standards of Excellence</h2>

                <div class="soeVideoCarousel">
                    <div class="carousel-controls">
                        <button class="carousel-btn" id="soePrevBtn" disabled>
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <div class="video-counter"  style="color: white !important;">
                            <span id="soeCurrentVideo">1</span> of <span id="soeTotalVideos">6</span>
                        </div>
                        <button class="carousel-btn" id="soeNextBtn">
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="progress-indicator" id="soeProgress"></div>

                    <div class="video-info">
                        <h3 class="video-title" id="soeVideoTitle">The Impact of the SOE - Major Kistan, Intermountain Divisional Commander</h3>
                    </div>

                    <div class="video-slides">
                        <div class="video-slide active" data-index="0">
                            <div data-bynder-widget="video-item" data-media-id="95E27A68-804D-43A0-9CBDB374426B32B4"></div>
                        </div>
                        <div class="video-slide" data-index="1">
                            <div data-bynder-widget="video-item" data-media-id="8CFEF386-A5FE-4633-8D91C40F09D236FC" ></div>
                        </div>
                        
                        <div class="video-slide" data-index="2">
                            <div data-bynder-widget="video-item" data-media-id="28D6205C-4BE1-43B6-824B34DE85A04126" ></div>
                        </div>
                        <div class="video-slide" data-index="3">
                          <div data-bynder-widget="video-item" data-media-id="C736DB3A-25CB-4203-A416DD7BB1967AEA" > </div>  
                        </div>
                        <div class="video-slide" data-index="4">
                           <div data-bynder-widget="video-item" data-media-id="4A01392E-4713-405F-9B67457CFF25B9BA" ></div>
                        </div>
            
                    </div>
                </div>
            </div>
        </div>

        <script id="bynder-widgets-js" data-account-url="dam.redshieldtoolkit.org" data-language="en" src="https://d8ejoa1fys2rk.cloudfront.net/bynder-embed/latest/bynder-embed.js"></script>
        <script>
            (function() {
                var soeVideoTitles = [
                '1. The Impact of the SOE - Major Kistan, Intermountain Divisional Commander',    
                '2. Why I made the SOE a priority for my division - Major Kistan, Intermountain Divisional Commander',
                '3. Impact of going through the SOE application process - Emily Heimann, Chair of the Gillette, WY Advisory Board',
                '4. Impact of the Standards of Excellence - Mike Karpeles, Director of Special Projects, Intermountain Division',
                '5. Impact of the SOE on the mission of Salvation Army - Mike Karpeles, Director of Special Projects, Intermountain Division'
                ];

                var soeCurrentIndex = 0;
                var soeSlides = document.querySelectorAll('#soeVideoLibrary .video-slide');
                var soeTotal = soeSlides.length;
                var soePrevBtn = document.getElementById('soePrevBtn');
                var soeNextBtn = document.getElementById('soeNextBtn');
                var soeTitleEl = document.getElementById('soeVideoTitle');
                var soeCurrentEl = document.getElementById('soeCurrentVideo');
                var soeProgress = document.getElementById('soeProgress');

                document.getElementById('soeTotalVideos').textContent = soeTotal;

                for (var i = 0; i < soeTotal; i++) {
                    var dot = document.createElement('button');
                    dot.className = 'progress-dot' + (i === 0 ? ' active' : '');
                    dot.setAttribute('aria-label', 'Go to video ' + (i + 1));
                    (function(index) {
                        dot.addEventListener('click', function() { soeShowVideo(index); });
                    })(i);
                    soeProgress.appendChild(dot);
                }

                function soeStopVideosIn(slideEl) {
                    if (!slideEl) return;
                    slideEl.querySelectorAll('video').forEach(function(v) {
                        v.pause();
                    });
                    slideEl.querySelectorAll('iframe').forEach(function(f) {
                        var src = f.getAttribute('src');
                        if (src) {
                            f.setAttribute('src', src);
                        }
                    });
                }

                function soeShowVideo(index) {
                    if (index < 0 || index >= soeTotal) return;

                    if (index !== soeCurrentIndex) {
                        soeStopVideosIn(soeSlides[soeCurrentIndex]);
                    }

                    soeCurrentIndex = index;

                    soeSlides.forEach(function(slide, i) {
                        slide.classList.toggle('active', i === index);
                    });

                    var dots = soeProgress.querySelectorAll('.progress-dot');
                    dots.forEach(function(dot, i) {
                        dot.classList.toggle('active', i === index);
                    });

                    soeTitleEl.textContent = soeVideoTitles[index] || '';
                    soeCurrentEl.textContent = index + 1;

                    soePrevBtn.disabled = index === 0;
                    soeNextBtn.disabled = index === soeTotal - 1;
                }

                soePrevBtn.addEventListener('click', function() { soeShowVideo(soeCurrentIndex - 1); });
                soeNextBtn.addEventListener('click', function() { soeShowVideo(soeCurrentIndex + 1); });

                soeShowVideo(0);
            })();
        </script>

        <div class="text-center">
            <h5>Requirements and Document Templates for Standards of Excellence Awards Applications</h5>

            <div class="btn-group">
                <a class="btn btn-primary group-btn" href="/SOE-advisory-organizations.html">Advisory Boards &amp; Councils</a>
                 <a class="btn btn-primary group-btn" href="/SOE-echelon.html">Echelon Chapter</a>
               <a class="btn btn-primary group-btn" href="/SOE-womens-auxiliary.html">Women's Auxiliary</a>
            </div>

            	<p style="text-align: center;" class="mt-3">
<a class="btn btn-secondary mb-2" href="https://aohub.org/images/National_Advisory_Organizations_Standards_of_Excellence_March20.pdf" target="_blank">Standards of Excellence Overview</a>&nbsp;<a class="btn btn-primary mb-2" href="https://saapps.quickbase.com/db/bsk2zeb5j" target="_blank">Apply for Standards of Excellence</a>
	</p>
        </div>
        </div>

</body>
</html>