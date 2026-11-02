<style>
    /* PASTE YOUR EXTERNAL CSS HERE - This will override everything */
    /* 
    Copy and paste your CSS classes from your external stylesheet here
    to ensure they load properly with the video library
    */
    
    .fa-chevron-left:not(.nav-link), .fa-chevron-right:not(.nav-link) {
        font-size: 12px !important;
    }
    
    .video-library-container {
        background: #61769c;
        padding: 2rem ;
    }
    
 .category-btn {
    margin: 3px;
    border: none;
    font-size: 14px;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 5px 10px;
    /* backdrop-filter: blur(10px); */
    border: 1px solid transparent;
    display: inline-block;
    width: 186px;
    height: 66px;
}
    
    .category-btn:hover, .category-btn.active {
        background: rgba(255,255,255,0.9);
        color: white;
        border-color: white;
    }
    
    .video-section {
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 2rem 0;
    }
    
    .video-section.active {
        display: block;
        opacity: 1;
    }
    
    .category-title {
        color: white;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .video-carousel {
        background: rgba(255,255,255,0.95);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .carousel-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .carousel-btn {
        background: #61769c;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .carousel-btn:hover:not(:disabled) {
        background: #4a5a7a;
        transform: translateY(-2px);
    }
    
    .carousel-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    .video-counter {
        font-weight: bold;
        color: #61769c;
    }
    
    .progress-indicator {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .progress-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ddd;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .progress-dot.active {
        background: #61769c;
        transform: scale(1.2);
    }
    
    .video-info {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .video-title {
        color: #61769c;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .video-description {
        color: #666;
        margin-bottom: 0;
    }
    
    .video-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .loading-message {
        text-align: center;
        color: white;
        font-size: 1.2rem;
        margin: 2rem 0;
    }
    
    .error-message {
        text-align: center;
        color: #ff6b6b;
        background: rgba(255,255,255,0.9);
        padding: 1rem;
        border-radius: 10px;
        margin: 1rem;
    }
    
    .config-section {
        display: none;
    }
    
    .config-input {
        display: none;
    }
    
    .config-btn {
        display: none;
    }
</style>

<div class="container-fluid" style="padding: 0px" id="LEAD">
    <div class="video-library-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="text-center text-white my-5">
                    LEAD Learning Video Library
                </h1>
            </div>
        </div>

        <div id="loadingMessage" class="loading-message">
            <i class="fas fa-spinner fa-spin"></i> Loading videos...
        </div>

        <div id="errorMessage" class="error-message" style="display: none;"></div>

        <div class="row justify-content-center category-buttons" id="categoryButtons" style="display: none;">
            <div class="col-12 text-center" id="categoryButtonsContainer">
                <!-- Category buttons will be generated dynamically -->
            </div>
        </div>

        <!-- Video sections will be generated dynamically -->
        <div id="videoSections"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    let videoData = {};
    let currentVideoIndex = {};

    const GOOGLE_SHEETS_CONFIG = {
        apiKey: 'AIzaSyAPM5xbd3Lxk6dRx_5Kb9U2fUzdUEiwUzw',
        spreadsheetId: '1J08P54a4jZ4rFqGjYwYzRFoho1RNkQfzlXkN1z7NUzo',
        range: 'LEAD!A:D'
    };

    async function loadFromGoogleSheets() {
        const apiUrl = `https://sheets.googleapis.com/v4/spreadsheets/${GOOGLE_SHEETS_CONFIG.spreadsheetId}/values/${GOOGLE_SHEETS_CONFIG.range}?key=${GOOGLE_SHEETS_CONFIG.apiKey}`;

        try {
            showLoading(true);
            hideError();
            console.log('Fetching from API:', apiUrl);

            const response = await fetch(apiUrl, { method: 'GET', headers: { 'Accept': 'application/json' }, cache: 'no-cache' });

            if (!response.ok) throw new Error(`API request failed: ${response.status} ${response.statusText}`);

            const jsonData = await response.json();
            if (!jsonData.values || jsonData.values.length === 0) throw new Error('No data found in the spreadsheet');

            console.log('Raw API response:', jsonData);

            localStorage.setItem('videoLibrary_backup', JSON.stringify(jsonData));
            localStorage.setItem('videoLibrary_backup_timestamp', Date.now().toString());

            const data = convertApiDataToCSVFormat(jsonData.values);
            console.log('Converted data:', data);

            videoData = processVideoData(data);
            console.log('Processed categories:', Object.keys(videoData));

            initializeVideoLibrary();
            showLoading(false);
            document.body.classList.add('video-library-loaded');

            setTimeout(() => {
                if (typeof window !== 'undefined') {
                    window.dispatchEvent(new CustomEvent('videoLibraryReady', { detail: { categories: Object.keys(videoData) } }));
                }
            }, 200);

        } catch (error) {
            showLoading(false);
            console.error('API Error:', error);

            const backupData = localStorage.getItem('videoLibrary_backup');
            const backupTimestamp = localStorage.getItem('videoLibrary_backup_timestamp');

            if (backupData && backupTimestamp) {
                console.warn('Using backup data due to API error:', error.message);
                try {
                    const jsonData = JSON.parse(backupData);
                    const data = convertApiDataToCSVFormat(jsonData.values);
                    videoData = processVideoData(data);
                    initializeVideoLibrary();
                    showLoading(false);

                    const backupDate = new Date(parseInt(backupTimestamp)).toLocaleString();
                    showError(`Using cached data (last updated: ${backupDate}). ${error.message}`);
                    document.body.classList.add('video-library-loaded');
                    setTimeout(() => {
                        if (typeof window !== 'undefined') {
                            window.dispatchEvent(new CustomEvent('videoLibraryReady', { detail: { categories: Object.keys(videoData) } }));
                        }
                    }, 200);
                    return;
                } catch (backupError) {
                    console.error('Backup data also failed:', backupError);
                }
            }

            showError(`Error loading data: ${error.message}`);
        }
    }

    function convertApiDataToCSVFormat(values) {
        if (!values || values.length === 0) return [];
        const headers = values[0];
        const data = [];
        console.log('Headers found:', headers);

        for (let i = 1; i < values.length; i++) {
            if (values[i] && values[i].length > 0) {
                const row = {};
                headers.forEach((header, index) => { row[header] = values[i][index] || ''; });
                data.push(row);
            }
        }
        return data;
    }

    function processVideoData(data) {
        const processedData = {};
        data.forEach(row => {
            const originalCategory = row.category || 'uncategorized';
            const safeCategoryId = originalCategory.toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^a-z0-9\-]/g, '')
                .replace(/\-+/g, '-')
                .replace(/^\-|\-$/g, '');

            if (!processedData[safeCategoryId]) processedData[safeCategoryId] = { displayName: originalCategory, videos: [] };
            processedData[safeCategoryId].videos.push({ id: row.video_url || row.id || '', title: row.title || 'Untitled', description: row.description || '' });
        });
        return processedData;
    }

    function initializeVideoLibrary() {
        const categories = Object.keys(videoData);
        if (categories.length === 0) { showError('No video categories found in the data'); return; }
        generateCategoryButtons(categories);
        generateVideoSections(categories);
        categories.forEach(category => { currentVideoIndex[category] = 0; });
        document.getElementById('categoryButtons').style.display = 'block';
        forceReloadExternalCSS();
        setTimeout(() => { const firstBtn = document.querySelector('.category-btn'); if (firstBtn) firstBtn.click(); }, 100);
    }

    function forceReloadExternalCSS() {
        try { document.body.style.display = 'none'; document.body.offsetHeight; document.body.style.display = ''; } catch(e) {}
        try { if (typeof window !== 'undefined') window.dispatchEvent(new Event('videoLibraryLoaded')); } catch(e) {}
        console.log('External CSS reload triggered after video library initialization');
    }

    function generateCategoryButtons(categories) {
        const container = document.getElementById('categoryButtonsContainer');
        container.innerHTML = '';
        categories.forEach(categoryId => {
            const categoryData = videoData[categoryId];
            const button = document.createElement('button');
            button.className = 'btn category-btn';
            button.setAttribute('data-category', categoryId);
            button.textContent = categoryData.displayName;

            button.addEventListener('click', function() {
                const selectedCategory = this.dataset.category;
                showCategory(selectedCategory);
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
            container.appendChild(button);
        });
    }

    function showCategory(category) {
        document.querySelectorAll('.video-section').forEach(section => section.classList.remove('active'));
        setTimeout(() => {
            document.getElementById(category).classList.add('active');
            initializeCategory(category);
        }, 200);
    }

    function generateVideoSections(categories) {
        const container = document.getElementById('videoSections');
        container.innerHTML = '';
        categories.forEach(category => container.appendChild(createVideoSection(category)));
    }

    function createVideoSection(categoryId) {
        const categoryData = videoData[categoryId];
        const section = document.createElement('div');
        section.className = 'video-section';
        section.id = categoryId;
        section.innerHTML = `
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-12">
                    <h2 class="category-title">${categoryData.displayName}</h2>
                    <div class="video-carousel">
                        <div class="carousel-controls">
                            <button class="carousel-btn" id="prevBtn-${categoryId}" disabled>
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <div class="video-counter">
                                <span id="currentVideo-${categoryId}">1</span> of <span id="totalVideos-${categoryId}">0</span>
                            </div>
                            <button class="carousel-btn" id="nextBtn-${categoryId}">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="progress-indicator" id="progress-${categoryId}"></div>
                        <div class="video-info">
                            <h3 class="video-title" id="videoTitle-${categoryId}">Loading...</h3>
                            <p class="video-description" id="videoDesc-${categoryId}">Loading video information...</p>
                        </div>
                        <div class="video-container">
                            <iframe id="videoFrame-${categoryId}" src="" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        `;
        return section;
    }

    function initializeCategory(category) {
        const categoryData = videoData[category];
        const videos = categoryData ? categoryData.videos : [];
        if (videos.length === 0) { showError(`No videos found for category: ${categoryData.displayName}`); return; }
        currentVideoIndex[category] = 0;
        const totalElement = document.getElementById(`totalVideos-${category}`);
        if (totalElement) totalElement.textContent = videos.length;
        createProgressDots(category, videos.length);
        _programmaticLoad[category] = true;
        loadVideo(category, 0);
        _programmaticLoad[category] = false;
        setTimeout(() => { setupNavigation(category); }, 100);
    }

    function createProgressDots(category, count) {
        const progressContainer = document.getElementById(`progress-${category}`);
        if (!progressContainer) return;
        progressContainer.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const dot = document.createElement('div');
            dot.className = 'progress-dot';
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', ((index) => () => loadVideo(category, index))(i));
            progressContainer.appendChild(dot);
        }
    }

    function setupNavigation(category) {
        const prevBtn = document.getElementById(`prevBtn-${category}`);
        const nextBtn = document.getElementById(`nextBtn-${category}`);
        if (!prevBtn || !nextBtn) return;
        prevBtn.onclick = null;
        nextBtn.onclick = null;
        prevBtn.onclick = (e) => { e.preventDefault(); if (!prevBtn.disabled) navigateVideo(category, -1); };
        nextBtn.onclick = (e) => { e.preventDefault(); if (!nextBtn.disabled) navigateVideo(category, 1); };
    }

    function navigateVideo(category, direction) {
        const categoryData = videoData[category];
        const videos = categoryData ? categoryData.videos : [];
        const currentIndex = currentVideoIndex[category] || 0;
        const newIndex = currentIndex + direction;
        if (newIndex < 0 || newIndex >= videos.length) return;
        loadVideo(category, newIndex);
    }

    // ----------------------- YouTube Fix Applied -----------------------
    function loadVideo(category, index) {
        const categoryData = videoData[category];
        const videos = categoryData ? categoryData.videos : [];
        const video = videos[index];
        if (!video) return;
        currentVideoIndex[category] = index;

        const videoFrame = document.getElementById(`videoFrame-${category}`);
        if (videoFrame) {
            let videoUrl = video.id.trim();
            const isYouTube = /youtube\.com|youtu\.be/.test(videoUrl);
            // Convert YouTube URLs to embed format with enablejsapi=1 for GA4 tracking
            if (isYouTube) {
                const ytMatch = videoUrl.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\s&]+)/);
                if (ytMatch && ytMatch[1]) videoUrl = `https://www.youtube.com/embed/${ytMatch[1]}?rel=0&showinfo=0&enablejsapi=1`;
            }

            // Track duration of the video that was playing before this switch
            if (!_programmaticLoad[category] && videoSessions[category]) {
                trackVideoDuration(category);
            }

            videoFrame.src = videoUrl;

            // Fire GA4 tracking for user-initiated video loads
            if (!_programmaticLoad[category]) {
                trackVideoStart(category, video, videoUrl);
                if (isYouTube) {
                    initYouTubeTracking(category, videoFrame);
                }
            }
        }

        const titleElement = document.getElementById(`videoTitle-${category}`);
        const descElement = document.getElementById(`videoDesc-${category}`);
        const currentElement = document.getElementById(`currentVideo-${category}`);
        if (titleElement) titleElement.textContent = video.title;
        if (descElement) descElement.innerHTML = video.description.includes('<a') ? video.description : video.description;
        if (currentElement) currentElement.textContent = index + 1;

        const progressDots = document.querySelectorAll(`#progress-${category} .progress-dot`);
        progressDots.forEach((dot, i) => dot.classList.toggle('active', i === index));

        updateNavigationButtons(category, index, videos.length);
    }

    function updateNavigationButtons(category, currentIndex, totalVideos) {
        const prevBtn = document.getElementById(`prevBtn-${category}`);
        const nextBtn = document.getElementById(`nextBtn-${category}`);
        if (!prevBtn || !nextBtn) return;

        const isFirstVideo = currentIndex === 0;
        const isLastVideo = currentIndex === totalVideos - 1;
        prevBtn.disabled = isFirstVideo;
        nextBtn.disabled = isLastVideo;
        prevBtn.style.opacity = isFirstVideo ? '0.5' : '1';
        prevBtn.style.cursor = isFirstVideo ? 'not-allowed' : 'pointer';
        nextBtn.style.opacity = isLastVideo ? '0.5' : '1';
        nextBtn.style.cursor = isLastVideo ? 'not-allowed' : 'pointer';
    }

    function showLoading(show) { document.getElementById('loadingMessage').style.display = show ? 'block' : 'none'; }
    function showError(message) { const errorDiv = document.getElementById('errorMessage'); errorDiv.textContent = message; errorDiv.style.display = 'block'; }
    function hideError() { document.getElementById('errorMessage').style.display = 'none'; }

    document.addEventListener('DOMContentLoaded', () => { loadFromGoogleSheets(); });

    // === GA4 Video Tracking ===
    const videoSessions = {};
    const _programmaticLoad = {};
    const _ytProgressTimers = {};

    function trackVideoStart(category, video, videoUrl) {
        const catData = videoData[category];
        videoSessions[category] = {
            title: video.title,
            url: videoUrl,
            type: /youtube\.com|youtu\.be/.test(video.id) ? 'youtube' : 'cloudfront',
            category: catData ? catData.displayName : category,
            startTime: Date.now(),
            progressFired: {}
        };
        gtag('event', 'video_start', {
            video_title: video.title,
            video_url: videoUrl,
            video_provider: /youtube\.com|youtu\.be/.test(video.id) ? 'youtube' : 'cloudfront',
            video_category: catData ? catData.displayName : category
        });
    }

    function trackVideoDuration(category) {
        const session = videoSessions[category];
        if (!session || !session.startTime) return;
        const duration = Math.round((Date.now() - session.startTime) / 1000);
        if (duration < 2) return;
        gtag('event', 'video_watch_duration', {
            video_title: session.title,
            video_url: session.url,
            video_provider: session.type,
            video_category: session.category,
            watch_duration_seconds: duration
        });
        session.startTime = null;
    }

    function trackVideoProgress(category, percent) {
        const session = videoSessions[category];
        if (!session || session.progressFired[percent]) return;
        session.progressFired[percent] = true;
        gtag('event', 'video_progress', {
            video_title: session.title,
            video_url: session.url,
            video_provider: session.type,
            video_category: session.category,
            video_percent: percent
        });
    }

    function initYouTubeTracking(category, iframe) {
        clearInterval(_ytProgressTimers[category]);
        function createYTPlayer() {
            if (!window.YT || !window.YT.Player) { setTimeout(createYTPlayer, 300); return; }
            try { if (videoSessions[category] && videoSessions[category].ytPlayer) videoSessions[category].ytPlayer.destroy(); } catch(e) {}
            const player = new YT.Player(iframe, {
                events: {
                    onStateChange: function(e) {
                        const session = videoSessions[category];
                        if (!session) return;
                        if (e.data === YT.PlayerState.PLAYING) {
                            clearInterval(_ytProgressTimers[category]);
                            _ytProgressTimers[category] = setInterval(function() {
                                try {
                                    const pct = (player.getCurrentTime() / player.getDuration()) * 100;
                                    if (pct >= 25) trackVideoProgress(category, 25);
                                    if (pct >= 50) trackVideoProgress(category, 50);
                                    if (pct >= 75) trackVideoProgress(category, 75);
                                    if (pct >= 90) trackVideoProgress(category, 90);
                                } catch(err) {}
                            }, 1000);
                        } else {
                            clearInterval(_ytProgressTimers[category]);
                            if (e.data === YT.PlayerState.ENDED) {
                                trackVideoProgress(category, 100);
                                gtag('event', 'video_complete', {
                                    video_title: session.title,
                                    video_url: session.url,
                                    video_provider: 'youtube',
                                    video_category: session.category
                                });
                            }
                        }
                    }
                }
            });
            if (videoSessions[category]) videoSessions[category].ytPlayer = player;
        }
        iframe.addEventListener('load', function() { setTimeout(createYTPlayer, 100); }, { once: true });
    }

    // Load YouTube IFrame API once
    (function() {
        if (!document.getElementById('yt-iframe-api')) {
            const s = document.createElement('script');
            s.id = 'yt-iframe-api';
            s.src = 'https://www.youtube.com/iframe_api';
            document.head.appendChild(s);
        }
    })();

    // Fire duration event when user leaves the page
    window.addEventListener('beforeunload', function() {
        Object.keys(videoSessions).forEach(function(cat) { trackVideoDuration(cat); });
    });
</script>

<link rel="stylesheet" href="/css/main.css">
